<?php
/*
 * ユーザクラス
 */
class User extends Database
{
    /**
     * authセッションの削除
     */
    public static function clearAuthSession()
    {
        if (!empty($_SESSION['_auth'])) {
            unset($_SESSION['_auth']);
        }
    }

    /**
     * ログイン可否判定
     *
     * @param $loginId
     * @param $loginPass
     * @return void
     */
    public function login($loginId, $loginPass)
    {
        try {
            $this->connect();

            $sql =
                ' SELECT '
                    . ' id, '
                    . ' role, ' //👈追加仕様1
                    . ' name, '
                    . ' login_pass, '
                    . ' mail '  //👈課題の条件に応じ追加
                . ' FROM '
                    . ' users '
                . ' WHERE delete_flg = 0 '
                    . ' AND login_id = :id '
            ;

            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $loginId, PDO::PARAM_STR);
            $stmt->execute();
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new PDOException($e);
        }

        // IDとパスワードに一致するユーザがいたらtrueを返す
        if (!empty($userData) && password_verify($loginPass, $userData['login_pass'])) {
            session_regenerate_id(true);

            // 本当は固定値じゃなくてハッシュ値
            $_SESSION['_auth']['hash'] = 1;
            $_SESSION['_auth']['id'] = $userData['id'];
            $_SESSION['_auth']['name'] = $userData['name'];
            $_SESSION['_auth']['mail'] = $userData['mail'];  //👈課題の条件に応じ追加
            $_SESSION['_auth']['role'] = $userData['role'];  //👈追加仕様1


            header('Location: ' . ROOT_URL . '/');
            exit;
        }
    }

    /**
     * ログイン状態判定
     *
     * @return bool
     */
    public static function chkLogin()
    {
        return !empty($_SESSION['_auth']);
    }
}