<?php
class connect
{
    const HOST = 'JRW-SUPPORT2\SQLEXPRESS';
    const DB_NAME = 'test';
    const USER = 'USER01';
    const PASS = 'P_ssw0rd';

    // DB接続
    function pdo() {
        $dsn = "sqlsrv:server=".self::HOST.";database=".self::DB_NAME;
        try {
            $pdo = new PDO($dsn, self::USER, self::PASS, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        } catch (PDOException $e) {
            print('Error:'.$e->getMessage());
        }
        return $pdo;
    }

    //各種TBL取得
    function selectTable($sql) {
        try {
            $pdo = $this->pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            print('Error:'.$e->getMessage());
        }
    }

    // カードマスタ登録
    function insertCardTable($sql, $param) {
        try {
            $pdo = $this->pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($param[0], $param[1], $param[2], $param[3]));
            $mes = '登録が完了しました。';
        } catch (PDOException $e) {
            if($e->getCode() == '23000') {
                $mes = '入力されたカードIDは既に登録されています。';
            } else {
                $mes = 'データベースエラー';
            }
        }
        return $mes;
    }
    
    // カードマスタ更新
    function updateCardTable($sql, $param) {
        try {
            $pdo = $this->pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($param[0], $param[1], $param[2]));
            $mes = '更新が完了しました。';
        } catch (PDOException $e) {
                $mes = 'データベースエラー';
        }
        return $mes;
    }

    // 社員マスタ登録
    function insertEmpTable($sql, $param) {
        try {
            $pdo = $this->pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($param[0], $param[1], $param[2], $param[3], $param[4]));
            $mes = '登録が完了しました。';
        } catch (PDOException $e) {
            if($e->getCode() == '23000') {
                $mes = '入力された社員番号は既に登録されています。';
            } else {
                $mes = 'データベースエラー';
            }
        }
        return $mes;
    }
    
    // 社員マスタ更新
    function updateEmpTable($sql, $param) {
        try {
            $pdo = $this->pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($param[0], $param[1], $param[2], $param[3]));
            $mes = '更新が完了しました。';
        } catch (PDOException $e) {
                $mes = 'データベースエラー';
        }
        return $mes;
    }

    // 社員マスタ削除
    function deleteEmpTable($sql, $param) {
        try {
            $pdo = $this->pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($param));
            $mes = '削除が完了しました。';
        } catch (PDOException $e) {
                $mes = 'データベースエラー';
        }
        return $mes;
    }

    // 勤怠管理一覧を取得
    function selectAttendance($sql, $param) {
        try {
            $pdo = $this->pdo();
            $stmt = $pdo->prepare($sql);
            
            if ($param[1] == '') {
                $stmt->execute(array($param[0]));
            } else {
                $stmt->execute(array($param[0], $param[1]));
            }
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            print('Error:'.$e->getMessage());
        }
    }

    // 最大値+1の勤怠管理IDを取得
    function getAttendanceId($sql) {
        try {
            $pdo = $this->pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            print('Error:'.$e->getMessage());
        }
    }
    
    // ATTENDANCE_IDを取得
    function selectAttendanceId($sql, $param) {
        try {
            $pdo = $this->pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($param[0], $param[1], $param[2]));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            print('Error:'.$e->getMessage());
        }
    }

    // PAIR_ATTENDANCE_IDを取得
    function selectPairAttendanceId ($sql, $param) {
        try {
            $pdo = $this->pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($param[0], $param[1]));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            print('Error:'.$e->getMessage());
        }
    }
    
    // 勤怠管理更新
    function updateAttendanceTable($sql, $param) {
        try {
            $pdo = $this->pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($param[0], $param[1], $param[2], $param[3]));
            $mes = '更新が完了しました。';
        } catch (PDOException $e) {
                $mes = 'データベースエラー';
        }
        return $mes;
    }

    // PairAttendanceIDを更新
    function updatePairAttendance($sql, $param) {
        try {
            $pdo = $this->pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($param));
            $success = true;
        } catch (PDOException $e) {
                $success = false;
        }
        return $success;
    }
    
    // 勤怠管理登録
    function insertAttendanceTable($sql, $param) {
        try {
            $pdo = $this->pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($param[0], $param[1], $param[2], $param[3], $param[4], $param[5], $param[6], $param[7]));
            $mes = '更新が完了しました。';
        } catch (PDOException $e) {
                $mes = 'データベースエラー';
        }
        return $mes;
    }

    // 勤怠管理登録
    function insertAttendanceTable2($sql, $param) {
        try {
            $pdo = $this->pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($param[0], $param[1], $param[2], $param[3], $param[4], $param[5], $param[6], $param[7]));
            $success = true;
        } catch (PDOException $e) {
                $success = false;
        }
        return $success;
    }

}