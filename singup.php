<!DOCTYPE html>
<html lang="ar">
<head>
<link rel="icon" type="image/png" href="../img/them.jfif">

  <meta charset="UTF-8">
  <title>إنشاء حساب</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/singup.css">
</head>
<body>
  <div class="all">
    <h1 class="title">إنشاء حساب</h1>

    <form id="signupForm" action="register.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
      <div class="image-picker">
        <button type="button" onclick="document.getElementById('fileInput').click()" class="b">اختر صورة</button>
        <input type="file" id="fileInput" name="image" accept="image/*" style="display: none;" onchange="previewImage(event)">
        <img src="img/use.jpg" id="preview" class="us">
      </div>

      <input type="text" id="fname" name="fname" class="p" placeholder="الإسم الأول" required>
      <input type="text" id="lname" name="lname" class="p" placeholder="اللقب" required>
      <input type="email" id="email" name="email" class="p" placeholder="الإيميل" required>
      <input type="password" id="password" name="password" class="p" placeholder="كلمة المرور" minlength="6" required>
      <input type="password" id="confpass" name="confpass" class="p" placeholder="تأكيد كلمة المرور" minlength="6" required>

      <div id="message" class="msg"></div>

      <button type="submit" class="p">إنشاء حساب</button>
      <a href="login.php">هل لديك حساب؟</a>
    </form>
  </div>

  <script>
    function previewImage(event) {
      const reader = new FileReader();
      reader.onload = function () {
        document.getElementById('preview').src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    }

    function showMessage(text) {
      const msg = document.getElementById("message");
      msg.textContent = text;
      msg.style.display = "block";
      msg.style.color = "red";
    }

    function validateForm() {
      const fname = document.getElementById("fname").value.trim();
      const lname = document.getElementById("lname").value.trim();
      const email = document.getElementById("email").value.trim();
      const pass = document.getElementById("password").value;
      const confpass = document.getElementById("confpass").value;
      const file = document.getElementById("fileInput").files[0];

      if (fname === "" || lname === "") {
        showMessage("الرجاء إدخال الاسم واللقب");
        return false;
      }

      if (!email.includes("@") || !email.includes(".")) {
        showMessage("يرجى إدخال بريد إلكتروني صالح");
        return false;
      }

      if (pass.length < 6) {
        showMessage("كلمة المرور يجب أن تكون على الأقل 6 أحرف");
        return false;
      }

      if (pass !== confpass) {
        showMessage("كلمتا المرور غير متطابقتين");
        return false;
      }

      if (!file) {
        showMessage("الرجاء اختيار صورة");
        return false;
      }

      return true;
    }
  </script>
</body>
</html>
