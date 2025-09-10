<?php
/**
 * XSS対策（特殊文字をHTMLエンティティに変換）
 *
 * @param $str
 * @return string
 */
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * 先頭、末尾のスペース、全ての改行を除いた文字数取得
 *
 * @param $str
 * @return integer
 */
function getTrimStrlen($str)
{
    return mb_strlen(str_replace(["\r\n", "\r", "\n"], '' , (trim(mb_convert_kana($str, 's')))));
}

/**
 * エラー画面表示
 *
 * @param $message
 */
function displayErrorPage($message)
{
    $menu = 1;
    require_once(ROOT_PATH . '/template/header.php');
    echo '<span class="title">エラー</span>'
        . '<p class="error-message">' . $message . '</p>'
    ;
    require_once(ROOT_PATH . '/template/footer.php');
    exit;
}

/**
 * デバッグ用関数
 *
 * @param $data
 */
function dbg($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}