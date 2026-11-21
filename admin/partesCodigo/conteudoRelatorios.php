<?php

// Recupera os cadastros.
$fretes = $_SESSION['fretes'] ?? [];
$produtos = $_SESSION['produtos'] ?? [];
$transportadores = $_SESSION['transportadores'] ?? [];
$enderecos = $_SESSION['endereco'] ?? [];
$veiculos = $_SESSION['veiculos'] ?? [];

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
