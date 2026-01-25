<?php
include 'database.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$retype_password = $_POST['retype-password'];

if ($password !== $retype_password) {
    echo "Passwords do not match.";
    exit();
}

// Check if email already exists
$sql = "SELECT email FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Email already exists.";
    exit();
}


$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param("sss", $name, $email, $hashed_password);

if ($stmt->execute()) {
    header("Location: ../components/homepage.html?registration=success");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>