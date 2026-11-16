
<?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Inicializa
    if (!isset($_SESSION['fretes'])) {
        $_SESSION['fretes'] = [];
    }

    // Recebe os dados enviados pelo formulário..
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'cadastrar') {

        $produto = (int)($_POST['produto'] ?? 0);
        $transportador = (int)($_POST['transportador'] ?? 0);
        $origem = (int)($_POST['origem'] ?? 0);
        $destino = (int)($_POST['destino'] ?? 0);
        $veiculo = (int)($_POST['veiculo'] ?? 0);
        $dataFrete = trim($_POST['dataFrete'] ?? '');
        $valor = str_replace(',', '.', trim($_POST['valor'] ?? ''));

        // Verifica se os campos foram preenchidos.
        if (
            $produto > 0 &&
            $transportador > 0 &&
            $origem > 0 &&
            $destino > 0 &&
            $veiculo > 0 &&
            $dataFrete !== '' &&
            is_numeric($valor) &&
            (float)$valor >= 0
        ) {

            
            $veiculoValido = false;

            foreach ($_SESSION['veiculos'] ?? [] as $itemVeiculo) {
                if (
                    (int)$itemVeiculo['id'] === $veiculo &&
                    (int)$itemVeiculo['transportador'] === $transportador
                ) {
                    $veiculoValido = true;
                    break;
                }
            }

            // Confere se os cadastros  existem.
            $produtoValido = false;
            foreach ($_SESSION['produtos'] ?? [] as $item) {
                if ((int)$item['id'] === $produto) {
                    $produtoValido = true;
                    break;
                }
            }

            $transportadorValido = false;
            foreach ($_SESSION['transportadores'] ?? [] as $item) {
                if ((int)$item['id'] === $transportador) {
                    $transportadorValido = true;
                    break;
                }
            }

            $origemValida = false;
            $destinoValido = false;

            foreach ($_SESSION['endereco'] ?? [] as $item) {
                if ((int)$item['id'] === $origem) {
                    $origemValida = true;
                }

                if ((int)$item['id'] === $destino) {
                    $destinoValido = true;
                }
            }

            if (
                $veiculoValido &&
                $produtoValido &&
                $transportadorValido &&
                $origemValida &&
                $destinoValido &&
                $origem !== $destino
            ) {

                // Gera o próximo código sem repetir IDs após exclusões.
                $maiorId = 0;

                foreach ($_SESSION['fretes'] as $frete) {
                    $maiorId = max($maiorId, (int)$frete['id']);
                }

                $_SESSION['fretes'][] = [
                    'id' => $maiorId + 1,
                    'produto' => $produto,
                    'transportador' => $transportador,
                    'origem' => $origem,
                    'destino' => $destino,
                    'veiculo' => $veiculo,
                    'dataFrete' => $dataFrete,
                    'valor' => (float)$valor,
                    'status' => 'Pendente'
                ];

                header("Location: ../paginas/fretes.php?sucesso=1");
                exit;

            } else {
                header("Location: ../paginas/fretes.php?erro=relacionamento");
                exit;
            }

        } else {
            header("Location: ../paginas/fretes.php?erro=campos");
            exit;
        }
    }
