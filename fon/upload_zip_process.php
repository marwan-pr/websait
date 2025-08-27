<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "غير مصرح";
    exit;
}

include '../includes/db.php';
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'مستخدم';
$profile_img = $_SESSION['profile_img'] ?? 'img/use.jpg';
$description = trim($_POST['description'] ?? '');

if (empty($description)) {
    echo "يرجى إدخال وصف للملف.";
    exit;
}

if (!isset($_FILES['zip_file']) || $_FILES['zip_file']['error'] !== 0) {
    echo "يرجى اختيار ملف ZIP صالح.";
    exit;
}

$uploadDir = realpath(__DIR__ . '/../DESKTOP/uploads');
if ($uploadDir === false) {
    $parentDir = realpath(__DIR__ . '/../DESKTOP');
    if ($parentDir === false) {
        echo "مجلد DESKTOP غير موجود.";
        exit;
    }
    $uploadDir = $parentDir . '/uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
}
$uploadDir = rtrim($uploadDir, '/') . '/';

$filename = time() . '_' . preg_replace('/\s+/', '_', basename($_FILES['zip_file']['name']));
$targetFile = $uploadDir . $filename;

if (move_uploaded_file($_FILES['zip_file']['tmp_name'], $targetFile)) {
    $stmt = $conn->prepare("INSERT INTO files (user_id, username, profile_img, file_name, file_path, file_description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $username, $profile_img, $filename, $targetFile, $description);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "تم رفع الملف بنجاح.";
    } else {
        echo "خطأ في حفظ بيانات الملف في قاعدة البيانات.";
    }
    $stmt->close();
} else {
    echo "فشل في رفع الملف.";
}
