<?php
require_once('../../library/library.php');

// authセッションがなかった場合、ログイン画面に遷移
if (!User::chkLogin()) {
    header('Location: ' . ROOT_URL . '/login.php');
    exit;
}

$report = new Report(); 
//編集画面用のデータ読み込みメソッド呼び出し
$rows = $report->getDataForEdit($_GET['id']); //indexから渡されたIDのデータをDBから持ってくる

//遷移可能なステータスのみをプルダウンに表示させるための準備
$status_transitions = [  
  1 => [2, 99],       // 未対応 → 対応中 or 却下
  2 => [3, 99],       // 対応中 → 対応済 or 却下
  3 => [],           // 対応済 → 変更不可
  99 => []            // 却下 → 変更不可
];


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
    <!--特定のステータスのみに遷移できるようにするのはこの画面で行う--> 
    <label for="status">現在の状態</label><br>
        <?=REPORT_STATUS[$row['status']]?><br><br>
        <!--以下条件分け-->    
        <?php if ($row['status'] == 1): ?>
            <label for="status">状態を更新する</label><br>
                <div class="button-area">
                    <form action="index.php" method="post" class="input-form"> <!--index.phpに送信-->
                        <select name="new_status" id="status">
                            <?php foreach ($status_transitions[$row['status']] as $key => $val) :?>
                                <option value="<?=$val?>"><?=REPORT_STATUS[$val]?></option>
                            <?php endforeach;?>   
                        </select> 
                        <input type="hidden" name="id" value="<?=$_GET['id']?>"> <!--現在のURLのIDのゲットパラメータをindex.phpに送信-->
                        <input type="hidden" name="old_status" value="<?=$row['status']?>"> <!--現在DBに入ってるstatusの値をold_statusとして送信-->                    
                        <p><button type="submit" class="button primary-button">更新</button></p>
                    </form>
                </div>
        <?php elseif ($row['status'] == 2): ?>
                <div class="button-area">
                    <form action="index.php" method="post" class="input-form"> <!--index.phpに送信-->
                        <select name="new_status" id="status">
                            <?php foreach ($status_transitions[$row['status']] as $key => $val) :?>
                                <option value="<?=$val?>"><?=REPORT_STATUS[$val]?></option>
                            <?php endforeach;?>   
                        </select> 
                        <input type="hidden" name="id" value="<?=$_GET['id']?>"> <!--現在のURLのIDのゲットパラメータをindex.phpに送信-->
                        <input type="hidden" name="old_status" value="<?=$row['status']?>"> <!--現在DBに入ってるstatusの値をold_statusとして送信-->
                        <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
                        <p><button type="submit" class="button primary-button">更新</button></p>
                    </form>
                </div>
        <?php elseif ($row['status'] == 3 || $row['status'] == 99) :?> <!--「対応済み」と「却下」の場合はプルダウンを表示しない-->
            <p><?='ステータスを変更できません。'?><p>
        <?php endif;?>

<?php endforeach; ?>
<?php require_once('../../template/footer.php');?>


