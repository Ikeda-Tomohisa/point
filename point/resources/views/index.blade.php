<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

?>
<!DOCTYPE html>
<head>
<title>出勤/退勤</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
  setInterval(function(){
    var date = new Date()
    var y = date.getFullYear();
    var m = ('0' + (date.getMonth() + 1)).slice(-2);
    var d = ('0' + date.getDate()).slice(-2);
    var w = date.getDay();
    var wd = ["日","月","火","水","木","金","土"];
    var youbi = wd[w];
    var h = ('0' + date.getHours()).slice(-2);
    var min = ('0' + date.getMinutes()).slice(-2);
    var s = ('0' + date.getSeconds()).slice(-2);
    $("#year_month_day").html(y + "年" + m + "月" + d + "日" + "(" + youbi + ")");
    $("#time").html(h + ":" + min + ":" + s);
  },1000);
});
</script>
<script>
  function confirm_attendance() {
    var select = confirm("出勤確認");
    return select;
  }
  function confirm_leaving() {
    var select = confirm("退勤確認");
    return select;
  }
</script>
</head>
<div>
  @include('inc.header_general', ['name' => $info['name']])

  <div class="main_wrap">
    <div class="main">
      <div class="current_time">
        <p id="year_month_day">時刻を</p>
        <p id="time">読み込み中</p>
      </div>
      <div class="state">
        @if ($info['attendance_flg'] == 0)
        <div class="leaving_now">退勤中</div>
        @elseif ($info['attendance_flg'] == 1)
        <div class="attendance_now">出勤中</div>
        @endif
      </div>
      <div class="state_button">
        @if ($info['attendance_flg'] == 0)
        <form action="/attendance" method="post" onsubmit="return confirm_attendance()">
          @csrf
          <input type="submit" value="出勤する">
        </form>
        @elseif ($info['attendance_flg'] == 1)
        <form action="/leaving" method="post" onsubmit="return confirm_leaving()">
          @csrf
          <input type="submit" value="退勤する">
        </form>
        @endif
      </div>
    </div>
  </div>

  @include('inc.footer')
</body>
</html>
