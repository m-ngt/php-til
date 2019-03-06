<?php
require_once 'DatebaseClass.php';

// メッセージの初期化
$mes = '';
$obj = new connect();

// 登録ボタン押下
if ('submit' == filter_input(INPUT_POST, 'submit') && filter_input(INPUT_POST, 'submitFlg') ==='1') {
    $emp_no = filter_input(INPUT_POST, 'empNo');
    if ($emp_no == NULL) {
        $emp_no = filter_input(INPUT_POST, 'empNo2');
    }
    $emp_name = filter_input(INPUT_POST, 'empName');
    // 入力チェック
    if (!$emp_no) {
        $mes = '社員番号が未入力です。';
    } elseif (!$emp_name) {
        $mes = '氏名が未入力です。';
    } else {
        $dept = filter_input(INPUT_POST, 'dept');
        // 無効フラグ設定
        if(!filter_input(INPUT_POST, 'flg')) {
            $flg = 0;
        } else {
            $flg = 1;
        }
        // 登録用処理
        if (filter_input(INPUT_POST, 'select') === '') {
            $sql1 = 'INSERT INTO M_EMP VALUES (?, ?, ?, ?, GETDATE(), ?, GETDATE())';
            $param = [$emp_no, $emp_name, $dept, $flg, 'EMP_MASTER'];
            $mes = $obj->insertEmpTable($sql1, $param);
        // 更新用処理
        } else {
            $sql2 = 'UPDATE M_EMP SET '
                    . 'EMP_NAME = ?, DEPT_NAME = ?, REVOKE_FLAG = ?, UPDATE_DATE = GETDATE() '
                    . 'WHERE EMP_NO = ?';
            $param = [$emp_name, $dept, $flg, $emp_no];
            $mes = $obj->updateEmpTable($sql2, $param);
        }
    }
}

// 削除ボタン押下
if (filter_input(INPUT_POST, 'submit') == 'delete' && filter_input(INPUT_POST, 'deleteFlg') === '1') {
    if (filter_input(INPUT_POST, 'select') !== '') {
        $sql3 = 'DELETE FROM M_EMP WHERE EMP_NO = ?';
        $emp_no = filter_input(INPUT_POST, 'empNo2');
        $mes = $obj->deleteEmpTable($sql3, $emp_no);
    } else {
        $mes = '削除対象レコードが選択されていません。';
    }
}

// 画面初期表示用データ取得
$sql1 = 'SELECT EMP_NO, EMP_NAME, DEPT_NAME, REVOKE_FLAG FROM M_EMP '
        . 'ORDER BY EMP_NO';
$result = $obj->selectTable($sql1);