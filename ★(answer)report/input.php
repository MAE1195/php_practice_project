<?php
require_once('../library/library.php');

// authセッションがなかった場合、ログイン画面に遷移
if (!User::chkLogin()) {
    header('Location: ' . ROOT_URL . '/login.php');
    exit;
}
//トークンの生成とセッションへの保存
//(session_start(); は、一番上で読み込んでるlibrary.phpで実行されてるのでここでは記載しない)
$_SESSION['token'] = hash('sha256', md5(uniqid(mt_rand(), true)));

$menu = 4;
require_once('../template/header.php');
?>
<span class="title">報告</span>
<?php if (!empty($errors)) :?>
    <ul class="warning-message-area">
        <?php foreach ($errors as $val) :?>
            <li>
                <?=$val?>
            </li>
        <?php endforeach;?>
    </ul>
<?php endif;?>
<p class="notice-message">※発生日時、再現手順、発生環境は報告種別がバグ報告の場合のみ必須です。</p>
<form action="confirm.php" method="post" class="input-form">
    <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
    <div>             <!--セッションに保存したトークンをformのhiddenフィールドで送信-->
        <label for="title" class="required">タイトル</label><br>
        <input type="text" name="title" id="title" value="<?=isset($_POST['title']) ? h($_POST['title']) : ''?>">
    </div>
    <div>
        <label for="report_type">報告種別</label><br>
        <select name="report_type" id="report_type">
            <?php foreach (REPORT_TYPES as $key => $val) :?>
                <option value="<?=$key?>"<?=isset($_POST['report_type']) && $_POST['report_type'] == $key ? ' selected' : ''?>><?=$val?></option>
            <?php endforeach;?>
        </select>
    </div>
    <div>
        <label for="description" class="required">詳細説明</label><br>
        <textarea rows="5" name="description" id="description"><?=isset($_POST['description']) ? h($_POST['description']) : ''?></textarea>
    </div>
    <div>
        <label for="occurred_at">発生日時</label><br>
        <input type="datetime-local" name="occurred_at" id="occurred_at" value="<?=isset($_POST['occurred_at']) ? h($_POST['occurred_at']) : ''?>">
    </div>
    <div>
        <label for="reproduction_steps">再現手順</label><br>
        <textarea rows="5" name="reproduction_steps" id="reproduction_steps"><?=isset($_POST['reproduction_steps']) ? h($_POST['reproduction_steps']) : ''?></textarea>
    </div>
    <div>
        <label for="environment">発生環境</label><br>
        <textarea rows="5" name="environment" id="environment"><?=isset($_POST['environment']) ? h($_POST['environment']) : ''?></textarea>
    </div>
    <div class="button-area">
        <button type="submit" class="button primary-button">確認画面へ</button>
    </div>
</form>
<?php require_once('../template/footer.php');?>