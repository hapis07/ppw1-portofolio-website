<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $message = isset($_POST['message']) ? trim($_POST['message']) : null;
    
    // Basic validation
    if (empty($email) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Email and Message are required.']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit;
    }
    
    try {
        // Prepare statement
        // Note: We leave sender_name as NULL if empty to allow the database trigger to set it to 'Guest'
        // Also, submitted_at is not passed to let the trigger fill it.
        $stmt = $pdo->prepare("INSERT INTO contact_messages (sender_name, sender_email, message) VALUES (:name, :email, :message)");
        
        $final_name = ($name === '') ? null : $name;
        
        $stmt->bindParam(':name', $final_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Message sent successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send message.']);
        }
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
