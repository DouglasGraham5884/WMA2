"use strict";

{
    
    creates.forEach((create) => create.addEventListener("click", () => createAction()));
    Imports.forEach((Import) => {
        Import.addEventListener("click", () => {
            alert("This is a feature under development.\n開発中の機能です。");
        });
    });
    logouts.forEach((logout) => logout.addEventListener("click", () => logoutAction()));
}