<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include '../includes/db.php';

$uid = $_SESSION['user_id'];
$conn->query("UPDATE users SET last_activity = NOW() WHERE id = $uid");

$username = $_SESSION['username'] ?? 'مستخدم';
$profile_img = 'img/use.jpg';
if (!empty($_SESSION['profile_img'])) {
    $path = $_SESSION['profile_img'];
    if (strpos($path, '../') !== 0 && file_exists("../$path")) {
        $profile_img = "../$path";
    } elseif (file_exists($path)) {
        $profile_img = $path;
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<link rel="icon" type="image/png" href="../img/them.jfif">

  <meta charset="UTF-8">
  <title>شبكة مربعات</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/s2.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/s3.css">
  <link rel="icon" href="data:,">
</head>
<body>
<div class="all">


  <div class="p us">
    <img src="<?php echo htmlspecialchars($profile_img); ?>" class="img_u" alt="صورة المستخدم">
    <br>
    <b class="name_u"><?php echo htmlspecialchars($username); ?></b>
    <br><br>
    <form action="../logout.php" method="POST">
      <button class="btn" type="submit">تسجيل الخروج</button>
    </form>
  </div>


  <div class="p all_u_s">
    <h2 class="tit_u_s">المستخدمين النشطين</h2>
    <div class="box_u_s" id="activeUsers"></div>
  </div>


  <div class="p fail">
    <div class="f1" id="uploadedFiles"></div>

    <div class="f2">
      <h3>رفع ملف</h3>
      <form id="uploadForm" enctype="multipart/form-data">
        <textarea class="tex_f" name="file_description" placeholder="يجب عليك وصف الملف لكي يبقي العمل منضم" required></textarea>
        <button type="button" onclick="document.getElementById('fileInput').click()" class="b_z">اختر ملف ZIP</button>
        <input type="file" id="fileInput" name="zipfile" accept=".zip" style="display: none;" required>
        <br><br>
        <button type="submit" class="b_z">رفع الملف</button>
        <p id="uploadProgress" style="font-family: monospace; color: green; margin-top: 10px;"></p>
      </form>
    </div>
  </div>

 
  <div class="p all_box_chat">
    <div class="title">
      <h2 class="tit">دردشة جماعية 📨</h2>
    </div>
    <div class="bock_chat" id="chatBox"></div>
    <div class="chat-input">
      <input type="text" placeholder="اكتب رسالة..." class="text-input" id="chatMessage">
      <label for="image-input" class="image-label">📷</label>
      <input type="file" id="image-input" accept="image/*" hidden>
      <button class="send-btn" onclick="sendMessage()">إرسال</button>
    </div>
    <audio id="chat-sound" src="../oudio/notification.mp3" preload="auto"></audio>
  </div>

</div>


<script>
  let userInteracted = false;
  document.addEventListener('click', () => { userInteracted = true; });

  const chatBox = document.getElementById("chatBox");
  const chatSound = document.getElementById("chat-sound");

  let lastHTML = "";
  let lastUsersHTML = "";
  let lastFilesHTML = "";

  function fetchChat() {
    fetch("get_chat.php")
      .then(res => res.text())
      .then(html => {
        if (html !== lastHTML) {
          const isAtBottom = (chatBox.scrollTop + chatBox.clientHeight) >= (chatBox.scrollHeight - 100);
          chatBox.innerHTML = html;
          if (isAtBottom) chatBox.scrollTop = chatBox.scrollHeight;
          if (html.length > lastHTML.length && userInteracted) chatSound.play();
          lastHTML = html;
        }
      });
  }

  function fetchActiveUsers() {
    fetch("get_active_users.php")
      .then(res => res.text())
      .then(html => {
        if (html !== lastUsersHTML) {
          document.getElementById("activeUsers").innerHTML = html;
          lastUsersHTML = html;
        }
      });
  }

  function loadFiles() {
    fetch("get_files.php")
      .then(res => res.text())
      .then(html => {
        if (html !== lastFilesHTML) {
          document.getElementById("uploadedFiles").innerHTML = html;
          lastFilesHTML = html;
        }
      });
  }

  function sendMessage() {
    const message = document.getElementById("chatMessage").value.trim();
    const imageFile = document.getElementById("image-input").files[0];
    const formData = new FormData();
    formData.append("message", message);
    if (imageFile) formData.append("image", imageFile);

    fetch("send_message.php", {
      method: "POST",
      body: formData
    })
    .then(() => {
      document.getElementById("chatMessage").value = "";
      document.getElementById("image-input").value = "";
      fetchChat();
    });
  }

 
  document.getElementById("uploadForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();
    const progressText = document.getElementById("uploadProgress");
    const file = document.getElementById("fileInput").files[0];

    if (!file) {
      progressText.textContent = "يرجى اختيار ملف.";
      return;
    }

    xhr.upload.addEventListener("progress", function(e) {
      if (e.lengthComputable) {
        const uploadedMB = (e.loaded / (1024 * 1024)).toFixed(1);
        const totalMB = (e.total / (1024 * 1024)).toFixed(1);
        progressText.textContent = `تم رفع ${uploadedMB}MB / ${totalMB}MB`;
      }
    });

    xhr.onload = function() {
      progressText.textContent = xhr.responseText;
      form.reset();
      loadFiles();
    };

    xhr.onerror = function() {
      progressText.textContent = "حدث خطأ أثناء رفع الملف.";
    };

    xhr.open("POST", "upload_file.php");
    xhr.send(formData);
  });

  window.onload = () => {
    fetchChat();
    fetchActiveUsers();
    loadFiles();
  };

  setInterval(fetchChat, 3000);
  setInterval(fetchActiveUsers, 3000);
  setInterval(loadFiles, 5000);
</script>
</body>
</html>
