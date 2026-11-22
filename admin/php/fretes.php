
<?php

require_once __DIR__ . '/../partesCodigo/verificarLogin.php';

// Inicializa a lista de fretes.
$_SESSION['fretes'] = $_SESSION['fretes'] ?? [];


// ==================================================
// ATUALIZAR STATUS
// ==================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'atualizar_status') {

    $idFrete = (int)($_POST['id'] ?? 0);
    $novoStatus = $_POST['status'] ?? '';

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


// ==================================================
// EXCLUIR FRETE
// ==================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'excluir') {

    $idFrete = (int)($_POST['id'] ?? 0);

    foreach ($_SESSION['fretes'] as $indice => $frete) {

        if ((int)$frete['id'] === $idFrete) {
            unset($_SESSION['fretes'][$indice]);
            break;
        }
    }

    $_SESSION['fretes'] = array_values($_SESSION['fretes']);

    header("Location: ../paginas/fretes.php?excluido=1");
    exit;
}


// ==================================================
// CADASTRAR OU EDITAR FRETE
// ==================================================

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    in_array($_POST['acao'] ?? '', ['cadastrar', 'editar'], true)
) {

    $acao = $_POST['acao'];
    $idFrete = (int)($_POST['id'] ?? 0);

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


    // ==================================================
    // VERIFICAR CAMPOS OBRIGATÓRIOS
    // ==================================================

    if (
        count($produtos) > 0 &&
        $transportador > 0 &&
        $origem > 0 &&
        $destino > 0 &&
        $veiculo > 0 &&
        $dataFrete !== '' &&
        $prazoChegada !== '' &&
        is_numeric($valor) &&
        (float)$valor >= 0
    ) {

        // Verifica se o frete existe, no caso de edição.
        $indiceFrete = null;

        if ($acao === 'editar') {

            foreach ($_SESSION['fretes'] as $indice => $frete) {

                if ((int)$frete['id'] === $idFrete) {
                    $indiceFrete = $indice;
                    break;
                }
            }

            if ($indiceFrete === null) {
                header("Location: ../paginas/fretes.php?erro=frete_nao_encontrado");
                exit;
            }
        }


        // ==================================================
        // VERIFICAR SE O VEÍCULO PERTENCE AO TRANSPORTADOR
        // ==================================================

        $veiculoValido = false;

        foreach ($_SESSION['veiculos'] ?? [] as $itemVeiculo) {

            $idTransportadorVeiculo = (int)(
                $itemVeiculo['transportador']
                ?? $itemVeiculo['idTransportador']
                ?? 0
            );

            if (
                (int)($itemVeiculo['id'] ?? 0) === $veiculo &&
                $idTransportadorVeiculo === $transportador
            ) {
                $veiculoValido = true;
                break;
            }
        }


        // ==================================================
        // VERIFICAR SE O VEÍCULO ESTÁ OCUPADO
        // ==================================================

        $veiculoOcupado = false;

        foreach ($_SESSION['fretes'] as $freteExistente) {

            $statusExistente = $freteExistente['status'] ?? 'Programado';

            // Na edição, ignora o próprio frete.
            if (
                (int)($freteExistente['veiculo'] ?? 0) === $veiculo &&
                (int)($freteExistente['id'] ?? 0) !== $idFrete &&
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


        // ==================================================
        // VERIFICAR SE OS PRODUTOS EXISTEM
        // ==================================================

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


        // ==================================================
        // VERIFICAR SE O TRANSPORTADOR EXISTE
        // ==================================================

        $transportadorValido = false;

        foreach ($_SESSION['transportadores'] ?? [] as $item) {

            if ((int)$item['id'] === $transportador) {
                $transportadorValido = true;
                break;
            }
        }


        // ==================================================
        // VERIFICAR SE OS ENDEREÇOS EXISTEM
        // ==================================================

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


        // ==================================================
        // CONFERIR TODOS OS RELACIONAMENTOS
        // ==================================================

        if (
            $veiculoValido &&
            !$veiculoOcupado &&
            $produtosValidos &&
            $transportadorValido &&
            $origemValida &&
            $destinoValido &&
            $origem !== $destino
        ) {

            // ==============================================
            // CADASTRAR NOVO FRETE
            // ==============================================

            if ($acao === 'cadastrar') {

                // Gera o próximo ID.
                $maiorId = 0;

                foreach ($_SESSION['fretes'] as $frete) {
                    $maiorId = max($maiorId, (int)$frete['id']);
                }

                $_SESSION['fretes'][] = [
                    'id' => $maiorId + 1,
                    'produtos' => $produtos,
                    'transportador' => $transportador,
                    'origem' => $origem,
                    'destino' => $destino,
                    'veiculo' => $veiculo,
                    'dataFrete' => $dataFrete,
                    'prazoChegada' => $prazoChegada,
                    'valor' => (float)$valor,
                    'status' => 'Programado'
                ];

                header("Location: ../paginas/fretes.php?sucesso=1");
                exit;

            } else {

                // ==========================================
                // EDITAR FRETE EXISTENTE
                // ==========================================

                $_SESSION['fretes'][$indiceFrete]['produtos'] = $produtos;
                $_SESSION['fretes'][$indiceFrete]['transportador'] = $transportador;
                $_SESSION['fretes'][$indiceFrete]['origem'] = $origem;
                $_SESSION['fretes'][$indiceFrete]['destino'] = $destino;
                $_SESSION['fretes'][$indiceFrete]['veiculo'] = $veiculo;
                $_SESSION['fretes'][$indiceFrete]['dataFrete'] = $dataFrete;
                $_SESSION['fretes'][$indiceFrete]['prazoChegada'] = $prazoChegada;
                $_SESSION['fretes'][$indiceFrete]['valor'] = (float)$valor;

                header("Location: ../paginas/fretes.php?editado=1");
                exit;
            }

        } elseif ($veiculoOcupado) {

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
