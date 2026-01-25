<?php
session_start();
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: ../components/enrolleeside.php?error=Email and password are required.");
        exit();
    }

    $sql = "SELECT id, name, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: ../components/dashboard.php");
            exit();
        } else {
            header("Location: ../components/enrolleeside.php?error=Invalid email or password.");
            exit();
        }
    } else {
        header("Location: ../components/enrolleeside.php?error=Invalid email or password.");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../components/enrolleeside.php");
    exit();
}
?>