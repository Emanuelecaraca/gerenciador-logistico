const botaoUsuario = document.getElementById('botaoUsuario');
const menuUsuario = document.getElementById('menuUsuario');

// Abre e fecha o menu ao clicar no usuário.
botaoUsuario.addEventListener('click', function () {

    if (menuUsuario.style.display === 'block') {
        menuUsuario.style.display = 'none';
    } else {
        menuUsuario.style.display = 'block';
    }

});

// Fecha o menu ao clicar fora dele.
document.addEventListener('click', function (evento) {

    if (!botaoUsuario.contains(evento.target) &&
        !menuUsuario.contains(evento.target)) {

        menuUsuario.style.display = 'none';

    }

});