<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$message = trim($_POST['message'] ?? '');
$image   = $_FILES['image'] ?? null;

if (empty($message) && empty($image)) {
    http_response_code(400);
    exit;
}

$image_path = null;

if ($image && $image['error'] === 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    
    if (!in_array($image['type'], $allowed_types)) {
        http_response_code(415);
        exit;
    }

    $upload_dir = '../uploads/chat/';
    $save_path  = 'uploads/chat/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $image_name = "img_" . uniqid() . "." . $ext;
    
    $full_path   = $upload_dir . $image_name;
    $image_path  = $save_path . $image_name;

    if (!move_uploaded_file($image['tmp_name'], $full_path)) {
        http_response_code(500);
        exit;
    }
}

$stmt = $conn->prepare("INSERT INTO messages (user_id, message, image_path, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iss", $user_id, $message, $image_path);

if (!$stmt->execute()) {
    http_response_code(500);
    exit;
}

http_response_code(204);
