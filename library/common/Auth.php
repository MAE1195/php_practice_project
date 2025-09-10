<?php

class Auth
{
    /*
    * ユーザーの権限をチェック
    */
    public static function chkUserRole($roleLevel)
    {
        if (!empty($roleLevel) && $roleLevel <= 2) //管理者ロールを持つユーザーのみがアクセスできるようにメソッド側で設定
            {
                return 1; //許可
            }
        return 0; //不許可
    }
    //この関数は、引数として要求される最低限のロールレベルを受け取り、
    // 現在のセッションに保存されたユーザーのロールと比較して、アクセス許可/拒否を判定します。

    /*
    * エラーコードを受け取り、対応するメッセージを取得してエラーページを表示するメソッド
    */
    public static function showErrorPage($errorCode = 500) //引数が指定されなかった場合は500エラーとして扱う
    {     
        require_once(ROOT_PATH . '/library/error/common_error.php'); //共通エラーページを呼び出し処理終了
        exit;
    }
}
