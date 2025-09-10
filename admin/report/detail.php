<?php
require_once('../../library/library.php');

// authセッションがなかった場合、ログイン画面に遷移
if (!User::chkLogin()) {
    header('Location: ' . ROOT_URL . '/login.php');
    exit;
}

$report = new Report();
$rows = $report->getDataForEdit($_GET['id']); //👈編集画面と表示する内容自体は一緒なので
$rows2 = $report->showEditHistory($_GET['id']); //ステータス変更履歴参照用のメソッド

$menu = 3;
require_once('../../template/header.php');
?>
<div>
    <label>ID：<?= $_GET['id'] ?></label> <!--indexから渡されたIDを表示-->
</div>
<?php foreach($rows as $row): ?>
    <div>
        <label>タイトル</label><br>
        <?=nl2br($row['title'])?>
    </div>
    <div>
        <label>報告種別</label><br>
        <?=REPORT_TYPES[$row['type']]?>
    </div>
    <div>
        <label>作成日時</label><br>
        <?=$row['created_at']?>
    </div>
    <div>
        <label>報告者ID</label><br>
        <?=$row['reporter_user_id']?>
    </div>
    <div>
        <label>状態</label><br>
        <?=REPORT_STATUS[$row['status']]?>
        <br><br>
    </div>
    <div>
        <label>報告ステータス変更履歴（最終更新）</label><br>
        <?php foreach($rows2 as $row2): ?>
            <?=$row2['updated_at']?>
        <?php endforeach;?>
    </div>

<?php endforeach; ?>
<?php require_once('../../template/footer.php');?>