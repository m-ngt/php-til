<?php
require_once 'DatebaseClass.php';

$obj = new connect();
// 画面初期表示用データ取得
$sql = 'SELECT EMP_NO, EMP_NAME FROM M_EMP '
        . 'ORDER BY EMP_NO';
$result = $obj->selectTable($sql);