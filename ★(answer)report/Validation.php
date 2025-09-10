<?php
/*
 * バリデーションクラス
 */
class Validation
{
    /**
     * 報告機能用のバリデーションチェック
     * @param $post
     * @return array
     */
    public static function validateReport($post)
    {
        $errors = [];

        // タイトル
        if (($length = getTrimStrlen($post['title'])) == 0) {
            $errors['title'] = 'タイトルを入力してください。';
        } elseif ($length > 100) {
            $errors['title'] = 'タイトルは100文字以内で入力してください。';
        }

        // 詳細説明
        if (($length = getTrimStrlen($post['description'])) == 0) {
            $errors['description'] = '詳細説明を入力してください。';
        } elseif ($length > 1000) {
            $errors['description'] = '詳細説明は1000文字以内で入力してください。';
        }

        // 報告種別がバグ報告の場合
        if ($post['report_type'] == 1) {

            // 発生日時
            if (getTrimStrlen($post['occurred_at']) == 0) {
                $errors['occurred_at'] = '種別が' . REPORT_TYPES[$post['report_type']] . 'の場合は、発生日時を入力してください。';
            }

            // 再現手順
            if (($length = getTrimStrlen($post['reproduction_steps'])) == 0) {
                $errors['reproduction_steps'] = '種別が' . REPORT_TYPES[$post['report_type']] . 'の場合は、再現手順を入力してください。';
            } elseif ($length > 1000) {
                $errors['reproduction_steps'] = '種別が' . REPORT_TYPES[$post['report_type']] . 'の場合は、再現手順は1000文字以内で入力してください。';
            }

            // 発生環境
            if (($length = getTrimStrlen($post['environment'])) == 0) {
                $errors['environment'] = '種別が' . REPORT_TYPES[$post['report_type']] . 'の場合は、発生環境を入力してください。';
            } elseif ($length > 500) {
                $errors['environment'] = '種別が' . REPORT_TYPES[$post['report_type']] . 'の場合は、発生環境は500文字以内で入力してください。';
            }
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
