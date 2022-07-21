<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

?>
<!DOCTYPE html>
<head>
<title>ログアウト</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  @include('inc.header_loginout')

  <div class="logout_wrap">
    <div class="logout_box">
      <h2>ログアウトしました</h2><br>
      <button onclick="location.href='/login'">ログインページに戻る</button>
    </div>
  </div>

  @include('inc.footer')
</body>
</html>
