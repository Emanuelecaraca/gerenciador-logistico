
<?php

// Prepara os cadastros para preencher os campos de seleção.
$produtos = $_SESSION['produtos'] ?? [];
$transportadores = $_SESSION['transportadores'] ?? [];
$enderecos = $_SESSION['endereco'] ?? [];
$veiculos = $_SESSION['veiculos'] ?? [];
$fretes = $_SESSION['fretes'] ?? [];

// Aplica os filtros da consulta.
$filtroData = $_GET['filtroData'] ?? '';
$filtroTransportador = $_GET['filtroTransportador'] ?? '';

$fretesFiltrados = [];

foreach ($fretes as $frete) {

    $correspondeData = ($filtroData === '' || $frete['dataFrete'] === $filtroData);

    $correspondeTransportador = (
        $filtroTransportador === '' ||
        (int)$frete['transportador'] === (int)$filtroTransportador
    );

    if ($correspondeData && $correspondeTransportador) {
        $fretesFiltrados[] = $frete;
    }
}

// Localiza o nome de um cadastro pelo ID.
function nomeCadastroFrete($lista, $id, $campo = 'nome') {
    foreach ($lista as $item) {
        if ((int)$item['id'] === (int)$id) {
            return $item[$campo] ?? '';
        }
    }

    return 'Não encontrado';
}

// Monta o endereço completo para exibição.
function enderecoCadastroFrete($lista, $id) {
    foreach ($lista as $item) {
        if ((int)$item['id'] === (int)$id) {
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

<main class="fretes">

    <h2>Fretes</h2>
    <p>Cadastro e consulta de fretes</p>

    <div class="mb-3">
        <a href="relatorios.php" class="btn btn-outline-primary">
            Acessar relatórios
        </a>
    </div>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success">
            Frete cadastrado com sucesso!
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger">
            <?php if ($_GET['erro'] === 'relacionamento'): ?>
                Confira os cadastros selecionados. O veículo deve pertencer ao transportador escolhido e a origem deve ser diferente do destino.
            <?php else: ?>
                Preencha todos os campos corretamente.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- CADASTRO DE FRETE -->
    <div class="card mb-4">

        <div class="card-header">
            <h5>Cadastrar Frete</h5>
        </div>

        <div class="card-body">

            <form method="post" action="../php/fretes.php">

                <input type="hidden" name="acao" value="cadastrar">

                <div class="mb-3">
                    <label for="produto" class="form-label">Produto</label>

                    <select name="produto" id="produto" class="form-select" required>
                        <option value="">Selecione o produto</option>

                        <?php foreach ($produtos as $produto): ?>
                            <option value="<?= $produto['id'] ?>">
                                <?= htmlspecialchars($produto['nome']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label for="transportador" class="form-label">Transportador</label>

                    <select name="transportador" id="transportador" class="form-select" required>
                        <option value="">Selecione o transportador</option>

                        <?php foreach ($transportadores as $transportador): ?>
                            <option value="<?= $transportador['id'] ?>">
                                <?= htmlspecialchars($transportador['nome']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label for="veiculo" class="form-label">Veículo</label>

                    <select name="veiculo" id="veiculo" class="form-select" required>
                        <option value="">Selecione o veículo</option>

                        <?php foreach ($veiculos as $veiculo): ?>
                            <option value="<?= $veiculo['id'] ?>">
                                <?= htmlspecialchars($veiculo['placa'] . ' - ' . $veiculo['tipo']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label for="origem" class="form-label">Origem</label>

                    <select name="origem" id="origem" class="form-select" required>
                        <option value="">Selecione a origem</option>

                        <?php foreach ($enderecos as $endereco): ?>
                            <option value="<?= $endereco['id'] ?>">
                                <?= htmlspecialchars(
                                    ($endereco['rua'] ?? '') . ', ' .
                                    ($endereco['numero'] ?? '') . ' - ' .
                                    ($endereco['cidade'] ?? '')
                                ) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label for="destino" class="form-label">Destino</label>

                    <select name="destino" id="destino" class="form-select" required>
                        <option value="">Selecione o destino</option>

                        <?php foreach ($enderecos as $endereco): ?>
                            <option value="<?= $endereco['id'] ?>">
                                <?= htmlspecialchars(
                                    ($endereco['rua'] ?? '') . ', ' .
                                    ($endereco['numero'] ?? '') . ' - ' .
                                    ($endereco['cidade'] ?? '')
                                ) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label for="dataFrete" class="form-label">Data do Frete</label>
                    <input type="date" name="dataFrete" id="dataFrete" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="valor" class="form-label">Valor do Frete (R$)</label>
                    <input type="number" name="valor" id="valor" class="form-control"
                           min="0" step="0.01" placeholder="Ex.: 1500,00" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Cadastrar Frete
                </button>

            </form>

        </div>
    </div>

    <!-- CONSULTA DE FRETES -->
    <div class="card mb-4">

        <div class="card-header">
            <h5>Consultar Fretes</h5>
        </div>

        <div class="card-body">

            <form method="get">

                <div class="mb-3">
                    <label for="filtroData" class="form-label">Data</label>
                    <input type="date" name="filtroData" id="filtroData"
                           class="form-control"
                           value="<?= htmlspecialchars($filtroData) ?>">
                </div>

                <div class="mb-3">
                    <label for="filtroTransportador" class="form-label">Transportador</label>

                    <select name="filtroTransportador" id="filtroTransportador" class="form-select">
                        <option value="">Todos os transportadores</option>

                        <?php foreach ($transportadores as $transportador): ?>
                            <option value="<?= $transportador['id'] ?>"
                                <?= ((string)$transportador['id'] === (string)$filtroTransportador) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($transportador['nome']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <button type="submit" class="btn btn-secondary">
                    Pesquisar
                </button>

                <a href="fretes.php" class="btn btn-outline-secondary">
                    Limpar filtros
                </a>

            </form>

            <hr>
            
            
            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Produto</th>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Transportador</th>
                            <th>Veículo</th>
                            <th>Data</th>
                            <th>Valor</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                                        </thead>

                    <tbody>

                        <?php if (count($fretesFiltrados) > 0): ?>

                            <!-- Percorre os fretes encontrados na consulta. -->
                            <?php foreach ($fretesFiltrados as $frete): ?>

                                <tr>
                                    <td><?= $frete['id'] ?></td>

                                    <td>
                                        <?= htmlspecialchars(nomeCadastroFrete($produtos, $frete['produto'])) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(enderecoCadastroFrete($enderecos, $frete['origem'])) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(enderecoCadastroFrete($enderecos, $frete['destino'])) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(nomeCadastroFrete($transportadores, $frete['transportador'])) ?>
                                    </td>

                                    <td>
                                        <?php
                                        // Localiza a placa e o tipo do veículo pelo ID.
                                        $veiculoTexto = 'Não encontrado';

                                        foreach ($veiculos as $itemVeiculo) {
                                            if ((int)$itemVeiculo['id'] === (int)$frete['veiculo']) {
                                                $veiculoTexto = $itemVeiculo['placa'] . ' - ' . $itemVeiculo['tipo'];
                                                break;
                                            }
                                        }
                                        ?>

                                        <?= htmlspecialchars($veiculoTexto) ?>
                                    </td>

                                    <td><?= date('d/m/Y', strtotime($frete['dataFrete'])) ?></td>

                                    <td>
                                        R$ <?= number_format($frete['valor'], 2, ',', '.') ?>
                                    </td>

                                    <td><?= htmlspecialchars($frete['status']) ?></td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <!-- Informa quando não existem fretes para os filtros selecionados. -->
                            <tr>
                                <td colspan="9" class="text-center">
                                    Nenhum frete encontrado.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>  

                

            </div>

        </div>
    </div>

    <a href="home.php">Voltar</a>

</main>
