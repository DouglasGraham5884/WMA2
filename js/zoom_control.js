"use strict";

{
    // CLICK
    [edit, spEdit].forEach((target) => {
        target.addEventListener("click", (e) => {
            // e.preventDefault(); // フォームの送信を防ぐ
            zoomTextBox.classList.remove("display-none");
            zoomUrlBox.classList.add("display-none");
            zoomDescBox.classList.add("display-none");
        });
    });
    // urlとかspUrlとかのやつはfaunctions.jsのgetMemoInfo()内に移行しました。
    // 戻ってきました。
    [url, spUrl].forEach((target) => {
        target.addEventListener("click", () => {
            zoomTextBox.classList.add("display-none");
            zoomUrlBox.classList.remove("display-none");
            zoomDescBox.classList.add("display-none");
        });
    });
    [desc, spDesc].forEach((target) => {
        target.addEventListener("click", () => {
            zoomTextBox.classList.add("display-none");
            zoomUrlBox.classList.add("display-none");
            zoomDescBox.classList.remove("display-none");
        });
    });
    [Export, spExport].forEach((target) => {
        target.addEventListener("click", () => {
            alert("This is a feature under development.\n開発中の機能です。");
        });
    });
    [Delete, spDelete].forEach((target) => {
        target.addEventListener("click", () => {
            if(confirm("Delete this file?\nファイルを削除しますか？")) {
                deleteMemo();
            }
        });
    });
    [share, spShare].forEach((target) => {
        target.addEventListener("click", () => {
            alert("This is a feature under development.\n開発中の機能です。");
        });
    });
    [save, spSave].forEach((target) => {
        target.addEventListener("click", () => {
            save.classList.add("display-none");
            spSave.classList.add("display-none");
            open.style.color = "#ccc";
            sendMemo();
        });
    });

    //////////////////// OTHER ////////////////////
    titleBar.addEventListener("input", () => {
        editInputAction();
        descTitle.value = titleBar.value;
    });
    descTitle.addEventListener("input", () => {
        editInputAction();
        titleBar.value = descTitle.value;
    });
    descTag.addEventListener("input", () => editInputAction());
    descFolder.addEventListener("input", () => editInputAction());
    textarea.addEventListener("input", () => editInputAction());
}