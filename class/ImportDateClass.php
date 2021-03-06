<?php
require_once 'DatebaseClass.php';

const START_TIME = '09:00:00';
$mes = '';
$errflg = false;

if (filter_input(INPUT_POST, 'import')) {
    try {
        // CSV取込み
        $file = new SplFileObject('C:\TEMP\test.csv');
        $file->setFlags(
                SplFileObject::READ_CSV |
                SplFileObject::READ_AHEAD |
                 SplFileObject::SKIP_EMPTY |
                SplFileObject::DROP_NEW_LINE
                );
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    $readFile = [];
    foreach ($file as $line) {
        $readFile[] = $line;
    }
    $obj = new connect();
    for ($i = 0; $i < count($readFile); $i++) {
        $vals = $readFile[$i];
        switch ($vals[0]) {
            case '0':
                $success = in($vals, $obj);
                if (!$success) {
                    $errflg = true;
                }
                break;
            case '1':
                $success = out($vals, $obj);
                if (!$success) {
                    $errflg = true;
                }
                break;
            default :
                //処理しない
        }
        if ($errflg) {
            $mes = '取込みに失敗したレコードがあります。';
        } else {
            $mes = '正常に取込めました。';
        }
    }
}

// 出勤情報登録
function in($vals, $obj) {
    $success = true;
    $card_id = $vals[1];
    $datetime = $vals[2];
    $date = str_replace('-', '', substr($datetime, 0, 10));
    $time = substr($datetime, 11);
    if ($time <= START_TIME) {
        $late_flg = 0;
    } else {
        $late_flg = 1;
    }
    // 最大値+1のAttendanceIDを取得
    $att_id = selectMaxAttendanceID($obj);
    // 同日に出勤レコードがあるか確認する
    $attendanc = selectAttendanceId($obj, $date, $card_id, 0);
    // $attendancが取得できたらPAIR_ATTENDANCEをNULLに更新する
    if ($attendanc !== null) {
        $warn = 1;
        $sql1 = 'UPDATE T_ATTENDANCE SET PAIR_ATTENDANCE = NULL '
                . 'WHERE ATTENDANCE_ID = ?';
        $success = $obj->updatePairAttendance($sql1, $attendanc);
    } else {
        $warn = 0;
    }
    // UPDATEなし or UPDATEが成功したら実施する
    if($success) {
        $sql2 = 'INSERT INTO T_ATTENDANCE(EMP_NO, ATTENDANCE_ID, CARD_ID, ATTENDANCE_TYPE, TIME, '
                .'LATE_FLG, REVOKE_FLAG, WARN_FLG, PAIR_ATTENDANCE, REG_DATE, REG_SCREEN, UPDATE_DATE) '
                . 'SELECT EMP_NO, ?, ?, 0, ?, ?, 0, ?, ?, GETDATE(), ?, GETDATE() '
                . 'FROM M_CARD WHERE CARD_ID = ?';
        $sql2_param = [$att_id, $card_id, $datetime, $late_flg, $warn, $att_id, 'SYSTEM', $card_id];
        $success = $obj->insertAttendanceTable2($sql2, $sql2_param);
    }
    return $success;
}

// 退勤情報登録
function out($vals, $obj) {
    $success = true;
    $card_id = $vals[1];
    $datetime = $vals[2];
    $date = str_replace('-', '', substr($datetime, 0, 10));
    $late_flg = 0;
    // 最大値+1のAttendanceIDを取得
    $att_id = selectMaxAttendanceID($obj);
    // 同日に退勤レコードがあるか確認する
    $attendanc = selectAttendanceId($obj, $date, $card_id, 1);
    // $attendancが取得できたらPAIR_ATTENDANCEをNULLに更新する
    if ($attendanc !== null) {
        $warn = 1;
        $sql1 = 'UPDATE T_ATTENDANCE SET PAIR_ATTENDANCE = NULL '
                . 'WHERE ATTENDANCE_ID = ?';
        $success = $obj->updatePairAttendance($sql1, $attendanc);
    } else {
        $warn = 0;
    }
    // 
    $sq2 = 'SELECT TOP 1 ATTENDANCE_ID FROM T_ATTENDANCE WHERE CARD_ID = ? AND ATTENDANCE_TYPE = 0'
            . 'ORDER BY TIME DESC';
    $sql2_param = [$date, $card_id];
    $pair = $obj->selectPairAttendanceId($sq2, $$sql2_param);
    // UPDATEなし or UPDATEが成功したら実施する
    if($success) {
        $sql3 = 'INSERT INTO T_ATTENDANCE(EMP_NO, ATTENDANCE_ID, CARD_ID, ATTENDANCE_TYPE, TIME, '
                .'LATE_FLG, REVOKE_FLAG, WARN_FLG, PAIR_ATTENDANCE, REG_DATE, REG_SCREEN, UPDATE_DATE) '
                . 'SELECT EMP_NO, ?, ?, 1, ?, ?, 0, ?, ?, GETDATE(), ?, GETDATE() '
                . 'FROM M_CARD WHERE CARD_ID = ?';
        $sql3_param = [$att_id, $card_id, $datetime, $late_flg, $warn, $pair, 'SYSTEM', $card_id];
        $success = $obj->insertAttendanceTable2($sql3, $sql3_param);
    }
    return $success;
}

// 最大値+1のAttendanceIDを取得
function selectMaxAttendanceID($obj) {
    $sql = 'SELECT MAX(ATTENDANCE_ID) + 1 ATTENDANCE_ID FROM T_ATTENDANCE';
    $result = $obj->getAttendanceId($sql)[0]['ATTENDANCE_ID'];
    if ($result) {
        return $result[0]['ATTENDANCE_ID'];
    } else {
        return 1;
    }
}

// 同日にデータがあるか確認
function  selectAttendanceId($obj, $date, $card_id, $type) {
    $sql = 'SELECT ATTENDANCE_ID FROM T_ATTENDANCE '
        . 'WHERE CONVERT(NVARCHAR, TIME, 112) = ? AND CARD_ID = ? AND ATTENDANCE_TYPE = ?';
    $param = [$date, $card_id, $type];
    $result = $obj->selectAttendanceId($sql, $param);
    if ($result) {
        return $result[0]['ATTENDANCE_ID'];
    } else {
        return null;
    }
}
