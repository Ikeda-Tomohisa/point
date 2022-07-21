<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

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
        必要ポイント<br>
        {{ $product->required_point }}
        @if ($balance < 0)
        <br><span class="red">ポイントが足りません</span><br><br>
        <button class="center" onclick="window.history.back();">&emsp;戻る&emsp;</button>
        @endif
      </div>
      @if ($balance >= 0)
      <form action="/purchase" method="post">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product_id }}">
        <input type="submit" class="center" value="購入確認"><br>
        <input type="button" class="center" onclick="window.history.back();" value="&emsp;戻る&emsp;">
      </form>
      @endif
    </div>
  </div>

  @include('inc.footer')
</body>
</html>
