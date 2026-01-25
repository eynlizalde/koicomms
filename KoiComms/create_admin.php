<?php
include 'php/database.php';

// --- Admin User Details ---
$name = 'Admin';
$email = 'aais@gmail.com';
$password = '1998characaboveallarmysangelsinc';
$role = 'admin';
// --------------------------

// Securely hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if email already exists
$sql_check = "SELECT email FROM users WHERE email = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    echo "Error: An account with the email '{$email}' already exists.";
    exit();
}
$stmt_check->close();

// Insert the new admin user
$sql_insert = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("ssss", $name, $email, $hashed_password, $role);

if ($stmt_insert->execute()) {
    echo "Successfully created admin user:<br>";
    echo "<b>Email:</b> {$email}<br>";
    echo "<b>Password:</b> (the one you provided)<br>";
} else {
    echo "Error creating admin user: " . $conn->error;
}

$stmt_insert->close();
$conn->close();

echo "<br><br><h3 style='color:red;'>IMPORTANT: Please delete this file (create_admin.php) immediately.</h3>";

?>