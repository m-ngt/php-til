<?php
require_once 'Func.php';
require_once './class/ImportDateClass.php';
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="./css/Common.css">
  <title>メニュー</title>
</head>
<body>
  <h1 class="h1">メニュー画面</h1>
  <form name="menu" action="" method="POST">
    <a href="emp.php" >社員マスタ</a><br><br>
    <a href="card.php" >カードマスタ</a><br><br>
    <a href="holyday.php" >休日マスタ</a><br><br>
    <a href="attendance.php" >勤怠管理画面</a><br><br>
    <button type="submit" name="import" value="import">勤怠取込</button>
  </form>
<?= h($mes)?>
</body>
</html>