<?php
// 以後の章で解説するが、ファイルを読み込んでいる
require_once('config.php');
require_once('function.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>入力画面</title>
</head>
<body>
    <form action="confirm.php" method="post">
        <div>
            <label>氏名</label><br>
            <input type="text" name="name" value="<?=isset($_POST['name']) ? h($_POST['name']) : ''?>">
        </div>
        <div>
            <label>メールアドレス</label><br>
            <input type="email" name="email" value="<?=!empty($_POST['email']) ? h($_POST['email']) : ''?>">
        </div>
        <div>
            <label>性別</label><br>
            <select name="gender">
                <?php foreach (GENDER as $key => $val) : ?>
                    <option value="<?=$key?>"<?=isset($_POST['gender']) && $_POST['gender'] == $key ? ' selected' : ''?>><?=$val?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div>
            <label>希望言語</label><br>
            <?php foreach (LANGUAGE as $key => $val) : ?>
                <label><input type="checkbox" name="language[]" value="<?=$key?>"<?=isset($_POST['language']) && in_array($key, $_POST['language']) ? ' checked' : ''?>><?=$val?></label><br>
            <?php endforeach;?>
        </div>
        <div>
            <label>備考</label><br>
            <textarea name="remarks" rows="4" cols="40"><?=!empty($_POST['remarks']) ? h($_POST['remarks']) : ''?></textarea>
        </div>
        <div>
            <button type="submit">確認画面へ</button>
        </div>
    </form>
</body>
</html>