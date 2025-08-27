<?php
require_once "includes/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

if ($check->num_rows > 0) {
    die("هذا البريد مستخدم بالفعل. يرجى استخدام بريد آخر.");
}
$check->close();

    $password = $_POST['password'];
    $confpass = $_POST['confpass'];

    if ($password !== $confpass) {
        die("كلمتا المرور غير متطابقتين.");
    }

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
        die("خطأ في رفع الصورة.");
    }

    $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
    $upload_dir = 'uploads/users/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $target_path = $upload_dir . $image_name;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        die("فشل في حفظ الصورة.");
    }

    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

    $username = $fname . ' ' . $lname;

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, profile_img) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_pass, $target_path);

    if ($stmt->execute()) {
        echo "تم إنشاء الحساب بنجاح!";
        header("Location: login.php");
    } else {
        echo "فشل إنشاء الحساب: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "طريقة الإرسال غير صحيحة.";
}
?>
