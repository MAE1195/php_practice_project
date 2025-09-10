<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 定数、モデル外部ファイル読み込み
require_once('config/const.php');
require_once('config/database.php');
require_once('config/error_messages.php'); //追加仕様1
require_once('common/function.php');
require_once('common/Auth.php');
require_once('Model/Base/Database.php');
require_once('Model/Report.php'); //追加仕様2