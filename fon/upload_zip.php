<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8" />
  <title>رفع ملف ZIP</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0; font-family: sans-serif; background: #f2f2f2;
      display: flex; justify-content: center; align-items: center; height: 100vh;
    }
    .upload-card {
      background: white; padding: 20px; width: 90%; max-width: 400px;
      border-radius: 15px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .upload-card h2 {
      margin-bottom: 15px; text-align: center; font-size: 1.4em;
    }
    .upload-card label {
      display: block; margin: 10px 0 5px; font-size: 0.95em;
    }
    .upload-card textarea,
    .upload-card input[type="file"] {
      width: 100%; padding: 10px; font-size: 0.95em;
      border: 1px solid #ccc; border-radius: 8px;
    }
    .upload-card button {
      width: 100%; margin-top: 15px; padding: 12px;
      font-size: 1em; border: none; border-radius: 8px;
      background: linear-gradient(to right, #444, #111);
      color: white; cursor: pointer; transition: 0.3s;
    }
    .upload-card button:hover {
      background: linear-gradient(to right, #111, #000);
    }
    .message {
      margin-top: 15px; font-weight: bold; text-align: center;
    }
  </style>
</head>
<body>

  <div class="upload-card">
    <h2>📦 رفع ملف ZIP</h2>
    <form id="uploadForm" method="post" enctype="multipart/form-data">
      <label for="description">الوصف:</label>
      <textarea name="description" id="description" rows="3" required></textarea>

      <label for="zip_file">اختر ملف ZIP:</label>
      <input type="file" name="zip_file" id="zip_file" accept=".zip" required>

      <button type="submit">⬆️ رفع الملف</button>
    </form>
    <div class="message" id="message"></div>
  </div>

  <script>
    const form = document.getElementById('uploadForm');
    const messageDiv = document.getElementById('message');

    form.addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(form);

      const xhr = new XMLHttpRequest();

      xhr.open('POST', 'upload_zip_process.php', true);

      xhr.upload.onprogress = function(event) {
        if (event.lengthComputable) {
          const percent = Math.floor((event.loaded / event.total) * 100);
          messageDiv.textContent = `⏳ جاري رفع الملف... ${percent}%`;
        }
      };

      xhr.onload = function() {
        if (xhr.status === 200) {
          messageDiv.textContent = xhr.responseText;

          if (xhr.responseText.includes("✅")) {
            form.reset();
          }
        } else {
          messageDiv.textContent = "❌ حدث خطأ أثناء الرفع.";
        }
      };

      xhr.onerror = function() {
        messageDiv.textContent = "❌ حدث خطأ في الاتصال.";
      };

      xhr.send(formData);
    });
  </script>

</body>
</html>
