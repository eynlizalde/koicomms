<?php
include '../php/database.php';

// Fetch the current enrollment URL
$enrollment_url = '#'; // Default fallback URL
$sql_url = "SELECT setting_value FROM settings WHERE setting_name = 'enrollment_url'";
$result_url = $conn->query($sql_url);
if ($result_url && $result_url->num_rows > 0) {
    $row_url = $result_url->fetch_assoc();
    $enrollment_url = $row_url['setting_value'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - AAIS</title>
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

        .top-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 30px;
            height: 85vh; 
        }

        .content-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.5);
            padding: 35px;
            overflow-y: auto;
        }
        
        .history-card {
            grid-column: 1;
            grid-row: 1 / 3;
        }

        .vision-card {
            grid-column: 2;
            grid-row: 1;
        }
        
        .mission-card {
            grid-column: 2;
            grid-row: 2;
        }

        .full-width-card {
            width: 100%;
        }

        .history-card h1 {
            position: -webkit-sticky; 
            position: sticky;
            top: -35px; 
            background: #FFFFFF;
            padding-top: 20px;
            padding-bottom: 20px;
            margin-top: -35px; 
            margin-left: -35px; 
            margin-right: -35px; 
        }

        .content-card h1 {
            font-size: 2.4rem;
            font-weight: 800;
            color: #333;
            text-align: center;
            margin-bottom: 25px;
            transition: text-shadow 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .content-card h1:hover {
            text-shadow: 0 0 10px rgba(39, 174, 96, 0.6);
        }
        
        .content-card h1 i {
            color: #27ae60;
        }

        .content-card h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #27ae60;
            margin-top: 20px;
            margin-bottom: 15px;
        }

        .content-card p, .content-card .song {
            font-size: 1.05rem; 
            line-height: 1.9; 
            text-align: justify;
            color: #444; 
            margin-bottom: 20px;
            white-space: pre-wrap;
        }

        footer {
            background: #344E41; 
            color: #ffffff; 
            border-top: 5px solid #588157; 
        }
        footer .fw-text, footer .copyright, footer .social-link-clean {
            color: rgba(255,255,255,0.9) !important;
        }
        footer .fw-school-name {
            color: #ffffff !important;
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
        <div class="top-grid">
            <div class="content-card history-card">
                <h1><i class="fas fa-book-open"></i>History</h1>
                <p>Named after the Philippine Army Organization Army’s Angels Integrated School, Inc., was founded by LTC Milagros J. Baloro (N115133), who dedicated twenty years of service as a Military Nurse. With a background in pediatric nursing LTC Baloro’s commitment to child care led her to establish a preschool upon her retirement in June 1997. The initial facility is located in a 100-squaremeter space at Blk. 15 Lot 22 AFP/PNP Western Bicutan during the 1997-1998 school year, welcomed 20 students divided into 8 nursery and 12 kindergarten pupils. The school’s inaugural staff included Ms. Sheila P. Abello, a 1996 graduate of Southern Mindanao Colleges in Zamboanga, and Ms. Estela Segumalian from the University of the Philippines, serving as the first Principal.</p>
                <p>Due to parental demand, the founder applied for and received a DepEd Permit for the following school year, which led to the establishment of classes from Preschool to Grade 3. By the 1998-1999 school year, a new four-story building accommodated 194 students organized as follows: - Nursery – 35 – Kinder – 61 – Preparatory – 20 – Grade 1 – 46 – Grade 2 – 32. The student population grew steadily each year until 2003, when the existing building could not longer accommodate the increasing numbers. Consequently AAIS relocated to a new address at Blk. 8 Lots 2 & 4 Yakal cor. Narra St. Engineers Hills Signal Village Taguig City. There, a five-story structure was constructed, covering an area of approximately 2,200 square meters and featuring 19 classrooms, four faculty rooms, labs for TLE/HLE and science, computer labs, a guidance office, a registrar’s office, a library, a clinic, and an administrative office. This new facility received DepEd recognition for Preschool (No. P-018 in 2007). Elementary (No. E014 in 2007), and Secondary (No. S-014 in 2009) education.</p>
                <p>AAIS is distinguished by its adoption of the Montessori method in preschool, emphasizing a supportive environment that fosters individual development through active learning. This educational philosophy extends through elementary to senior high school, where a comprehensive approach to the K-12 curriculum prepares students across cognitive, affective, and psychomotor domains for future challenges.</p>
                <p>Now celebrating its 28th year, AAIS has nurtured a legacy of successful alumni in diverse fields. The school's enduring success is attributed to Dr. Milagros J. Baloro, whose leadership style is characterized by approachability and responsiveness, earning her widespread respect within the school community. The leadership team also includes Ms. Felicita Benavidez as Curriculum Head/Principal, along with four dedicated OICs managing various academic departments. The school's commitment to the holistic development of its students is further supported by Spiritual Directors, who enhance the spiritual life of the community.</p>
                <p>As AAIS evolves, it remains dedicated to reinforcing the MATATAG curriculum's focus on collaboration among teachers, students, parents, and the community, thereby amplifying its educational influence, service, and community involvement.</p>
            </div>

            <div class="content-card vision-card">
                <h1><i class="fas fa-eye"></i>Educational Vision</h1>
                <p>"The vision of Army's Angels Integrated School, INC.. is to be a premier non-sectarian and inclusive educational institution in Taguig City, fostering MATATAG individuals with life-ready skills and strong character to succeed in a transformative and dynamic society."</p>
            </div>
            
            <div class="content-card mission-card">
                <h1><i class="fas fa-bullseye"></i>Mission</h1>
                <p>"The mission of Army's Angels Integrated School, INC.. is to shape MATATAG students of character, guided by faith and moral values who are active, competent, resilient, and life ready, fostering patriotism and civic responsibility in building a better society. "</p>
            </div>
        </div>

        <div class="content-card full-width-card">
            <h1><i class="fas fa-music"></i>School Songs</h1>
            <h2>ALMA MATTER</h2>
            <div class="song">Lundo ng pangarap, mga kabataan, abutin ang tayog, pangarap na tangan, aaakibat nito, gagamunggong liwanag pilit sinusundan buo ang pag-asa sa dako pa roon, ay nakangiting haring araw.

Mula't sapul nang ang mundo'y ating masilayan, sumibol na sa damdamin, samo't saring agam-agam, nabuo sa isipan iba't ibang katanungan bakit, ano, kanino, sino, saan at kaylan

Nagkakahugis na unti-unti ang gagamunggong sinag AAIS ang nabuo, sinundan ko't kumikislap, di ko alintana magalusan, madapa ma't lahat puso ko'y may utos tuntunin ang liwanag.

Army's Angels Integrated School at mga magulang salamat sa liwanag, salamat sa alalay, binura nyo sa aming dibdib, mga bahid agam-agam Itinuwid ang landasing, tatahaking habang buhay.

Nagkakahugis na unti-unti ang gagamunggong sinag AAIS ang nabuo, sinundan ko't kumikislap, di ko alintana magalusan, madapa ma't lahat puso ko'y may utos tuntunin ang liwanag.

Army's Angels Integrated School at mga magulang salamat sa liwanag, salamat sa alalay, binura nyo sa aming dibdib, mga bahid agam-agam Itinuwid ang landasing, tatahaking habang buhay.</div>
        </div>
        
        <div class="content-card full-width-card">
            <h2>School Song</h2>
            <div class="song">Halina, halina hakbang lakad takbo Alapaap hawiin, takbo na tulinan mo abot kamay na lang, langit sa harap mo tagumpay ng kabtaan nakasalalay sayo.

AAIS MOOG NA NAGBIGAY LAKAS (2x)

Sandigan ng pag-asa ulirang paaralan mapagpalang bakuran, sa mga kabataan kabataang nagmimithi makamtan ang karunungan sa palad mo nakaukit, ang kanilang kapalaran

AAIS GABAY NG BAYAN SA EDUKASYON (2x)

Sa silong ng langit ikaw tanging tala, taas noo AAIS dahil ikaw ang dakila matapat, matatag, matibay, puno ng buhay marangal ka AAIS bukas ng karunungan

AAIS GABAY NG BAYAN SA EDUKASYON (2x)

Army's Angels Integrated School karamay ng bayan, na nagpupunyagi para sa kinabukasan.</div>
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
    <script src="../assets/js/main.js" defer></script>
</body>
</html>