<?php
session_start();

// If user is not logged in or is not an admin, redirect to admin login page
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: adminside.php?error=Please log in as an admin to view this page.');
    exit;
}

$adminName = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AAIS</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f4f4; }
        .dashboard-wrapper { padding-top: 120px; padding-bottom: 80px; min-height: 80vh; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .dashboard-container { text-align: center; width: 100%; max-width: 900px; padding: 20px; }
        .header-title { font-size: 1.2rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #555; margin-bottom: 10px; }
        .greeting { font-size: 2.5rem; font-weight: 800; margin-bottom: 60px; color: #333; }
        .button-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; padding: 0 50px; }
        .dash-btn { display: flex; justify-content: center; align-items: center; text-decoration: none; color: #333; font-weight: 700; font-size: 1.1rem; text-transform: uppercase; padding: 25px; background: white; border-radius: 50px; border: 3px solid var(--bright-green); box-shadow: 8px 8px 0px 0px var(--leaf-green); transition: all 0.2s ease; position: relative; }
        .dash-btn:hover { background-color: #f9f9f9; transform: translateY(-2px); box-shadow: 10px 10px 0px 0px var(--leaf-green); }
        .dash-btn:active { transform: translate(4px, 4px); box-shadow: 4px 4px 0px 0px var(--leaf-green); }
        @media (max-width: 768px) { .button-grid { grid-template-columns: 1fr; } .greeting { font-size: 1.8rem; } }
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
            <li><a href="../php/logout.php">Log Out</a></li>
        </ul>
    </nav>

    <div class="dashboard-wrapper">
        <div class="dashboard-container">
            <div class="header-title">Admin Dashboard</div>
            <h1 class="greeting">Welcome, <?php echo $adminName; ?>!</h1>

            <div class="button-grid">
                <a href="enrollees.php" class="dash-btn">View Enrollees</a>
                <a href="fees.html" class="dash-btn">Information on Fees</a>
                <a href="history.html" class="dash-btn">History</a>
                <a href="activities.php" class="dash-btn">School Activities</a>
            </div>
        </div>
    </div>

</body>
</html>
