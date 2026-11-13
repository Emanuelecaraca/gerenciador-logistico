
<?php

    // Inicia a sessão para acessar os dados cadastrados.
    session_start();

    // Verifica se a lista de veículos já existe.
    // Caso não exista, cria uma lista vazia.
    if (!isset($_SESSION['veiculos'])) {
        $_SESSION['veiculos'] = [];
    }

    // Verifica se o formulário foi enviado pelo método POST.
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Recebe os dados enviados pelo formulário.
        $transportador = trim($_POST['transportador'] ?? '');
        $placa = trim($_POST['placa'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');

        // Verifica se todos os campos foram preenchidos.
        if (
            $transportador != '' &&
            $placa != '' &&
            $tipo != ''
        ) {

            // Cria um identificador para o veículo.
            $id = count($_SESSION['veiculos']) + 1;

            // Guarda os dados do veículo na sessão.
            $_SESSION['veiculos'][] = [
                'id' => $id,
                'transportador' => $transportador,
                'placa' => $placa,
                'tipo' => $tipo
            ];

            // Retorna para a página de transportadores.
            header("Location: ../paginas/transportadores.php");
            exit;
        }
    }

?>
