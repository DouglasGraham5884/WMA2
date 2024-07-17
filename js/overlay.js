"use strict";

{
    // overlayのdisplayはflex;
    [close, secondOverlay].forEach((target) => target.addEventListener("click", () => zoomAction()));
    document.addEventListener("keydown", (e) => {if(e.key === "Escape") zoomAction()});
    open.addEventListener("click", () => {
        secondOverlay.style.display = "flex";
        open.style.display = "none";
        close.classList.add("double");
    });
    window.addEventListener("resize", () => {
        if(overlay.classList.contains("show")) {
            if(window.innerWidth <= 800 || window.innerHeight <= 500) {
                open.style.display = "block";
            } else {
                if(close.classList.contains("double")) {
                    secondOverlayClose();
                } else {
                    open.style.display = "none";
                }
            }
        }
    });
}