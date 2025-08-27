<?php
session_start();

include '../includes/db.php';

$sql = "SELECT * FROM files ORDER BY uploaded_at DESC";
$result = $conn->query($sql);
?>

<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8" />
    <title>عرض الملفات المرفوعة</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body {
            background: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0; padding: 20px;
            color: #333;
        }
        .file-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 4px rgb(0 0 0 / 0.1);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }
        .user-info img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
        }
        .user-info b {
            font-size: 1.1em;
            color: #222;
        }
        .file-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }
        .file-info img.zip-icon {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        .file-info h3 {
            margin: 0;
            font-size: 1.3em;
            color: #555;
            word-break: break-word;
        }
        .file-description {
            font-size: 1em;
            color: #666;
            white-space: pre-wrap;
            margin-bottom: 15px;
        }
        .download-btn {
            display: inline-block;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 18px;
            font-size: 1em;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .download-btn:hover {
            background: #0056b3;
        }
        @media (max-width: 640px) {
            .file-card {
                padding: 12px 15px;
                max-width: 100%;
            }
            .file-info img.zip-icon {
                width: 50px;
                height: 50px;
            }
            .user-info img {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>
<body>

<?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()):
        $username = htmlspecialchars($row['username']);
        $filename = htmlspecialchars($row['file_name']);
        $filepath = htmlspecialchars($row['file_path']);
        $desc = nl2br(htmlspecialchars($row['file_description'] ?? ''));

        $raw_profile = $row['profile_img'];
        $profile_path = "../" . $raw_profile;
        $profile = (!empty($raw_profile) && file_exists($profile_path))
            ? htmlspecialchars($profile_path)
            : "../img/use.jpg";
        ?>
        <div class="file-card">
            <div class="user-info">
                <img src="<?= $profile ?>" alt="صورة المستخدم">
                <b><?= $username ?></b>
            </div>
            <div class="file-info">
                <img src="../img/zip.jpg" alt="ZIP" class="zip-icon">
                <h3><?= $filename ?></h3>
            </div>
            <p class="file-description"><?= $desc ?></p>
            <a href="<?= $filepath ?>" download class="download-btn">تحميل الملف</a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center; color:#666; font-size:1.1em;">لا توجد ملفات مرفوعة بعد.</p>
<?php endif; ?>

</body>
</html>

<?php
$conn->close();
?>
