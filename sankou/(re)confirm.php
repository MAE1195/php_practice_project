<?php//これは模範解答
require_once('config.php');
require_once('function.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>確認画面</title>
</head>
<body>
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
    <div>
        <form action="result.php" method="post">
            <input type="hidden" name="name" value="<?=h($_POST['name'])?>">
            <input type="hidden" name="email" value="<?=h($_POST['email'])?>">
            <input type="hidden" name="gender" value="<?=h($_POST['gender'])?>">
            <?php if (!empty($_POST['language'])): ?>
                <?php foreach ($_POST['language'] as $lang): ?>
                    <input type="hidden" name="language[]" value="<?=h($lang)?>">
                <?php endforeach; ?>
            <?php endif; ?>
            <input type="hidden" name="remarks" value="<?=h($_POST['remarks'])?>">
            <button type="submit">送信</button>
            <button type="submit" formaction="input.php">戻る</button>
        </form>
    </div>
</body>
</html>