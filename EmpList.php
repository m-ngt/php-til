<?php
require_once 'Func.php';
require_once './class/EmpListClass.php';
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="./css/Common.css">
  <script type="text/javascript" src="./js/EmpList.js"></script>
  <title>社員一覧</title>
</head>
<body>
  <h1 class="h1">社員一覧</h1>
  <form name="form1" action="" method="post">
    <table class="table1">
      <thead class="thead1">
        <tr class="tr1">
          <td class="td2"></td>
          <td class="td3">社員番号</td>
          <td class="td3">氏名</td>
        </tr>
      </thead>
      <tbody class="tbody2">
<?php if ($result) :
        $i = 0;
        foreach ($result as $value) :
?>
          <tr>
            <td class="td5"><input type="radio" name="select" onClick="setValues()" value="<?= $i ?>"></td>
            <input type="hidden" name="empNo_list" value="<?= h($value['EMP_NO'])?>">
            <input type="hidden" name="empName_list" value="<?= h($value['EMP_NAME'])?>">
            <td class="td6"><?= h($value['EMP_NO'])?></td>
            <td class="td6"><?= h($value['EMP_NAME'])?></td>
          </tr>
<?php     $i++;
        endforeach;
      endif;
?>
      </tbody>
    </table>
    <div class="div1"><button type="submit" onClick="window.close()">閉じる</button></div>
  </form>
</body>
</html>