<?php
	
	session_start();

	if (!isset($_SESSION['transportadores'])) {
        $_SESSION['transportadores'] = [];
    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Recebe os dados enviados.
        // trim() remove espaços desnecessários no início e no final.
        $nome = trim($_POST['nome'] ?? '');
        $documento = trim($_POST['documento'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // Verifica se todos os campos foram preenchidos.
        if (
            $nome != '' &&
            $documento != '' &&
            $telefone != '' &&
            $email != ''
        ) {

            // Identificador para o novo transportador.
            $id = count($_SESSION['transportadores']) + 1;

            // Adiciona os dados do transportador na lista.
            $_SESSION['transportadores'][] = [
                'id' => $id,
                'nome' => $nome,
                'documento' => $documento,
                'telefone' => $telefone,
                'email' => $email
            ];

            // Retorna para a página de transportadores.
            header("Location: ../paginas/transportadores.php");
            exit;
        }
    }


?>