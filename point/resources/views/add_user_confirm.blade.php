<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
$pattern= "{/add_user}";
if (!preg_match($pattern, $referer)) {
    header("Location: /user_admin");
    exit();
}

?>
<!DOCTYPE html>
<head>
<title>ユーザー追加確認</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  @include('inc.header_admin', ['name' => $info['name']])

  <section>
    <div class="add_user_main">
      <h2>ユーザー追加確認</h2>
      <form action="/add_user_complete" method="post">
      @csrf
      <table class="add_user_table">
        <tr>
          <th>id</th>
          <td>
            <input type="hidden" name="id" value="{{ $inputs["id"] }}">
            {{ $inputs["id"] }}
          </td>
        </tr>
        <tr>
            <th>名前</th>
            <td>
              <input type="hidden" name="name" value="{{ $inputs["name"] }}">
              {{ $inputs["name"] }}
            </td>
          </tr>
        <tr>
          <th>部署</th>
          <td>
            <input type="hidden" name="department" value="{{ $inputs["department"] }}">
            {{ $inputs["department"] }}
          </td>
        </tr>
        <tr>
          <th>パスワード</th>
          <td>
            <input type="hidden" name="name" value="{{ $inputs["pass"] }}">
            表示されません
          </td>
        </tr>

      </table><br>
      <input type="submit" class="center_admin" name="add_complete" value="追加完了"><br>
      <input type="button" class="center_admin" onclick="location.href='/add_user'" value="&emsp;戻る&emsp;">
      </form><br>
    </div>
  </section>

  @include('inc.footer')
</body>
</html>
