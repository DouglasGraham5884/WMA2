"use strict";

{
    window.addEventListener("load", () => autoResizeTextarea(textarea));
    textarea.addEventListener("input", () => autoResizeTextarea(textarea));
}