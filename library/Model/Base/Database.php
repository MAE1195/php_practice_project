<?php
/*
 * DBの親クラス
 */
class Database
{
    // PDOクラスオブジェクト
    public $dbh; //dbhはデータベースハンドラーの略らしい

    /**
     * DB接続
     */
    public function connect()
    {
        try {
            $this->dbh = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $this->dbh->exec('set names utf8');
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }
}

// Model継承クラスの呼び出し
$files = glob(MODEL_DIR . '/*.php');
foreach ($files as $file) {
    require_once($file);
}