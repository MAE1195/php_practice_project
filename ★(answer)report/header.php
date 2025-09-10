<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?=ROOT_URL?>/css/style.css" rel="stylesheet">
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
                    <li class="nav">
                        <a href="<?=ROOT_URL?>/" class="nav<?=$menu == 1 ? ' current' : ''?>">top</a>
                    </li>
                    <li class="nav">
                        <a href="<?=ROOT_URL?>/new-info/" class="nav<?=$menu == 2 ? ' current' : ''?>">新着情報管理</a>
                    </li>
                    <li class="nav">
                        <a href="" class="nav<?=$menu == 3 ? ' current' : ''?>">××</a>
                    </li>
                    <li class="nav">
                        <a href="<?=ROOT_URL?>/report/input.php" class="nav<?=$menu == 4 ? ' current' : ''?>">報告</a>
                    </li>
                </ul>
            </nav>
            <div class="contents-area">