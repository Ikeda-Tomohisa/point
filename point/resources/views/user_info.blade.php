<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

?>
<!DOCTYPE html>
<head>
<title>ユーザー情報</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  @if ($info['user_login'] == 0)
  @include('inc.header_general', ['name' => $info['name']])
  @else
  @include('inc.header_admin', ['name' => $info['name']])
  @endif

  <div class="user_info_main">
    <h2>ユーザー情報</h2>
    <table class="user_info_table">
      <tr>
        <td><span class="bold">ID</span><br>{{ $info['id'] }}</td>
        <td></td>
      </tr>
      <tr>
        <td><span class="bold">名前</span><br>{{ $info['name'] }}</td>
        <td></td>
      </tr>
      <tr>
        <td><span class="bold">部署</span><br>{{ $info['department'] }}</td>
        <td></td>
      </tr>
      <tr>
        <td><span class="bold">パスワード</span><br>表示されません</td>
        <td class="pass_change_button"><button onclick="location.href='/pass_change'">変更</button></td>
      </tr>
      <tr>
        <td>
          <span class="bold">ユーザー種類</span><br>
          @if($info['admin_flg'] === 1)
          管理者
          @else
          一般ユーザー
          @endif
        </td>
        <td></td>
      </tr>
      <tr>
        <td><span class="bold">ポイント</span><br>{{ $info['point'] }}</td>
        <td></td>
      </tr>
    </table>
    <br>
    <button class="logout_button" onclick="location.href='/logout'">ログアウト</button>
  </div>
  @include('inc.footer')
</body>
</html>
