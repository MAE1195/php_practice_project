<?php //追加仕様2 課題に指定は無いが他の機能との混同を避けるために新たにクラスを定義

//データの登録
class Report extends Database
{
    public function registration($newtitle, $newtype, $newdescription, $newoccurred_at, $newreproduction_steps, $newenvironment, $newreporter_user_id)
    { //statusは登録時は指定しない（かつ初期値は1）ので、登録メソッドではINSERTしない
        try {
            $this->connect();             
            $sql = " INSERT INTO reports (title, type, description, occurred_at, reproduction_steps, environment, reporter_user_id) VALUES (:title, :type, :description, :occurred_at, :reproduction_steps, :environment, :reporter_user_id)";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':title', $newtitle, PDO::PARAM_STR);
            $stmt->bindValue(':type', $newtype, PDO::PARAM_STR); //👈PARAM_INTだと反映されなかった
            $stmt->bindValue(':description', $newdescription, PDO::PARAM_STR);
            $stmt->bindValue(':occurred_at', $newoccurred_at, PDO::PARAM_STR);
            $stmt->bindValue(':reproduction_steps', $newreproduction_steps, PDO::PARAM_STR);
            $stmt->bindValue(':environment', $newenvironment, PDO::PARAM_STR);
            $stmt->bindValue(':reporter_user_id', $newreporter_user_id, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    //一覧画面(index)でのデータの取得 
    /*一覧画面の上部に、報告者（報告したユーザー）、報告種別、報告日時（作成日時）、ステータスで検索を行えるエリアを表示し、
      検索結果が一覧表示されるようにします。
      初期状態では**全件表示（報告日時の新しい順）**とし、表示項目については検索項目に加え、必要と思われる項目を含めてDBから取得した報告データを表示します。*/
    public function getData()
    {
        try {
            $this->connect();
            $sql =
                ' SELECT '
                    . ' id, ' 
                    . ' title, '
                    . ' type, '
                    . ' created_at, '
                    . ' status, ' 
                    . ' reporter_user_id '
                . ' FROM '
                    . ' reports '
                . ' ORDER BY '
                    . ' created_at DESC '
            ;
            $stmt = $this->dbh->query($sql); //👈bindvalue使わないのでprepareじゃなくてよい
            return $stmt->fetchAll(PDO::FETCH_ASSOC); //データを取得してreturnする
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    //編集画面(edit)でのデータの取得　
    public function getDataForEdit($newid) //引数でGETパラメータの値をもらう
    {
        try {
            $this->connect(); 
            $sql = 
                ' SELECT '
                    . ' id, ' 
                    . ' title, '
                    . ' type, '
                    . ' created_at, '
                    . ' status, ' 
                    . ' reporter_user_id '
                . ' FROM '
                    . ' reports '
                . ' WHERE id = :id '
            ; 
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $newid, PDO::PARAM_INT);  
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); //👈fetchだとエラーが出る
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }


    //データの編集
    public function edit($newid, $status)
    {
        try {
            $this->connect();
            $sql = " UPDATE reports SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->dbh->prepare($sql);            //👆更新日時、初期値はNULLなので現在時刻に更新
            $stmt->bindValue(':id', $newid, PDO::PARAM_STR);                                    
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    //報告ステータス変更履歴を記録
    public function editHistory($reportId, $oldStatus, $newStatus, $newUpdateUserId)
    {
        try {
            $this->connect();
            $sql = " INSERT INTO report_status_histories (report_id, old_status, new_status, update_user_id) VALUES (:report_id, :old_status, :new_status, :update_user_id)";
            $stmt = $this->dbh->prepare($sql);         
            $stmt->bindValue(':report_id', $reportId, PDO::PARAM_STR);                                    
            $stmt->bindValue(':old_status', $oldStatus, PDO::PARAM_STR);
            $stmt->bindValue(':new_status', $newStatus, PDO::PARAM_STR);
            $stmt->bindValue(':update_user_id', $newUpdateUserId, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    //一覧画面での検索用メソッド
    public function search($reporter_user_id, $type, $created_at, $status)
    {
        try {
            $this->connect();
            $sql = 
                ' SELECT '
                    . ' id, ' 
                    . ' title, '
                    . ' type, '
                    . ' created_at, '
                    . ' status, ' 
                    . ' reporter_user_id '
                . ' FROM '
                    . ' reports '
                . ' WHERE reporter_user_id = :reporter_user_id '
                . ' OR type = :type '
                . ' OR created_at = :created_at '
                . ' OR status = :status '
                . ' ORDER BY '
                    . ' created_at DESC '
            ;           
            $stmt = $this->dbh->prepare($sql);         
            $stmt->bindValue(':reporter_user_id', $reporter_user_id, PDO::PARAM_STR);                                    
            $stmt->bindValue(':type', $type, PDO::PARAM_STR);
            $stmt->bindValue(':created_at', $created_at, PDO::PARAM_STR);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); //データを取得してreturnする
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    //報告ステータス変更履歴を参照
    public function showEditHistory($newid)
    {
        try {
            $this->connect();
            $sql =
                ' SELECT '
                    . ' report_id, ' 
                    . ' updated_at '
                . ' FROM '
                    . ' report_status_histories '
                . ' WHERE report_id = :report_id ' 
                . ' ORDER BY '   
                    . ' updated_at DESC ' //👈更新日時の降順（最新が上）にして、1件目から1件のみ取得
                . ' LIMIT '
                    . ' 1 '
                . ' OFFSET '
                    . ' 0 '
            ;
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':report_id', $newid, PDO::PARAM_INT);  
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); //👈fetchだとエラーが出る
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }
}