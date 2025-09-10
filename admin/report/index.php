<?php //検索機能はまだ未完成
require_once('../../library/library.php');

// authセッションがなかった場合、ログイン画面に遷移
if (!User::chkLogin()) {
    header('Location: ' . ROOT_URL . '/login.php');
    exit;
}

//権限チェック
$roles = Auth::chkUserRole($_SESSION['_auth']['role']); //セッションに保存されてる権限レベルを引数としてメソッドに渡す
if($roles == 0) {  //権限チェックのメソッドからの返り値が0（不許可）なら
    Auth::showErrorPage(403); //エラー403を引き渡しメソッド実行
    } 

$report = new Report(); //報告機能のクラスをインスタンス化

//この画面で検索ボタンが押されたら、検索メソッド呼び出して変数に代入
if (isset($_POST['search'])) {
    $rows2 = $report->search($_POST['reporter_user_id'], $_POST['type'], $_POST['created_at'], $_POST['status']);
} elseif (isset($_POST['new_status'])) {    //edit画面から状態を更新するPOST送信を受け取ったら編集メソッド呼び出し
    $report->edit($_POST['id'], $_POST['new_status']); //対応するデータの状態を更新
    $report->editHistory($_POST['id'], $_POST['old_status'], $_POST['new_status'], $_SESSION['_auth']['id']);   //👈報告ステータス変更履歴を記録
} 

/*edit画面から状態を更新するPOST送信を受け取ったら編集メソッド呼び出し
if (isset($_POST['new_status'])) {
    $report->edit($_POST['id'], $_POST['new_status']); //対応するデータの状態を更新
    $report->editHistory($_POST['id'], $_POST['old_status'], $_POST['new_status'], $_SESSION['_auth']['id']);   //👈報告ステータス変更履歴を記録
} 
*/

$rows = $report->getData(); //上記の処理を行わない場合は、DBから一覧用のデータを取得して変数に代入（初期表示）

//ヘッダー読み込み
$menu = 3;
require_once('../../template/header.php');

?>
<span class="title">報告一覧</span>
<form action="" method="post"> <!--検索機能はいろいろ未完成（あいまい検索・報告種別と状態が初期表示で値が入ってしまっていること等）-->
    報 告 者 :<input type="text" name="reporter_user_id" value="<?= isset($_POST['reporter_user_id']) ? $_POST['reporter_user_id'] : ''?>"><br>
    報告種別:
            <select name="type">
                <?php foreach (REPORT_TYPES as $key => $val) :?> 
                    <option value="<?=$key?>"<?=isset($_POST['type']) && $_POST['type'] == $key ? ' selected' : ''?>><?=$val?></option> 
                <?php endforeach;?>     
            </select><br>
    作成日時:<input type="text" name="created_at" value="<?= isset($_POST['created_at']) ? $_POST['created_at'] : ''?>"><br>
    状　　態:
            <select name="status">
                <?php foreach (REPORT_STATUS as $key => $val) :?> 
                    <option value="<?=$key?>"<?=isset($_POST['status']) && $_POST['status'] == $key ? ' selected' : ''?>><?=$val?></option> 
                <?php endforeach;?>     
            </select><br>
    <p><button type="submit" name="search">検索</button></p>

</form>

<?php if (isset($_POST['search'])) :?>  <!--無理やり条件分岐させてるけど、一つのスマートなものにしたい。条件分岐の中でforeachした場合、どこでendforeachすべきなのかがわからない-->
    
    <table border="1">
        <thead>
            <tr>
                <th>ID
                <th>タイトル
                <th>報告種別
                <th>作成日時
                <th>状態
                <th>報告者ID
                <th>操作
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows2 as $row2): ?> 
                <tr>
                    <td><?=$row2['id']?></td>
                    <td><?=nl2br($row2['title'])?></td> 
                    <td><?=REPORT_TYPES[$row2['type']]?></td>  
                    <td><?=$row2['created_at']?></td> 
                    <td><?=REPORT_STATUS[$row2['status']]?></td>
                    <td><?=$row2['reporter_user_id']?></td>
                    <td>        
                        <form action="" method="post">
                            <a href="edit.php?id=<?=$row2['id']?>">編集</a>
                            <a href="detail.php?id=<?=$row2['id']?>">詳細</a>
                        </form>                    
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>

<?php else :?>
    <table border="1">
        <thead>
            <tr>
                <th>ID
                <th>タイトル
                <th>報告種別
                <th>作成日時
                <th>状態
                <th>報告者ID
                <th>操作
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row): ?>
                <tr>
                    <td><?=$row['id']?></td>
                    <td><?=nl2br($row['title'])?></td> 
                    <td><?=REPORT_TYPES[$row['type']]?></td>  
                    <td><?=$row['created_at']?></td> 
                    <td><?=REPORT_STATUS[$row['status']]?></td>
                    <td><?=$row['reporter_user_id']?></td>
                    <td>        
                        <form action="" method="post">
                            <a href="edit.php?id=<?=$row['id']?>">編集</a>
                            <a href="detail.php?id=<?=$row['id']?>">詳細</a>
                        </form>                    
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>

<?php endif ;?>
<?php require_once('../../template/footer.php');?>