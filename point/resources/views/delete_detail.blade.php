<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
$pattern= "{/edit_detail}";
if (!preg_match($pattern, $referer)) {
    header("Location: /user_admin");
    exit();
}

?>
<!DOCTYPE html>
<head>
<title>勤怠削除</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  @include('inc.header_admin', ['name' => $info['name']])

  <section>
    <div class="edit_detail_main">
      <h2>勤怠削除しました</h2><br>
      <a href="/user_admin">ユーザー一覧へ戻る</a>
    </div>
  </section>

  @include('inc.footer')
</body>
</html>
