<?php

// Recupera os cadastros.
$fretes = $_SESSION['fretes'] ?? [];
$produtos = $_SESSION['produtos'] ?? [];
$transportadores = $_SESSION['transportadores'] ?? [];
$enderecos = $_SESSION['endereco'] ?? [];
$veiculos = $_SESSION['veiculos'] ?? [];
// Inicializa os indicadores dos fretes.
$totalCarregamento = 0;
$totalTransito = 0;
$totalDescarga = 0;
$totalConcluidos = 0;

// Conta os fretes de acordo com o status atual.
foreach ($fretes as $frete) {

    $status = $frete['status'] ?? '';

    switch ($status) {

        case 'Em carregamento':
            $totalCarregamento++;
            break;

        case 'Em trânsito':
            $totalTransito++;
            break;

        case 'Em descarga':
            $totalDescarga++;
            break;

        case 'Finalizado':
            $totalConcluidos++;
            break;
    }
}

// Recebe a data selecionada.
$dataRelatorio = $_GET['dataRelatorio'] ?? '';
$fretesRelatorio = [];

// Filtra os fretes pela data informada.
if ($dataRelatorio !== '') {
    foreach ($fretes as $frete) {
        if (($frete['dataFrete'] ?? '') === $dataRelatorio) {
            $fretesRelatorio[] = $frete;
        }
    }
}

// Localiza um cadastro pelo ID e retorna o campo solicitado.
function nomeCadastroRelatorio($lista, $id, $campo = 'nome')
{
    foreach ($lista as $item) {
        if ((int)($item['id'] ?? 0) === (int)$id) {
            return $item[$campo] ?? '';
        }
    }

    return 'Não encontrado';
}

// Localiza um endereço pelo ID.
function enderecoCadastroRelatorio($lista, $id)
{
    foreach ($lista as $item) {
        if ((int)($item['id'] ?? 0) === (int)$id) {
            return trim(
                ($item['rua'] ?? '') . ', ' .
                ($item['numero'] ?? '') . ' - ' .
                ($item['bairro'] ?? '') . ', ' .
                ($item['cidade'] ?? '') . '/' .
                ($item['estado'] ?? ''),
                " ,-/"
            );
        }
    }

    return 'Não encontrado';
}

// Localiza um veículo pelo ID.
function veiculoCadastroRelatorio($lista, $id)
{
    foreach ($lista as $item) {
        if ((int)($item['id'] ?? 0) === (int)$id) {
            return trim(($item['placa'] ?? '') . ' - ' . ($item['tipo'] ?? ''), ' -');
        }
    }

    return 'Não encontrado';
}

// Retorna os nomes dos produtos. Aceita o campo singular "produto"
// e também "produtos" como lista de IDs.
function produtosFreteRelatorio($frete, $listaProdutos)
{
    $idsProdutos = $frete['produtos'] ?? ($frete['produto'] ?? []);

    if (!is_array($idsProdutos)) {
        $idsProdutos = [$idsProdutos];
    }

    $nomes = [];

    foreach ($idsProdutos as $produto) {
        // Também aceita itens armazenados como arrays com o ID do produto.
        $idProduto = is_array($produto)
            ? ($produto['id'] ?? $produto['produto'] ?? null)
            : $produto;

        if ($idProduto === null || $idProduto === '') {
            continue;
        }

        $nome = nomeCadastroRelatorio($listaProdutos, $idProduto);

        if ($nome !== 'Não encontrado' && !in_array($nome, $nomes, true)) {
            $nomes[] = $nome;
        } elseif ($nome === 'Não encontrado') {
            $nomes[] = $nome;
        }
    }

    return count($nomes) > 0 ? implode(', ', $nomes) : 'Não informado';
}

?>

<main class="container mt-4">

    <h2><i class="bi bi-bar-chart-fill"></i>
        Relatórios
    </h2>
    <!-- INDICADORES DE FRETES -->
<div class="row g-3 mb-4">

    <!-- Em carregamento -->
    <div class="col-md-3">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">

                <div class="fs-2 text-warning">
                    <i class="bi bi-box-seam"></i>
                </div>

                <div>
                    <h3 class="mb-0">
                        <?= $totalCarregamento ?>
                    </h3>
                    <small class="text-muted">Em carregamento</small>
                </div>

            </div>
        </div>
    </div>

    <!-- Em trânsito -->
    <div class="col-md-3">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">

                <div class="fs-2 text-primary">
                    <i class="bi bi-truck"></i>
                </div>

                <div>
                    <h3 class="mb-0">
                        <?= $totalTransito ?>
                    </h3>
                    <small class="text-muted">Em trânsito</small>
                </div>

            </div>
        </div>
    </div>

    <!-- Em descarga -->
    <div class="col-md-3">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">

                <div class="fs-2 text-info">
                    <i class="bi bi-box-arrow-down"></i>
                </div>

                <div>
                    <h3 class="mb-0">
                        <?= $totalDescarga ?>
                    </h3>
                    <small class="text-muted">Em descarga</small>
                </div>

            </div>
        </div>
    </div>

    <!-- Concluídos -->
    <div class="col-md-3">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">

                <div class="fs-2 text-success">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div>
                    <h3 class="mb-0">
                        <?= $totalConcluidos ?>
                    </h3>
                    <small class="text-muted">Concluídos</small>
                </div>

            </div>
        </div>
    </div>

</div>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Gerar relatório de fretes</h5>
        </div>

        <div class="card-body">
            <form method="get">
                <div class="mb-3">
                    <label for="dataRelatorio" class="form-label">Selecione a data</label>
                    <input
                        type="date"
                        name="dataRelatorio"
                        id="dataRelatorio"
                        class="form-control"
                        value="<?= htmlspecialchars($dataRelatorio, ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">Pesquisar</button>
                <a href="fretes.php" class="btn btn-outline-secondary">Voltar para Fretes</a>
            </form>
        </div>


    </div>

    <?php if ($dataRelatorio !== ''): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Resultado do relatório</h5>
            </div>

            <div class="card-body">
                <p>
                    <strong>Data selecionada:</strong>
                    <?= htmlspecialchars(date('d/m/Y', strtotime($dataRelatorio)), ENT_QUOTES, 'UTF-8') ?>
                </p>

                <p>
                    <strong>Total de fretes encontrados:</strong>
                    <?= count($fretesRelatorio) ?>
                </p>

                <?php if (count($fretesRelatorio) > 0): ?>
                    <div class="table-responsive">
                        <table id="tabelaRelatorio" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Produto(s)</th>
                                    <th>Origem</th>
                                    <th>Destino</th>
                                    <th>Transportador</th>
                                    <th>Veículo</th>
                                    <th>Data</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($fretesRelatorio as $frete): ?>
                                    <tr>
                                        <td><?= htmlspecialchars((string)($frete['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars(produtosFreteRelatorio($frete, $produtos), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars(enderecoCadastroRelatorio($enderecos, $frete['origem'] ?? 0), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars(enderecoCadastroRelatorio($enderecos, $frete['destino'] ?? 0), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars(nomeCadastroRelatorio($transportadores, $frete['transportador'] ?? 0), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars(veiculoCadastroRelatorio($veiculos, $frete['veiculo'] ?? 0), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <?php
                                            $dataFreteFormatada = !empty($frete['dataFrete'])
                                                ? date('d/m/Y', strtotime($frete['dataFrete']))
                                                : '';
                                            ?>
                                            <?= htmlspecialchars($dataFreteFormatada, ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                        <td>R$ <?= number_format((float)($frete['valor'] ?? 0), 2, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($frete['status'] ?? 'Programado', ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <button
                        type="button"
                        class="btn btn-success"
                        id="baixarRelatorio"
                        data-data-relatorio="<?= htmlspecialchars($dataRelatorio, ENT_QUOTES, 'UTF-8') ?>"
                    >
                        Baixar relatório CSV
                    </button>
                <?php else: ?>
                    <div class="alert alert-warning mb-0">
                        Nenhum frete encontrado para a data selecionada.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

</main>

<!-- O JavaScript fica em arquivo separado: admin/js/relatorios.js -->
<script src="../js/relatorios.js"></script>
