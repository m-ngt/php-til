<?php
require_once 'DatebaseClass.php';

$mes = '';
// 日付設定
if (filter_input(INPUT_POST, 'date')) {
    $date = filter_input(INPUT_POST, 'date');
} else {
    $date = date('Ymd');
}
// 所属設定
if (filter_input(INPUT_POST, 'dept')) {
    $dept = filter_input(INPUT_POST, 'dept');
} else {
    $dept = '';
}
// 初期表示用データ取得
function getAttendacne($date, $dept) {
    $obj = new connect();
    $sql = getSelectSql();
    $sql .= 'AND CONVERT(NVARCHAR, TAT.TIME, 112) = ? '
            . 'WHERE MSY.REVOKE_FLAG = 0 ';
    if ($dept !== '') {
        $sql .= 'AND MSY.DEPT_NAME = ? ';
    }
    $sql .= 'ORDER BY 社員番号, 出勤';
    $param = [$date, $dept];
    $result = $obj->selectAttendance($sql, $param);
    return $result;
}

function getSelectSql() {
    $sql = 'SELECT TAT.ATTENDANCE_ID, MSY.EMP_NO 社員番号, MSY.EMP_NAME 社員名, MSY.DEPT_NAME 所属, '
            . 'CASE WHEN TAT.ATTENDANCE_TYPE in (2,3) THEN null ELSE CONVERT(NVARCHAR, TAT.TIME, 108) END 出勤, '
            . 'CASE WHEN TAT.PAIR_ATTENDANCE = TAT.ATTENDANCE_ID THEN null '
            . 'ELSE (select CONVERT(NVARCHAR, TAT2.TIME, 108) from T_ATTENDANCE TAT2 where TAT.PAIR_ATTENDANCE = TAT2.ATTENDANCE_ID) '
            . 'END 退勤, '
            . 'CASE WHEN TAT.LATE_FLG = 1 THEN 1 ELSE 0 END 遅刻, '
            . 'CASE WHEN TAT.ATTENDANCE_TYPE = 2 THEN 1 ELSE 0 END 欠勤, '
            . 'CASE WHEN TAT.ATTENDANCE_TYPE = 3 THEN 1 ELSE 0 END 有休, '
            . 'TAT.REMARK 備考, '
            . 'CASE WHEN TAT.WARN_FLG is null THEN 0 ELSE TAT.WARN_FLG END 警告フラグ '
            . 'FROM M_EMP MSY '
            . 'LEFT JOIN T_ATTENDANCE TAT ON TAT.EMP_NO = MSY.EMP_NO '
            . 'AND TAT.ATTENDANCE_TYPE in (0,2,3) '
            . 'AND TAT.PAIR_ATTENDANCE is not null '
            . 'AND TAT.REVOKE_FLAG = 0 ';
    return $sql;
}

// 保存ボタン押下した場合
if (filter_input(INPUT_POST, 'submit') && filter_input(INPUT_POST, 'submitFlg') === '1') {
    $select =  filter_input(INPUT_POST, 'select');
    // ラジオボタンが選択されているか
    if ($select !== null) {
        $id = filter_input(INPUT_POST, 'id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY)[$select];
        // 入力値を取得
        $late_flg = filter_input(INPUT_POST, 'late_flg', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY)[$select];
        $absence_flg = filter_input(INPUT_POST, 'absence_flg', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY)[$select];
        $paid_flg = filter_input(INPUT_POST, 'paid_flg', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY)[$select];
        $remake = filter_input(INPUT_POST, 'remake', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY)[$select];        
        $emp_no = filter_input(INPUT_POST, 'emp_no', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY)[$select];

        // 出退区分を設定する
        if (!$absence_flg && !$paid_flg) {
            $type = 0;
        } elseif ($absence_flg === '1') {
            $type = 2;
        } else {
            $type = 3;
        }
        $obj = new connect();
        if ($id) {
            // 更新処理
            $sql2 = 'UPDATE T_ATTENDANCE SET '
                    . 'LATE_FLG = ?, ATTENDANCE_TYPE = ?, REMARK = ?, UPDATE_DATE = GETDATE() '
                    . 'WHERE ATTENDANCE_ID = ?';
            $param2 = [$late_flg, $type, $remake, $id];
            $mes = $obj->updateAttendanceTable($sql2, $param2);
        } else {
            // T_ATTENDANCEに登録
            $sql3 = 'SELECT MAX(ATTENDANCE_ID) + 1 ATTENDANCE_ID FROM T_ATTENDANCE';
            $att_id = $obj->getAttendanceId($sql3)[0]['ATTENDANCE_ID'];
            $sql4 = 'INSERT INTO T_ATTENDANCE(CARD_ID, EMP_NO, ATTENDANCE_ID, PAIR_ATTENDANCE, TIME,  ATTENDANCE_TYPE, REVOKE_FLAG, WARN_FLG, REMARK, REG_DATE, REG_SCREEN, UPDATE_DATE)  '
                    . 'SELECT MC.CARD_ID, ?, ?, ?, ?, ?, 0, 0, ?, GETDATE(), ?, GETDATE() '
                    . 'FROM M_EMP ME '
                    . 'LEFT JOIN M_CARD MC ON MC.EMP_NO = ME.EMP_NO '
                    . 'WHERE ME.EMP_NO = ?';
            $param = [$emp_no, $att_id, $att_id, $date, $type, $remake, 'ATTENDANCE', $emp_no];
            $mes = $obj->insertAttendanceTable($sql4, $param);
        }
    } else {
        $mes = '更新対象レコードが選択されていません。';
    }
}

$result = getAttendacne($date, $dept);

// 表示ボタン押下した場合
if(filter_input(INPUT_POST, 'display')) {
    $date = filter_input(INPUT_POST, 'date');
    $y = mb_substr($date, 0, 4);
    $m = mb_substr($date, 4, 2);
    $d = mb_substr($date, 6, 2);
    
    if (checkdate($m, $d, $y)) {
        $dept = filter_input(INPUT_POST, 'dept');
        $obj = new connect();
        $sql5 = getSelectSql();
        if ($date != '') {
            $sql5 .= 'AND CONVERT(NVARCHAR, TAT.TIME, 112) = ? ';
        }
        $sql5 .= 'WHERE MSY.REVOKE_FLAG = 0 ';
        if ($dept != '') {
            $sql5 .= 'AND MSY.DEPT_NAME = ? ';
        }
        $sql5 .= 'ORDER BY 社員番号, 出勤';
        $param = [$date, $dept];
        $result = $obj->selectAttendance($sql5, $param);
    } else {
        $mes = 'YYYYMMDD形式ではありません。';
    }
    
}

// csv
//if (filter_input(INPUT_POST, 'output')) {
//    $f = fopen("C:\TEMP\csvtest.csv", "w");
//    if ( $f ) {
//        foreach($result as $value){
//            fputcsv($f, $value);
//        }
//    }
//fclose($f);
//}