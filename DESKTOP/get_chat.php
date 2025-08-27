<?php
session_start();
require_once "../includes/db.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

$my_id = $_SESSION['user_id'];

$sql = "SELECT m.message, m.image_path, m.created_at, m.user_id, u.username, u.profile_img
        FROM messages m
        JOIN users u ON m.user_id = u.id
        ORDER BY m.created_at ASC
        LIMIT 100";

$result = $conn->query($sql);

$output = "";

while ($row = $result->fetch_assoc()) {
    $msg       = htmlspecialchars($row['message'] ?? '');
    $user_id   = $row['user_id'];
    $username  = htmlspecialchars($row['username']);
    
    $img       = !empty($row['profile_img']) ? "../" . $row['profile_img'] : '../img/use.jpg';
    $isMine    = ($user_id == $my_id);

    $alignment   = $isMine ? "flex-end" : "flex-start";
    $bubbleColor = $isMine ? "#dcf8c6" : "#ffffff";
    $marginSide  = $isMine ? "margin-left" : "margin-right";

    $output .= '<div class="chat-bubble" style="display: flex; justify-content: ' . $alignment . '; margin-bottom: 10px;">';
    $output .= '<div style="display: flex; align-items: flex-end; max-width: 70%;">';

    if (!$isMine) {
        $output .= '<img src="' . $img . '" class="chat-img" style="width: 35px; height: 35px; border-radius: 50%; ' . $marginSide . ': 10px;">';
    }

    $output .= '<div style="background-color: ' . $bubbleColor . '; padding: 10px 15px; border-radius: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">';

    if (!empty($msg)) {
        $output .= '<div>' . $msg . '</div>';
    }

  
    if (!empty($row['image_path'])) {
        $imgPath = "../" . $row['image_path']; 
        $output .= '<br><img src="' . $imgPath . '" class="chat-image" style="max-width: 100%; border-radius: 10px; margin-top: 5px;">';
    }

    $output .= '</div></div></div>';
}

echo $output;
$conn->close();
