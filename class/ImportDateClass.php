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
    $attendanc = selectAttendanceId($obj, $date, $card_id);
    // $attendancが取得できたらPAIR_ATTENDANCEをNULLに更新する
    if ($attendanc !== null) {
        $warn = 1;
        $sql2 = 'UPDATE T_ATTENDANCE SET PAIR_ATTENDANCE = NULL '
                . 'WHERE ATTENDANCE_ID = ?';
        $success = $obj->updatePairAttendance($sql2, $attendanc);
    } else {
        $warn = 0;
    }
    // UPDATEなし or UPDATEが成功したら実施する
    if($success) {
        $sql3 = 'INSERT INTO T_ATTENDANCE(EMP_NO, ATTENDANCE_ID, CARD_ID, ATTENDANCE_TYPE, TIME, '
                .'LATE_FLG, REVOKE_FLAG, WARN_FLG, PAIR_ATTENDANCE, REG_DATE, REG_SCREEN, UPDATE_DATE) '
                . 'SELECT EMP_NO, ?, ?, 0, ?, ?, 0, ?, ?, GETDATE(), ?, GETDATE() '
                . 'FROM M_CARD WHERE CARD_ID = ?';
        $sql3_param = [$att_id, $card_id, $datetime, $late_flg, $warn, $att_id, 'SYSTEM', $card_id];
        $success = $obj->insertAttendanceTable2($sql3, $sql3_param);
    }
    return $success;
}

// 退勤情報登録
function out($vals, $obj) {
    // 近日作成予定
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
function  selectAttendanceId($obj, $date, $card_id) {
    $sql = 'SELECT ATTENDANCE_ID FROM T_ATTENDANCE '
        . 'WHERE CONVERT(NVARCHAR, TIME, 112) = ? AND CARD_ID = ?';
    $param = [$date, $card_id];
    $result = $obj->selectAttendanceId($sql, $param);
    if ($result) {
        return $result[0]['ATTENDANCE_ID'];
    } else {
        return null;
    }
}