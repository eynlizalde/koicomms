<?php
session_start();
$isAdmin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');
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
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(30px); }
        }

        body {
            background: linear-gradient(135deg, #344E41 0%, #588157 100%);
            color: #2a2a2a;
            position: relative;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        body::before, body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            animation: float 8s infinite ease-in-out;
            z-index: -1;
        }
        body::before { width: 300px; height: 300px; top: -50px; left: -50px; }
        body::after { width: 400px; height: 400px; bottom: 0px; right: -50px; animation-delay: 4s; }

        .main-wrapper {
            padding: 120px 20px 80px 20px;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 30px;
            flex-grow: 1;
        }

        .content-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.5);
            padding: 35px;
        }

        .event-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .event-section h2 {
            font-size: 2rem;
            color: #27ae60;
            text-align: center;
            margin-bottom: 20px;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .image-grid.grid-3-col {
            grid-template-columns: repeat(3, 1fr);
        }

        .single-image-grid {
            display: grid;
            justify-content: center; 
            margin-bottom: 20px;
        }

        .single-image-grid img {
            max-width: 600px; 
            width: 100%;
            border-radius: 0; 
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .single-image-grid img:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .image-grid img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .image-grid img:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .event-description {
            text-align: center;
            font-size: 1.1rem;
            color: #555;
        }

        .content-card h1 {
            font-size: 2.4rem;
            font-weight: 800;
            color: #333;
            text-align: center;
            margin-bottom: 25px;
        }

        .content-card h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #27ae60;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .content-card h3 {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        .content-card p {
            margin-bottom: 15px;
            line-height: 1.8;
        }

        .content-card ul, .content-card ol {
            padding-left: 40px;
            margin-bottom: 20px;
        }

        .content-card li {
            margin-bottom: 10px;
        }

        .edit-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 5px 8px;
            border-radius: 50%;
            cursor: pointer;
            display: none; 
            z-index: 10;
        }
        .edit-icon:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }
        .image-container-wrapper {
            position: relative;
            display: inline-block; 
            width: 100%; 
            height: 100%; 
        }
        .image-grid > .image-container-wrapper {
            display: flex; 
            justify-content: center;
            align-items: center;
        }
        .single-image-grid > .image-container-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
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
            <li><a href="homepage.html">Home</a></li>
            <li><a href="history.html">History</a></li>
            <li><a href="fees.html">Fees Information</a></li>
            <li><a href="activities.php">School Activities</a></li>
            <li><a href="https://docs.google.com/forms/d/e/1FAIpQLSfgqKHwYmDm2FPWLBCyHL0awb6zPHps4rwwPDKNpnRU3maDSA/viewform" target="_blank">Enroll Now</a></li>
            <li><a href="#contact" class="btn-nav">Contact Us</a></li>
        </ul>
    </nav>

    <div class="main-wrapper">
        <p style="color: white; text-align: center; font-size: 1.1rem; max-width: 800px; margin: 0 auto 30px auto;">Participation in extra and co-curricular activities is greatly encouraged by the school as the best means to ensure a well-rounded education.</p>
        
        <div class="event-section">
            <h2>Field Trip</h2>
            <div class="image-grid">
                <div class="image-container-wrapper">
                    <img src="../assets/fieldtrip1.jpg" alt="Field Trip 1" id="img_fieldtrip1">
                    <?php if ($isAdmin): ?>
                    <i class="fas fa-pencil-alt edit-icon" data-image-id="img_fieldtrip1" data-image-path="assets/fieldtrip1.jpg"></i>
                    <?php endif; ?>
                </div>
                <div class="image-container-wrapper">
                    <img src="../assets/fieldtrip2.jpg" alt="Field Trip 2" id="img_fieldtrip2">
                    <?php if ($isAdmin): ?>
                    <i class="fas fa-pencil-alt edit-icon" data-image-id="img_fieldtrip2" data-image-path="assets/fieldtrip2.jpg"></i>
                    <?php endif; ?>
                </div>
                <div class="image-container-wrapper">
                    <img src="../assets/fieldtrip3.jpg" alt="Field Trip 3" id="img_fieldtrip3">
                    <?php if ($isAdmin): ?>
                    <i class="fas fa-pencil-alt edit-icon" data-image-id="img_fieldtrip3" data-image-path="assets/fieldtrip3.jpg"></i>
                    <?php endif; ?>
                </div>
            </div>
            <p class="event-description">After a busy month, students enjoyed a relaxing day out, promoting both physical and mental well-being. The trip also included a visit to a museum, offering valuable educational insights.</p>
        </div>

        <div class="event-section">
            <h2>GPSOA and Foundation Celebration</h2>
            <div class="image-grid grid-3-col">
                <div class="image-container-wrapper">
                    <img src="../assets/foundationday1.jpg" alt="Foundation Day 1" id="img_foundationday1">
                    <?php if ($isAdmin): ?>
                    <i class="fas fa-pencil-alt edit-icon" data-image-id="img_foundationday1" data-image-path="assets/foundationday1.jpg"></i>
                    <?php endif; ?>
                </div>
                <div class="image-container-wrapper">
                    <img src="../assets/foundationday2.jpg" alt="Foundation Day 2" id="img_foundationday2">
                    <?php if ($isAdmin): ?>
                    <i class="fas fa-pencil-alt edit-icon" data-image-id="img_foundationday2" data-image-path="assets/foundationday2.jpg"></i>
                    <?php endif; ?>
                </div>
                <div class="image-container-wrapper">
                    <img src="../assets/foundationday3.jpg" alt="Foundation Day 3" id="img_foundationday3">
                    <?php if ($isAdmin): ?>
                    <i class="fas fa-pencil-alt edit-icon" data-image-id="img_foundationday3" data-image-path="assets/foundationday3.jpg"></i>
                    <?php endif; ?>
                </div>
                <div class="image-container-wrapper">
                    <img src="../assets/foundationday4.jpg" alt="Foundation Day 4" id="img_foundationday4">
                    <?php if ($isAdmin): ?>
                    <i class="fas fa-pencil-alt edit-icon" data-image-id="img_foundationday4" data-image-path="assets/foundationday4.jpg"></i>
                    <?php endif; ?>
                </div>
                <div class="image-container-wrapper">
                    <img src="../assets/foundationday5.jpg" alt="Foundation Day 5" id="img_foundationday5">
                    <?php if ($isAdmin): ?>
                    <i class="fas fa-pencil-alt edit-icon" data-image-id="img_foundationday5" data-image-path="assets/foundationday5.jpg"></i>
                    <?php endif; ?>
                </div>
                <div class="image-container-wrapper">
                    <img src="../assets/foundationday6.jpg" alt="Foundation Day 6" id="img_foundationday6">
                    <?php if ($isAdmin): ?>
                    <i class="fas fa-pencil-alt edit-icon" data-image-id="img_foundationday6" data-image-path="assets/foundationday6.jpg"></i>
                    <?php endif; ?>
                </div>
            </div>
            <p class="event-description">One of the most anticipated school events, GPSOA features exciting tournaments in sports like chess, volleyball, basketball, and majorette. Winners are recognized during the Foundation Celebration, which also includes a lively dance intermission.</p>
        </div>

        <div class="event-section">
            <h2>Recollection</h2>
            <div class="single-image-grid">
                <div class="image-container-wrapper">
                    <img src="../assets/gospelrecoll1.jpg" alt="Recollection" id="img_gospelrecoll1">
                    <?php if ($isAdmin): ?>
                    <i class="fas fa-pencil-alt edit-icon" data-image-id="img_gospelrecoll1" data-image-path="assets/gospelrecoll1.jpg"></i>
                    <?php endif; ?>
                </div>
            </div>
            <p class="event-description">Strengthening our connection with the Lord is a key mission of our school. Every year, students participate in a meaningful recollection at the chapel beside the school.</p>
        </div>

        <div class="event-section">
            <h2>Women’s Month Celebration</h2>
            <div class="single-image-grid">
                <div class="image-container-wrapper">
                    <img src="../assets/womensceleb1.jpg" alt="Women's Month" id="img_womensceleb1">
                    <?php if ($isAdmin): ?>
                    <i class="fas fa-pencil-alt edit-icon" data-image-id="img_womensceleb1" data-image-path="assets/womensceleb1.jpg"></i>
                    <?php endif; ?>
                </div>
            </div>
            <p class="event-description">Honoring the achievements, strength, and contributions of women, a program is arranged to inspire students to appreciate and empower the women in their lives through meaningful reflections.</p>
        </div>

        <div class="content-card">
            <h1>Activity Details</h1>
            <h2>A. EXTRA-CURRICULAR ACTIVITIES</h2>
            <p>These are those which are not directly linked to academic studies but are essential to the development of a well-rounded education of the learner. Some of these are Onwards Staff, Girl Scouting, Boy Scouting, Math, Science, English clubs.</p>
            <h2>FOUNDATION CELEBRATION - 2nd week of February of every year</h2>
            <p><strong>Activities:</strong> Field Demonstration, Games, Quiz Bee & Spelling Contest, Ball Games, Cheer dance. Medals and Certificates are to be given to the best Performers during the Recognition/Graduation Day</p>

            <h2>B. CO-CURRICULAR ACTIVITIES</h2>
            <p>These are those which directly supplement and complement the school's academic program.</p>
            <ul>
                <li><strong>English:</strong> Literary Club</li>
                <li><strong>Science:</strong> Scientist's Club</li>
                <li><strong>Filipino:</strong> Balagtas Club</li>
                <li><strong>Math:</strong> Math Guilds</li>
                <li><strong>HELE:</strong> Homemakers, Computer Club</li>
                <li><strong>ART:</strong> Art Club</li>
                <li><strong>P.Ε.:</strong> Performing Arts Club, Dance Clubs</li>
            </ul>

            <h2>C. STUDENT COUNCIL</h2>
            <p>The student council represents the general populace. It is charged with the responsibility of working in harmony with the Faculty and Administration.</p>
            <p>No other organization can be formed without the:</p>
            <ol type="a">
                <li>approval of the Director,</li>
                <li>appointment of a faculty member as adviser and,</li>
                <li>a statement of the general aims and policies different from those other organization already existing in the school</li>
            </ol>
            
            <h2>D. FIELD TRIPS</h2>
            <p>Field trips are valued as supplementary to classroom activities. Whenever field trips are called for, pupils are required to submit a signed parental approval.</p>
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

    <script>
        <?php if ($isAdmin): ?>
        // Show edit icons for admin
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
                                document.getElementById(imageId).src = '../' + result.newPath + '?' + new Date().getTime(); // Append timestamp to bust cache
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
        <?php endif; ?>

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
        
        const heroSection = document.getElementById('hero-section');
        const heroBg = document.getElementById('hero-bg');
        const heroContent = document.getElementById('hero-content');

        if (heroSection && heroBg && heroContent) { 
            heroSection.addEventListener('mousemove', (e) => {
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;
                const bgX = -x * 30; 
                const bgY = -y * 30;
                const contentX = x * 10;
                const contentY = y * 10;

                heroBg.style.transform = `translate(${bgX}px, ${bgY}px) scale(1.05)`;
                heroContent.style.transform = `translate(${contentX}px, ${contentY}px)`;
            });
        }
    </script>
</body>
</html>
