<?php
require_once 'Func.php';
require_once './class/AttendanceClass.php';
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="./css/attendance.css">
  <script type="text/javascript" src="./js/attendance.js"></script>
  <title>社員出勤怠一覧</title>
</head>
<body>
  <h1 class="h1">社員出勤退一覧</h1>
    <form name="form1" action="" method="post">
      <div class="div1">
        <label>日付</label>
        <input type="text" name="date" class="date" value="<?= h($date) ?>" pattern="^[0-9]{8}" maxlength="8" required>
        &nbsp;&nbsp;
        <label>所属</label>
        <select name="dept">
          <option value=""></option>
          <option value="AAA">AAA</option>
          <option value="BBB">BBB</option>
          <option value="CCC">CCC</option>
        </select>
        <input type="hidden" name="hdept" value="<?= h($dept) ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;
        <button type="submit" name="display" value="display">表示</button>
        <button type="submit" name="submit" value="submit" class="submit" onclick="submitClick()">保存</button>
        <input type="hidden" name="submitFlg">
      </div>
      <br>
      <table class="table1" border=0>
        <thead class="thead1">
          <tr>
            <td class="td1"></td>
            <td class="td2">社員番号</td>
            <td class="td3">社員名</td>
            <td class="td3">所属</td>
            <td class="td2">出勤</td>
            <td class="td2">退勤</td>
            <td class="td4">遅刻</td>
            <td class="td4">欠勤</td>
            <td class="td4">有休</td>
            <td class="td5">備考</td>
          </tr>
        </thead>
        <tbody class="tbody1">
<?php     if ($result) :
            $i = 0;
            foreach ($result as $value) :
              if ($value['警告フラグ'] == '0') :
?>
                <tr>
<?php         else : ?>   
                <tr style="background: #cccccc">
<?php         endif; ?>
                  <input type="hidden" name="id[]" value="<?= $value['ATTENDANCE_ID']?>">
                  <td class="td6"><input type="radio" name="select" value="<?= $i ?>" ></td>
                  <input type="hidden" name="emp_no[]" value="<?= $value['社員番号']?>">
                  <td class="td7"><?= h($value['社員番号'])?></td>
                  <td class="td8"><?= h($value['社員名'])?></td>
                  <td class="td8"><?= h($value['所属'])?></td>
                  <input type="hidden" name="in" value="<?= $value['出勤']?>">
                  <td class="td7"><?= h($value['出勤'])?></td>
                  <td class="td7"><?= h($value['退勤'])?></td>
                  <input type="hidden" name="late_flg[]" value="<?= $value['遅刻']?>">
                  <td class="td9"><input type="checkbox" name="late" onclick="setLateParam()"></td>
                  <input type="hidden" name="absence_flg[]" value="<?= $value['欠勤']?>">
                  <td class="td9"><input type="checkbox" name="absence" onclick="setAbsenceParam()"></td>
                  <input type="hidden" name="paid_flg[]" value="<?= $value['有休']?>">
                  <td class="td9"><input type="checkbox" name="paid" onclick="setPaidParam()"></td>
                  <td class="td10"><input type="text" name="remake[]" class="remake" value="<?= h($value['備考'])?>"></td>
                </tr>
<?php         $i++;
            endforeach;
          endif;
?>
        </tbody>
      </table>
    </form>
    <br>
      <form action="menu.php">
        <div class="div2"><button type="submit" value="menu" >メニュー</button></div>
      </form>
<?=   h($mes) ?>
</body>
</html>