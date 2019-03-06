<?php
require_once 'Func.php';
require_once './class/EmpClass.php';
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="./css/Common.css">
  <script type="text/javascript" src="./js/emp.js"></script>
  <title>社員マスタ</title>
</head>
<body>
  <h1 class="h1">社員マスタメンテナンス</h1>
  <form name="form1" action="" method="post">
    <table class="table1">
      <tr class="tr1">
        <td class="td1">社員番号</td>
        <td class="td1">氏名</td>
        <td>所属部署</td>
        <td>無効</td>
      </tr>
      <tr>
        <td><input type="text" name="empNo" maxlength="6" pattern="^[0-9]{6}"></td>
        <td><input type="text" name="empName"></td>
        <td><select name="dept">
          <option value=""></option>
          <option value="AAA">AAA</option>
          <option value="BBB">BBB</option>
          <option value="CCC">CCC</option>
        </select></td>
        <td><input type="checkbox" id="flg" name="flg"></td>
        <input type="hidden" name="empNo2">
      </tr>
    </table>
    <div class="div1">
      <button type="submit" name="submit" value="submit" onclick="submitClick()">登録</button>
      <input type="hidden" name="submitFlg">
      <button type="submit" name="submit" value="delete" onclick="deleteClick()">削除</button>
      <input type="hidden" name="deleteFlg">
    </div>
    <br><br><br>
    <table class="table2">
      <thead class="thead1">
        <tr class="tr2">
          <td class="td2"></td>
          <td class="td3">社員番号</td>
          <td class="td3">氏名</td>
          <td class="td3">所属部署</td>
          <td class="td4">無効</td>
        </tr>
      </thead>
      <tbody class="tbody1">
        <tr>
          <td class="td5"><input type="radio" name="select" onClick="setValues()" checked value=""></td>
          <td class="td6"></td>
          <td class="td6"></td>
          <td class="td6"></td>
          <td class="td7">
            <input type="checkbox" name="flg" disabled='disabled'>
          </td>
        </tr>
<?php   if ($result) :
          $i = 0;
          foreach ($result as $value) :
            if ($value['REVOKE_FLAG'] == '0') :
?>
              <tr  class="tr3">
<?php       else : ?>
              <tr  class="tr3" style="background: #cccccc">   
<?php       endif; ?>
                <td class="td5"><input type="radio" name="select" onClick="setValues()" value="<?= $i ?>"></td>
                <input type="hidden" name="empNo_list" value="<?= h($value['EMP_NO'])?>">
                <input type="hidden" name="empName_list" value="<?= h($value['EMP_NAME'])?>">
                <input type="hidden" name="dept_list" value="<?= h($value['DEPT_NAME'])?>">
                <input type="hidden" name="flg_list" value="<?= h($value['REVOKE_FLAG'])?>">
                <td class="td6"><?= h($value['EMP_NO'])?></td>
                <td class="td6"><?= h($value['EMP_NAME'])?></td>
                <td class="td6"><?= h($value['DEPT_NAME'])?></td>
                <td class="td7">
<?php       if ($value['REVOKE_FLAG'] == '0') : ?>
                  <input type="checkbox" name="flg" disabled="disabled">
<?php       else : ?>
                  <input type="checkbox" name="flg" disabled="disabled" checked="checked">
<?php       endif; ?>
                </td>
              </tr>
<?php       $i++;
          endforeach;
          unset($value);
        endif;
?>
      </tbody>
    </table>
  </form>
  <br>
  <form action="menu.php">
    <div class="div1"><button type="submit" value="menu" >メニュー</button></div>
  </form>
<?= h($mes) ?>
</body>
</html>