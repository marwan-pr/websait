<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once "../includes/db.php";

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT username, profile_img FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $profile_img);
$stmt->fetch();
$stmt->close();

$img = 'img/use.jpg';

if (!empty($profile_img)) {
    $path = str_replace('\\', '/', $profile_img);
    if (file_exists($path)) {
        $img = $path;
    } elseif (file_exists("../$path")) {
        $img = "../$path";
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة الشخصية</title>
    <link rel="stylesheet" href="css/me.css">
</head>
<body>
    <div class="card">
        <div class="profile-pic">
            <img src="<?= htmlspecialchars($img) ?>" alt="صورة المستخدم" style="width:120px;height:120px;border-radius:50%;object-fit:cover;">
        </div>
        <h2 class="username"><?= htmlspecialchars($username) ?></h2>
        <button type="button" class="logout-btn" onclick="logout()">تسجيل الخروج</button>
    </div>

    <script>
    function logout() {
        window.top.location.href = '../logout.php';
    }
    </script>
</body>
</html>
