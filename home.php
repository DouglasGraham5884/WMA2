<?php

$place_name = "Home";

// セッションクッキーの寿命を20分に設定
$cookieLifetime = 1200; // 秒
ini_set('session.gc_maxlifetime', $cookieLifetime);
session_set_cookie_params($cookieLifetime);
session_start();

require_once dirname(__FILE__) . "/./classes/DataLogic.php";

// ログインしていた場合ホームへ遷移
if(!isset($_SESSION["login_user"])) {
    header("Location: ./index.php");
}
$user = $_SESSION["login_user"];

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Home | WMA2</title>
    
<!-- <php include "./_parts/favicon.php"; ?> -->
    
    <!-- Google Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- RESET CSS -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css"> -->
    <link rel="stylesheet" href="./css/ress.min.css">
    
    <!-- CSS -->
    <!-- <base href="/wma2/"> -->
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/scroll.css">

<!-- Google Adsense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5987367501461422" crossorigin="anonymous"></script>
</head>
<body>
<?php include "./_parts/overlay.php"; ?>
    <div class="container-L">
        <header>
            <form action="./test.php" class="header-form" id="header-form" method="GET"></form>
            <div class="header-1">
                <!-- <h1 class="logo logo-home"><span class="logo-W">W</span><span class="logo-M">M</span><span class="logo-A">A</span><span class="logo-2">2</span></h1> -->
                <img class ="wma2-logo" src="./img/WMA2.png" alt="wma2_logo">
                <div class="header-button">
                    <button class="create">Create</button>
                    <button class="import">Import</button>
                    <button class="logout">Logout</button>
                    <button class="export display-none" form="header-form">Export</button>
                </div>
            </div>
            <div class="header-2">
                <div class="input">
                    <!-- <button class="folder-button">search for folder</button> -->
                    <input type="search" name="searchFolderName" spellcheck="false" placeholder="search for folder※開発中..." form="header-form"readonly>
                    <!-- 今はdisplay: none;後で独自のUIを作成する。overlayを挟む。 -->
                    <!-- 一度ちゃんとFigmaで作成したほうが良いかもしれない。 -->
                    <select name="searchFolderName" class="display-none" form="header-form" multiple>
                        <option value="all">----- ALL -----</option>
                        <option value="folder1">folder1</option>
                        <option value="folder2">folder2</option>
                        <option value="folder3">folder3</option>
                        <option value="folder4">folder4</option>
                    </select>
                </div>
            </div>
            <div class="header-3">
                <p><span class="username"><?= $user["user_name"]; ?></span> - <span class="item-count"></span class="item-count"> Items</p>
                <div class="input">
                    <!-- Enterやsubmitを使わず、YouTube方式で随時検索結果を表示しようと思う。 -->
                    <input type="search" name="searchTag" spellcheck="false" placeholder="search for tag※開発中..." form="header-form" readonly>
                    <input type="search" name="searchFileName" spellcheck="false" placeholder="search for filename※開発中..." form="header-form" readonly>
                </div>
            </div>
            <div class="header-4">
                <!-- <h1 class="logo logo-home"><span class="logo-W">W</span><span class="logo-M">M</span><span class="logo-A">A</span><span class="logo-2">2</span></h1> -->
                <img class ="wma2-logo sp" src="./img/WMA2.png" alt="wma2_logo">
            </div>
            <div class="header-5">
                <p><span class="username"><?= $user["user_name"]; ?></span> - <span class="item-count"></span class="item-count"> Items</p>
            </div>
            <div class="header-6">
            <div class="header-button">
                <button class="create">Create</button>
                <button class="import">Import</button>
                <button class="logout">Logout</button>
                <button class="export display-none" form="header-form">Export</button>
            </div>
        </header>
        <div class="main-container">
            <main>
            </main>
            <!-- wma2-aside -->
            <!-- <aside class="ad">
                <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-5987367501461422" data-ad-slot="8073276884" data-ad-format="auto" data-full-width-responsive="true"></ins>
            </aside> -->
        </div><!-- /main-container -->
        <!-- wma2-section -->
        <!-- <secction class="bottom-ad">
            <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-5987367501461422" data-ad-slot="6807979441" data-ad-format="auto" data-full-width-responsive="true"></ins>
        </secction> -->
    </div><!-- /container-L -->

    <script type="text/javascript">
        // From Redirect
        const open = document.getElementById("open");
        const close = document.getElementById("close");
        const overlay = document.querySelector(".overlay");
        const secondOverlay = document.querySelector(".second-overlay");
        const zoomForm = document.getElementById("zoom-form");
        const titleBar = document.querySelector(".zoom-title-bar-title");
        const descTitle = document.querySelector(".zoom-desc-box-title");
        const zoomTextBox = document.querySelector(".zoom-text-box");
        const zoomUrlBox = document.querySelector(".zoom-url-box");
        const zoomDescBox = document.querySelector(".zoom-desc-box");
        const textarea = document.querySelector(".zoom-display textarea");
        const descTag = document.querySelector(".desc-tag");
        const descFolder = document.querySelector(".desc-folder");
        const descCreated = document.querySelector(".desc-created");
        const descUpdated = document.querySelector(".desc-updated");
        const edit = document.querySelector(".edit");
        const spEdit = document.querySelector(".sp-edit");
        const url = document.querySelector(".url");
        const spUrl = document.querySelector(".sp-url");
        const desc = document.querySelector(".desc");
        const spDesc = document.querySelector(".sp-desc");
        const Export = document.querySelector(".export");
        const spExport = document.querySelector(".sp-export");
        const Delete = document.querySelector(".delete");
        const spDelete = document.querySelector(".sp-delete");
        const share = document.querySelector(".share");
        const spShare = document.querySelector(".sp-share");
        const save = document.querySelector(".save");
        const spSave = document.querySelector(".sp-save");
        
        // From PHP
        const user_id = <?= $user["user_id"]; ?>;

        // From Static
        const TOP_DIR = "https://st-gallery.net/wma2/";
        const body = document.querySelector("body");
        const containerL = document.querySelector(".container-L");
        const creates = document.querySelectorAll(".create");
        const Imports = document.querySelectorAll(".import");
        const logouts = document.querySelectorAll(".logout");
        const itemCounts = document.querySelectorAll(".item-count");
        const main = document.querySelector("main");
    </script>
    <script type="text/javascript" defer src="./js/functions.js"></script>
    
    <script type="text/javascript" defer src="./js/zoom_control.js"></script>
    <script type="text/javascript" defer src="./js/overlay.js"></script>
    
    <script type="text/javascript" src="./js/clamptext.js"></script>
    <script type="text/javascript" defer src="./js/textarea.js"></script>
    <script type="text/javascript" defer src="./js/header.js"></script>

    <!-- Google Adsense -->
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
</body>
</html>
