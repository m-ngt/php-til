<?php
require_once 'Func.php';
require_once './class/CardClass.php';
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="./css/Common.css">
  <script type="text/javascript" src="./js/card.js"></script>
  <title>カードマスタ</title>
</head>
<body>
  <h1 class="h1">カードマスタメンテナンス</h1>
  <form name="form1" action="" method="post">
    <table class="table1">
      <tr class="tr1">
        <td class="td1">カードID</td>
        <td>使用者社員番号</td>
        <td>無効</td>
      </tr>
      <tr>
        <td><input type="text" name="cardId" maxlength="6" pattern="^[0-9]{6}"></td>
        <td><input type="text" name="empNo" maxlength="6" pattern="^[0-9]{6}"></td>
        <td><input type="checkbox" id="flg" name="flg"></td>
        <input type="hidden" name="cardId2">
      </tr>
    </table>
    <div class="div1">
      <button type="submit" onClick="empList()">社員一覧</button>
      <button type="submit" name="submit" value="submit" onclick="submitClick()">登録</button>
      <input type="hidden" name="submitFlg">
    </div>
    <br><br><br>
    <table class="table2">
      <thead class="thead1">
        <tr class="tr2">
          <td class="td2"></td>
          <td class="td3">カードID</td>
          <td class="td3">使用者社員番号</td>
          <td class="td3">社員名</td>
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
<?php if ($result) :
        $i = 0;
        foreach ($result as $value) :
          if ($value['REVOKE_FLAG'] == '0') :
?>
            <tr  class="tr3">
<?php     else : ?>
            <tr  class="tr3" style="background: #cccccc">   
<?php     endif; ?>
              <td class="td5"><input type="radio" name="select" onClick="setValues()" value="<?= $i ?>"></td>
              <input type="hidden" name="cardId_list" value="<?= h($value['CARD_ID'])?>">
              <input type="hidden" name="empNo_list" value="<?= h($value['EMP_NO'])?>">
              <input type="hidden" name="flg_list" value="<?= h($value['REVOKE_FLAG'])?>">
              <td class="td6"><?= h($value['CARD_ID'])?></td>
              <td class="td6"><?= h($value['EMP_NO'])?></td>
              <td class="td6"><?= h($value['EMP_NAME'])?></td>
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