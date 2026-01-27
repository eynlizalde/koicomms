<?php
session_start();
include 'database.php';

header('Content-Type: application/json');

// 1. Verify admin session
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

// 2. Get POST data
$page_name = $_POST['page_name'] ?? '';
$section_id = $_POST['section_id'] ?? '';
$content_text = $_POST['content_text'] ?? '';

// 3. Validate data
if (empty($page_name) || empty($section_id)) {
    echo json_encode(['success' => false, 'message' => 'Missing page or section identifier.']);
    exit();
}

// 4. Update the database
// Using ON DUPLICATE KEY UPDATE is robust; it works for both existing and potentially new content sections.
$sql = "INSERT INTO content (page_name, section_id, content_text) VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE content_text = VALUES(content_text)";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Database error: Could not prepare statement.']);
    exit();
}

$stmt->bind_param("sss", $page_name, $section_id, $content_text);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Content updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: Could not update content. ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
