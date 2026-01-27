<?php
session_start();
include '../php/database.php';

$isAdmin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');

// Fetch content for the fees page
$content = [];
$sql = "SELECT section_id, content_text FROM content WHERE page_name = 'fees'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $content[$row['section_id']] = $row['content_text'];
    }
}

// Fetch the current enrollment URL
$enrollment_url = '#'; // Default fallback URL
$sql_url = "SELECT setting_value FROM settings WHERE setting_name = 'enrollment_url'";
$result_url = $conn->query($sql_url);
if ($result_url && $result_url->num_rows > 0) {
    $row_url = $result_url->fetch_assoc();
    $enrollment_url = $row_url['setting_value'];
}

// Function to render editable content
function render_content($page, $section, $default, $is_admin, $is_html = false) {
    global $content;
    $text = isset($content[$section]) ? $content[$section] : $default;
    
    echo '<div class="editable-container">';
    echo "<div data-page='{$page}' data-section='{$section}'>";
    echo $text;
    echo "</div>";
    
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
    <title>Fees & Admission - AAIS</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Existing styles */
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(30px); } }
        body { background: linear-gradient(135deg, #344E41 0%, #588157 100%); color: #2a2a2a; position: relative; overflow-x: hidden; display: flex; flex-direction: column; min-height: 100vh; }
        body::before, body::after { content: ''; position: absolute; border-radius: 50%; background: rgba(255,255,255,0.05); animation: float 8s infinite ease-in-out; z-index: -1; }
        body::before { width: 300px; height: 300px; top: -50px; left: -50px; }
        body::after { width: 400px; height: 400px; bottom: 0px; right: -50px; animation-delay: 4s; }
        .main-wrapper { padding: 120px 20px 80px 20px; width: 100%; max-width: 1400px; margin: 0 auto; display: flex; flex-direction: column; gap: 30px; flex-grow: 1; }
        .top-grid { display: grid; grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; gap: 30px; height: 85vh; }
        .content-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); border-radius: 20px; box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2); border: 1px solid rgba(255, 255, 255, 0.5); padding: 35px; overflow-y: auto; }
        .payment-card { grid-column: 1; grid-row: 1 / 3; }
        .admission-card { grid-column: 2; grid-row: 1; }
        .fees-card { grid-column: 2; grid-row: 2; }
        .full-width-card { width: 100%; }
        .content-card h1 { position: -webkit-sticky; position: sticky; top: -35px; background: #FFFFFF; padding-top: 20px; padding-bottom: 20px; margin: -35px -35px 25px -35px; font-size: 2.4rem; font-weight: 800; color: #333; text-align: center; transition: text-shadow 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 15px; z-index: 10; }
        .content-card h1:hover { text-shadow: 0 0 10px rgba(39, 174, 96, 0.6); }
        .content-card h1 i { color: #27ae60; }
        .content-card h2 { font-size: 1.6rem; font-weight: 700; color: #27ae60; margin-top: 20px; margin-bottom: 15px; }
        .content-card p, .content-card li { font-size: 1.05rem; line-height: 1.9; color: #444; margin-bottom: 15px; }
        .content-card ul, .content-card ol { padding-left: 30px; margin-bottom: 20px; }
        .content-card ul ul, .content-card ol ol { margin-top: 10px; margin-bottom: 10px; padding-left: 25px; }
        footer { background: #344E41; color: #ffffff; border-top: 5px solid #588157; }
        footer .fw-text, footer .copyright, footer .social-link-clean { color: rgba(255,255,255,0.9) !important; }
        footer .fw-school-name { color: #ffffff !important; }

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
        <div class="top-grid">
            <div class="content-card payment-card">
                <h1><i class="fas fa-money-bill-wave"></i><?php render_content('fees', 'payment_schedule_title', 'Schedule of Payment', $isAdmin); ?></h1>
                <div><?php render_content('fees', 'payment_note', '<strong>Note:</strong> Books, P.E. Uniforms, Regular Uniforms, Miscellaneous and other fees are paid upon enrollment.', $isAdmin, true); ?></div>
                <h2><?php render_content('fees', 'tuition_fees_title', 'A. Tuition Fees may be paid on the following basis:', $isAdmin); ?></h2>
                <ul><?php render_content('fees', 'tuition_fees_list', '<li>Default list</li>', $isAdmin, true); ?></ul>
                <div><?php render_content('fees', 'payment_notification', 'Parents are requested to notify the Office of the Finance...', $isAdmin); ?></div>
                <h2><?php render_content('fees', 'refunds_title', 'B. Refunds of tuition fees...', $isAdmin); ?></h2>
                <ol><?php render_content('fees', 'refunds_list', '<li>Default list</li>', $isAdmin, true); ?></ol>
                <div><?php render_content('fees', 'refunds_note', 'This is applicable regardless...', $isAdmin); ?></div>
            </div>

            <div class="content-card admission-card">
                <h1><i class="fas fa-user-check"></i><?php render_content('fees', 'admission_req_title', 'Admission Requirements', $isAdmin); ?></h1>
                <div><?php render_content('fees', 'admission_privilege', "Admission to ARMY'S ANGELS...", $isAdmin); ?></div>
                <h2><?php render_content('fees', 'general_req_title', 'A. GENERAL REQUIREMENTS', $isAdmin); ?></h2>
                <ol><?php render_content('fees', 'general_req_list', '<li>Default list</li>', $isAdmin, true); ?></ol>
                <h2><?php render_content('fees', 'preschool_req_title', 'B. PRE SCHOOL', $isAdmin); ?></h2>
                <ol><?php render_content('fees', 'preschool_req_list', '<li>Default list</li>', $isAdmin, true); ?></ol>
            </div>
            
            <div class="content-card fees-card">
                <h1><i class="fas fa-tags"></i><?php render_content('fees', 'fees_adjustment_title', 'Adjustment of Fees', $isAdmin); ?></h1>
                <h2><?php render_content('fees', 'scholarship_title', 'A. Scholarship', $isAdmin); ?></h2>
                <div><?php render_content('fees', 'scholarship_desc', 'A student who garnered...', $isAdmin); ?></div>
                <h2><?php render_content('fees', 'discount_title', 'B. Discount', $isAdmin); ?></h2>
                <ol><?php render_content('fees', 'discount_list', '<li>Default list</li>', $isAdmin, true); ?></ol>
                <h2><?php render_content('fees', 'transferees_title', 'C. Transferees-Honor Pupils', $isAdmin); ?></h2>
                <div><?php render_content('fees', 'transferees_desc', 'Honor pupils who transferred in...', $isAdmin); ?></div>
                <ol><?php render_content('fees', 'transferees_list', '<li>Default list</li>', $isAdmin, true); ?></ol>
                <div><?php render_content('fees', 'transferees_req', '<strong>Requirement:</strong>...', $isAdmin, true); ?></div>
            </div>
        </div>

        <div class="content-card full-width-card">
            <h1><i class="fas fa-info-circle"></i><?php render_content('fees', 'other_info_title', 'Other Information', $isAdmin); ?></h1>
            <ol start="1" type="A"><?php render_content('fees', 'other_info_list', '<li>Default list</li>', $isAdmin, true); ?></ol>
        </div>
        
        <div class="content-card full-width-card">
            <h1><i class="fas fa-exclamation-triangle"></i><?php render_content('fees', 'enrollment_policies_title', 'Enrollment Policies', $isAdmin); ?></h1>
            <h2><?php render_content('fees', 'disqualification_title', 'DISQUALIFICATION FOR ENROLLMENT', $isAdmin); ?></h2>
            <div><?php render_content('fees', 'disqualification_desc', 'The school reserves the right...', $isAdmin); ?></div>
            <h2><?php render_content('fees', 'dropping_title', 'DROPPING OR WITHDRAWAL FROM THE COURSE', $isAdmin); ?></h2>
            <div><?php render_content('fees', 'dropping_desc_1', 'Miscellaneous fees are made in full...', $isAdmin); ?></div>
            <ul><?php render_content('fees', 'dropping_list', '<li>Default list</li>', $isAdmin, true); ?></ul>
            <div><?php render_content('fees', 'dropping_note', '<strong>NOTE:</strong> Students who withdraw...', $isAdmin, true); ?></div>
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
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Flag_of_the_Philippines.svg/320px-Flag_of_the_Philippines.svg.png" class="fw-flag" alt="PH Flag">
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
            &copy; 2025 Army's Angels Integrated School, Inc. All Rights Reserved.
        </div>
    </footer>

    <!-- Modal for editing text -->
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
        // This script is identical to the one in activities.php
        <?php if ($isAdmin): ?>
        const modal = document.getElementById('edit-modal');
        const textarea = document.getElementById('edit-textarea');
        const saveBtn = document.getElementById('save-edit-btn');
        const cancelBtn = document.getElementById('cancel-edit-btn');
        let currentEditingElement = null;

        document.querySelectorAll('.text-edit-icon').forEach(icon => {
            icon.style.display = 'inline-block';
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
    </script>
</body>
</html>