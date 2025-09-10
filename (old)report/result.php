<?php
 //👆コメントアウトをスペース開けずにコードの後ろに置くと、直前のコードがコメントアウトとみなされてエラーになるので注意！

require_once ( dirname(__FILE__ , 2) . '/library/library.php');

// トークンチェック
if (!isset($_SESSION['token']) || !isset($_POST['token']) || $_SESSION['token'] !== $_POST['token']) {
    header('Location:input.php');
    exit;
}

//セッショントークン削除
unset($_SESSION['token']);

//ログインチェック
if (empty($_SESSION['_auth'])) {
    header('Location:/php_practice_project/login.php');
}


$to = $_SESSION['_auth']['mail'];            // 👈User.phpから引用
$subject = '【社内システム】ご報告ありがとうございます';        // 件名（機種依存文字を含む例）
$body = '以下の内容でご報告を受け付けました。
担当者が確認のうえ、必要に応じてご連絡いたします。

────────────────
■ 報告タイトル：' . "\n"
 . h($_POST['title']) . "\n\n"              //👈改行は'\n' 連続させると一行空けられる。
 . '■ 報告種別：' . "\n" . REPORT[h($_POST['kind'])] . "\n\n"
 . '■ 詳細説明：' . "\n" . h($_POST['detail']) . "\n\n"
 . '■ 発生日時：' . "\n" . h($_POST['date']) . "\n\n"
 . '■ 再現手順：' . "\n" . h($_POST['reproduction']) . "\n\n"
 . '■ 発生環境：' . "\n" . h($_POST['environment'])
 . 
'
────────────────

※このメールは自動送信です。返信には対応しておりません。
ご不明な点がある場合は、システム管理者までご連絡ください。

──────
[社内システム] サポートチーム
noreply@example.com';  // 本文

$fromName = mb_encode_mimeheader('送信者：前之園 迪人', 'UTF-8');  // 差出人名（日本語）
$fromEmail = 'noreply@example.com';                             // 差出人メールアドレス

$headers  = 'From: ' . $fromName . ' <' . $fromEmail . '>' . "\r\n";  // 差出人名あり
// $headers  = 'From: ' . $fromEmail . "\r\n";  // 差出人名なしの場合はこちら
$headers .= 'Cc: cc@example.com' . "\r\n";                                 // CC
$headers .= 'Bcc: bcc@example.com' . "\r\n";                               // BCC
$headers .= 'Reply-To: reply@example.com' . "\r\n";                        // 返信先
$headers .= 'Content-Type: text/plain; charset=UTF-8' . "\r\n";           // コンテンツ形式

if (mb_send_mail($to, $subject, $body, $headers)) {
    echo 'ご登録いただいているメールアドレスにメールを送信しました。';
} else {
    echo 'メールの送信に失敗しました。';
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>完了画面</title>
</head>
<body>
    <div>
        <p>以下の内容で送信しました。</p>
        <div>
            <label>タイトル：</label>
            <?=h($_POST['title'])?>
        </div>
        <div>
            <label>報告種別：</label>
            <?=REPORT[h($_POST['kind'])]?>  
        </div>
        <div>
            <label>詳細説明：</label>
            <?=h($_POST['detail'])?>
        </div>
        <div>
            <label>発生日時：</label>
            <?=h($_POST['date'])?>
        </div>
        <div>
            <label>再現手順：</label><br>
            <?=h($_POST['reproduction'])?>
        </div>
        <div>
            <label>発生環境：</label><br>
            <?=h($_POST['environment'])?>
        </div>
    </div>
</body>
</html>