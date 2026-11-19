const botaoMenu = document.getElementById("botaoMenu");
const menuLateral = document.querySelector("nav");

// O menu começa expandido
menuLateral.classList.remove("recolhido");

botaoMenu.addEventListener("click", function () {
    menuLateral.classList.toggle("recolhido");
});