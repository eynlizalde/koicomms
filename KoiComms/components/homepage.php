<?php
include '../php/database.php';

// Fetch the current enrollment URL
$enrollment_url = '#'; // Default fallback URL
$sql = "SELECT setting_value FROM settings WHERE setting_name = 'enrollment_url'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $enrollment_url = $row['setting_value'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Army's Angels Integrated School, INC.</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

    <header class="hero" id="hero-section">
        <div class="hero-bg" id="hero-bg"></div>
        <div class="hero-content" id="hero-content">
            <h1>Army's Angels Integrated Schools, INC.</h1>
            <p>Character Above All</p>
            <div class="hero-buttons">
                <a href="<?php echo htmlspecialchars($enrollment_url); ?>" target="_blank"
                    class="btn-primary">Apply Now</a>
                <a href="#tracks" class="btn-secondary">Explore Tracks</a>
                <a href="adminside.php" class="btn-secondary">Admin Login</a>
            </div>
        </div>
    </header>

    <section id="tracks" class="section-grey">
        <div class="container">
            <h2 class="section-title">Senior High School Tracks</h2>
            <p class="section-subtitle">Welcome to senior high school! We offer these tracks:</p>

            <div class="tracks-wrapper">
                <h3>1. Academic Track: prepares you for college</h3>
                <div class="track-grid">
                    <div class="track-card">
                        <div class="card-header">GAS – General Academic Strand</div>
                        <div class="card-body">
                            <p>Take this strand if you can’t decide just yet, and want to take a combination of
                                different subjects.</p>
                            <strong>Possibilities:</strong> employment, entrepreneurship, higher education, mid-level
                            skills development
                        </div>
                    </div>
                    <div class="track-card">
                        <div class="card-header">STEM – Science, Technology, Engineering, Mathematics</div>
                        <div class="card-body">
                            <p>Here you will learn scientific and mathematical skills, advance concepts and topics.</p>
                            <strong>RELATED COURSES AND CAREERS:</strong>
                            <ul>
                                <li>Chemistry - Chemist</li>
                                <li>Engineering (mechanical, civil, architecture, etc.) - Engineer</li>
                                <li>Biology - Biologist</li>
                                <li>Aviation - Pilot</li>
                                <li>Medicine - Nurse, Doctor</li>
                            </ul>
                        </div>
                    </div>
                    <div class="track-card">
                        <div class="card-header">ABM – Accountancy, Business, and Management</div>
                        <div class="card-body">
                            <p>Here you will learn accountancy, business administration, financial management, and
                                corporate finance and operation.</p>
                            <strong>RELATED COURSES AND CAREERS:</strong>
                            <ul>
                                <li>Accountancy - Accountant</li>
                                <li>Business administration - Administrative officer</li>
                                <li>Marketing - Marketing assistant</li>
                                <li>Tourism - Director</li>
                                <li>Banking and finance - Bookkeeper</li>
                                <li>Financial management - Sales manager</li>
                                <li>Commerce - Internal auditing</li>
                            </ul>
                        </div>
                    </div>
                    <div class="track-card">
                        <div class="card-header">HUMSS – Humanities and Social Science</div>
                        <div class="card-body">
                            <p>Here you will learn to improve your communication, learn people skills, and priesthood is
                                also a choice.</p>
                            <strong>RELATED COURSES AND CAREERS:</strong>
                            <ul>
                                <li>Communication arts - Lawyer</li>
                                <li>Liberal arts - HR personnel</li>
                                <li>Education - Psychologist</li>
                                <li>Criminology - Teacher</li>
                                <li>Other related social science and related courses - Social worker, Writer, Law
                                    enforcer, Journalist, Priest</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tracks-wrapper">
                <h3>2. Technical-Vocational Livelihood: this prepares you with job-ready skills</h3>
                <div class="track-grid">
                    <div class="track-card">
                        <div class="card-header">ICT – Information and Communication Technology</div>
                        <div class="card-body">
                            <p>Here you will learn to write computer programs, illustrating and designing websites,
                                technical drafting, animation, and java programming.</p>
                            <strong>RELATED COURSES AND CAREERS:</strong>
                            <ul>
                                <li>Computer science - Animator</li>
                                <li>Information and technology - Web designer</li>
                                <li>Digital illustration and animation - Data encoder</li>
                                <li>Graphic design - Graphic designer</li>
                                <li>Junior programmer</li>
                                <li>Online sales agent</li>
                                <li>Web illustrator</li>
                            </ul>
                            <p>Eligible for obtaining certification: TESDA certificates of competency, national
                                certificate (NC)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="admissions" class="admissions-layout section-light">
                <div class="requirements-box">
                    <h3>APPLY NOW:</h3>
                    <h4>Grade 10 completers from private schools:</h4>
                    <ul>
                        <li>no tuition fee – voucher value</li>
                        <li>will pay for miscellaneous fees – with free e-books</li>
                    </ul>
                    <h4>Grade 10 completers from the public schools</h4>
                    <ul>
                        <li>no tuition fee – voucher value</li>
                        <li>with free e-books</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

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

    <script>
        var acc = document.getElementsByClassName("accordion");
        for (var i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function () {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                var icon = this.querySelector('.fa-chevron-down');

                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                    if (icon) icon.style.transform = "rotate(0deg)";
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                    if (icon) icon.style.transform = "rotate(180deg)";
                }
            });
        }
        const heroSection = document.getElementById('hero-section');
        const heroBg = document.getElementById('hero-bg');
        const heroContent = document.getElementById('hero-content');

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
    </script>
</body>

</html>
