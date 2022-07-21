<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
$pattern= "{/product}";
if (!preg_match($pattern, $referer)) {
    header("Location: /point");
    exit();
}

?>
<!DOCTYPE html>
<head>
<title>ポイント {{ $product->product_name }}</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  @include('inc.header_general', ['name' => $info['name']])

  <div class="product_main_wrap">
    <div class="product_main">
      <div class="product">
        <div class="product_photo">
          <img src="./img/product/{{ $product_id }}.png">
        </div>
        <div class="product_details">
          <div class="product_name">
            {{ $product->product_name }}
          </div>
          <div class="product_description">
            {!! nl2br($product->description) !!}
          </div>
        </div>
      </div>
      <div class="required_point">
        ポイント残高<br>
        {{ $info['point'] }} → {{ $balance }}
        <br>
        購入しますか？
      </div>

      <form action="/purchase_complete" method="post">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product_id }}">
        <input type="submit" class="center" value="&emsp;購入&emsp;"><br>
        <input type="button" class="center" onclick="window.history.back();" value="&emsp;戻る&emsp;">
      </form>
    </div>
  </div>

  @include('inc.footer')
</body>
</html>

