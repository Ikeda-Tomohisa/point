<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

?>
<!DOCTYPE html>
<head>
<title>管理者ログイン</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  @include('inc.header_loginout')

  <div class="form_wrap">
    <div class="form_box">
      <h2>管理者ログイン</h2>
      @if (isset($login_error))
        <p class="red">IDまたはパスワードが間違っています</p>
      @endif
      <form action="/auth_admin" method="post">
        @csrf
        <label for="id">ID</label><br>
        <input type="text" class="login_textbox" name="id" placeholder="ID"><br><br>
        <label for="password">パスワード</label><br>
        <input type="password" class="login_textbox" name="password" placeholder="パスワード"><br><br>
        <input type="submit" class="login_button" value="ログイン">
      </form>
      <br>
      <a href="/login">一般ユーザーはこちら</a>
    </div>
  </div>

  @include('inc.footer')
</body>
</html>
