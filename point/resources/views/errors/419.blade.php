<?php

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
// var_dump($referer);
// / /index /purchase /edit_detail /edit_detail_confirm
// /edit_user /edit_user_confirm /add_user_confirm
$pattern= "{(/)|(/index)|(/purchase)}";
if (preg_match($pattern, $referer)) {
    $user = 0;
} else {
    $user = 1;
}

?>
<!DOCTYPE html>
<head>
<title>エラー</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  @if ($user == 0)
  @include('inc.header_error_general')
  @elseif ($user == 1)
  @include('inc.header_error_admin')
  @else
  @include('inc.header_loginout')
  @endif

  <div class="error419_wrap">
    <div class="error419_box">
      <h2>419エラー<br>ブラウザのリロードは使わないでください</h2><br>
      @if ($user == 0)
      <button onclick="location.href='/'">&emsp;トップに戻る&emsp;</button>
      @elseif ($user == 1)
      <button onclick="location.href='/user_admin'">ユーザー一覧に戻る</button>
      @else
      <button onclick="location.href='/login'">ログインページに戻る</button>
      @endif
    </div>
  </div>

  </div>
  @include('inc.footer')
</body>
</html>
