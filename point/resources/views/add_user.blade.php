<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
$pattern= "{(/user_admin)|(/add_user)|(/add_user_confirm)}";
if (!preg_match($pattern, $referer)) {
    header("Location: /user_admin");
    exit();
}

?>
<!DOCTYPE html>
<head>
<title>ユーザー追加</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  @include('inc.header_admin', ['name' => $info['name']])

  <div class="add_user_main">
    <h2>ユーザー追加</h2>
    @if ($errors->has('id'))
      <p class="red">{{ $errors->first('id') }}</p>
    @elseif ($errors->has('name'))
      <p class="red">{{ $errors->first('name') }}</p>
    @elseif ($errors->has('pass'))
      <p class="red">{{ $errors->first('pass') }}</p>
    @elseif ($errors->has('pass_confirm'))
      <p class="red">{{ $errors->first('pass_confirm') }}</p>
    @endif
    <form action="/add_user_confirm" method="post">
    @csrf
    <table class="add_user_table">
      <tr>
        <th>ID</th>
        <td>
          <input type="text" name="id" placeholder="ID" value="{{ old('id', $input['id']) }}">

        </td>
      </tr>
      <tr>
        <th>名前</th>
        <td>
          <input type="text" name="name" placeholder="名前" value="{{ old('name', $input['name']) }}">
        </td>
      </tr>
      <tr>
        <th>部署</th>
        <td>
          <select name="department">
            <option value="総務部" @if('総務部' == old('department', $input['department'])) selected @endif>総務部</option>
            <option value="経理部" @if('経理部' == old('department', $input['department'])) selected @endif>経理部</option>
            <option value="製造部" @if('製造部' == old('department', $input['department'])) selected @endif>製造部</option>
          </select>
        </td>
      </tr>
      <tr>
        <th>パスワード</th>
        <td>
          <input type="password" name="pass" placeholder="パスワード">
        </td>
      </tr>
      <tr>
        <th>パスワード(確認)</th>
        <td>
          <input type="password" name="pass_confirm" placeholder="パスワード(確認)">
        </td>
      </tr>
    </table>
    <br>
    <input type="submit" class="center_admin" value="&emsp;確認&emsp;"><br>
    </form>
  </div>

  @include('inc.footer')
</body>
</html>
