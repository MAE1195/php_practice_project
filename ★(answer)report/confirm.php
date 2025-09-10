<?php
require_once('../library/library.php');
require_once('../library/common/Validation.php');

// authセッションがなかった場合、ログイン画面に遷移
if (!User::chkLogin()) {
    header('Location: ' . ROOT_URL . '/login.php');
    exit;
}

// CSRF対策トークンチェック
if (empty($_SESSION['token']) || empty($_POST['token']) || $_SESSION['token'] != $_POST['token']) {
    header('Location: input.php');
    exit;
}

if (!empty($errors = Validation::validateReport($_POST))) {
    require_once('input.php');
    exit;
}

$menu = 4;
require_once('../template/header.php');
?>
<span class="title">報告確認</span>
<div class="confirm-area">
    <div>
        <label>タイトル</label><br>
        <?=h($_POST['title'])?>
    </div>
    <div>
        <label>報告種別</label><br>
        <?=REPORT_TYPES[$_POST['report_type']]?>
    </div>
    <div>
        <label>詳細説明</label><br>
        <?=nl2br(h($_POST['description']))?>
    </div>
    <div>
        <label>発生日時</label><br>
        <?=str_replace('T', ' ', h($_POST['occurred_at']))?>
    </div>
    <div>
        <label>再現手順</label><br>
        <?=nl2br(h($_POST['reproduction_steps']))?>
    </div>
    <div>
        <label>発生環境</label><br>
        <?=nl2br(h($_POST['environment']))?>
    </div>
</div>
<div class="button-area">
    <form action="result.php" method="post">
        <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
        <input type="hidden" name="title" value="<?=h($_POST['title'])?>">
        <input type="hidden" name="report_type" value="<?=h($_POST['report_type'])?>">
        <input type="hidden" name="description" value="<?=h($_POST['description'])?>">
        <input type="hidden" name="occurred_at" value="<?=h($_POST['occurred_at'])?>">
        <input type="hidden" name="reproduction_steps" value="<?=h($_POST['reproduction_steps'])?>">
        <input type="hidden" name="environment" value="<?=h($_POST['environment'])?>">
        <button type="submit" formaction="input.php" class="button secondary-button">戻る</button>
        <button type="submit" class="button primary-button">完了</button>
    </form>
</div>
<?php require_once('../template/footer.php');?>