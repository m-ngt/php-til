<?php
require_once 'DatebaseClass.php';

// メッセージの初期化
$mes = '';
$obj = new connect();

// 登録ボタン押下
if (filter_input(INPUT_POST, 'submit') && filter_input(INPUT_POST, 'submitFlg') === '1') {
    // 入力チェック
    $card_id = filter_input(INPUT_POST, 'cardId');
    $card_id2 = filter_input(INPUT_POST, 'cardId2');
    if (!$card_id && !$card_id2) {
        $mes = 'カードIDが未入力です。';
    } else {
        // 社員ID設定
        if (!$emp_no  = filter_input(INPUT_POST, 'empNo')) {
            $emp_no = 999999;
        }
        // 無効フラグ設定
        if(!filter_input(INPUT_POST, 'flg')) {
            $flg = 0;
        } else {
            $flg = 1;
        }
        // 登録用処理
        if (filter_input(INPUT_POST, 'select') === '') {
            $sql1 = 'INSERT INTO M_CARD VALUES (?, ?, ?, GETDATE(), ?, GETDATE())';
            $param = [$card_id, $emp_no, $flg, 'CARD_MASTER'];
            $mes = $obj->insertCardTable($sql1, $param);
        // 更新用処理
        } else {
            $sql2 = 'UPDATE M_CARD SET '
                    . 'EMP_NO = ?, REVOKE_FLAG = ?, UPDATE_DATE = GETDATE() '
                    . 'WHERE CARD_ID = ?';
            $param = [$emp_no, $flg, $card_id2];
            $mes = $obj->updateCardTable($sql2, $param);
        }
    }
}
// 画面初期表示用データ取得
$sql3 = 'SELECT mc.CARD_ID, mc.EMP_NO, me.EMP_NAME, mc.REVOKE_FLAG FROM M_CARD mc '
        . 'LEFT JOIN M_EMP me ON mc.EMP_NO = me.EMP_NO '
        . 'ORDER BY mc.CARD_ID';
$result = $obj->selectTable($sql3);