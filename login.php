<?php
require_once('library/library.php');

// セッション破棄
User::clearAuthSession();

//認証ボタン押下
if (!empty($_POST['login'])) {
    if (getTrimStrlen($_POST['login_id']) == 0 || getTrimStrlen($_POST['login_pass']) == 0) {
        $errorMessage = 'IDかパスワードが入力されていません。';
    } else {
        try {
            $user = new User();
            $user->login($_POST['login_id'], $_POST['login_pass']);
            $errorMessage = 'IDかパスワードが間違っています。';
        } catch (PDOException $e) {
            $errorMessage =
                'システムエラーが発生しました。<br>'
                . '再度実行してもエラーが発生する場合は、お手数ですが〇〇までご連絡ください。'
            ;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?=ROOT_URL?>/css/common.css" rel="stylesheet">
    <link href="<?=ROOT_URL?>/css/login.css" rel="stylesheet">
    <title><?=SITE_NAME?>　ログイン画面</title>
</head>
<body>
    <div class="wrapper">
        <main>
            <h1><?=SITE_NAME?>　ログイン画面</h1>
            <div class="login-area">
                <?=!empty($errorMessage) ? '<div class="error">' . $errorMessage . '</div>' : ''?>
                <form action="" method="post">
                    <table>
                        <tr>
                            <th>ログインID:</th>
                            <td><input type="text" name="login_id" value="<?=isset($_POST['login_id']) ? h($_POST['login_id']) : ''?>"></td>
                        </tr>
                        <tr>
                            <th>パスワード:</th>
                            <td><input type="password" name="login_pass"></td>
                        </tr>
                    </table>
                    <p><input type="submit" name="login" value="認証"></p>
                </form>
            </div>
        </main>
        <footer>
            <?=date('Y')?> <?=CORP_NAME?>
        </footer>
    </div>
</body>
</html>