<?php
session_start();
include '../php/database.php';

$isAdmin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');

$content = [];
$sql = "SELECT section_id, content_text FROM content WHERE page_name = 'activities'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $content[$row['section_id']] = $row['content_text'];
    }
}

$enrollment_url = '#'; 
$sql_url = "SELECT setting_value FROM settings WHERE setting_name = 'enrollment_url'";
$result_url = $conn->query($sql_url);
if ($result_url && $result_url->num_rows > 0) {
    $row_url = $result_url->fetch_assoc();
    $enrollment_url = $row_url['setting_value'];
}

// Function to render editable content
function render_content($page, $section, $default, $is_admin, $is_html = false) {
    global $content;
    // Use the fetched content if it exists, otherwise use the default text.
    $text = isset($content[$section]) ? $content[$section] : $default;
    
    echo '<div class="editable-container">';
    // The main content element that will be updated by JS
    echo "<div data-page='{$page}' data-section='{$section}'>";
    echo $text; // The content is directly echoed. If it contains HTML, it will be rendered.
    echo "</div>";
    
    // The edit icon, visible only to admins
    if ($is_admin) {
        echo "<i class='fas fa-pencil-alt text-edit-icon' data-page='{$page}' data-section='{$section}' data-is-html='" . ($is_html ? '1' : '0') . "'></i>";
    }
    echo '</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Activities - AAIS</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Existing styles from the file */
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(30px); } }
        body { background: linear-gradient(135deg, #344E41 0%, #588157 100%); color: #2a2a2a; position: relative; overflow-x: hidden; display: flex; flex-direction: column; min-height: 100vh; }
        body::before, body::after { content: ''; position: absolute; border-radius: 50%; background: rgba(255,255,255,0.05); animation: float 8s infinite ease-in-out; z-index: -1; }
        body::before { width: 300px; height: 300px; top: -50px; left: -50px; }
        body::after { width: 400px; height: 400px; bottom: 0px; right: -50px; animation-delay: 4s; }
        .main-wrapper { padding: 120px 20px 80px 20px; width: 100%; max-width: 1400px; margin: 0 auto; display: flex; flex-direction: column; gap: 30px; flex-grow: 1; }
        .content-card, .event-section { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .event-section h2 { font-size: 2rem; color: #27ae60; text-align: center; margin-bottom: 20px; }
        .image-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .image-grid.grid-3-col { grid-template-columns: repeat(3, 1fr); }
        .single-image-grid { display: grid; justify-content: center; margin-bottom: 20px; }
        .single-image-grid img { max-width: 600px; width: 100%; border-radius: 0; transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .image-grid img { width: 100%; height: 250px; object-fit: cover; border-radius: 10px; transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .single-image-grid img:hover, .image-grid img:hover { transform: scale(1.05); box-shadow: 0 15px 30px rgba(0,0,0,0.2); }
        .event-description { text-align: center; font-size: 1.1rem; color: #555; }
        .content-card { padding: 35px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.5); }
        .content-card h1 { font-size: 2.4rem; font-weight: 800; color: #333; text-align: center; margin-bottom: 25px; }
        .content-card h2 { font-size: 1.8rem; font-weight: 700; color: #27ae60; margin-top: 30px; margin-bottom: 15px; }
        .content-card h3 { font-size: 1.4rem; font-weight: 600; color: #333; margin-top: 25px; margin-bottom: 10px; }
        .content-card p, .content-card li { margin-bottom: 15px; line-height: 1.8; }
        .content-card ul, .content-card ol { padding-left: 40px; margin-bottom: 20px; }
        .image-container-wrapper { position: relative; display: inline-block; width: 100%; height: 100%; }
        .image-grid > .image-container-wrapper, .single-image-grid > .image-container-wrapper { display: flex; justify-content: center; align-items: center; }

        /* Styles for new editing functionality */
        .editable-container { position: relative; }
        .text-edit-icon { position: absolute; top: -5px; right: -5px; background-color: #ffc107; color: black; padding: 5px; border-radius: 50%; cursor: pointer; display: none; font-size: 12px; z-index: 10; }
        .editable-container:hover .text-edit-icon { display: block; }
        #edit-modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal-content { background-color: #fefefe; padding: 20px; border: 1px solid #888; width: 80%; max-width: 700px; border-radius: 10px; }
        .modal-content textarea { width: 100%; height: 250px; font-family: inherit; font-size: 1rem; padding: 10px; margin-bottom: 10px; box-sizing: border-box; }
        .modal-buttons { text-align: right; }
        .modal-buttons button { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;}
        #save-edit-btn { background-color: var(--bright-green); color: white; }
        #cancel-edit-btn { background-color: #ccc; }
        .edit-icon { position: absolute; top: 10px; right: 10px; background-color: rgba(0, 0, 0, 0.6); color: white; padding: 5px 8px; border-radius: 50%; cursor: pointer; display: none; z-index: 10; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo-container">
            <img src="../assets/logo.jpeg" alt="AAIS Logo" class="logo">
            <div class="brand-text">
                <span class="school-name">Army's Angels Integrated School, INC.</span>
            </div>
        </div>
        <ul class="nav-links">
            <li><a href="homepage.php">Home</a></li>
            <li><a href="history.php">History</a></li>
            <li><a href="fees.php">Fees Information</a></li>
            <li><a href="activities.php">School Activities</a></li>
            <li><a href="<?php echo htmlspecialchars($enrollment_url); ?>" target="_blank">Enroll Now</a></li>
            <li><a href="#contact" class="btn-nav">Contact Us</a></li>
        </ul>
    </nav>

    <div class="main-wrapper">
        <p style="color: white; text-align: center; font-size: 1.1rem; max-width: 800px; margin: 0 auto 30px auto;"><?php render_content('activities', 'page_intro', 'Default intro text.', $isAdmin); ?></p>
        
        <div class="event-section">
            <h2><?php render_content('activities', 'fieldtrip_title', 'Field Trip', $isAdmin); ?></h2>
            <div class="image-grid">
                <div class="image-container-wrapper">
                    <img src="../assets/fieldtrip1.jpg" alt="Field Trip 1" id="img_fieldtrip1">
                    <?php if ($isAdmin): ?><i class="fas fa-pencil-alt edit-icon" data-image-id="img_fieldtrip1" data-image-path="assets/fieldtrip1.jpg"></i><?php endif; ?>
                </div>
                <div class="image-container-wrapper">
                    <img src="../assets/fieldtrip2.jpg" alt="Field Trip 2" id="img_fieldtrip2">
                    <?php if ($isAdmin): ?><i class="fas fa-pencil-alt edit-icon" data-image-id="img_fieldtrip2" data-image-path="assets/fieldtrip2.jpg"></i><?php endif; ?>
                </div>
                <div class="image-container-wrapper">
                    <img src="../assets/fieldtrip3.jpg" alt="Field Trip 3" id="img_fieldtrip3">
                    <?php if ($isAdmin): ?><i class="fas fa-pencil-alt edit-icon" data-image-id="img_fieldtrip3" data-image-path="assets/fieldtrip3.jpg"></i><?php endif; ?>
                </div>
            </div>
            <div class="event-description"><?php render_content('activities', 'fieldtrip_desc', 'Default field trip description.', $isAdmin); ?></div>
        </div>

        <div class="event-section">
            <h2><?php render_content('activities', 'gpsoa_title', 'GPSOA and Foundation Celebration', $isAdmin); ?></h2>
            <div class="image-grid grid-3-col">
                <div class="image-container-wrapper"><img src="../assets/foundationday1.jpg" alt="Foundation Day 1" id="img_foundationday1"><?php if ($isAdmin): ?><i class="fas fa-pencil-alt edit-icon" data-image-id="img_foundationday1" data-image-path="assets/foundationday1.jpg"></i><?php endif; ?></div>
                <div class="image-container-wrapper"><img src="../assets/foundationday2.jpg" alt="Foundation Day 2" id="img_foundationday2"><?php if ($isAdmin): ?><i class="fas fa-pencil-alt edit-icon" data-image-id="img_foundationday2" data-image-path="assets/foundationday2.jpg"></i><?php endif; ?></div>
                <div class="image-container-wrapper"><img src="../assets/foundationday3.jpg" alt="Foundation Day 3" id="img_foundationday3"><?php if ($isAdmin): ?><i class="fas fa-pencil-alt edit-icon" data-image-id="img_foundationday3" data-image-path="assets/foundationday3.jpg"></i><?php endif; ?></div>
                <div class="image-container-wrapper"><img src="../assets/foundationday4.jpg" alt="Foundation Day 4" id="img_foundationday4"><?php if ($isAdmin): ?><i class="fas fa-pencil-alt edit-icon" data-image-id="img_foundationday4" data-image-path="assets/foundationday4.jpg"></i><?php endif; ?></div>
                <div class="image-container-wrapper"><img src="../assets/foundationday5.jpg" alt="Foundation Day 5" id="img_foundationday5"><?php if ($isAdmin): ?><i class="fas fa-pencil-alt edit-icon" data-image-id="img_foundationday5" data-image-path="assets/foundationday5.jpg"></i><?php endif; ?></div>
                <div class="image-container-wrapper"><img src="../assets/foundationday6.jpg" alt="Foundation Day 6" id="img_foundationday6"><?php if ($isAdmin): ?><i class="fas fa-pencil-alt edit-icon" data-image-id="img_foundationday6" data-image-path="assets/foundationday6.jpg"></i><?php endif; ?></div>
            </div>
            <div class="event-description"><?php render_content('activities', 'gpsoa_desc', 'Default GPSOA description.', $isAdmin); ?></div>
        </div>

        <div class="event-section">
            <h2><?php render_content('activities', 'recollection_title', 'Recollection', $isAdmin); ?></h2>
            <div class="single-image-grid">
                <div class="image-container-wrapper"><img src="../assets/gospelrecoll1.jpg" alt="Recollection" id="img_gospelrecoll1"><?php if ($isAdmin): ?><i class="fas fa-pencil-alt edit-icon" data-image-id="img_gospelrecoll1" data-image-path="assets/gospelrecoll1.jpg"></i><?php endif; ?></div>
            </div>
            <div class="event-description"><?php render_content('activities', 'recollection_desc', 'Default recollection description.', $isAdmin); ?></div>
        </div>

        <div class="event-section">
            <h2><?php render_content('activities', 'womens_month_title', 'Women’s Month Celebration', $isAdmin); ?></h2>
            <div class="single-image-grid">
                <div class="image-container-wrapper"><img src="../assets/womensceleb1.jpg" alt="Women's Month" id="img_womensceleb1"><?php if ($isAdmin): ?><i class="fas fa-pencil-alt edit-icon" data-image-id="img_womensceleb1" data-image-path="assets/womensceleb1.jpg"></i><?php endif; ?></div>
            </div>
            <div class="event-description"><?php render_content('activities', 'womens_month_desc', 'Default women\'s month description.', $isAdmin); ?></div>
        </div>

        <div class="content-card">
            <h1><?php render_content('activities', 'details_title', 'Activity Details', $isAdmin); ?></h1>
            <h2><?php render_content('activities', 'extra_curricular_title', 'A. EXTRA-CURRICULAR ACTIVITIES', $isAdmin); ?></h2>
            <div><?php render_content('activities', 'extra_curricular_desc', 'Default extra-curricular description.', $isAdmin); ?></div>
            
            <h2><?php render_content('activities', 'foundation_celebration_title', 'FOUNDATION CELEBRATION...', $isAdmin); ?></h2>
            <div><?php render_content('activities', 'foundation_celebration_desc', 'Default foundation celebration description.', $isAdmin, true); ?></div>

            <h2><?php render_content('activities', 'co_curricular_title', 'B. CO-CURRICULAR ACTIVITIES', $isAdmin); ?></h2>
            <div><?php render_content('activities', 'co_curricular_desc', 'Default co-curricular description.', $isAdmin); ?></div>
            <ul><?php render_content('activities', 'co_curricular_list', '<li>Default list</li>', $isAdmin, true); ?></ul>

            <h2><?php render_content('activities', 'student_council_title', 'C. STUDENT COUNCIL', $isAdmin); ?></h2>
            <div><?php render_content('activities', 'student_council_desc_1', 'Default council description 1.', $isAdmin); ?></div>
            <div><?php render_content('activities', 'student_council_desc_2', 'Default council description 2.', $isAdmin); ?></div>
            <ol type="a"><?php render_content('activities', 'student_council_list', '<li>Default list</li>', $isAdmin, true); ?></ol>
            
            <h2><?php render_content('activities', 'field_trips_title', 'D. FIELD TRIPS', $isAdmin); ?></h2>
            <div><?php render_content('activities', 'field_trips_desc', 'Default field trips description.', $isAdmin); ?></div>
        </div>
    </div>

    <footer id="contact">
        <div class="footer-wireframe-container">

            <div class="fw-col-left">
                <div class="fw-brand-row">
                    <img src="../assets/logo.jpeg" alt="AAIS Logo" class="fw-logo">
                    <span class="fw-school-name">Army's Angels Integrated School, INC.</span>
                </div>

                <div class="fw-row">
                    <a href="https://www.facebook.com/share/1D87zohRSJ/" target="_blank" class="social-link-clean">
                        <i class="fa-brands fa-facebook"></i> Visit Facebook Page
                    </a>
                </div>

                <div class="fw-row location-row">
                    <i class="fa-solid fa-location-dot fw-icon"></i>
                    <div class="fw-text">
                        <strong style="color: var(--bright-green);">Elementary Campus:</strong><br>
                        Blk 8 Lots 2&4 Yakal cor. Narra Sts. Engr's Hills, Taguig City
                    </div>
                </div>
            </div>

            <div class="fw-col-right">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Flag_of_the_Philippines.svg/320px-Flag_of_the_Philippines.svg.png"
                    class="fw-flag" alt="PH Flag">

                <div class="fw-row right-align-content">
                    <i class="fa-solid fa-envelope fw-icon"></i>
                    <span class="fw-text">aais.privskul@gmail.com</span>
                </div>

                <div class="fw-row right-align-content">
                    <i class="fa-solid fa-location-dot fw-icon"></i>
                    <div class="fw-text">
                        <strong style="color: var(--bright-green);">High School Campus:</strong><br>
                        Blk 24 Lots 2-5 Salazar St. Central Signal Village, Taguig City
                    </div>
                </div>

                <div class="fw-row right-align-content">
                    <i class="fa-solid fa-phone fw-icon"></i>
                    <div class="fw-text">
                        0917-855-1800 / 0998-842-9557<br>
                        8688-9578 (PLDT)
                    </div>
                </div>
            </div>

        </div>

        <div class="copyright">
            &copy; 2025 Army's Angels Integrated School, INC. All Rights Reserved.
        </div>
    </footer>

    <div id="edit-modal">
        <div class="modal-content">
            <h3>Edit Content</h3>
            <textarea id="edit-textarea"></textarea>
            <div class="modal-buttons">
                <button id="save-edit-btn">Save</button>
                <button id="cancel-edit-btn">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        <?php if ($isAdmin): ?>
        document.querySelectorAll('.edit-icon').forEach(icon => {
            icon.style.display = 'block'; 
            icon.addEventListener('click', function() {
                const imageId = this.dataset.imageId;
                const imagePath = this.dataset.imagePath;
                
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.accept = 'image/*';
                fileInput.onchange = async (e) => {
                    if (e.target.files.length > 0) {
                        const file = e.target.files[0];
                        const formData = new FormData();
                        formData.append('newImage', file);
                        formData.append('imagePath', imagePath);

                        try {
                            const response = await fetch('../php/upload_activity_image.php', {
                                method: 'POST',
                                body: formData
                            });
                            const result = await response.json();

                            if (result.success) {
                                document.getElementById(imageId).src = '../' + result.newPath + '?' + new Date().getTime();
                                alert('Image updated successfully!');
                            } else {
                                alert('Error updating image: ' + result.message);
                            }
                        } catch (error) {
                            console.error('Error uploading image:', error);
                            alert('An error occurred during image upload.');
                        }
                    }
                };
                fileInput.click();
            });
        });

        // --- TEXT EDITING SCRIPT ---
        const modal = document.getElementById('edit-modal');
        const textarea = document.getElementById('edit-textarea');
        const saveBtn = document.getElementById('save-edit-btn');
        const cancelBtn = document.getElementById('cancel-edit-btn');
        let currentEditingElement = null;

        document.querySelectorAll('.text-edit-icon').forEach(icon => {
            icon.addEventListener('click', function() {
                const section = this.dataset.section;
                currentEditingElement = document.querySelector(`div[data-section='${section}']`);
                textarea.value = currentEditingElement.innerHTML.trim();
                modal.style.display = 'flex';
            });
        });

        cancelBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        saveBtn.addEventListener('click', async () => {
            const newContent = textarea.value;
            const page = currentEditingElement.dataset.page;
            const section = currentEditingElement.dataset.section;

            const formData = new FormData();
            formData.append('page_name', page);
            formData.append('section_id', section);
            formData.append('content_text', newContent);

            try {
                const response = await fetch('../php/update_content.php', { method: 'POST', body: formData });
                const result = await response.json();

                if (result.success) {
                    currentEditingElement.innerHTML = newContent;
                    modal.style.display = 'none';
                    alert('Content updated successfully!');
                } else {
                    alert('Error updating content: ' + result.message);
                }
            } catch (error) {
                console.error('Error updating content:', error);
                alert('An error occurred during content update.');
            }
        });
        <?php endif; ?>

        // --- ACCORDION SCRIPT ---
        var acc = document.getElementsByClassName("accordion");
        for (var i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                var icon = this.querySelector('.fa-chevron-down');
                
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                    if(icon) icon.style.transform = "rotate(0deg)";
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                    if(icon) icon.style.transform = "rotate(180deg)";
                }
            });
        }
    </script>
</body>
</html>