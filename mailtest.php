<?php
// 以下の設定はMailHogに送る場合は From/To 何でもOK
$to      = 'test@example.com';
$subject = 'MailHog Test';
$message = 'MailHogがメールを受信できているか確認します。';
$headers = 'From: sender@your-local.com';

if (mb_send_mail($to, $subject, $message, $headers)) {
    echo 'メール送信関数は成功を返しました。MailHogを確認してください。';
} else {
    echo 'メール送信関数は失敗を返しました。PHPのエラーログなどを確認してください。';
}