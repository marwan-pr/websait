<!DOCTYPE html>
<html lang="ar">
<head>
  <link rel="icon" type="image/png" href="../img/them.jfif">
  <meta charset="UTF-8">
  <title>جاري التحميل...</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      height: 100vh;
      background: radial-gradient(circle at center, #0e0e0e, #000);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      direction: rtl;
    }

    .scene {
      width: 100px;
      height: 100px;
      perspective: 800px;
    }

    .cube {
      width: 100px;
      height: 100px;
      position: relative;
      transform-style: preserve-3d;
      animation: rotate 3s infinite linear;
    }

    .face {
      position: absolute;
      width: 100px;
      height: 100px;
      background: #00fff2;
      opacity: 0.85;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 255, 255, 0.5);
    }

    .front  { transform: translateZ(50px); }
    .back   { transform: rotateY(180deg) translateZ(50px); }
    .right  { transform: rotateY(90deg) translateZ(50px); }
    .left   { transform: rotateY(-90deg) translateZ(50px); }
    .top    { transform: rotateX(90deg) translateZ(50px); }
    .bottom { transform: rotateX(-90deg) translateZ(50px); }

    @keyframes rotate {
      0%   { transform: rotateX(0deg) rotateY(0deg); }
      100% { transform: rotateX(360deg) rotateY(360deg); }
    }

    .loading-text {
      margin-top: 20px;
      font-size: 20px;
      color: #00fff2;
      text-shadow: 0 0 10px #00fff2, 0 0 20px #00fff2;
    }

    @media (max-width: 500px) {
      .scene {
        width: 70px;
        height: 70px;
      }

      .cube, .face {
        width: 70px;
        height: 70px;
      }

      .loading-text {
        font-size: 16px;
        margin-top: 15px;
      }
    }
  </style>
</head>
<body>

  <div class="scene">
    <div class="cube">
      <div class="face front"></div>
      <div class="face back"></div>
      <div class="face right"></div>
      <div class="face left"></div>
      <div class="face top"></div>
      <div class="face bottom"></div>
    </div>
  </div>

  <br>
  <div class="loading-text">جاري التحميل<span id="dots"></span></div>

  <script>
    const dotsSpan = document.getElementById('dots');
    let count = 0;

    setInterval(() => {
      count = (count + 1) % 4;
      dotsSpan.textContent = '.'.repeat(count);
    }, 500);

    function isDesktop() {
      return !/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    setTimeout(function () {
      if (isDesktop()) {
        window.location.href = "DESKTOP/index.php";
      } else {
        window.location.href = "fon/index.html";
      }
    }, 6000);
  </script>

</body>
</html>
