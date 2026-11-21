
<?php

// Recupera os cadastros.
$produtos = $_SESSION['produtos'] ?? [];
$transportadores = $_SESSION['transportadores'] ?? [];
$enderecos = $_SESSION['endereco'] ?? [];
$veiculos = $_SESSION['veiculos'] ?? [];
$fretes = $_SESSION['fretes'] ?? [];

// Recebe o ID do frete pela URL.
$idFrete = (int)($_GET['id'] ?? 0);

// Localiza o frete selecionado.
$freteSelecionado = null;

foreach ($fretes as $frete) {

    if ((int)($frete['id'] ?? 0) === $idFrete) {
        $freteSelecionado = $frete;
        break;
    }
}

// Localiza um cadastro pelo ID.
function buscarCadastroRelatorio($lista, $id, $campo = 'nome') {

    foreach ($lista as $item) {

        if ((int)($item['id'] ?? 0) === (int)$id) {
            return $item[$campo] ?? 'Não informado';
        }
    }

    return 'Não encontrado';
}

// Monta o endereço completo.
function buscarEnderecoRelatorio($lista, $id) {

    foreach ($lista as $item) {

        if ((int)($item['id'] ?? 0) === (int)$id) {

            return trim(
                ($item['rua'] ?? '') . ', ' .
                ($item['numero'] ?? '') . ' - ' .
                ($item['bairro'] ?? '') . ', ' .
                ($item['cidade'] ?? '') . '/' .
                ($item['estado'] ?? '')
            );
        }
    }

    return 'Não encontrado';
}

?>

<main class="container mt-4 mb-5" id="relatorioFrete">

    <?php if (!$freteSelecionado): ?>

        <div class="alert alert-danger">
            Frete não encontrado.
        </div>

    <?php else: ?>

        <?php

        // Recupera os produtos vinculados.
        $produtosFrete = $freteSelecionado['produtos'] ?? [];

        if (empty($produtosFrete) && isset($freteSelecionado['produto'])) {
            $produtosFrete = [$freteSelecionado['produto']];
        }

        $idTransportador = (int)($freteSelecionado['transportador'] ?? 0);

        ?>

        <!-- CABEÇALHO -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2>Relatório de Frete</h2>

                <p class="text-muted mb-0">
                    Frete nº <?php echo $idFrete; ?>
                </p>
            </div>

            <button type="button"
                    class="btn btn-primary"
                    onclick="window.print()">

                <i class="bi bi-printer"></i>
            </button>

        </div>

        <hr>

        <!-- DADOS DO FRETE -->
        <h5 class="mb-3">Informações do frete</h5>

        <div class="row">

            <div class="col-md-6 mb-3">
                <strong>Código do frete:</strong>
                <?php echo $idFrete; ?>
            </div>

            <div class="col-md-6 mb-3">
                <strong>Transportador:</strong>

                <?php
                echo htmlspecialchars(
                    buscarCadastroRelatorio(
                        $transportadores,
                        $idTransportador
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>
            </div>

            <div class="col-md-6 mb-3">
                <strong>Veículo:</strong>

                <?php
                echo htmlspecialchars(
                    buscarCadastroRelatorio(
                        $veiculos,
                        $freteSelecionado['veiculo'] ?? 0,
                        'placa'
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>
            </div>

            <div class="col-md-6 mb-3">
                <strong>Data do frete:</strong>

                <?php
                echo !empty($freteSelecionado['dataFrete'])
                    ? date('d/m/Y', strtotime($freteSelecionado['dataFrete']))
                    : 'Não informada';
                ?>
            </div>

            <div class="col-md-6 mb-3">
                <strong>Prazo de chegada:</strong>

                <?php
                echo !empty($freteSelecionado['prazoChegada'])
                    ? date('d/m/Y', strtotime($freteSelecionado['prazoChegada']))
                    : 'Não informado';
                ?>
            </div>

            <div class="col-md-6 mb-3">
                <strong>Valor do frete:</strong>

                R$ <?php
                echo number_format(
                    (float)($freteSelecionado['valor'] ?? 0),
                    2,
                    ',',
                    '.'
                );
                ?>
            </div>

            <div class="col-md-6 mb-3">
                <strong>Status:</strong>

                <?php
                echo htmlspecialchars(
                    $freteSelecionado['status'] ?? 'Programado',
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>
            </div>

        </div>

        <hr>

        <!-- ORIGEM E DESTINO -->
        <h5 class="mb-3">Trajeto</h5>

        <div class="row">

            <div class="col-md-6 mb-3">

                <strong>Origem:</strong>

                <p>
                    <?php
                    echo htmlspecialchars(
                        buscarEnderecoRelatorio(
                            $enderecos,
                            $freteSelecionado['origem'] ?? 0
                        ),
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </p>

            </div>

            <div class="col-md-6 mb-3">

                <strong>Destino:</strong>

                <p>
                    <?php
                    echo htmlspecialchars(
                        buscarEnderecoRelatorio(
                            $enderecos,
                            $freteSelecionado['destino'] ?? 0
                        ),
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </p>

            </div>

        </div>

        <hr>

        <!-- PRODUTOS -->
        <h5 class="mb-3">Produtos vinculados</h5>

        <?php if (!empty($produtosFrete)): ?>

            <?php foreach ($produtosFrete as $idProduto): ?>

                <p class="mb-1">
                    <?php
                    echo htmlspecialchars(
                        buscarCadastroRelatorio($produtos, $idProduto),
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </p>

            <?php endforeach; ?>

        <?php else: ?>

            <p class="text-muted">
                Nenhum produto vinculado a este frete.
            </p>

        <?php endif; ?>

     <?php endif; ?>


</main>

