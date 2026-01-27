<?php
session_start();
include 'database.php';

// 1. Verify admin session
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../components/adminside.php?error=Unauthorized access.');
    exit();
}

// 2. Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $setting_name = $_POST['setting_name'] ?? '';
    // The value of the setting is sent in a field with the same name as the setting_name
    $setting_value = $_POST[$setting_name] ?? '';

    // 3. Validate data
    if (empty($setting_name) || empty($setting_value)) {
        header('Location: ../components/admin_dashboard.php?error=Invalid setting data provided.');
        exit();
    }
    
    // Validate URL if that's the setting being updated
    if ($setting_name === 'enrollment_url' && filter_var($setting_value, FILTER_VALIDATE_URL) === false) {
        header('Location: ../components/admin_dashboard.php?error=Invalid URL format. Please provide a full URL (e.g., https://...).');
        exit();
    }

    // 4. Update the database
    $sql = "UPDATE settings SET setting_value = ? WHERE setting_name = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        header('Location: ../components/admin_dashboard.php?error=Database error: Could not prepare statement.');
        exit();
    }

    $stmt->bind_param("ss", $setting_value, $setting_name);

    if ($stmt->execute()) {
        header('Location: ../components/admin_dashboard.php?message=Setting updated successfully.');
    } else {
        header('Location: ../components/admin_dashboard.php?error=Database error: Could not update setting.');
    }

    $stmt->close();
    $conn->close();
    exit();

} else {
    // Redirect if accessed directly without POST data
    header('Location: ../components/admin_dashboard.php');
    exit();
}
?>
