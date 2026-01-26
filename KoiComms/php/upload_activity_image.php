<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access. Admin login required.']);
    exit();
}

if (!isset($_FILES['newImage']) || $_FILES['newImage']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'File upload error or no file provided.']);
    exit();
}

$file = $_FILES['newImage'];
$imagePath = $_POST['imagePath'] ?? ''; 

if (empty($imagePath)) {
    echo json_encode(['success' => false, 'message' => 'Original image path not provided.']);
    exit();
}


$baseDir = dirname(__DIR__); 
$targetDir = $baseDir . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
$relativePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $imagePath); // Normalize path
$fullTargetPath = $baseDir . DIRECTORY_SEPARATOR . $relativePath;

if (strpos($relativePath, 'assets' . DIRECTORY_SEPARATOR) !== 0 || !realpath($targetDir)) { // Check $targetDir not $fullTargetPath here
    echo json_encode(['success' => false, 'message' => 'Invalid or unsafe image path.']);
    exit();
}

$realAssetPath = realpath($targetDir . basename($relativePath));
$expectedAssetPath = $targetDir . basename($relativePath);


if (!$realAssetPath || strpos($realAssetPath, realpath($targetDir)) === false) {
    echo json_encode(['success' => false, 'message' => 'Target image file not found or path is unsafe.']);
    exit();
}

$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($file['type'], $allowedMimeTypes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, GIF, WEBP are allowed.']);
    exit();
}

$imageExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
if (!in_array($imageExtension, $allowedExtensions)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file extension.']);
    exit();
}

if (move_uploaded_file($file['tmp_name'], $fullTargetPath)) {
    echo json_encode(['success' => true, 'newPath' => $relativePath, 'message' => 'Image updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file. Check directory permissions.']);
}

?>