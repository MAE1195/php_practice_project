<?php
// ログイン画面に遷移したらセッションクリアされるが、一応
require_once('library/library.php');

// セッション破棄
User::clearAuthSession();
header('Location: ' . ROOT_URL . '/login.php');
exit;