<?php
session_start();

header('Content-Type: application/json');

// 1. Verify admin session
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access. Admin login required.']);
    exit();
}

// Check if file was uploaded without errors
if (!isset($_FILES['newImage']) || $_FILES['newImage']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'File upload error or no file provided.']);
    exit();
}

$file = $_FILES['newImage'];
$imagePath = $_POST['imagePath'] ?? ''; // e.g., 'assets/fieldtrip1.jpg'

if (empty($imagePath)) {
    echo json_encode(['success' => false, 'message' => 'Original image path not provided.']);
    exit();
}

// Sanitize and validate imagePath to prevent directory traversal
// Ensure the path starts with 'assets/' and contains only allowed characters
$baseDir = dirname(__DIR__); // Go up one level from 'php' to the project root
$targetDir = $baseDir . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
$relativePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $imagePath); // Normalize path
$fullTargetPath = $baseDir . DIRECTORY_SEPARATOR . $relativePath;

// Ensure the target directory is within 'assets/' and the filename is valid
if (strpos($relativePath, 'assets' . DIRECTORY_SEPARATOR) !== 0 || !realpath($targetDir)) { // Check $targetDir not $fullTargetPath here
    echo json_encode(['success' => false, 'message' => 'Invalid or unsafe image path.']);
    exit();
}

// It's crucial to ensure that the image being overwritten actually exists within the designated assets directory
// and that the path provided by the client doesn't try to access other parts of the filesystem.
$realAssetPath = realpath($targetDir . basename($relativePath));
$expectedAssetPath = $targetDir . basename($relativePath);

// Double check that the real path of the target file is within the intended assets directory
// and that the original imagePath from POST corresponds to an actual file
if (!$realAssetPath || strpos($realAssetPath, realpath($targetDir)) === false) {
    echo json_encode(['success' => false, 'message' => 'Target image file not found or path is unsafe.']);
    exit();
}


// 3. Validate file type (only images)
$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($file['type'], $allowedMimeTypes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, GIF, WEBP are allowed.']);
    exit();
}

// Further check extension for safety (though MIME type is better)
$imageExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
if (!in_array($imageExtension, $allowedExtensions)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file extension.']);
    exit();
}

// Move the uploaded file, overwriting the old one
if (move_uploaded_file($file['tmp_name'], $fullTargetPath)) {
    // Return the relative path from the project root (as expected by the frontend)
    echo json_encode(['success' => true, 'newPath' => $relativePath, 'message' => 'Image updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file. Check directory permissions.']);
}

?>