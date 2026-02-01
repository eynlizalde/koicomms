<?php
session_start();
include 'database.php';

// Only admins can perform this action
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');

$response = ['success' => true, 'message' => 'Changes saved successfully.'];

// --- 1. HANDLE DELETIONS ---
if (isset($_POST['deleted_keys']) && is_array($_POST['deleted_keys'])) {
    foreach ($_POST['deleted_keys'] as $key) {
        $safe_key = $conn->real_escape_string($key);
        $content_key_pattern = $safe_key . '_%';

        // Delete from content table
        $stmt_content = $conn->prepare("DELETE FROM content WHERE section_id LIKE ?");
        $stmt_content->bind_param('s', $content_key_pattern);
        if (!$stmt_content->execute()) {
            $response['success'] = false;
            $response['message'] = 'Error deleting content from database.';
        }
        $stmt_content->close();

        // Find and delete image files and records
        $stmt_images = $conn->prepare("SELECT image_path FROM activity_images WHERE section_key = ?");
        $stmt_images->bind_param('s', $safe_key);
        $stmt_images->execute();
        $result = $stmt_images->get_result();
        while($row = $result->fetch_assoc()) {
            $file_to_delete = __DIR__ . '/../' . $row['image_path'];
            if (file_exists($file_to_delete)) {
                unlink($file_to_delete);
            }
        }
        $stmt_images->close();

        // Delete from activity_images table
        $stmt_del_images = $conn->prepare("DELETE FROM activity_images WHERE section_key = ?");
        $stmt_del_images->bind_param('s', $safe_key);
        if (!$stmt_del_images->execute()) {
            $response['success'] = false;
            $response['message'] = 'Error deleting images from database.';
        }
        $stmt_del_images->close();
    }
}


// --- 2. HANDLE CREATIONS ---
if (isset($_POST['new_section_titles']) && is_array($_POST['new_section_titles'])) {
    foreach ($_POST['new_section_titles'] as $index => $title) {
        $caption = $_POST['new_section_captions'][$index] ?? '';
        $temp_key = $_POST['new_section_keys'][$index] ?? '';
        
        // Sanitize title to create a permanent, safe key
        $perm_key = strtolower(trim($title));
        $perm_key = preg_replace('/[^a-z0-9_]+/', '_', $perm_key);
        $perm_key = trim($perm_key, '_');
        $perm_key = $perm_key . '_' . time(); 

        // Insert title and caption into content table
        $title_key = $perm_key . '_title';
        $desc_key = $perm_key . '_desc';

        $stmt_title = $conn->prepare("INSERT INTO content (page_name, section_id, content_text) VALUES ('activities', ?, ?)");
        $stmt_title->bind_param('ss', $title_key, $title);
        if (!$stmt_title->execute()) { $response['success'] = false; $response['message'] = 'Error saving title.'; }
        $stmt_title->close();

        $stmt_desc = $conn->prepare("INSERT INTO content (page_name, section_id, content_text) VALUES ('activities', ?, ?)");
        $stmt_desc->bind_param('ss', $desc_key, $caption);
        if (!$stmt_desc->execute()) { $response['success'] = false; $response['message'] = 'Error saving caption.'; }
        $stmt_desc->close();
        
        // Handle file uploads
        $file_input_name = "new_images_{$temp_key}";
        if (isset($_FILES[$file_input_name])) {
            $files = $_FILES[$file_input_name];
            $display_order = 0;

            foreach ($files['tmp_name'] as $file_index => $tmp_name) {
                if (is_uploaded_file($tmp_name)) {
                    $original_name = $files['name'][$file_index];
                    $extension = pathinfo($original_name, PATHINFO_EXTENSION);
                    $new_filename = $perm_key . '_' . ($display_order + 1) . '.' . $extension;
                    $upload_path = __DIR__ . '/../assets/' . $new_filename;

                    if (move_uploaded_file($tmp_name, $upload_path)) {
                        $db_path = 'assets/' . $new_filename;
                        $stmt_img = $conn->prepare("INSERT INTO activity_images (section_key, image_path, display_order) VALUES (?, ?, ?)");
                        $stmt_img->bind_param('ssi', $perm_key, $db_path, $display_order);
                        if (!$stmt_img->execute()) { $response['success'] = false; $response['message'] = 'Error saving image path to DB.'; }
                        $stmt_img->close();
                        $display_order++;
                    } else {
                        $response['success'] = false;
                        $response['message'] = 'Error moving uploaded file.';
                    }
                }
            }
        }
    }
}

echo json_encode($response);
?>