<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
header("X-FRAME-OPTIONS: SAMEORIGIN");

?>
<!DOCTYPE html>
<head>
<title>ユーザー一覧</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  @include('inc.header_admin', ['name' => $info['name']])

  <div class="user_admin_main">
    <h2>ユーザー一覧</h2>
    <div class="search_add">
        <form action="/user_admin_search" method="get">
          @csrf
          <select name="department">
            <option value="">全て</option>
            <option value="総務部">総務部</option>
            <option value="経理部">経理部</option>
            <option value="製造部">製造部</option>
          </select>
          <br>
          <input type="submit" value="検索">
        </form>
      <button onclick="location.href='/add_user'">＋ユーザー追加</button>
    </div>

    <?php if (!empty($result)) : ?>
      <table class="users_table">
        <tr class="table_column">
          <th>ID</th>
          <th>部署</th>
          <th>名前</th>
          <th>管理者</th>
          <th></th>
          <th></th>
        </tr>
        <?php foreach ($result as $value) : ?>
        <tr class="table_value">
          <td>{{ $value->id }}</td>
          <td class="department">{{ $value->department }}</td>
          <td>{{ $value->name }}</td>
          @if ($value->admin_flg == 1)
            <td class="admin_flg">●</td>
          @else
            <td></td>
          @endif
          <td>
            <form action="/edit_user" method="post">
              @csrf
              <input type="hidden" name="id" value="{{ $value->id }}">
              <input type="submit" name="edit" class="link" value="編集">
            </form>
          </td>
          <td>
            <form action="/detail" method="get">
              @csrf
              <input type="hidden" name="id" value="{{ $value->id }}">
              <input type="submit" name="detail" class="link" value="勤怠詳細">
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
