<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
$pattern= "{(/user_info)|(/pass_change)}";
if (!preg_match($pattern, $referer)) {
    header("Location: /user_info");
    exit();
}

?>
<!DOCTYPE html>
<head>
<title>パスワード変更</title>
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

  <div class="pass_change_main">
    <h2>パスワード変更</h2>
    @if (isset($login_error))
      <p class="red">いずれかのパスワードが間違っています</p>
    @endif
    <form action="/pass_change_check" method="post">
      <input type="hidden" value="{{ $info['id'] }}">
    @csrf
    <table class="pass_change_table">
      <tr>
        <th>今のパスワード</th>
        <td>
          <input type="password" name="current_pass" placeholder="今のパスワード">
        </td>
      </tr>
      <tr>
        <th>変更後のパスワード</th>
        <td>
          <input type="password" name="change_pass" placeholder="変更後のパスワード">
        </td>
      </tr>
      <tr>
        <th>変更後のパスワード(確認)</th>
        <td>
          <input type="password" name="change_pass_confirm" placeholder="変更後のパスワード(確認)">
        </td>
      </tr>
    </table>
    <br>
    <input type="submit" class="change" value="変更">
    </form>
  </div>
  @include('inc.footer')
</body>
</html>
