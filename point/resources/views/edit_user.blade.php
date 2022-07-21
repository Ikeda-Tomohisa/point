<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
$pattern= "{(/user_admin)|(/edit_user)|(/edit_user_confirm)}";
if (!preg_match($pattern, $referer)) {
    header("Location: /user_admin");
    exit();
}

?>
<!DOCTYPE html>
<head>
<title>ユーザー編集</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function confirm_delete() {
    var select = confirm("削除しますか？");
    return select;
}
</script>
</head>
<body>
  @include('inc.header_admin', ['name' => $info['name']])

  <section>
    <div class="edit_user_main">
      <h2>編集</h2>
      @if($errors->has('name'))
        <p class="red">{{ $errors->first('name') }}</p>
      @endif
      <form action="/edit_user_confirm" method="post">
      @csrf
      <table class="edit_user_table">
        <tr>
          <th>id</th>
          <td>
            <input type="hidden" name="id" value="{{ $value->id }}">
            {{ $value->id }}
          </td>
        </tr>
        <tr>
          <th>部署</th>
          <td>
            <select class="input_box" name="department">
              <option value="{{ $value->department }}">{{ $value->department }}</option>
              <option value="総務部" @if($input['department'] == "総務部") selected @endif>総務部</option>
              <option value="経理部" @if($input['department'] == "経理部") selected @endif>経理部</option>
              <option value="製造部" @if($input['department'] == "製造部") selected @endif>製造部</option>
            </select>
          </td>
        </tr>
        <tr>
          <th>名前</th>
          <td>
            <input type="text" class="input_box" name="name" @if($input['name'] == "") value="{{ $value->name }}" @else value="{{ $input['name'] }}" @endif>
          </td>
        </tr>
      </table><br>
      <input type="submit" class="center_admin" name="edit" value="編集確認">
      </form><br>
      <form action="/delete_user" method="post" onsubmit="return confirm_delete()">
        @csrf
        <input type="hidden" name="id" value="{{ $value->id }}">
        <input type="submit" class="center_admin red" name="delete"value="&emsp;削除&emsp;">
      </form>
    </div>
  </section>

  @include('inc.footer')
</body>
</html>
