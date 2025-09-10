<?php//これは模範解答
require_once('config.php');
require_once('function.php');
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
            <label>氏名：</label>
            <?=h($_POST['name'])?>
        </div>
        <div>
            <label>メールアドレス：</label>
            <?=h($_POST['email'])?>
        </div>
        <div>
            <label>性別：</label>
            <?=GENDER[$_POST['gender']]?>
        </div>
        <div>
            <label>希望言語：</label>
            <?php if (isset($_POST['language'])): ?>
                <?php foreach ($_POST['language'] as $lang): ?>
                    <?=LANGUAGE[h($lang)]?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div>
            <label>備考：</label><br>
            <?=nl2br(h($_POST['remarks']))?>
        </div>
    </div>
</body>
</html>