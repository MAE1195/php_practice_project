<?php
require_once('library/library.php');

// authセッションがなかった場合、ログイン画面に遷移
if (!User::chkLogin()) {
    header('Location: ' . ROOT_URL . '/login.php');
    exit;
}

$menu = 1;
require_once('template/header.php');

$new_info = new NewInfo(); //インスタンス化
$rows = $new_info->getDataForTopPage();//メソッド呼び出し
?>
<div class="top-news">
    <h3 class="top-title">新着情報</h3>
<?php  foreach($rows as $row): ?> 
    <ul>
        <li>
            <p class="top-left"><?= $row['created_at'] ?></p>
            <p class="top-right"><?= $row['content'] ?></p>
        </li>
    </ul>
<?php endforeach; ?>
</div>
<?php require_once('template/footer.php');?>
