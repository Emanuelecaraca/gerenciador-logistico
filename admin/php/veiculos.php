
<?php

require_once __DIR__ . '/../partesCodigo/verificarLogin.php';

// Inicializa a lista de veículos.
if (!isset($_SESSION['veiculos'])) {
    $_SESSION['veiculos'] = [];
}

// Verifica se o formulário foi enviado.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $transportador = (int)($_POST['transportador'] ?? 0);
    $placa = trim($_POST['placa'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');

    // Só continua se os campos obrigatórios estiverem preenchidos.
    if ($transportador > 0 && $placa !== '' && $tipo !== '') {

        // EDIÇÃO DE VEÍCULO
        if (($_POST['acao'] ?? '') === 'atualizarVeiculo') {

            $id = (int)($_POST['id'] ?? 0);

            foreach ($_SESSION['veiculos'] as $indice => $veiculo) {

                if ((int)$veiculo['id'] === $id) {

                    $_SESSION['veiculos'][$indice]['transportador'] = $transportador;
                    $_SESSION['veiculos'][$indice]['placa'] = $placa;
                    $_SESSION['veiculos'][$indice]['tipo'] = $tipo;

                    break;
                }
            }

        } else {

            // CADASTRO DE NOVO VEÍCULO
            $maiorId = 0;

            foreach ($_SESSION['veiculos'] as $veiculo) {
                $maiorId = max($maiorId, (int)$veiculo['id']);
            }

            $novoId = $maiorId + 1;

            $_SESSION['veiculos'][] = [
                'id' => $novoId,
                'transportador' => $transportador,
                'placa' => $placa,
                'tipo' => $tipo
            ];
        }

        
        header("Location: ../paginas/transportadores.php?matricula=" . $transportador . "&aba=veiculos");
        exit;
    }
}

?>
