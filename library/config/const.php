<?php
// システム情報
const SITE_NAME = '社内システム'; //👈これをresultのメールの所で使えた
const CORP_NAME = 'ebacorp.inc';
const ROOT_PATH = 'C:\xampp\htdocs\php_practice_project'; // 自分のプロジェクト名に合わせる。
const ROOT_URL = 'http://localhost/php_practice_project'; // 自分のサイトのURLに合わせる（VirtualHostを設定していない場合はlocalhost/プロジェクト名）
define('LIBRARY_DIR', ROOT_PATH . '/library');
define('MODEL_DIR', LIBRARY_DIR . '/Model');

// From用メールアドレス
const FROM_MAIL = 'noreply@example.com';

// 報告種別
const REPORT_TYPES = [
    1 => 'バグ報告',
    2 => '要望',
    3 => '改善提案',
    4 => '質問',
    5 => 'その他',
];
// 登録か編集かの出し分け用
const MODES = [
    'new' => '登録',
    'edit' => '編集',
];
const ROLE = [            //追加仕様1
    1 => 'システム管理者', 
    2 => '管理者', 
    99 => '一般ユーザ', 
];
const REPORT_STATUS = [   //追加仕様2
    1 => '未対応', 
    2 => '対応中', 
    3 => '対応済',
    99 => '却下',
];
