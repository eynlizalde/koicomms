<?php
session_start();
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: ../components/adminside.php?error=Email and password are required.");
        exit();
    }

    $sql = "SELECT id, name, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify password AND check role
        if (password_verify($password, $user['password']) && $user['role'] === 'admin') {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role']; // Set the role in the session
            header("Location: ../components/admin_dashboard.php");
            exit();
        } else {
            // Error for wrong password OR not being an admin
            header("Location: ../components/adminside.php?error=Invalid credentials or not an admin.");
            exit();
        }
    } else {
        header("Location: ../components/adminside.php?error=Invalid credentials or not an admin.");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../components/adminside.php");
    exit();
}
?>