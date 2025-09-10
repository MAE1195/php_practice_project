<?php
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

$validation = new Validation();
$errormassages = $validation->validateNewInfo($_POST);
if (!empty($errormassages)) {
    require_once ('input.php'); 
    exit;  
} 

// CSRF対策トークンチェック  👈👈前回からのコピペだが、何故かinputに付き返してしまうので一時的にコメントアウトしている。👈👈
if (empty($_SESSION['token']) || empty($_POST['token']) || $_SESSION['token'] != $_POST['token']) {
    header('Location: index.php'); 
    exit;
}
// トークン生成、セッションに代入
$_SESSION['token'] = hash('sha256', md5(uniqid(mt_rand(), true)));

?>
<html>
<!--一項目ごとif文で無理やり変えてるが、もっと効率的なやり方ある？-->
<?php if (isset($_GET['mode']) && $_GET['mode'] == 'edit' && isset($_POST)) : ?>
<span class="title">新着情報編集確認</span>
<?php else : ?>
<span class="title">新着情報登録確認</span>
<?php endif ; ?>
<div>
		<!-- 編集の場合はIDを表示する -->
	<?php if (isset($_GET['mode']) && $_GET['mode'] == 'edit') : ?> <!--👈 if (!empty($_GET['id']))だけでよい-->
        <div>
            <label>ID：<?=$_GET['id'] ?></label> <!--👈ID修正OK!-->
        </div>
	<?php endif ; ?>
		<div>
		    <label for="content">内容：</label><br>
			<?=nl2br(h($_POST['content']))?>
		</div>
		<div>
		    <label for="release_at">公開日時：</label><br>
			<?=str_replace('T', ' ', h($_POST['release_at']))?>
		</div>
</div>
<div> 
<?php  //編集の場合
if (isset($_GET['mode']) && $_GET['mode'] == 'edit' && isset($_POST)) : ?>
        <form action="result.php?mode=edit&id=<?=$_GET['id'] ?>" method="post"> <!--👈ID修正-->
			<input type="hidden" name="content" value="<?=h($_POST['content'])?>">
			<input type="hidden" name="release_at" value="<?=h($_POST['release_at'])?>">
			<input type="hidden" name="token" value="<?=$_SESSION['token']?>"> <!--トークンを送信-->
			<button type="submit" formaction="input.php?mode=edit&id=<?=$_GET['id'] ?>">戻る</button> <!--👈ID修正-->
            <button type="submit" id="comp_ed">編集完了</button>
		</form>
<?php else : ?> 
		<form action="result.php?mode=new" method="post">
			<input type="hidden" name="content" value="<?=h($_POST['content'])?>">
			<input type="hidden" name="release_at" value="<?=h($_POST['release_at'])?>">
			<input type="hidden" name="token" value="<?=$_SESSION['token']?>"> <!--トークンを送信-->
			<button type="submit" formaction="input.php?mode=new">戻る</button>		
		    <button type="submit" id="comp_reg">登録完了</button>
		</form>
<?php endif ; ?>
</div>
</html>
<?php require_once('../template/footer.php');?>