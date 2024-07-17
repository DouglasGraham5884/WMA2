<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Home | WMA2</title>
    
    <!-- Google Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- RESET CSS -->
    <link rel="stylesheet" href="./css/ress.min.css">
    
    <!-- CSS -->
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/scroll.css">

    <!-- Google Adsense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5987367501461422" crossorigin="anonymous"></script>
</head>
<body>
<?php include "./_parts/ad_overlay.php"; ?>
    <div class="container-L">
        <header>
            <form action="./test.php" class="header-form" id="header-form" method="GET"></form>
            <div class="header-1">
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
                    <input type="search" name="searchFolderName" spellcheck="false" placeholder="search for folder※開発中..." form="header-form"readonly>
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
                <p><span class="username">Guest</span> - <span class="item-count"></span> Items</p>
                <div class="input">
                    <input type="search" name="searchTag" spellcheck="false" placeholder="search for tag※開発中..." form="header-form" readonly>
                    <input type="search" name="searchFileName" spellcheck="false" placeholder="search for filename※開発中..." form="header-form" readonly>
                </div>
            </div>
            <div class="header-4">
                <img class ="wma2-logo sp" src="./img/WMA2.png" alt="wma2_logo">
            </div>
            <div class="header-5">
                <p><span class="username">Guest</span> - <span class="item-count"></span> Items</p>
            </div>
            <div class="header-6">
                <div class="header-button">
                    <button class="create">Create</button>
                    <button class="import">Import</button>
                    <button class="logout">Logout</button>
                    <button class="export display-none" form="header-form">Export</button>
                </div>
            </div>
        </header>
        <div class="main-container">
            <main>
            </main>
            <aside class="ad">
                <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-5987367501461422" data-ad-slot="8073276884" data-ad-format="auto" data-full-width-responsive="true"></ins>
            </aside>
        </div><!-- /main-container -->
        <section class="bottom-ad">
            <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-5987367501461422" data-ad-slot="6807979441" data-ad-format="auto" data-full-width-responsive="true"></ins>
        </section>
    </div><!-- /container-L -->

    <script type="text/javascript">
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
