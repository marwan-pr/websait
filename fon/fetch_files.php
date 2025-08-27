<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
?>


<?php



include '../includes/db.php';

$sql = "SELECT * FROM files ORDER BY uploaded_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
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

        <div class="ff">
            <div>
                <img src="<?php echo $profile; ?>" class="g_ud" alt="صورة المستخدم">
                <b><?php echo $username; ?></b>
            </div>
            <div class="pzip">
                <img src="../img/zip.jpg" alt="ZIP" class="zip">
                <h3 class="titzip"><?php echo $filename; ?></h3>
            </div>
            <p class="texzip"><?php echo $desc; ?></p>
            <a href="<?php echo $filepath; ?>" download>
                <button class="btnd">تحميل الملف</button>
            </a>
        </div>
        <br>

        <?php
    }
} else {
    echo '<div style="padding:10px;">لا توجد ملفات مرفوعة بعد.</div>';
}
?>
