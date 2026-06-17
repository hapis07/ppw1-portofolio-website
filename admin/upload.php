<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'] ?? null;
    $image = $_FILES['image'] ?? null;

    if ($event_id && $image && $image['error'] == 0) {
        $allowed_exts = ['jpg', 'jpeg', 'png'];
        $file_info = pathinfo($image['name']);
        $ext = strtolower($file_info['extension']);

        if (in_array($ext, $allowed_exts)) {
            $upload_dir = '../uploads/';
            
            // Create directory if not exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $new_filename = uniqid('event_', true) . '.' . $ext;
            $destination = $upload_dir . $new_filename;

            if (move_uploaded_file($image['tmp_name'], $destination)) {
                // Insert into database
                try {
                    $stmt = $pdo->prepare("INSERT INTO event_galleries (event_id, image_path) VALUES (:event_id, :image_path)");
                    $image_path_db = 'uploads/' . $new_filename;
                    $stmt->bindParam(':event_id', $event_id);
                    $stmt->bindParam(':image_path', $image_path_db);
                    
                    if ($stmt->execute()) {
                        $_SESSION['upload_msg'] = "Image uploaded successfully!";
                    } else {
                        $_SESSION['upload_msg'] = "Database error while saving image path.";
                    }
                } catch(PDOException $e) {
                    $_SESSION['upload_msg'] = "Database error: " . $e->getMessage();
                }
            } else {
                $_SESSION['upload_msg'] = "Failed to move uploaded file.";
            }
        } else {
            $_SESSION['upload_msg'] = "Invalid file type. Only JPG and PNG are allowed.";
        }
    } else {
        $_SESSION['upload_msg'] = "Please provide an event and a valid image.";
    }
}

header("Location: dashboard.php");
exit;
?>
