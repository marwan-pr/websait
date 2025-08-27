<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">

  <title>دردشة جماعية</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body, html {
      height: 100%;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f7fc;
      padding-bottom: 20px;
    }

    .chat-container {
      display: flex;
      flex-direction: column;
      height: 100%;
      max-width: 480px;
      margin: 0 auto;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      position: relative;
      overflow: hidden;
    }

    .chat-messages {
      flex: 1;
      overflow-y: auto;
      padding: 20px;
      font-size: 14px;
      line-height: 1.6;
      height: 70%;
      background: #f9f9f9;
    }

    .chat-input-bar {
      display: flex;
      padding: 12px 20px;
      background: #fff;
      border-top: 1px solid #ddd;
      position: fixed;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 100%;
      max-width: 480px;
      z-index: 10;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .chat-input-bar input[type="text"] {
      flex: 1;
      padding: 12px 15px;
      border-radius: 20px;
      border: 1px solid #ddd;
      font-size: 14px;
      margin-right: 10px;
      outline: none;
      transition: border-color 0.3s ease;
    }

    .chat-input-bar input[type="text"]:focus {
      border-color: #4CAF50;
    }

    .chat-input-bar button {
      padding: 12px 15px;
      border: none;
      background: #4CAF50;
      color: #fff;
      font-size: 16px;
      border-radius: 35%;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .chat-input-bar button:hover {
      background-color: #45a049;
    }

    .attach-btn {
      cursor: pointer;
      font-size: 20px;
      margin-left: 10px;
    }

    .chat-img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 10px;
    }



 


    .chat-image {
      width: 100%;
      max-width: 250px;
      border-radius: 10px;
      margin-top: 10px;
    }

    .chat-bubble::before {
      content: "";
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 0;
      height: 0;
      border-left: 10px solid transparent;
      border-right: 10px solid transparent;
    }

    .chat-bubble.mine::before {
      right: -10px;
      border-top: 10px solid #dcf8c6;
    }

    .chat-bubble:not(.mine)::before {
      left: -10px;
      border-top: 10px solid #f1f0f0;
    }

  </style>
</head>
<body>
  <header class="header">
    <img src="../img/them.jfif" class="logo">
    <h1 class="title">GALAXY ARTS</h1>
    <img src="../img/Hamburger_icon.svg.png" class="menu-btn" onclick="toggleMenu()">
  </header>

  <nav class="menu" id="menu">
    <button onclick="loadPage('me.php')">الملف الشخصي</button>
    <button onclick="window.location.href = ('group_chat.php')">الدردشات</button>
    <button onclick="loadPage('display_files.php')">الملفات</button>
    <button onclick="loadPage('upload_zip.php')">رفع الملفات</button>
  </nav>

  <div class="chat-container">
    <div class="chat-messages" id="chat-messages">
      <div style="text-align:center;color:#888;">جارٍ تحميل الرسائل...</div>

      <br><br>
    </div><br>

    <form class="chat-input-bar" onsubmit="return false;">
      <label class="attach-btn" title="إرسال صورة">📎
        <input type="file" accept="image/*" hidden>
      </label>
      <input type="text" class="message-input" placeholder="اكتب رسالة...">
      <button type="submit">إرسال</button>
    </form>
  </div>

  <script>
    let lastContent = "";

    function fetchMessages() {
      fetch("../DESKTOP/get_chat.php")
        .then(res => res.text())
        .then(html => {
          const box = document.getElementById("chat-messages");
          if (html !== lastContent) {
            const atBottom = box.scrollTop + box.clientHeight >= box.scrollHeight - 50;
            box.innerHTML = html;
            if (atBottom) box.scrollTop = box.scrollHeight;
            lastContent = html;

            const snd = new Audio("msg.mp3");
            snd.play().catch(() => {});
          }
        });
    }

    setInterval(fetchMessages, 3000);
    fetchMessages();

    const form = document.querySelector(".chat-input-bar");
    const input = document.querySelector(".message-input");
    const fileInput = document.querySelector(".attach-btn input");

    form.addEventListener("submit", sendMessage);

    function sendMessage(e) {
      e.preventDefault();
      const text = input.value.trim();
      const file = fileInput.files[0];
      if (!text && !file) return;

      const data = new FormData();
      data.append("message", text);
      if (file) data.append("image", file);

      fetch("send_message.php", { method: "POST", body: data })
        .then(res => {
          if (res.status === 204) {
            input.value = "";
            fileInput.value = "";
            fetchMessages();
          } else {
            alert("حدث خطأ أثناء إرسال الرسالة.");
          }
        })
        .catch(() => alert("تعذر الاتصال بالخادم."));
    }
  </script>

<script>
  var pges = false;

  if (localStorage.getItem('pges') == 'true') {
    pges = true;
  }

  function toggleMenu() {
    document.getElementById("menu").classList.toggle("show");
  }


  function loadPage(page) {
    pges = true;
    setp();
    
 
    localStorage.setItem('page', page);

  
    window.location.href = 'index.html';
  }


  function setp() {
    localStorage.setItem('pges', pges);
  }
</script>

</body>
</html>
