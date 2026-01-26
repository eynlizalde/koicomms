<?php
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Army's Angels Integrated School, INC.</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../styles/styles.css"> 
    
    <style>
        /* NEW STYLES FOR THE LOGIN FORM */
        .login-wrapper {
            margin-top: 30px;
            
            background: rgba(255, 255, 255, 0.15); /* Glass effect */
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            width: 100%;
            max-width: 350px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .login-wrapper h3 {
            color: white;
            text-align: center;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #333;
        }

        .login-input {
            width: 100%; /* Full width of container */
            padding: 12px 12px 12px 40px; /* Space for icon */
            border: none;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            box-sizing: border-box; /* Ensures padding doesn't expand width */
            outline: none;
        }

        .btn-login {
            background-color: #2ecc71; /* Green to match your theme */
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            transition: 0.3s;
            text-transform: uppercase;
            width: 100%;
        }

        .btn-login:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
        }

        #login-msg {
            text-align: center;
            font-size: 12px;
            margin-top: 5px;
            font-weight: 600;
            min-height: 20px;
            color: #ff6b6b;
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
            <li><a href="activities.html">School Activities</a></li>
            <li><a href="enrolleeside.php">School Portal</a></li>
            <li><a href="#contact" class="btn-nav">Contact Us</a></li>
        </ul>
    </nav>

    <header class="hero" id="hero-section">
        <div class="hero-bg" id="hero-bg"></div>
        <div class="hero-content" id="hero-content" style="display: flex; flex-direction: column; align-items: center;">
            <h1>Army's Angels Integrated Schools, INC.</h1>
            <p>Character Above All</p>
            
            <form action="../php/login.php" method="POST" class="login-wrapper">
                <h3>Enrollee Login</h3>
                
                <div class="input-group">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" id="email" name="email" class="login-input" placeholder="Email Address" required>
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="password" name="password" class="login-input" placeholder="Password" required>
                </div>

                <button type="submit" class="btn-login">Sign In</button>
                <p id="login-msg">
                    <?php
                    if (isset($_GET['error'])) {
                        echo htmlspecialchars($_GET['error']);
                    }
                    ?>
                </p>
            </form>
        </div>
    </header>

    <footer id="contact">
        <!-- Footer content remains the same -->
    </footer>

    <script>
        // Hero background animation script
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
