<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
$pattern= "{(/detail)|(/edit_detail)|(/edit_detail_confirm)}";
if (!preg_match($pattern, $referer)) {
    header("Location: /user_admin");
    exit();
}

?>
<!DOCTYPE html>
<head>
<title>勤怠編集</title>
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
    <div class="edit_detail_main">
      <h2>編集</h2>
      @if($errors->has('date'))
        <p class="red">{{ $errors->first('date') }}</p>
      @elseif($errors->has('time'))
        <p class="red">{{ $errors->first('time') }}</p>
      @endif
      <form action="/edit_detail_confirm" method="post">
      @csrf
      <table class="edit_detail_table">
        <tr>
          <th>出勤/退勤</th>
          <td>
            <select class="input_box" name="attendance_flg">
              @if ($value->attendance_flg === 1)
              <option value="1" selected>出勤</option>
              <option value="0">退勤</option>
              @elseif ($value->attendance_flg === 0)
              <option value="0" selected>退勤</option>
              <option value="1">出勤</option>
              @endif
            </select>
          </td>
        </tr>
        <tr>
          <th>日付<br>例：2022-07-12</th>
          <td class="datetime_box">
            <input type="text" class="input_box" name="date" value="{{ $value->date }}">
          </td>
        </tr>
        <tr>
          <th>時間<br>例：17:30:00</th>
          <td class="datetime_box">
            <input type="text" class="input_box" name="time" value="{{ $value->time }}">
          </td>
        </tr>
      </table><br>
      <input type="hidden" name="id" value="{{ $value->id }}">
      <input type="submit" class="center_admin" name="edit" value="編集確認">
      </form><br>
      <form action="/delete_detail" method="post" onsubmit="return confirm_delete()">
        @csrf
        <input type="hidden" name="id" value="{{ $value->id }}">
        <input type="submit" class="center_admin red" name="delete"value="&emsp;削除&emsp;">
      </form>
    </div>
  </section>

  @include('inc.footer')
</body>
</html>
