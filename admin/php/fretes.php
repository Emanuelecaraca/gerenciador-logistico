
<?php

require_once __DIR__ . '/../partesCodigo/verificarLogin.php';

// Inicializa a lista de fretes.
if (!isset($_SESSION['fretes'])) {
    $_SESSION['fretes'] = [];
}

// Verifica se o formulário foi enviado para atualizar o status.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'atualizar_status') {

    $idFrete = (int)($_POST['id'] ?? 0);
    $novoStatus = $_POST['status'] ?? '';

    // Lista os status permitidos.
    $statusPermitidos = [
    'Programado',
    'Em carregamento',
    'Aguardando saída',
    'Em trânsito',
    'Em parada',
    'Em ocorrência',
    'Em descarga',
    'Finalizado'
];

    // Verifica se o status enviado é válido.
    if (in_array($novoStatus, $statusPermitidos, true)) {

        foreach ($_SESSION['fretes'] as &$frete) {

            if ((int)$frete['id'] === $idFrete) {
                $frete['status'] = $novoStatus;
                break;
            }

        }

        unset($frete);
    }

    header("Location: ../paginas/fretes.php?status_atualizado=1");
    exit;
}

// Verifica se o formulário foi enviado para cadastrar um frete.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'cadastrar') {

    // Recebe os produtos selecionados.
    $produtos = array_map('intval', $_POST['produtos'] ?? []);
    $produtos = array_values(array_unique(array_filter($produtos, function ($id) {
        return $id > 0;
    })));

    $transportador = (int)($_POST['transportador'] ?? 0);
    $origem = (int)($_POST['origem'] ?? 0);
    $destino = (int)($_POST['destino'] ?? 0);
    $veiculo = (int)($_POST['veiculo'] ?? 0);
    $dataFrete = trim($_POST['dataFrete'] ?? '');
    $prazoChegada = trim($_POST['prazoChegada'] ?? '');
    $valor = str_replace(',', '.', trim($_POST['valor'] ?? ''));

    // Verifica se os campos obrigatórios foram preenchidos.
    if (
        count($produtos) > 0 &&
        $transportador > 0 &&
        $origem > 0 &&
        $destino > 0 &&
        $veiculo > 0 &&
        $dataFrete !== '' &&
        is_numeric($valor) &&
        (float)$valor >= 0
    ) {

        // Verifica se o veículo pertence ao transportador escolhido.
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

            // Verifica se o veículo já possui um frete ativo.
            $veiculoOcupado = false;

            foreach ($_SESSION['fretes'] as $freteExistente) {

                $statusExistente = $freteExistente['status'] ?? 'Programado';

                if (
                    (int)$freteExistente['veiculo'] === $veiculo &&
                    !in_array($statusExistente, [
                        'Finalizado',
                        'Rota concluída',
                        'Rota concluída / Finalizado'

                    ], true)
                ) {
                    $veiculoOcupado = true;
                    break;
                }
            }

        // Verifica se todos os produtos selecionados existem.
        $produtosCadastrados = [];

        foreach ($_SESSION['produtos'] ?? [] as $item) {
            $produtosCadastrados[] = (int)$item['id'];
        }

        $produtosValidos = true;

        foreach ($produtos as $idProduto) {
            if (!in_array($idProduto, $produtosCadastrados, true)) {
                $produtosValidos = false;
                break;
            }
        }

        // Verifica se o transportador existe.
        $transportadorValido = false;

        foreach ($_SESSION['transportadores'] ?? [] as $item) {
            if ((int)$item['id'] === $transportador) {
                $transportadorValido = true;
                break;
            }
        }

        // Verifica se os endereços existem.
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

        // Confere todos os relacionamentos.
        if (
            $veiculoValido &&
            !$veiculoOcupado &&
            $produtosValidos &&
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

            // Salva o novo frete.
            $_SESSION['fretes'][] = [
                'id' => $maiorId + 1,
                'produtos' => $produtos,
                'transportador' => $transportador,
                'origem' => $origem,
                'destino' => $destino,
                'veiculo' => $veiculo,
                'dataFrete' => $dataFrete,
                'valor' => (float)$valor,
                'status' => 'Programado'
            ];

            header("Location: ../paginas/fretes.php?sucesso=1");
            exit;

            } else if ($veiculoOcupado) {

            header("Location: ../paginas/fretes.php?erro=veiculo_ocupado");
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
