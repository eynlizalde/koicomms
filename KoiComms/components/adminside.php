<?php
session_start();
include '../php/database.php';

// If an admin is already logged in, redirect to the admin dashboard
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header('Location: admin_dashboard.php');
    exit;
}

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
    <title>Admin Login - Army's Angels Integrated School, INC.</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css"> 
    <style>
        .login-wrapper {
            margin-top: 30px;
            background: rgba(255, 255, 255, 0.15);
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
        .login-wrapper h3 { color: white; text-align: center; margin-bottom: 10px; font-weight: 600; }
        .input-group { position: relative; }
        .input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #333; }
        .login-input { width: 100%; padding: 12px 12px 12px 40px; border: none; border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 14px; box-sizing: border-box; outline: none; }
        .btn-login { background-color: var(--dark-green); color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: 700; text-transform: uppercase; width: 100%; transition: 0.3s; }
        .btn-login:hover { background-color: var(--leaf-green); }
        #login-msg { text-align: center; font-size: 12px; margin-top: 5px; font-weight: 600; min-height: 20px; color: #ff6b6b; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo-container">
            <img src="../assets/logo.jpeg" alt="AAIS Logo" class="logo">
            <div class="brand-text"><span class="school-name">Army's Angels Integrated School, INC.</span></div>
        </div>
        <ul class="nav-links">
            <li><a href="homepage.php">Home</a></li>
            <li><a href="<?php echo htmlspecialchars($enrollment_url); ?>" target="_blank">Enroll Now</a></li>
        </ul>
    </nav>

    <header class="hero" id="hero-section">
        <div class="hero-bg" id="hero-bg"></div>
        <div class="hero-content" id="hero-content" style="display: flex; flex-direction: column; align-items: center;">
            <h1>Admin Portal</h1>
            
            <form action="../php/admin_login.php" method="POST" class="login-wrapper">
                <h3>Admin Login</h3>
                
                <div class="input-group">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" id="email" name="email" class="login-input" placeholder="Admin Email" required>
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
                    } else if (isset($_GET['message'])) { // Display general messages too
                        echo htmlspecialchars($_GET['message']);
                    }
                    ?>
                </p>
                <div style="text-align: center; margin-top: 15px;">
                    <a href="forgot_password.php" style="color: white; font-size: 0.9em;">Forgot Password?</a>
                </div>
            </form>
            <a href="homepage.php" style="color: white; text-decoration: underline; margin-top: 20px;">&larr; Back to Home</a>
        </div>
    </header>

    <script src="../assets/js/main.js" defer></script>
</body>
</html>
