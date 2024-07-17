/**
 * @param {Array} textFields リサイズした文字列を表示させたい要素
 * @param {Array} textData リサイズしたい元データ
 * @param {String} type 関数を使用するオブジェクトのタイプ
 */
function vieTextResize(textFields, textData, type) {
    var maxWidth = vieTextMaximizer(type);
    for(var j = 0; j < textFields.length; j++) {
        var textField = textFields[j];
        var fileTextContent = textData[j];
        var displayText = '';
        textField.textContent = '';
        
        if(type === "memo") {
            var maxCount = 100;
        } else if(type === "url") {
            var maxCount = 2000;
        }
        if(!fileTextContent.length > maxCount) {
            var maxCount = fileTextContent.length;
        }
        for(var i = 0; i < maxCount; i++) {
            var char = fileTextContent[i];
            textField.textContent += char;
            
            if(char === undefined) {
                textField.textContent = displayText.trim();
                break;
            }
            if(textField.scrollWidth > maxWidth || i == (maxCount - 1)) {
                textField.textContent = displayText.trim() + '...';
                break;
            }
            displayText += char;
        }
    }
}

/**
 * @param {String} type 関数を使用するオブジェクトのタイプ
 * @returns {Number} テキストの最大幅
 */
function vieTextMaximizer(type) {
    if(type === "memo") {
        var mainWidth = parseInt(window.getComputedStyle(main).width);
        if(window.innerWidth <= 800 || window.innerHeight <= 500) {
            var maxWidth = (mainWidth - 10 - (10 * 1)) / 1 - 30;
        } else if(window.innerWidth > 801 && window.innerWidth <= 1200) {
            var maxWidth = (mainWidth - 10 - (10 * 2)) / 2 - 30;
        } else if(window.innerWidth > 1201 && window.innerWidth <= 1600) {
            var maxWidth = (mainWidth - 10 - (10 * 3)) / 3 - 30;
        } else {
            var maxWidth = (mainWidth - 10 - (10 * 4)) / 4 - 30;
        }
    } else if(type === "url") {
        var urlLinks = document.querySelectorAll(".url-link");
        var urlWidth = parseInt(window.getComputedStyle(urlLinks[0].parentElement).width);
        var maxWidth = urlWidth - 20;
    }
    return maxWidth;
}

function memoMaximizer(memo) {
    var mainWidth = window.getComputedStyle(main).width;
    if(window.innerWidth <= 800 || window.innerHeight <= 500) {
        memo.style.maxWidth = "calc((" + mainWidth + " - 10px - (10px * 1)) / 1)";
    } else if(window.innerWidth > 801 && window.innerWidth <= 1200) {
        memo.style.maxWidth = "calc((" + mainWidth + " - 10px - (10px * 2)) / 2)";
    } else if(window.innerWidth > 1201 && window.innerWidth <= 1600) {
        memo.style.maxWidth = "calc((" + mainWidth + " - 10px - (10px * 3)) / 3)";
    } else {
        memo.style.maxWidth = "calc((" + mainWidth + " - 10px - (10px * 4)) / 4)";
    }
}

function autoResizeTextarea(textarea) {
    // textarea.style.height = 'auto';  // テキストエリアの高さを一旦リセット
    textarea.style.height = textarea.scrollHeight + 'px';  // スクロール高さを高さとして設定
    if(textarea.scrollHeight > textarea.parentElement.clientHeight) {
        textarea.parentElement.style.overflowY = "scroll";
    } else {
        textarea.parentElement.style.overflowY = "hidden";
    }
}

function URLImportDown(down) {
    var pullUp = document.querySelectorAll(".pull-up");
    pullUp.forEach((up) => URLImportUp(up));
    var parent3 = down.parentNode.parentNode.parentNode;
    var parent3_last1 = parent3.lastElementChild;
    var next1 = down.nextElementSibling;
    var parent1_next1 = down.parentNode.nextElementSibling;
    
    parent3_last1.style.opacity = "1";
    parent3_last1.style.pointerEvents = "auto";
    parent3.style.height = "calc(3em + 10px)";
    parent1_next1.firstElementChild.style.display = "none";
    parent1_next1.lastElementChild.style.display = "inline-block";
    down.classList.add("hide");
    down.classList.remove("appear");
    next1.classList.add("appear");
    next1.classList.remove("hide");
}

function URLImportUp(up) {
    var parent3 = up.parentNode.parentNode.parentNode;
    var parent3_last1 = parent3.lastElementChild;
    var parent1_next1 = up.parentNode.nextElementSibling;
    var prev1 = up.previousElementSibling;
    
    parent3_last1.style.opacity = "0";
    parent3_last1.style.pointerEvents = "none";
    parent3.style.height = "calc(1.5em + 10px)";
    parent1_next1.firstElementChild.style.display = "inline-block";
    parent1_next1.lastElementChild.style.display = "none";
    up.classList.add("hide");
    up.classList.remove("appear");
    prev1.classList.add("appear");
    prev1.classList.remove("hide");
    vieTextResize([parent1_next1.firstElementChild], [parent1_next1.firstElementChild.textContent], "url");
}

function editButtonAction(edit) {
    var urlInput = edit.parentElement.previousElementSibling.lastElementChild.lastElementChild;
    var parent1_prev1_last1 = edit.parentElement.previousElementSibling.lastElementChild;
    if(edit.classList.contains("save-url-name")) {
        urlInput.setAttribute("readonly", "readonly");
        edit.textContent = "EDIT";
        edit.classList.remove("save-url-name");
        sendURL(edit, parent1_prev1_last1.firstElementChild.getAttribute("href"), parent1_prev1_last1.lastElementChild.value);
    } else {
        urlInput.removeAttribute("readonly");
        edit.textContent = "SAVE";
        edit.classList.add("save-url-name");
        var len = urlInput.value.length;
        urlInput.focus();
        urlInput.setSelectionRange(len, len);
        setTimeout(() => {
            urlInput.scrollLeft = urlInput.scrollWidth;
        }, 0);
    }
}

/**
 * overlayのeditタブで入力があった際の挙動
 * editボタンをsaveボタンに変える。
 */
function editInputAction() {
    [save, spSave].forEach((target) => {
        target.classList.remove("display-none");
    });
    open.style.color = "#0F6";
}

/**
 * overlayが表示されているかどうかで
 * bodyをスクロールできるかを切り替える
 */
function bodyAction() {
    if(overlay.classList.contains("show")) {
        body.style.overflow = "hidden";
    } else {
        body.style.overflow = "auto";
    }
}

/**
 * overlay上のzoom要素を非表示にする
 */
function zoomClose() {
    overlay.style.display = "none";
    open.style.display = "none";
    overlay.classList.remove("show");
    bodyAction();
    zoomTextBox.classList.remove("display-none");
    zoomUrlBox.classList.add("display-none");
    zoomDescBox.classList.add("display-none");
    textarea.value = "";
    textarea.style.height = 'auto';  // テキストエリアの高さを一旦リセット
    // displayAllMemos();
}

/**
 * overlay上のzoom要素を表示する
 */
function zoomOpen() {
    overlay.style.display = "flex";
    overlay.style.top = body.scrollTop + "px";
    if(window.innerWidth <= 800 || window.innerHeight <= 500) {
        open.style.display = "block";
    }
    overlay.classList.add("show");
    bodyAction();
    autoResizeTextarea(textarea);
}

/**
 * zoomOpen()とzoomClose()の複合
 * 非推奨になりました
 */
function zoomAction() {
    if(overlay.classList.contains("show")) {
        if(close.classList.contains("double")) {
            secondOverlayClose();
        } else {
            zoomClose();
        }
    } else {
        zoomOpen();
    }
}

function secondOverlayClose() {
    secondOverlay.style.display = "none";
    if(window.innerWidth <= 800 || window.innerHeight <= 500) open.style.display = "block";
    close.classList.remove("double");
}

/**
 * 非同期でメモの情報を取得する
 * @param {String} memo_id memo_id
 * @param {String} caller 呼び出し元の関数名
 */
function getMemoInfo(memo_id, caller) {
    var formData = new FormData(zoomForm);
    formData.append("user_id", user_id);
    formData.append("memo_id", memo_id);
    formData.append("mode", "info");

    // 非同期通信
    fetch("memo_info.php", { // 別のページのほうが良い気がする。
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data["result"]) {
            var memoData = data["memoData"];
            var urlTitles = [];
            createAction();
            for(let mdata of memoData) {
                if (!mdata["url"]) break;
                // <div class="zoom-url-box-url-container">
                var zoomUrlBoxUrlContainer = document.createElement("div");
                zoomUrlBoxUrlContainer.classList.add("zoom-url-box-url-container");
                zoomUrlBox.appendChild(zoomUrlBoxUrlContainer);
            
                // <div class="zoom-url-box-url">
                var zoomUrlBoxUrl = document.createElement("div");
                zoomUrlBoxUrl.classList.add("zoom-url-box-url");
                zoomUrlBoxUrlContainer.appendChild(zoomUrlBoxUrl);
            
                // <div class="up-down">
                var up_down = document.createElement("div");
                up_down.classList.add("up-down");
                zoomUrlBoxUrl.appendChild(up_down);
            
                // <span class="pull-down">▼</span>
                var down = document.createElement("span");
                down.classList.add("pull-down");
                down.classList.add("appear");
                down.textContent = "▼";
                up_down.appendChild(down);
            
                // <span class="pull-up">▲</span>
                var up = document.createElement("span");
                up.classList.add("pull-up");
                up.classList.add("hide");
                up.textContent = "▲";
                up_down.appendChild(up);
            
                // <div class="url-content">
                var urlContent = document.createElement("div");
                urlContent.classList.add("url-content");
                zoomUrlBoxUrl.appendChild(urlContent);
            
                // <a href="<?= $url["url"]; ?>" class="url-link"><?= $url["page_title"]; ?></a>
                var link = document.createElement("a");
                link.classList.add("url-link");
                link.setAttribute("href", mdata["url"]);
                link.setAttribute("target", "_blank");
                link.textContent = mdata["page_title"];
                urlContent.appendChild(link);
            
                // <input type="text" name="url[]" class="url-name" value="<?= $url["page_title"]; ?>" readonly form="zoom-form">
                var Name = document.createElement("input");
                Name.classList.add("url-name");
                Name.setAttribute("type", "text");
                Name.setAttribute("name", "urlName");
                Name.setAttribute("readonly", "readonly");
                // Name.setAttribute("form", "zoom-form");
                Name.value = mdata["page_title"];
                urlContent.appendChild(Name);
            
                // <div class="zoom-url-box-control-box">
                var zoomUrlBoxControlBox = document.createElement("div");
                zoomUrlBoxControlBox.classList.add("zoom-url-box-control-box");
                zoomUrlBoxUrlContainer.appendChild(zoomUrlBoxControlBox);
            
                // <p class="zoom-url-box-control-box-edit">EDIT</p>
                var zoomUrlBoxControlBoxEdit = document.createElement("p");
                zoomUrlBoxControlBoxEdit.classList.add("zoom-url-box-control-box-edit");
                zoomUrlBoxControlBoxEdit.setAttribute("data-urlid", mdata["url_id"]);
                zoomUrlBoxControlBoxEdit.textContent = "EDIT";
                zoomUrlBoxControlBox.appendChild(zoomUrlBoxControlBoxEdit);
            
                // <p class="zoom-url-box-control-box-delete">DELETE</p>
                var zoomUrlBoxControlBoxDelete = document.createElement("p");
                zoomUrlBoxControlBoxDelete.classList.add("zoom-url-box-control-box-delete");
                zoomUrlBoxControlBoxDelete.setAttribute("data-urlid", mdata["url_id"]);
                zoomUrlBoxControlBoxDelete.textContent = "DELETE";
                zoomUrlBoxControlBox.appendChild(zoomUrlBoxControlBoxDelete);

                urlTitles.push(mdata["page_title"]);
            }

            descCreated.lastElementChild.textContent = memoData[0]["created_at"];
            descUpdated.lastElementChild.textContent = memoData[0]["updated_at"];
            
            // zoomForm.removeAttribute("data-memoid");
            zoomForm.setAttribute("data-memoid", memo_id);
            titleBar.value = descTitle.value = memoData[0]["title"];
            textarea.value = memoData[0]["content"];
            descTag.value = "";
            descFolder.value = "";
            
            if(memoData[0]["url"] && memoData[0]["url"] !== "" && memoData[0]["url"] !== "NULL") {
                var pullDown = document.querySelectorAll(".pull-down");
                var pullUp = document.querySelectorAll(".pull-up");
                var urlLinks = document.querySelectorAll(".url-link");
                var urlNames = document.querySelectorAll(".url-name");
                var urlEdits = document.querySelectorAll(".zoom-url-box-control-box-edit");
                var urlDeletes = document.querySelectorAll(".zoom-url-box-control-box-delete");
                pullDown.forEach((down) => down.addEventListener("click", () => URLImportDown(down)));
                pullUp.forEach((up) => up.addEventListener("click", () => URLImportUp(up)));
                urlEdits.forEach((edit) => edit.addEventListener("click", () => editButtonAction(edit)));
                urlDeletes.forEach((Delete) => Delete.addEventListener("click", () => deleteURL(Delete)));
                window.addEventListener("resize", () => {
                    if(overlay.classList.contains("show")) {
                        vieTextResize(urlLinks, urlTitles, "url");
                    }
                });
                [url, spUrl].forEach((target) => {
                    target.addEventListener("click", () => {
                        vieTextResize(urlLinks, urlTitles, "url");
                        pullUp.forEach((up) => URLImportUp(up));
                    });
                });

                pullUp.forEach((up) => URLImportUp(up));
                if(caller == "sendURL" || caller == "deleteURL") vieTextResize(urlLinks, urlTitles, "url");
            }
        } else {
            console.log(data);
            alert("エラーが発生しました。");
            window.location.reload();
        }
    })
    .catch(error => console.error("Error:", +data.error));
}

/**
 * 非同期通信で作成・更新したメモをデータベースに格納
 */
function sendMemo() {
    var formData = new FormData(zoomForm);
    formData.append("user_id", user_id);
    formData.append("mode", "upload_memo");
    formData.append("title", titleBar.value);
    formData.append("content", textarea.value);
    if(zoomForm.hasAttribute("data-memoid")) {
        formData.append("memo_id", zoomForm.getAttribute("data-memoid"));
        var noticeWord = "メモを更新しました。";
    } else {
        var noticeWord = "メモを作成しました。";
    }

    // 非同期通信
    fetch("memo_info.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data["result"]) {
            var memo_id = data["memo_id"];
            
            // <div class="zoom-url-box-url-container">
            var sendMemoNotice = document.createElement("div");
            sendMemoNotice.classList.add("send-memo-notice");
            sendMemoNotice.textContent = noticeWord;
            body.prepend(sendMemoNotice);
            setTimeout(() => {
                body.removeChild(sendMemoNotice);
            }, 3000);
            displayAllMemos();
            getMemoInfo(memo_id, "sendMemo");
        } else {
            alert("エラーが発生しました。");
            console.log("Error: "+data.error);
            // window.location.reload();
        }
    })
    // .catch(error => console.error("Error:", error));
    .catch(error => console.error("Error:", +data.error));
}

/**
 * ユーザーのすべてのメモを取得する
 * 取得する情報は以下の5つ
 * Memos.memo_id, Memos.title, Memos.created_at, Memos.updated_at, TextMemos.content
 */
function displayAllMemos() {
    var formData = new FormData(zoomForm);
    formData.append("user_id", user_id);
    formData.append("mode", "display_info");

    // 非同期通信
    fetch("memo_info.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data["result"]) {
            var numOfFiles = data["fileData"].length;
            var textFileData = [];
            while(main.firstChild) {
                main.removeChild(main.firstChild);
            }
            if(data["fileData"] && numOfFiles > 0) {
                var fileData = data["fileData"];

                fileData.forEach((fdata) => {

                    // <div class="dummy memo-display" data-memoid="<?= $data["memo_id"]; ?>">
                    var display = document.createElement("div");
                    display.classList.add("memo-display");
                    display.setAttribute("data-memoid", fdata["memo_id"]);
                    main.appendChild(display);

                    //     <p class="memo-title"><?= $data["title"]; ?></p>
                    var title = document.createElement("p");
                    title.classList.add("memo-title");
                    title.textContent = fdata["title"];
                    display.appendChild(title);

                    //     <p class="memo-updated"><?= $data["updated"]; ?></p>
                    var updated = document.createElement("p");
                    updated.textContent = fdata["updated_at"];
                    updated.classList.add("memo-updated");
                    display.appendChild(updated);

                    //     <p class="memo-folder"><?= "FolderName"; ?></p>
                    var folder = document.createElement("p");
                    folder.classList.add("memo-folder");
                    folder.textContent = "FolderName※開発中...";
                    display.appendChild(folder);

                    //     <p class="memo-tag"><?= "#tag #tag #tag #tag #tag #ta..."; ?></p>
                    var tag = document.createElement("p");
                    tag.classList.add("memo-tag");
                    tag.textContent = "#tag #tag #tag※開発中...";
                    display.appendChild(tag);

                    //     <div class="memo-text-container">
                    var mtcontainer = document.createElement("div");
                    mtcontainer.classList.add("memo-text-container");
                    display.appendChild(mtcontainer);

                    //         <small class="memo-text"><?= $data["content"]; ?></small>
                    var mt = document.createElement("small");
                    mt.classList.add("memo-text");
                    mt.textContent = fdata["content"];
                    mtcontainer.appendChild(mt);

                    textFileData.push(fdata["content"]);
                });
                
                var memoDisplays = document.querySelectorAll(".memo-display");
                var memoTexts = document.querySelectorAll(".memo-text");

                itemCounts.forEach((num) => {num.textContent = numOfFiles});
                vieTextResize(memoTexts, textFileData, "memo");

                memoDisplays.forEach((memoDisplay) => {
                    window.addEventListener("load", () => memoDisplay.style.maxWidth = memoMaximizer(memoDisplay));
                    window.addEventListener("resize", () => memoDisplay.style.maxWidth = memoMaximizer(memoDisplay));
                    memoDisplay.addEventListener("click", () => getMemoInfo(memoDisplay.getAttribute("data-memoid"), "displayAllMemos"));
                });
            } else {
                itemCounts.forEach((num) => {num.textContent = numOfFiles});
                
                // <p>ファイルがありません。</p>
                var nmmessage = document.createElement("p");
                nmmessage.classList.add("no-memo-message");
                nmmessage.textContent = "No Files...";
                main.appendChild(nmmessage);
            }
        } else {
            alert("エラーが発生しました。");
            console.log("Error: "+data.error);
            // window.location.reload();
        }
    })
    .catch(error => console.error("Error:", error));
}

/**
 * ログアウト処理
 * logout.phpへ遷移する
 */
function logoutAction() {
    if(confirm("ログアウトしますか？")) {
        window.location.href = TOP_DIR+"logout.php";
    }
}

/**
 * メモを新規作成する処理
 */
function createAction() {
    zoomOpen();
    zoomForm.removeAttribute("data-memoid");
    titleBar.value = "New File";
    textarea.value = "";
    [save, spSave].forEach((target) => {
        target.classList.add("display-none");
    });
    open.style.color = "#ccc";
    while(zoomUrlBox.firstChild) {
        zoomUrlBox.removeChild(zoomUrlBox.firstChild);
    }
    createAddURLContainer();
    descTitle.value = "New File";
    descTag.value = "";
    descFolder.value = "";
}

/**
 * URLタブのURLを追加するための要素を作成する
 */
function createAddURLContainer() {
    // <div class="zoom-url-box-url-container-add">
    var zoomUrlBoxUrlContainerAdd = document.createElement("div");
    zoomUrlBoxUrlContainerAdd.classList.add("zoom-url-box-url-container-add");
    zoomUrlBox.appendChild(zoomUrlBoxUrlContainerAdd);

    // <div class="zoom-url-box-url">
    var zoomUrlBoxUrlAdd = document.createElement("div");
    zoomUrlBoxUrlAdd.classList.add("zoom-url-box-url-add");
    zoomUrlBoxUrlContainerAdd.appendChild(zoomUrlBoxUrlAdd);

    // <div class="url-content">
    var urlContentAdd = document.createElement("div");
    urlContentAdd.classList.add("url-content-add");
    zoomUrlBoxUrlAdd.appendChild(urlContentAdd);

    // <input type="text" name="urlLinkAdd" class="url-link-add" form="zoom-form" placeholder="New page address here" value="">
    var linkAdd = document.createElement("input");
    linkAdd.classList.add("url-link-add");
    linkAdd.setAttribute("type", "text");
    linkAdd.setAttribute("name", "urlLinkAdd");
    // linkAdd.setAttribute("form", "zoom-form");
    linkAdd.setAttribute("placeholder", "New page address here");
    urlContentAdd.appendChild(linkAdd);

    // <input type="text" name="urlNameAdd" class="url-name-add" value="" placeholder="New page title here" readonly form="zoom-form">
    var NameAdd = document.createElement("input");
    NameAdd.classList.add("url-name-add");
    NameAdd.setAttribute("type", "text");
    linkAdd.setAttribute("name", "urlNameAdd");
    // NameAdd.setAttribute("form", "zoom-form");
    NameAdd.setAttribute("placeholder", "New page title here");
    urlContentAdd.appendChild(NameAdd);

    // <div class="zoom-url-box-control-box">
    var zoomUrlBoxControlBoxAdd = document.createElement("div");
    zoomUrlBoxControlBoxAdd.classList.add("zoom-url-box-control-box-add");
    zoomUrlBoxUrlContainerAdd.appendChild(zoomUrlBoxControlBoxAdd);

    // <p class="zoom-url-box-control-box-add">ADD</p>
    var zoomUrlBoxControlBoxAddAdd = document.createElement("p");
    zoomUrlBoxControlBoxAddAdd.classList.add("zoom-url-box-control-box-add-add");
    zoomUrlBoxControlBoxAddAdd.textContent = "ADD";
    zoomUrlBoxControlBoxAdd.appendChild(zoomUrlBoxControlBoxAddAdd);

    zoomUrlBoxControlBoxAddAdd.addEventListener("click", () => sendURL(zoomUrlBoxControlBoxAddAdd, zoomUrlBoxControlBoxAddAdd.parentNode.previousElementSibling.lastElementChild.firstElementChild.value, zoomUrlBoxControlBoxAddAdd.parentNode.previousElementSibling.lastElementChild.lastElementChild.value));

}

/**
 * メモにURLを追加する処理
 * 不要になりました.。
 */
function addButtonAction() {
    var formData = new FormData(zoomForm);
    formData.append("user_id", user_id);
    formData.append("mode", "upload_url");
    formData.append("urlLink", );
    formData.append("urlName", );
    if(zoomForm.hasAttribute("data-memoid")) {
        formData.append("memo_id", zoomForm.gettribute("data-memoid"));
    }

    // 非同期通信
    fetch("memo_info.php", {
        method: "POST",
        body: forData
    })
    .then(response => response.json())
    .then(data => {
        if(data["result"]) {
            // console.log(data);
            var memoData = data["memoData"];
            if(!zoomForm.hasAttribute("data-memo")) {
                zoomForm.setAttribute("data-memo", memoData[0]["memo_id"]);
            }
        } else {
            alert("エラーが発生しました。");
            // console.log("Error: "+data.error);
            // window.location.reload();
        }
    })
    .catch(error => console.error("Error:", error));
}

/**
 * 非同期通信でURLを登録・更新する処理
 * @param {Element} button EDIT or ADD
 * @param {String} url サイトのURL
 * @param {String} page_title サイト名
 */
function sendURL(button, url, page_title) {
    if(url == "" || url == "NULL") {
        alert("Please Enter a URL.\nURLを入力してください。");
        return;
    }
    if(page_title == "" || page_title == "NULL") {
        alert("Please Enter a Page title.\nページタイトルを入力してください。");
        return;
    }
    var formData = new FormData(zoomForm);
    formData.append("user_id", user_id);
    formData.append("title", titleBar.value);
    formData.append("content", textarea.value);
    formData.append("url", url);
    formData.append("page_title", page_title);
    formData.append("mode", "upload_url");
    if(zoomForm.hasAttribute("data-memoid")) {
        formData.append("memo_id", zoomForm.getAttribute("data-memoid"));
    }
    if(button.hasAttribute("data-urlid")) {
        formData.append("url_id", button.getAttribute("data-urlid"));
    }
    
    // 非同期通信
    fetch("memo_info.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data["result"]) {
            memo_id = data["memo_id"];
            if(!zoomForm.hasAttribute("data-memoid")) {
                zoomForm.setAttribute("data-memoid", memo_id);
            }
            getMemoInfo(memo_id, "sendURL");
        } else {
            alert("エラーが発生しました。");
            console.log("Error: "+data.error);
            // window.location.reload();
        }
    })
    .catch(error => console.error("Error:", error));
}

/**
 * 非同期通信でURLを削除する処理
 * @param {Element} button DELETE
 */
function deleteURL(button) {
    var formData = new FormData(zoomForm);
    formData.append("user_id", user_id);
    formData.append("mode", "delete_url");
    formData.append("memo_id", zoomForm.getAttribute("data-memoid"));
    formData.append("url_id", button.getAttribute("data-urlid"));

    var memo_id = zoomForm.getAttribute("data-memoid");

    // 非同期通信
    fetch("memo_info.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data["result"]) {
            getMemoInfo(memo_id, "deleteURL");
            displayAllMemos();
        } else {
            alert("エラーが発生しました。");
            console.log("Error: "+data.error);
            // window.location.reload();
        }
    })
    .catch(error => console.error("Error:", error));
}

/**
 * 非同期通信でメモを削除する処理
 */
function deleteMemo() {
    var formData = new FormData(zoomForm);
    formData.append("user_id", user_id);
    formData.append("mode", "delete_memo");
    if(zoomForm.hasAttribute("data-memoid")) {
        formData.append("memo_id", zoomForm.getAttribute("data-memoid"));
    } else {
        zoomClose();
        return;
    }

    // 非同期通信
    fetch("memo_info.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data["result"]) {
            
            zoomClose();
            var deleteMemoNotice = document.createElement("div");
            deleteMemoNotice.classList.add("delete-memo-notice");
            deleteMemoNotice.textContent = "メモを削除しました。";
            body.prepend(deleteMemoNotice);
            setTimeout(() => {
                body.removeChild(deleteMemoNotice);
            }, 3000);
            displayAllMemos();
        } else {
            alert("エラーが発生しました。");
            console.log("Error: "+data.error);
            // window.location.reload();
        }
    })
    .catch(error => console.error("Error:", error));
}