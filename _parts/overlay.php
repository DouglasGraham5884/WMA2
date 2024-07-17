    <div class="overlay">
        <span class="material-icons" id="open">menu</span>
        <span class="material-icons" id="close">close</span>
        <div class="second-overlay">
            <div class="zoom-control-bar-button sp">
                <button class="sp-edit">edit</button>
                <button class="sp-url">url</button>
                <button class="sp-desc">desc</button>
                <button class="sp-share">share</button>
                <button class="sp-export">export</button>
                <button class="sp-delete">delete</button>
                <button class="sp-save">save</button>
            </div>
        </div>
        <form action="./test.php" class="zoom-form" id="zoom-form" method="POST"></form>
        <div class="zoom">
            <div class="zoom-title-bar">
                <input type="text" name="title" class="zoom-title-bar-title" spellcheck="false" placeholder="NULL" value="">
            </div>
            <div class="zoom-display">
                <div class="zoom-text-box">
                    <textarea name="content" class="zoom-text-box-text" spellcheck="false"></textarea>
                </div>
                <div class="zoom-url-box display-none">
                </div>
                <div class="zoom-desc-box display-none">
                    <div class="desc-title"><p>TITLE:</p><input type="text" name="title" class="zoom-desc-box-title" spellcheck="false" placeholder="NULL" value=""></p></div>
                    <div class="desc-created"><p>CREATED:</p><p> - </p></div>
                    <div class="desc-updated"><p>UPDATED:</p><p> - </p></div>
                    <!-- FOLDERはあとでselectタグとかで書き直す -->
                    <div class="desc-folder"><p>FOLDER:</p><input name="folder" spellcheck="false" placeholder="開発中の機能です。" value="" readonly></p></div>
                    <div class="desc-tag"><p>TAG:</p><input name="tag" spellcheck="false" placeholder="開発中の機能です。" value="" readonly></p></div>
                </div>
            </div>
            <div class="zoom-control-bar">
                <div class="zoom-control-bar-button pc">
                    <button class="edit">edit</button>
                    <button class="url">url</button>
                    <button class="desc">desc</button>
                    <button class="share">share</button>
                    <button class="export">export</button>
                    <button class="delete">delete</button>
                    <button class="save">save</button>
                </div>
            </div>
        </div>
    </div>