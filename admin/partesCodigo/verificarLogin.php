<?php

session_start();

// Verifica se existe um usuário autenticado.
if (!isset($_SESSION['usuario'])) {

    // Impede o acesso e retorna à tela de login.
    header("Location: ../../login.html");
    exit;
}

?>