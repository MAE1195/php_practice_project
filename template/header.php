<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?=ROOT_URL?>/css/style.css" rel="stylesheet"> <!--ここでCSSファイルを読み込んでる-->
    <title><?=SITE_NAME?>　管理画面</title>
</head>
<body>
    <div class="wrapper">
        <main>
            <header>
                <p class="greeting">ログイン名[<?=h($_SESSION['_auth']['name'])?>]さん、ご機嫌いかがですか？</p>
                <a href="<?=ROOT_URL?>/logout.php" class="logout">ログアウトする</a>
            </header>
            <h1><?=SITE_NAME?></h1>
            <nav>
                <ul>
                    <li class="nav"><!--メモ:'nav'はサイト内のリンク集（ナビゲーション）をまとめるタグです。-->
                        <a href="<?=ROOT_URL?>/" class="nav<?=$menu == 1 ? ' current' : ''?>">top</a>
                    </li>
                    <li class="nav">
                        <a href="<?=ROOT_URL?>/new-info/index.php" class="nav<?=$menu == 2 ? ' current' : ''?>">新着情報管理</a>
                    </li>                                                  <!-- current はcssのstyleタグ。「$menu == 2 のときcurrent というstyleを適用する」ということ-->
                    <li class="nav">
                        <a href="<?=ROOT_URL?>/admin/report/index.php" class="nav<?=$menu == 3 ? ' current' : ''?>">報告管理</a> <!--追加仕様2-->
                    </li>
                    <li class="nav">
                        <a href="<?=ROOT_URL?>/report/input.php" class="nav<?=$menu == 4 ? ' current' : ''?>">報告</a> <!--メモ：hrefに何か値入れる？-->
                    </li>       <!--👆入力画面へ移動するようになったが、👆このclassとか$menu == 4とかよくわからん-->
                </ul>
            </nav>
            <div class="contents-area">