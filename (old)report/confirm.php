<?php
require_once('../library/library.php');
require_once('../library/common/Validation.php');
//session_start();　///library/Model/library.phpで定義されてるので、ここではsession_startは必要ない。

// CSRF対策トークンチェックトークンチェック
if (empty($_SESSION['token']) || empty($_POST['token']) || $_SESSION['token'] != $_POST['token']) {
    header('Location: input.php');
    exit;
}
// トークン生成、セッションに代入
$_SESSION['token'] = hash('sha256', md5(uniqid(mt_rand(), true)));


$validation = new Validation(); //インスタンス化
//$validation->validation($_POST); //メソッド呼び出し
//function validationは現状returnしてるだけなので、$errorsに返り値を代入する必要がある
/*$errors = []; */ //👈Validation.phpで入れてるのでこちらで改めて初期化してはダメ
/*echo $validation->validation($_POST); *///これはダメ
//船木さんAd 新しい変数を用意してあげる必要がある
$errormassages = $validation->validation($_POST); //メソッドの呼び出しと変数の定義

if (!empty($errormassages)) {
    require_once ('input.php');  //headerを別のに変える 👈船木さんアドバイス。require_onceは、見えてはいないが呼び出されたコードが下に出力されている
    exit;  
} 

//ログインチェック
if (empty($_SESSION['_auth'])) {
    header('Location:/php_practice_project/login.php');
}

?>  
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>確認画面</title>
</head>
<body>
    <div>
        <label>タイトル：</label>
        <?=h($_POST['title'])?>
    </div>
    <div>
        <label>報告種別：</label>
        <?=REPORT[h($_POST['kind'])]?>  <!--  h($_POST['kind']) だと「報告種別： 1」と表示されてしまう -->
    </div>
    <div>
        <label>詳細説明：</label>
        <?=nl2br(h($_POST['detail']))?> <!--「Uncaught Error: Undefined constant "h"（未定義の関数の呼び出しのエラー）」が出る 👉hの後が()じゃなくて[]になってたから-->
    </div>  <!--👆改行が必要なところは、nl2br関数を使う-->
    <div>
        <label>発生日時：</label><br>
        <?=h($_POST['date'])?>
    </div>
    <div>
        <label>再現手順：</label><br>
        <?=nl2br(h($_POST['reproduction']))?>
    </div>
    <div>
        <label>発生環境：</label><br>
        <?=nl2br(h($_POST['environment']))?>
    </div>
    <div>  <!--↓テキスト参考にnameとvalueを変えた-->
        <form action="result.php" method="post">
            <input type="hidden" name="title" value="<?=h($_POST['title'])?>">
            <input type="hidden" name="kind" value="<?=h($_POST['kind'])?>"> 
            <input type="hidden" name="detail" value="<?=h($_POST['detail'])?>">
            <input type="hidden" name="date" value="<?=h($_POST['date'])?>">
            <input type="hidden" name="reproduction" value="<?=h($_POST['reproduction'])?>">
            <input type="hidden" name="environment" value="<?=h($_POST['environment'])?>">
            
            <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
            
            <button type="submit">送信</button>
            <button type="submit" formaction="input.php">戻る</button>
        </form>
    </div>
</body>
</html>