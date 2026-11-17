const botaoMenu = document.getElementById("botaoMenu");
const menuLateral = document.querySelector("nav");

// O menu começa recolhido
menuLateral.classList.add("recolhido");

botaoMenu.addEventListener("click", function () {
    menuLateral.classList.toggle("recolhido");
});