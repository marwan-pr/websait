<?php
session_start();
require_once "includes/db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, profile_img FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $email;
            $_SESSION['profile_img'] = $user['profile_img'];

            echo "<script>
              localStorage.setItem('rememberedEmail', '" . addslashes($email) . "');
              localStorage.setItem('rememberedPass', '" . addslashes($password) . "');
              window.location.href = 'loading.php';
            </script>";
            exit;
        } else {
            $error = "كلمة المرور غير صحيحة.";
        }
    } else {
        $error = "البريد الإلكتروني غير موجود.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
<link rel="icon" type="image/png" href="../img/them.jfif">

    <link rel="stylesheet" href="css/login.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة تسجيل الدخول</title>
</head>
<body style="background: url('img/login.jfif'); background-attachment: fixed; background-size: cover; background-repeat: no-repeat;">
    <div class="all">
        <h1 class="title">صفحة تسجيل الدخول</h1>

        <form action="login.php" method="POST" class="inp" id="loginForm">
            <input type="email" name="email" class="p" placeholder="الايميل" required>
            <input type="password" name="password" class="p" placeholder="أدخل كلمة المرور" minlength="6" required>
            <button type="submit" class="b">تسجيل الدخول</button>

            <?php if (!empty($error)): ?>
                <div class="error-msg" style="color: red; text-align: center; margin-top: 10px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
        </form>

        <div class="remember">
            <label>
                <input type="checkbox" name="remember" id="remember">
                تذكرني المرة القادمة
            </label>
        </div>

        <div class="instructions">
            <h2>إرشادات الدخول:</h2>
            <ul>
                <li>هذا الموقع مخصص لأعضاء فريق العمل فقط.</li>
                <li>لو نسيت كلمة المرور اتصل بصاحب الموقع</li>
            </ul>
        </div>

        <br>
        <a href="singup.php" style="text-align: center; display: block;">أنشئ حساب</a>
    </div>

    <script>
      const emailInput = document.querySelector('input[name="email"]');
      const passInput = document.querySelector('input[name="password"]');
      const rememberCheckbox = document.getElementById('remember');

      window.onload = function () {
        const savedEmail = localStorage.getItem("rememberedEmail");
        const savedPass = localStorage.getItem("rememberedPass");

        if (savedEmail && savedPass) {
          emailInput.value = savedEmail;
          passInput.value = savedPass;
          rememberCheckbox.checked = true;
        }
      };

      document.getElementById('loginForm').addEventListener('submit', function () {
        if (!rememberCheckbox.checked) {
          localStorage.removeItem("rememberedEmail");
          localStorage.removeItem("rememberedPass");
        }
      });
    </script>

    <br><br><br>
</body>
</html>
