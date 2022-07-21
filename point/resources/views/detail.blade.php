<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
$pattern= "{(/user_admin)|(/detail)}";
if (!preg_match($pattern, $referer)) {
    header("Location: /user_admin");
    exit();
}

?>
<!DOCTYPE html>
<head>
<title>{{ $info['name'] }}の勤怠一覧</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  @include('inc.header_admin', ['name' => $info['name']])

  <div class="detail_main">
    <h2>{{ $info['name'] }}の勤怠一覧</h2>
    <form action="{{ route('csv') }}" method="post">
      @csrf
      <input type="hidden" name="user_id" value="{{ $user_id }}">
      <div class="csv">
        <input type="submit" class="csv_button" value="CSV出力">
      </div>
    </form>

    <?php if (!empty($result)) : ?>
      <table class="times_table">
        <tr class="table_column">
          <th>出勤/退勤</th>
          <th>日付</th>
          <th>時間</th>
          <th></th>
        </tr>
        <?php foreach ($result as $value) : ?>
        <tr class="table_value">
          @if ($value->attendance_flg === 1)
          <td>出勤</td>
          @elseif ($value->attendance_flg === 0)
          <td>退勤</td>
          @endif
          <td>{{ $value->date }}</td>
          <td>{{ $value->time }}</td>
          <td>
            <form action="/edit_detail" method="post">
              @csrf
              <input type="hidden" name="id" value="{{ $value->id }}">
              <input type="submit" name="edit" class="link" value="編集">
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
    <div class="pagination">
    {{ $result->links() }}
    </div>
    <?php endif; ?>
  </div>

  @include('inc.footer')
</body>
</html>

