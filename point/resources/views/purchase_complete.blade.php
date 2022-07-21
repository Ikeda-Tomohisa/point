<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
$pattern= "{/purchase}";
if (!preg_match($pattern, $referer)) {
    header("Location: /point");
    exit();
}

?>
<!DOCTYPE html>
<head>
<title>購入完了</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  @include('inc.header_general', ['name' => $info['name']])

  <section>
    <div class="purchase_complete_main">
      <h2>購入完了しました</h2><br>
      <button onclick="location.href='/point'">&emsp;トップへ戻る&emsp;</button>
    </div>
  </section>

  @include('inc.footer')
</body>
</html>
