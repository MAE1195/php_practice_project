<?php //以下のソースを基にして処理を書き足してください。また、実装済みのソースに合わせるようにデザインをCSSで調整してください。
require_once('../library/library.php');
require_once('../library/common/Validation.php'); 
// authセッションがなかった場合、ログイン画面に遷移
if (!User::chkLogin()) {
    header('Location: ' . ROOT_URL . '/login.php');
    exit;
}
//ヘッダー読み込み
$menu = 2;
require_once('../template/header.php');
// トークン生成、セッションに代入
$_SESSION['token'] = hash('sha256', md5(uniqid(mt_rand(), true)));

//編集に進んだときの条件。登録画面ではそもそもDBの内容を呼び出す必要はないので、インスタンス化もif文の中に格納する
if ($_GET['mode'] == 'edit') {
    $new_info = new NewInfo(); 
    $rows = $new_info->getdata_foredit($_GET['id']); 
    $rows = array_merge($rows, $_POST); //編集画面に遷移した当初はDBの値、resultから戻ってきたらPOSTした値になる
} 

?>

<!--GETパラメータが送信されたか否かの場合分けを、「表示内容毎」に記述-->
<!--タイトル-->
<?php if (isset($_GET['mode']) && $_GET['mode'] == 'edit' && isset($_POST)) : ?>
    <span class="title">新着情報編集</span>
    <form action="confirm.php?mode=edit&id=<?= $_GET['id'] ?>" method="post"> <!--👈ID修正OK!-->
<?php else : ?>
    <span class="title">新着情報登録</span>
    <form action="confirm.php?mode=new" method="post">
<?php endif; ?>
<!--バリデーションチェックの結果を表示-->
<?php if (!empty($errormassages)) :?>
    <?php foreach ((array)$errormassages as $value) : ?>
        <p class="highlight"><?=$value, '<br>'?></p> <!--クラス指定で文字色変更-->
    <?php endforeach;?>
<?php endif;?>
    <!-- 「編集の場合はIDを表示する」とのことなので、ここでもif文で場合分け -->
<?php if (isset($_GET['mode']) && $_GET['mode'] == 'edit' && isset($_POST)) : ?> <!--👈 if (!empty($_GET['id']))だけでよい-->
    <div>
        <label>ID：<?= $_GET['id'] ?></label> <!--👈ID修正OK!-->
    </div>
<?php endif; ?>
<div>
    <label for="content">内容：</label><br>
    <textarea id="content" name="content" rows="5" cols="40"><?=!empty($merge['content']) ? h($merge['content']) : ''?></textarea>
</div>   
<div>  
    <label for="release_at">公開日時：</label><br>
    <input type="datetime-local" id="release_at" name="release_at" value="<?=!empty($merge['release_at']) ? h($merge['release_at']) : ''?>">
</div>
<div>
    <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
<?php if (isset($_GET['mode']) && $_GET['mode'] == 'edit' && isset($_POST)) : ?> <!--GETパラメータの有無で、表示するボタンを出しわける-->
        <button type="submit" id="input_ed">編集確認</button>
<?php else : ?>
        <button type="submit" id="input_reg">登録確認</button>
<?php endif;?>
        </div>
    </form>
<?php require_once('../template/footer.php');?>
