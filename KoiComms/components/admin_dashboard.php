<?php
session_start();
include '../php/database.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: adminside.php?error=Please log in as an admin to view this page.');
    exit;
}

$adminName = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Admin';

// Fetch the current enrollment URL
$enrollment_url = '';
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
    <title>Admin Dashboard - AAIS</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f4f4; }
        .dashboard-wrapper { padding-top: 120px; padding-bottom: 80px; min-height: 80vh; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; }
        .dashboard-container { text-align: center; width: 100%; max-width: 900px; padding: 20px; }
        .header-title { font-size: 1.2rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #555; margin-bottom: 10px; }
        .greeting { font-size: 2.5rem; font-weight: 800; margin-bottom: 40px; color: #333; }
        .button-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; padding: 0 50px; margin-top: 40px; }
        .dash-btn { display: flex; justify-content: center; align-items: center; text-decoration: none; color: #333; font-weight: 700; font-size: 1.1rem; text-transform: uppercase; padding: 25px; background: white; border-radius: 50px; border: 3px solid var(--bright-green); box-shadow: 8px 8px 0px 0px var(--leaf-green); transition: all 0.2s ease; position: relative; }
        .dash-btn:hover { background-color: #f9f9f9; transform: translateY(-2px); box-shadow: 10px 10px 0px 0px var(--leaf-green); }
        .dash-btn:active { transform: translate(4px, 4px); box-shadow: 4px 4px 0px 0px var(--leaf-green); }

        .settings-card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: left; margin-bottom: 40px; }
        .settings-card h2 { margin-top: 0; color: var(--dark-green); }
        .form-group { margin-bottom: 15px; }
        .form-group label { font-weight: 600; display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 1rem; box-sizing: border-box; }
        .btn-save { background-color: var(--bright-green); color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: 600; }
        .feedback-msg { margin-top: 15px; font-weight: 600; }

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
            <li><a href="homepage.php">Home</a></li>
            <li><a href="../php/logout.php">Log Out</a></li>
        </ul>
    </nav>

    <div class="dashboard-wrapper">
        <div class="dashboard-container">
            <div class="header-title">Admin Dashboard</div>
            <h1 class="greeting">Welcome, <?php echo $adminName; ?>!</h1>

            <div class="settings-card">
                <h2>Manage Website Settings</h2>
                <form action="../php/update_settings.php" method="POST">
                    <div class="form-group">
                        <label for="enrollment_url">"Enroll Now" / "Apply Now" Link</label>
                        <input type="url" id="enrollment_url" name="enrollment_url" value="<?php echo htmlspecialchars($enrollment_url); ?>" required>
                    </div>
                    <button type="submit" name="setting_name" value="enrollment_url" class="btn-save">Save Link</button>
                </form>
                <?php if (isset($_GET['message'])): ?>
                    <p class="feedback-msg" style="color: green;"><?php echo htmlspecialchars($_GET['message']); ?></p>
                <?php endif; ?>
                 <?php if (isset($_GET['error'])): ?>
                    <p class="feedback-msg" style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
                <?php endif; ?>
            </div>

            <div class="button-grid">
                <a href="fees.php" class="dash-btn">Edit Fees Page</a>
                <a href="activities.php" class="dash-btn">Edit Activities Page</a>
            </div>
        </div>
    </div>

</body>
</html>