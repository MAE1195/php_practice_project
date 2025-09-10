<?php
require_once('../library/library.php');

// authセッションがなかった場合、ログイン画面に遷移
if (!User::chkLogin()) {
    header('Location: ' . ROOT_URL . '/login.php');
    exit;
}
//ヘッダー読み込み
$menu = 2;
require_once('../template/header.php');

// CSRF対策トークンチェック  //👈confirmからresultのトークンチェックは正常に作動しているっぽい。
if (empty($_SESSION['token']) || empty($_POST['token']) || $_SESSION['token'] != $_POST['token']) {
    header('Location: index.php');
    exit;
}
// トークン削除
unset($_SESSION['token']);


$new_info = new NewInfo(); //共通するクラスをインスタンス化

    //前半はmodeがeditの場合という条件を追加して、その場合editメソッドを引き出そうとしてる
if (isset($_GET['mode']) && $_GET['mode'] == 'edit' && !empty($_POST['content']) && !empty($_POST['release_at'])) {
    $new_info->edit($_GET['id'], $_POST['content'], $_POST['release_at']);
    //登録の場合
} elseif (!empty($_POST['content']) && !empty($_POST['release_at'])) {
    $new_info->registration($_POST['content'], $_POST['release_at']);
     }
?>

<html>
<?php if (isset($_GET['mode']) && $_GET['mode'] == 'edit' && isset($_POST)) : ?>
    <span class="title">編集完了</span>
    <p class="highlight_blue">登録内容の編集が完了しました。</p>
<?php else : ?>
    <span class="title">新着情報登録完了</span>
    <p class="highlight_blue">新着情報が登録されました。</p>
<?php endif ; ?>
    <p ><a href="<?=ROOT_URL?>/new-info/index.php">一覧表示画面へ戻る</a></p>
</html>
<?php require_once('../template/footer.php');?>