
const botaoMenu = document.getElementById("botaoMenu");
const menuLateral = document.querySelector("nav");

// Recupera o estado salvo do menu
const menuRecolhido = localStorage.getItem("menuRecolhido");

if (menuRecolhido === "sim") {
    menuLateral.classList.add("recolhido");
} else {
    menuLateral.classList.remove("recolhido");
}

// Recolher ou expandir pelo botão
botaoMenu.addEventListener("click", function () {

    menuLateral.classList.toggle("recolhido");

    localStorage.setItem(
        "menuRecolhido",
        menuLateral.classList.contains("recolhido") ? "sim" : "nao"
    );

});
