<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
$pattern= "{/pass_change}";
if (!preg_match($pattern, $referer)) {
    header("Location: /user_info");
    exit();
}
?>
<!DOCTYPE html>
<head>
<title>パスワード変更完了</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  @if($info['admin_flg'] === 1)
  @include('inc.header_admin', ['name' => $info['name']])
  @else
  @include('inc.header_general', ['name' => $info['name']])
  @endif

  <div class="pass_change_complete_main">
    <h2>変更完了しました</h2>
    <br>
    <button onclick="location.href='/user_info'">ユーザー情報に戻る</button>
  </div>
  @include('inc.footer')
</body>
</html>
