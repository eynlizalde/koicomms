<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

$userId = $_SESSION['user_id'];

$sql_check = "SELECT enrollment_id FROM users WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $userId);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$user = $result_check->fetch_assoc();

if ($user['enrollment_id']) {
    echo json_encode(['enrollment_id' => $user['enrollment_id']]);
    exit;
}

$enrollmentId = "AAIS-" . date("Y") . "-" . strtoupper(uniqid());

$sql_update = "UPDATE users SET enrollment_id = ? WHERE id = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("si", $enrollmentId, $userId);

if ($stmt_update->execute()) {
    echo json_encode(['enrollment_id' => $enrollmentId]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to generate and save ID']);
}

$stmt_check->close();
$stmt_update->close();
$conn->close();
?>