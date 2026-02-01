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
        .event-section { position: relative; background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .delete-section-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            font-size: 1.2rem;
            line-height: 1;
            padding: 0;
            padding-bottom: 3px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: background-color 0.2s;
        }
        .delete-section-btn:hover {
            background-color: #c0392b;
        }
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
        #save-activities-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 15px 30px;
            font-size: 1.2rem;
            font-weight: 600;
            background-color: #ffc107;
            color: black;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            z-index: 1500;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            display: none; /* Initially hidden */
            transition: transform 0.2s ease-out;
        }
        #save-activities-btn:hover {
            transform: scale(1.05);
        }
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
        <?php if ($isAdmin): ?>
        <div style="text-align: center; margin-bottom: 30px;">
            <button id="add-section-btn" style="padding: 12px 25px; font-size: 1.1rem; cursor: pointer; background-color: #27ae60; color: white; border: none; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">Add New Section</button>
        </div>
        <?php endif; ?>
        <p style="color: white; text-align: center; font-size: 1.1rem; max-width: 800px; margin: 0 auto 30px auto;"><?php render_content('activities', 'page_intro', 'Default intro text.', $isAdmin); ?></p>
        
        <?php
        // --- DYNAMICALLY RENDER ALL EVENT SECTIONS ---

        // Define the order and layouts for the original, default sections
        $default_section_keys = ['fieldtrip', 'gpsoa', 'recollection', 'womens_month'];
        $section_layouts = [
            'gpsoa' => 'image-grid grid-3-col',
            'recollection' => 'single-image-grid',
            'womens_month' => 'single-image-grid'
        ];

        // Find any new sections created by the user
        $new_section_keys = [];
        $sql_new_keys = "SELECT section_id FROM content WHERE page_name = 'activities' AND section_id LIKE '%_title' AND section_id NOT IN ('fieldtrip_title', 'gpsoa_title', 'recollection_title', 'womens_month_title')";
        $result_new_keys = $conn->query($sql_new_keys);
        if ($result_new_keys) {
            while($row = $result_new_keys->fetch_assoc()) {
                $new_section_keys[] = str_replace('_title', '', $row['section_id']);
            }
        }
        
        // Combine default and new sections
        $all_section_keys = array_merge($default_section_keys, $new_section_keys);

        // Loop through each section key and render the section
        foreach ($all_section_keys as $key):
            // Get all images for this section
            $images = [];
            $stmt_images = $conn->prepare("SELECT image_path FROM activity_images WHERE section_key = ? ORDER BY display_order ASC");
            $stmt_images->bind_param('s', $key);
            $stmt_images->execute();
            $result_images = $stmt_images->get_result();
            while ($row_image = $result_images->fetch_assoc()) {
                $images[] = $row_image['image_path'];
            }
            $stmt_images->close();
            
            // Determine the correct grid class based on image count and predefined layouts
            $grid_class = 'image-grid';
            if (isset($section_layouts[$key])) {
                $grid_class = $section_layouts[$key];
            } elseif (count($images) === 1) {
                $grid_class = 'single-image-grid';
            }
        ?>
        <div class="event-section" data-section-key="<?php echo htmlspecialchars($key); ?>">
            <?php if ($isAdmin): ?><button class="delete-section-btn">&times;</button><?php endif; ?>
            <h2><?php render_content('activities', $key.'_title', 'New Activity', $isAdmin); ?></h2>
            
            <div class="<?php echo $grid_class; ?>">
                <?php foreach ($images as $index => $image_path):
                    // Ensure unique IDs for images, especially if keys can repeat or have similar names
                    $imageId = 'img_' . htmlspecialchars($key) . '_' . $index;
                ?>
                    <div class="image-container-wrapper">
                        <img src="../<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($key) . ' image ' . ($index + 1); ?>" id="<?php echo $imageId; ?>">
                        <?php if ($isAdmin): ?>
                            <i class="fas fa-pencil-alt edit-icon" data-image-id="<?php echo $imageId; ?>" data-image-path="<?php echo htmlspecialchars($image_path); ?>"></i>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="event-description"><?php render_content('activities', $key.'_desc', '', $isAdmin); ?></div>
        </div>
        <?php endforeach; ?>
        
        <div class="content-card">
            <h1><?php render_content('activities', 'details_title', 'IX. SCHOOL ACTIVITIES', $isAdmin); ?></h1>
            <p><?php render_content('activities', 'details_intro', 'Participation in extra and co-curricular activities is greatly encouraged by the school as the best means to ensure a well-rounded education.', $isAdmin); ?></p>

            <h2><?php render_content('activities', 'extra_curricular_title', 'A. EXTRA-CURRICULAR ACTIVITIES', $isAdmin); ?></h2>
            <div><?php render_content('activities', 'extra_curricular_desc', "are those which are not directly linked to academic studies but are essential to the development of a well-rounded education of the learner.<br><br>Some of these are Onwards Staff, Girl Scouting, Boy Scouting, Math, Science, English clubs.", $isAdmin, true); ?></div>
            
            <h2><?php render_content('activities', 'foundation_celebration_title', 'FOUNDATION CELEBRATION', $isAdmin); ?></h2>
            <div><?php render_content('activities', 'foundation_celebration_desc', "-\t2nd week of February of every year<br><br>Activities: \tField Demonstration, Games, Quiz Bee & Spelling Contest, Ball Games, Cheer dance<br>Medals and Certificates are to be given to the best Performers during the Recognition/Graduation Day", $isAdmin, true); ?></div>

            <h2><?php render_content('activities', 'co_curricular_title', 'B. CO-CURRICULAR ACTIVITIES', $isAdmin); ?></h2>
            <div><?php render_content('activities', 'co_curricular_desc', "are those which directly supplement and complement the school's academic program.", $isAdmin); ?></div>
            <ul><?php render_content('activities', 'co_curricular_list', "<li>English: Literary Club</li><li>Science: Scientist's Club</li><li>Filipino: Balagtas Club</li><li>Math: Math Guilds</li><li>HELE: Homemakers, Computer Club</li><li>ART: Art Club</li><li>P.Ε.: Performing Arts Club, Dance Clubs</li>", $isAdmin, true); ?></ul>

            <h2><?php render_content('activities', 'student_council_title', 'C. STUDENT COUNCIL', $isAdmin); ?></h2>
            <div><?php render_content('activities', 'student_council_desc_1', 'The student council represents the general populace. It is charged with the responsibility of working in harmony with the Faculty and Administration.', $isAdmin); ?></div>
            <div><?php render_content('activities', 'student_council_desc_2', 'No other organization can be formed without the', $isAdmin); ?></div>
            <ol type="a"><?php render_content('activities', 'student_council_list', "<li>approval of the Director,</li><li>appointment of a faculty member as adviser and,</li><li>a statement of the general aims and policies different from those other organization already existing in the school</li>", $isAdmin, true); ?></ol>
            
            <h2><?php render_content('activities', 'field_trips_title', 'D. FIELD TRIPS', $isAdmin); ?></h2>
            <div><?php render_content('activities', 'field_trips_desc', 'Field trips are valued as supplementary to classroom activities. Whenever field trips are called for, pupils are required to submit a signed parental approval.', $isAdmin); ?></div>
        </div>
    </div>

    <?php if ($isAdmin): ?>
    <button id="save-activities-btn">Save All Changes</button>
    <?php endif; ?>

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

        // --- DYNAMIC ACTIVITY MANAGEMENT SCRIPT ---

        const saveActivitiesBtn = document.getElementById('save-activities-btn');
        let changesMade = false;

        function showSaveButton() {
            if (!changesMade && saveActivitiesBtn) {
                saveActivitiesBtn.style.display = 'block';
                changesMade = true;
            }
        }

        // 1. ADD NEW SECTION LOGIC
        document.getElementById('add-section-btn')?.addEventListener('click', () => {
            const sectionTitle = prompt("Enter the title for the new section:");
            if (!sectionTitle) return;

            const numImagesStr = prompt("How many images will this section have?");
            const numImages = parseInt(numImagesStr, 10);
            if (isNaN(numImages) || numImages <= 0) {
                alert("Please enter a valid number.");
                return;
            }

            const sectionCaption = prompt("Enter the caption for the section:");

            // Create new section element
            const newSection = document.createElement('div');
            newSection.className = 'event-section new-section'; // Mark as new
            
            // Create a unique temporary key
            const tempKey = 'new_' + sectionTitle.toLowerCase().replace(/\s+/g, '_').replace(/[^\w-]/g, '') + '_' + Date.now();
            newSection.dataset.sectionKey = tempKey;


            // Add delete button
            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'delete-section-btn';
            deleteBtn.innerHTML = '&times;';
            newSection.appendChild(deleteBtn);

            // Add title
            const titleElement = document.createElement('h2');
            titleElement.className = 'section-title'; // Add class for easy selection
            titleElement.textContent = sectionTitle;
            newSection.appendChild(titleElement);

            // Create image grid
            const imageGrid = document.createElement('div');
            imageGrid.className = 'image-grid';
            if (numImages === 1) imageGrid.className = 'single-image-grid';
            else if (numImages === 3 || numImages === 6) imageGrid.className = 'image-grid grid-3-col';

            for (let i = 0; i < numImages; i++) {
                const wrapper = document.createElement('div');
                wrapper.className = 'image-container-wrapper';
                wrapper.style.cssText = 'border: 2px dashed #ccc; padding: 20px; text-align: center; display: flex; align-items: center; justify-content: center;';
                
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.name = `new_images_${tempKey}[]`; // Name for backend processing
                fileInput.accept = 'image/*';
                
                wrapper.appendChild(fileInput);
                imageGrid.appendChild(wrapper);
            }
            newSection.appendChild(imageGrid);

            // Add main caption
            const captionElement = document.createElement('div');
            captionElement.className = 'event-description section-caption'; // Add class
            captionElement.textContent = sectionCaption || '';
            newSection.appendChild(captionElement);

            // Append new section after the last existing one
            const allSections = document.querySelectorAll('.event-section');
            const lastSection = allSections.length > 0 ? allSections[allSections.length - 1] : document.querySelector('.main-wrapper > p');
            lastSection.insertAdjacentElement('afterend', newSection);

            showSaveButton();
        });

        // 2. DELETE LOGIC (handles both new and existing sections)
        document.querySelector('.main-wrapper').addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-section-btn')) {
                if (confirm('Are you sure you want to delete this section?')) {
                    const sectionToDelete = e.target.closest('.event-section');
                    if (sectionToDelete) {
                        if (sectionToDelete.classList.contains('new-section')) {
                            // If it's a new, unsaved section, just remove it
                            sectionToDelete.remove();
                        } else {
                            // If it's an existing section, hide it and mark for deletion
                            sectionToDelete.style.display = 'none';
                            sectionToDelete.classList.add('marked-for-deletion');
                        }
                        showSaveButton();
                    }
                }
            }
        });

        // 3. IMAGE PREVIEW LOGIC
        document.querySelector('.main-wrapper').addEventListener('change', function(e) {
            if (e.target.tagName === 'INPUT' && e.target.type === 'file' && e.target.files[0]) {
                const file = e.target.files[0];
                const wrapper = e.target.closest('.image-container-wrapper');
                if (wrapper) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const img = document.createElement('img');
                        img.src = event.target.result;

                        // Apply styles to make the preview match the final look
                        const isSingleGrid = wrapper.parentElement.classList.contains('single-image-grid');
                        if (isSingleGrid) {
                            img.style.maxWidth = '600px';
                            img.style.width = '100%';
                            img.style.borderRadius = '0';
                        } else {
                            img.style.width = '100%';
                            img.style.height = '250px';
                            img.style.objectFit = 'cover';
                            img.style.borderRadius = '10px';
                        }

                        wrapper.innerHTML = '';
                        wrapper.style.border = 'none';
                        wrapper.style.padding = '0';
                        wrapper.appendChild(img);
                        // Re-add the original file input, but hide it so the file data is still available for submission
                        e.target.style.display = 'none';
                        wrapper.appendChild(e.target);
                    }
                    reader.readAsDataURL(file);
                }
            }
        });

        // 4. SAVE ALL CHANGES LOGIC
        saveActivitiesBtn?.addEventListener('click', async () => {
            saveActivitiesBtn.textContent = 'Saving...';
            saveActivitiesBtn.disabled = true;

            const formData = new FormData();

            // Collect sections marked for deletion
            document.querySelectorAll('.marked-for-deletion').forEach(section => {
                formData.append('deleted_keys[]', section.dataset.sectionKey);
            });

            // Collect new sections
            document.querySelectorAll('.new-section').forEach((section, index) => {
                const title = section.querySelector('.section-title').textContent;
                const caption = section.querySelector('.section-caption').textContent;
                const tempKey = section.dataset.sectionKey;
                
                formData.append('new_section_titles[]', title);
                formData.append('new_section_captions[]', caption);
                formData.append('new_section_keys[]', tempKey);

                // Collect files for this section
                section.querySelectorAll('input[type="file"]').forEach(fileInput => {
                    if (fileInput.files.length > 0) {
                        formData.append(`new_images_${tempKey}[]`, fileInput.files[0]);
                    }
                });
            });

            try {
                const response = await fetch('../php/manage_activities.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    alert('Changes saved successfully!');
                    location.reload();
                } else {
                    alert('An error occurred: ' + result.message);
                    saveActivitiesBtn.textContent = 'Save All Changes';
                    saveActivitiesBtn.disabled = false;
                }
            } catch (error) {
                console.error('Save error:', error);
                alert('A critical error occurred while saving.');
                saveActivitiesBtn.textContent = 'Save All Changes';
                saveActivitiesBtn.disabled = false;
            }
        });

        // --- TEXT EDITING SCRIPT ---
        const modal = document.getElementById('edit-modal');
        const textarea = document.getElementById('edit-textarea');
        const saveModalBtn = document.getElementById('save-edit-btn');
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

        saveModalBtn.addEventListener('click', async () => {
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

    </script>
</body>
</html>
