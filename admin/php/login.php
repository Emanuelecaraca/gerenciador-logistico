<?php
$email = $_POST['email'] ?? ''; // '' Evita erro caso alguém acesse o arquivo sem enviar o formulário.
$senha = $_POST['senha'] ?? '';

header("Location: ../paginas/home.php");

exit;

?>