<?php

class Validation
{
public function validation($request)
    {
$errors = [];  // エラーメッセージを格納する配列。
    // タイトルが空かどうかをチェック
    if (empty($request['title'])) {
        $errors[] = 'タイトルは必須です。';
    } 
    // タイトルの文字数チェック
    if ((!empty($request['title'])) && 100 < mb_strlen($request['title'])) {
        $errors[] = 'タイトルは100文字以内で入力してください。';
    }
    // 報告種類が空かどうかをチェック
    if (empty($request['kind'])) {
        $errors[] = '報告種類は必須です。';
    }
    // 詳細説明が空かどうかをチェック
    if (empty($request['detail'])) {
        $errors[] = '詳細説明は必須です。';
    } 
     // 詳細説明の文字数チェック
    if ((!empty($request['detail'])) && 1000 < mb_strlen($request['detail'])) {
        $errors[] = '詳細説明は1000文字以内で入力してください。';
    }
    if (!empty($request['reproduction']) && mb_strlen($request['reproduction']) > 1000) {
        $errors[] = '再現手順は1000文字以内で入力してください。';
    }
    if (!empty($request['environment']) && mb_strlen($request['environment']) > 500) {
        $errors[] = '発生環境は500文字以内で入力してください。';
    }
    //バグ報告の際の必須項目の警告
    if (($request['kind'] == 1) && empty($request['reproduction'])) {
        $errors[] = 'バグ報告の場合、再現手順は必須です。';
    }
    if (($request['kind'] == 1) && empty($request['environment'])) {
        $errors[] = 'バグ報告の場合、発生環境は必須です。';
    }
    if (($request['kind'] == 1) && empty($request['date'])) {
        $errors[] = 'バグ報告の場合、発生日時は必須です。';
    }
return $errors;
    }

    /**
     * 新着情報管理用のバリデーションチェック
     * @param $post
     * @return array
     */
public static function validateNewInfo($post)
    {
$errors = [];
        // 内容
    if (($length = getTrimStrlen($post['content'])) == 0) {
        $errors['content'] = '内容を入力してください。';
    } elseif ($length > 100) {
        $errors['content'] = '内容は100文字以内で入力してください。';
    }
    // 公開日時
     if (getTrimStrlen($post['release_at']) == 0) {
        $errors['release_at'] = '公開日時を入力してください。';
    }

    return $errors;
    }
}

