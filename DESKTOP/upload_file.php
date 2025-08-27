<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "غير مصرح";
    exit;
}

include '../includes/db.php';

$user_id     = $_SESSION['user_id'];
$username    = $_SESSION['username'] ?? 'مستخدم';
$profile_img = $_SESSION['profile_img'] ?? 'img/use.jpg';
$description = trim($_POST['file_description'] ?? '');

if (empty($description)) {
    echo "يرجى إدخال وصف للملف.";
    exit;
}

if (isset($_FILES['zipfile']) && $_FILES['zipfile']['error'] === 0) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $originalName = basename($_FILES['zipfile']['name']);
    $filename = time() . '_' . preg_replace('/\s+/', '_', $originalName);
    $targetFile = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['zipfile']['tmp_name'], $targetFile)) {
        $stmt = $conn->prepare("INSERT INTO files (user_id, username, profile_img, file_name, file_path, file_description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $user_id, $username, $profile_img, $filename, $targetFile, $description);
        $stmt->execute();

        echo "تم رفع الملف بنجاح.";
    } else {
        echo "فشل في رفع الملف.";
    }
} else {
    echo "يرجى اختيار ملف ZIP صالح.";
}
?>
