<?php
require_once('../library/library.php');

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

//権限チェック（追加仕様1）
$roles = Auth::chkUserRole($_SESSION['_auth']['role']); //セッションに保存されてる権限レベルを引数としてメソッドに渡す
if($roles == 0) {  //権限チェックのメソッドからの返り値が0（不許可）なら
    Auth::showErrorPage(403); //エラー403を引き渡しメソッド実行
    } 

// トークン削除
unset($_SESSION['token']);

// メール送信
mb_language('uni');
mb_internal_encoding('UTF-8');

$body =
    h($_SESSION['_auth']['name']) . ' 様' . "\n\n"

    . '以下の内容でご報告を受け付けました。' . "\n"
    . '担当者が確認のうえ、必要に応じてご連絡いたします。' . "\n\n"

    . '────────────────' . "\n"
    . '■ 報告タイトル：' . "\n"
    . h($_POST['title']) . "\n\n"

    . '■ 報告種別：' . "\n"
    . REPORT_TYPES[$_POST['report_type']] . "\n\n"

    . '■ 詳細説明：' . "\n"
    . h($_POST['description']) . "\n\n"

    . '■ 発生日時：' . "\n"
    . str_replace('T', ' ', h($_POST['occurred_at'])) . "\n\n"

    . '■ 再現手順：' . "\n"
    . h($_POST['reproduction_steps']) . "\n\n"

    . '■ 発生環境：' . "\n"
    . h($_POST['environment']) . "\n\n"
    . '────────────────' . "\n\n"

    . '※このメールは自動送信です。返信には対応しておりません。' . "\n"
    . 'ご不明な点がある場合は、システム管理者までご連絡ください。' . "\n\n"

    . '──────' . "\n"
    . SITE_NAME . ' サポートチーム' . "\n"
    . FROM_MAIL
;

// メール送信処理
if (!mb_send_mail($_SESSION['_auth']['mail'], '【' . SITE_NAME . '】ご報告ありがとうございます', $body, 'From: ' . FROM_MAIL)) {
    $errorMessage =
        '確認メールの送信に失敗しました。<br>'
        . 'ただし、ご報告自体は承りましたので、回答までしばらくお待ちください。'
    ;
}

// DBへ登録
    $report = new Report();
    $report -> registration($_POST['title'], $_POST['report_type'], $_POST['description'], $_POST['occurred_at'], $_POST['reproduction_steps'], $_POST['environment'], $_SESSION['_auth']['id']);

$menu = 4;
require_once('../template/header.php');
?>
<span class="title">報告完了</span>
<?php if (!empty($errorMessage)) :?>
<p class="error-message">
        <?=$errorMessage?>
    </p>
<?php else :?>
<p class="success-message">
        以下の内容で報告を承りました。
    </p>
<?php endif;?>
<div class="result-area">
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
<?php require_once('../template/footer.php');?>