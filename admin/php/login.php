
<?php

session_start();

// Recupera os dados enviados pelo formulário.
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Carrega a lista de usuários autorizados.
require_once 'usuarios.php';

// Verifica se existe um usuário com esse e-mail.
foreach ($usuarios as $usuario) {

    if ($usuario['email'] === $email) {

        // Confere a senha informada.
        if (password_verify($senha, $usuario['senha'])) {

            // Regenera o identificador da sessão.
            session_regenerate_id(true);

            // Armazena os dados do usuário autenticado.
            $_SESSION['usuario'] = [
                'nome' => $usuario['nome'],
                'email' => $usuario['email']
            ];

            header("Location: ../paginas/home.php");
            exit;
        }
    }
}

// Se chegar aqui, o acesso não foi autorizado.
header("Location: ../../login.html?erro=1");
exit;

?>
