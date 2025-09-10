<?php
/*  ここまるごと不要
require_once(ROOT_PATH . '/library/library.php');

$error = Auth::showErrorPage();
*/

$menu = 1;
require_once(ROOT_PATH . '/template/header.php'); //ヘッダー呼び出し
?>
<span>エラー</span>
    <p class="error-message"><?=ERROR_MESSAGE[$errorCode] . '（エラーコード：' . $errorCode . '）';?></p>

<?php require_once('../template/footer.php');?> 