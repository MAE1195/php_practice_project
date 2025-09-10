<?php
/*
 * ユーザクラス
 */
class NewInfo extends Database
{
    //新着情報一覧(index)でのデータの取得 
    public function getdata() //👈引数は必要？
    {
        try {
            $this->connect(); //👈DBとの接続はこれでOK
            $sql =
                ' SELECT '
                    . ' id, '
                    . ' content, '
                    . ' release_at, '
                    . ' created_at, '
                    . ' updated_at ' //👈「,」消し忘れでsyntax error出るので注意！
                . ' FROM '
                    . ' new_infos '
                . ' WHERE delete_flg = 0 '
                . ' ORDER BY '
                    . ' id DESC '
               /*   . ' AND content = :content ' 
                    . ' AND release_at = :release_at ' */
            ; //bindValue を使って値をバインドします。bindValueを使うことで、安全に値をSQL文に組み込むことができます。
            $stmt = $this->dbh->query($sql); //👈bindvalue使わないのでprepareじゃなくてよい
          //$stmt = $this->dbh->query($sql);
          //$stmt->bindValue(':content', $_POST['content'], PDO::PARAM_INT);  //👈この2つはユーザーの操作で更新するからという理由で入れてるが、正しい? ↓
          //  $stmt->bindValue(':release_at', $_POST['release_at'], PDO::PARAM_INT); //このメソッドはテーブルを指定して値を取得すためのメソッドなので、入力するものはないのでこの2行必要ない
          //$stmt->execute(); 👈queryを使ってるのでexecuteは必要ない
            return $stmt->fetchAll(PDO::FETCH_ASSOC); //データを取得してreturnする
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    //入力（編集）画面(input)でのデータの取得
    public function getdata_foredit($newid) //引数でGETパラメータの値をもらう
    {
        try {
            $this->connect(); 
            $sql = 
                ' SELECT '
                    . ' content, '
                    . ' release_at '
                . ' FROM '
                    . ' new_infos '
                . ' WHERE delete_flg = 0 '
                . ' AND id = :id ' //当該idの値を編集画面で初期表示するため　
            ; 
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $newid, PDO::PARAM_INT);  //ここでスーパーグローバル変数使ったら登録押したときエラー出た
            $stmt->execute();
            $rows = $stmt->fetch(PDO::FETCH_ASSOC);
        return $rows;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }
            
    //データの登録
    public function registration($newcontent, $newrelease_at)
    {
        try {
            $this->connect();             
            $sql = " INSERT INTO new_infos (content, release_at) VALUES (:content, :release_at)";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':content', $newcontent, PDO::PARAM_STR);
            $stmt->bindValue(':release_at', $newrelease_at, PDO::PARAM_STR); //👈PARAM_INTだと反映されなかった
            $stmt->execute();
        //登録、更新、削除の操作ではデータの取得は行わないため、fetch() や fetchAll() は不要です。
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    //データの編集
    public function edit($newid, $newcontent, $newrelease_at)
    {
        try {
            $this->connect();
            $sql = " UPDATE new_infos SET content = :content, release_at = :release_at, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->dbh->prepare($sql);                                         //👆編集したときのみ更新日時を更新できるようにする
            $stmt->bindValue(':id', $newid, PDO::PARAM_STR);
            $stmt->bindValue(':content', $newcontent, PDO::PARAM_STR);
            $stmt->bindValue(':release_at', $newrelease_at, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }
    
    //データの削除
    public function delete($newid)
    {
        try {
            $this->connect();
            $sql = " UPDATE new_infos SET delete_flg = 1, updated_at = CURRENT_TIMESTAMP WHERE id = :id ";
            $stmt = $this->dbh->prepare($sql);  //👆論理削除
            $stmt->bindParam(':id', $newid);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    //トップページでのデータの取得 
    public function getDataForTopPage()
    {
        try {
            $this->connect();
            $sql =
                ' SELECT '
                    . ' id, '
                    . ' content, '
                    . ' release_at, '
                    . ' created_at '
                . ' FROM '
                    . ' new_infos '
                . ' WHERE delete_flg = 0 ' //「delete_flg = false のレコード」
                . ' AND release_at <= CURRENT_TIMESTAMP ' //「現在日時以前の release_at を持ち」
                . ' ORDER BY '
                    . ' release_at DESC ' //「表示順は release_at の新しい順」
                . ' LIMIT '
                    . ' 3 ' //「最大3件表示」
            ; 
            $stmt = $this->dbh->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    //index画面、ソート用
    public function sort($column, $sort)
    {
        try {
            $this->connect();
            $sql =
                ' SELECT '
                    . ' id, '
                    . ' content, '
                    . ' release_at, '
                    . ' created_at, '
                    . ' updated_at '
                . ' FROM '
                    . ' new_infos '
                . ' WHERE delete_flg = 0 '
                . ' ORDER BY '
                    . $column . ' ' . $sort  //👈このカラム名とASC/DESCの部分をそれぞれ変数にして、引数から値を受け取ることができる
            ;  //普通PHPでは変数を「"」（ダブルカラム）では囲わない、エラーの原因になる
            $stmt = $this->dbh->query($sql); //👈BindValueを使わない場合はprepareである必要はない
         // $stmt->execute(); //👈queryの場合はexecuteも必要ない
         // $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    //  return $rows; //👈ここで$rowsをリターンしているが、メソッド側ではわざわざ代入する必要がないので、$stmtをそのままreturnしてOK
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }
}