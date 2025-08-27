<?php
session_start();
include '../includes/db.php';

$cutoff = date("Y-m-d H:i:s", time() - 30);

$sql = "SELECT id, username, profile_img FROM users WHERE last_activity >= '$cutoff' GROUP BY id ORDER BY username";
$result = $conn->query($sql);

$seen_ids = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $uid = $row['id'];
        if (in_array($uid, $seen_ids)) continue;
        $seen_ids[] = $uid;

        $username = htmlspecialchars($row['username']);
        $img = 'img/use.jpg';

        if (!empty($row['profile_img'])) {
            $path = $row['profile_img'];
            if (strpos($path, '../') !== 0 && file_exists("../$path")) {
                $img = "../$path";
            } elseif (file_exists($path)) {
                $img = $path;
            }
        }

        echo '
        <div class="user-online" style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
            <span style="width:10px;height:10px;background:#2ecc71;border-radius:50%;display:inline-block;"></span>
            <img src="' . $img . '" alt="صورة" style="width:30px;height:30px;border-radius:50%;">
            <span>' . $username . '</span>
        </div>';
    }
} else {
    echo '<div style="padding:10px; color:gray;">لا يوجد مستخدمون نشطون حالياً.</div>';
}
?>
