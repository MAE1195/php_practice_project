<?php
require_once('../library/library.php');

// authセッションがなかった場合、ログイン画面に遷移
if (!User::chkLogin()) {
    header('Location: ' . ROOT_URL . '/login.php');
    exit;
}

$new_info = new NewInfo(); //インスタンス化

if (isset($_POST['delete'])) {
    $new_info->delete($_POST['delete']); //削除メソッド呼び出し⇒実行（ここでDBのデータが削除される）
}                               //👆ボタンの名前を入れることで、ボタン押下時の値を送る
//削除メソッドをデータ取得メソッドより後に呼び出してしまうと、削除ボタン押下時すぐに結果が画面へ反映されない（一度F5で更新しないと）

if (isset($_GET['column'])) { //👈呼び出すメソッドをソートボタン押したときで場合分け
    $rows = $new_info->sort($_GET['column'], $_GET['sort']); 
} else $rows = $new_info->getdata(); //初期表示はid降順になるように、デフォルトの表示メソッドを呼び出し

//ヘッダー読み込み
$menu = 2;
require_once('../template/header.php');
?>
<span class="title">新着情報一覧</span>
<?php if (isset($_POST['delete'])) : ?>
    <p class="highlight_blue">削除が実行されました</p>
<?php endif; ?>
<p><a href="input.php?mode=new">登録</a></p>
<table border="1">
    <thead>
        <tr>
            <th>ID
                <a href="index.php?column=id&sort=ASC">▲</a> <!--aタグはリンクを作成するためのタグ。href 属性でリンク先を指定できる-->
                <a href="index.php?column=id&sort=DESC">▼</a>
            </th>
            <th>内容
            <th>公開日時
                <a href="index.php?column=release_at&sort=ASC">▲</a> <!--👈この辺は8/4最後に入力。GETパラメータで取るやり方これで合ってる？-->
                <a href="index.php?column=release_at&sort=DESC">▼</a> <!--👈リクエストの範囲に思いっきり書かれていたので見返すこと-->
            </th>
            <th>作成日時
            <th>更新日時
                <a href="index.php?column=updated_at&sort=ASC">▲</a>
                <a href="index.php?column=updated_at&sort=DESC">▼</a>
            </th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($rows as $row): ?>   
        <!-- foreach() argument must be of type array|object, null given in が出る、何故👉メソッド側でreturnしてなかった-->
            <tr>
                <td><?=$row['id']?></td>
                <td><?=nl2br($row['content'])?></td> 
                <td><?=$row['release_at']?></td>  
                <td><?=$row['created_at']?></td> 
                <td><?=$row['updated_at']?></td>
                <td>         <!--👇自画面遷移の場合actionは空でいい-->
                    <form action="" method="post">
                        <!-- 本来aタグはformに含める必要はないが、横並びにするため-->
                        <a href="input.php?mode=edit&id=<?=$row['id']?>">編集</a> <!--👈どこからidを受け取る？-->
                        <button type="submit" name="delete" id='registration' value="<?=$row['id']?>" onclick="return confirm('本当に削除してよろしいですか？')">削除</button>
                    </form>                                             <!--👆valueがボタン押したときに送る値になる-->  <!--👆JavaScriptのconfirmメソッド-->
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once('../template/footer.php');?>