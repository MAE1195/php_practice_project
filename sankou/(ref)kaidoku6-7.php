<?php
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
session_start();
// フォーム送信時にCSRFトークンをチェックする想定
$tokenCheckedMessage = '不正なリクエストです。'; 
if (isset($_POST['token']) && isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
    $tokenCheckedMessage = 'トークンが検証されました。フォームは正しく送信されました。';
    unset($_SESSION['token']);
}
$_SESSION['token'] = hash('sha256', uniqid(mt_rand(), true));
?>
<?php if (!empty($tokenCheckedMessage)) :?>
    <p><?=$tokenCheckedMessage?></p>
<?php endif;?>
<form method="post" action="">
    <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
    <button type="submit">送信</button>
</form>