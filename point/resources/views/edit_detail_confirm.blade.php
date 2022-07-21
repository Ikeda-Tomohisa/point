<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
$pattern= "{/edit_detail}";
if (!preg_match($pattern, $referer)) {
    header("Location: /user_admin");
    exit();
}

?>
<!DOCTYPE html>
<head>
<title>勤怠編集確認</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  @include('inc.header_admin', ['name' => $info['name']])

  <section>
    <div class="edit_detail_main">
      <h2>編集確認</h2>
      <form action="/edit_detail_complete" method="post">
      @csrf
      <table class="edit_detail_table">
        <tr>
          <th>出勤/退勤</th>
          <td>
            <input type="hidden" name="attendance_flg" value="{{ $inputs["attendance_flg"] }}">
            @if ($inputs["attendance_flg"] == 1)
            出勤
            @elseif ($inputs["attendance_flg"] == 0)
            退勤
            @endif
          </td>
        </tr>
        <tr>
          <th>日付</th>
          <td>
            <input type="hidden" name="date" value="{{ $inputs["date"] }}">
            {{ $inputs["date"] }}
          </td>
        </tr>
        <tr>
          <th>時間</th>
          <td>
            <input type="hidden" name="time" value="{{ $inputs["time"] }}">
            {{ $inputs["time"] }}
          </td>
        </tr>
      </table><br>
      <input type="submit" class="center_admin" name="edit_complete" value="編集完了"><br>
      <input type="button" class="center_admin" onclick="location.href='/edit_detail?id={{ $inputs["id"] }}'" value="&emsp;戻る&emsp;">
      </form><br>
    </div>
  </section>

  @include('inc.footer')
</body>
</html>
