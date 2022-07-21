<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

?>
<!DOCTYPE html>
<head>
<title>ポイント</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  @include('inc.header_general', ['name' => $info['name']])

  <div class="point_main_wrap">
    <div class="point_main">
      <h2>利用可能ポイント<br>{{ $info['point'] }}pt</h2>
      <div class="product_container">
        <div class="product_item_wrap">
          <?php if (!empty($products)) : ?>
          <?php foreach ($products as $value) : ?>
          <div class="product_item">
            <div>
              <a href="/product?product_id={{ $value->id }}">
                <img src="./img/product/{{ $value->id }}.png">
              </a>
            </div>
            <div>
              {!! nl2br($value->description) !!}
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  @include('inc.footer')
</body>
</html>
