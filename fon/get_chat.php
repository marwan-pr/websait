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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>دردشة جماعية</title>
  <link rel="stylesheet" href="css/chat.css" />
</head>
<body>
  <div class="chat-container">

    <!-- 🟩 منطقة الرسائل -->
    <div class="chat-messages" id="chat-messages">
      <div class="loading-msg">جارٍ تحميل الرسائل...</div>
    </div>

    <!-- 🟦 شريط الإرسال -->
    <form class="chat-input-bar" onsubmit="return false;">
      <label class="attach-btn" title="إرسال صورة">📎
        <input type="file" accept="image/*" hidden />
      </label>

      <input type="text" class="message-input" placeholder="اكتب رسالة..." />

      <button type="submit" class="send-btn">📤</button>
    </form>
  </div>

  <script>
    let lastContent = "";

    function fetchMessages() {
      fetch("./DESKTOP/get_chat.php")
        .then(res => res.text())
        .then(html => {
          const box = document.getElementById("chat-messages");
          if (html !== lastContent) {
            const atBottom = box.scrollTop + box.clientHeight >= box.scrollHeight - 100;
            box.innerHTML = html;
            if (atBottom) box.scrollTop = box.scrollHeight;
            lastContent = html;

            let snd = new Audio("msg.mp3");
            snd.play().catch(()=>{});
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

      fetch("send_message.php", {
        method: "POST",
        body: data
      })
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
</body>
</html>
