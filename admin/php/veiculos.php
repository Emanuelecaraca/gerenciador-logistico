
<?php

    // Verifica o login e inicia a sessão.
    require_once __DIR__ . '/../partesCodigo/verificarLogin.php';

    // Verifica se a lista de veículos já existe.
    if (!isset($_SESSION['veiculos'])) {
        $_SESSION['veiculos'] = [];
    }

    // Verifica se o formulário foi enviado.
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Recebe os dados enviados.
        $transportador = trim($_POST['transportador'] ?? '');
        $placa = trim($_POST['placa'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');

        // Verifica se todos os campos foram preenchidos.
        if (
            $transportador != '' &&
            $placa != '' &&
            $tipo != ''
        ) {

            // Verifica se a ação é uma atualização.
            if (($_POST['acao'] ?? '') == 'atualizarVeiculo') {

                // Recebe o ID do veículo.
                $id = (int)($_POST['id'] ?? 0);

                // Procura o veículo que será atualizado.
                foreach ($_SESSION['veiculos'] as $indice => $veiculo) {

                    if ($veiculo['id'] == $id) {

                        // Atualiza os dados do veículo.
                        $_SESSION['veiculos'][$indice]['transportador'] = $transportador;
                        $_SESSION['veiculos'][$indice]['placa'] = $placa;
                        $_SESSION['veiculos'][$indice]['tipo'] = $tipo;

                        break;
                    }
                }

                // Recupera o termo da pesquisa.
                $pesquisa = trim($_POST['pesquisa'] ?? '');

                // Retorna mantendo a pesquisa.
                header("Location: ../paginas/transportadores.php?pesquisa=" . urlencode($pesquisa));
                exit;

            } else {

                // Cria um identificador para o novo veículo.
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
    }

?>
