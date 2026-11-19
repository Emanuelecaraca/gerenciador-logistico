
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
                
        <button type="button" id="btn-novo-frete" class="btn btn-primary">
            Novo frete
        </button>
        
    <div class="modal fade" id="modalFrete" tabindex="-1"
     aria-labelledby="tituloModalFrete" aria-hidden="true"
     data-editando="0">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="../php/fretes.php" method="post">
                        <div class="modal-header">
                        <h5 class="modal-title" id="tituloModalFrete">
                            Cadastrar Frete
                        </h5>

                        <button type="button" class="btn-close"
                                data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="acao" value="cadastrar">

                        <!-- Produtos -->
                        <div class="mb-3">
                            <label for="produtos" class="form-label">Produtos</label>

                            <select name="produtos[]" id="produtos"
                                    class="form-select" multiple required>

                                <?php foreach ($produtos as $produto): ?>
                                    <option value="<?php echo $produto['id']; ?>">
                                        <?php echo htmlspecialchars($produto['nome']); ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>

                            <small class="text-muted">
                                Para selecionar vários produtos, mantenha a tecla Ctrl pressionada.
                            </small>
                        </div>

                        <!-- Transportador e veículo -->
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="transportador" class="form-label">Transportador</label>

                                <select name="transportador" id="transportador"
                                        class="form-select" required>

                                    <option value="">Selecione o transportador</option>

                                    <?php foreach ($transportadores as $transportador): ?>
                                        <option value="<?php echo $transportador['id']; ?>">
                                            <?php echo htmlspecialchars($transportador['nome']); ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="veiculo" class="form-label">Veículo</label>

                                <select name="veiculo" id="veiculo"
                                        class="form-select" required>

                                    <option value="">Selecione o veículo</option>

                                    <?php foreach ($veiculos as $veiculo): ?>
                                        <option value="<?php echo $veiculo['id']; ?>">
                                            <?php echo htmlspecialchars($veiculo['placa']); ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                        </div>

                        <!-- Origem e destino -->
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="origem" class="form-label">Origem</label>

                                <select name="origem" id="origem"
                                        class="form-select" required>

                                    <option value="">Selecione a origem</option>

                                    <?php foreach ($enderecos as $endereco): ?>
                                        <option value="<?php echo $endereco['id']; ?>">
                                            <?php echo htmlspecialchars(
                                                $endereco['rua'] . ', ' .
                                                $endereco['numero'] . ' - ' .
                                                $endereco['cidade']
                                            ); ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="destino" class="form-label">Destino</label>

                                <select name="destino" id="destino"
                                        class="form-select" required>

                                    <option value="">Selecione o destino</option>

                                    <?php foreach ($enderecos as $endereco): ?>
                                        <option value="<?php echo $endereco['id']; ?>">
                                            <?php echo htmlspecialchars(
                                                $endereco['rua'] . ', ' .
                                                $endereco['numero'] . ' - ' .
                                                $endereco['cidade']
                                            ); ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                        </div>

                        <!-- Data, prazo e valor -->
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label for="dataFrete" class="form-label">Data do frete</label>

                                <input type="date" name="dataFrete" id="dataFrete"
                                       class="form-control" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="prazoChegada" class="form-label">Prazo de chegada</label>

                                <input type="date" name="prazoChegada" id="prazoChegada"
                                       class="form-control" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="valor" class="form-label">Valor do frete (R$)</label>

                                <input type="number" name="valor" id="valor"
                                       class="form-control" min="0" step="0.01"
                                       placeholder="0,00" required>
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Cancelar
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Cadastrar Frete
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>

    
<div class="input-group mb-2">

    <input type="search" id="busca-frete" class="form-control"
           placeholder="Pesquisar por ID ou produto">

    <button type="button" id="btn-pesquisar-frete" class="btn btn-primary">
        <i class="bi bi-search"></i> Pesquisar
    </button>

</div>

<small id="contador-frete" class="text-muted d-block mb-3"></small>
    <div class="mb-3">
        <a href="fretes.php" class="btn btn-outline-primary">
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

            <?php if ($_GET['erro'] === 'veiculo_ocupado'): ?>

                Este veículo já possui um frete em andamento. Finalize a rota atual antes de utilizá-lo novamente.

            <?php elseif ($_GET['erro'] === 'relacionamento'): ?>

                Confira os cadastros selecionados. O veículo deve pertencer ao transportador escolhido e a origem deve ser diferente do destino.

            <?php else: ?>

                Preencha todos os campos corretamente.

            <?php endif; ?>

        </div>
    <?php endif; ?>

    
<!-- TABELA DE FRETES CADASTRADOS -->
<div class="table-responsive mt-4">

    <table class="table table-bordered table-hover align-middle">

        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Produtos</th>
                <th>Transportador</th>
                <th>Veículo</th>
                <th>Origem</th>
                <th>Destino</th>
                <th>Data do frete</th>
                <th>Prazo de chegada</th>
                <th>Valor</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($fretesFiltrados)): ?>

                <?php foreach ($fretesFiltrados as $frete): ?>

                    <tr class="linha-frete">

                        <!-- ID -->
                        <td>
                            <?php echo htmlspecialchars((string)$frete['id']); ?>
                        </td>

                        <!-- Produtos -->
                        <td>
                            <?php
                            $produtosFrete = $frete['produtos'] ?? [];

                            // Compatibilidade com eventual cadastro antigo.
                            if (empty($produtosFrete) && isset($frete['produto'])) {
                                $produtosFrete = [$frete['produto']];
                            }

                            $nomesProdutos = [];

                            foreach ($produtosFrete as $idProduto) {
                                $nomesProdutos[] = nomeCadastroFrete(
                                    $produtos,
                                    $idProduto
                                );
                            }

                            echo htmlspecialchars(implode(', ', $nomesProdutos));
                            ?>
                        </td>

                        <!-- Transportador -->
                        <td>
                            <?php
                            echo htmlspecialchars(
                                nomeCadastroFrete($transportadores, $frete['transportador'])
                            );
                            ?>
                        </td>

                        <!-- Veículo -->
                        <td>
                            <?php
                            echo htmlspecialchars(
                                nomeCadastroFrete($veiculos, $frete['veiculo'], 'placa')
                            );
                            ?>
                        </td>

                        <!-- Origem -->
                        <td>
                            <?php
                            echo htmlspecialchars(
                                enderecoCadastroFrete($enderecos, $frete['origem'])
                            );
                            ?>
                        </td>

                        <!-- Destino -->
                        <td>
                            <?php
                            echo htmlspecialchars(
                                enderecoCadastroFrete($enderecos, $frete['destino'])
                            );
                            ?>
                        </td>

                        <!-- Data do frete -->
                        <td>
                            <?php
                            echo !empty($frete['dataFrete'])
                                ? date('d/m/Y', strtotime($frete['dataFrete']))
                                : 'Não informada';
                            ?>
                        </td>

                        <!-- Prazo de chegada -->
                        <td>
                            <?php
                            echo !empty($frete['prazoChegada'])
                                ? date('d/m/Y', strtotime($frete['prazoChegada']))
                                : 'Não informado';
                            ?>
                        </td>

                        <!-- Valor -->
                        <td>
                            R$ <?php echo number_format((float)$frete['valor'], 2, ',', '.'); ?>
                        </td>

                        
                        <!-- Status -->
                        <td style="min-width: 220px;">

                            <form action="../php/fretes.php" method="post">

                                <input type="hidden" name="acao" value="atualizar_status">

                                <input type="hidden" name="id"
                                       value="<?php echo (int)$frete['id']; ?>">

                                <?php

                                // Recupera o status atual do frete.
                                $statusAtual = $frete['status'] ?? 'Programado';

                                // Converte nomes antigos para os novos.
                                $statusAntigo = [
                                    'No destino / Em descarga' => 'Em descarga',
                                    'Rota concluída / Finalizado' => 'Finalizado',
                                    'Rota concluída' => 'Finalizado'
                                ];

                                if (isset($statusAntigo[$statusAtual])) {
                                    $statusAtual = $statusAntigo[$statusAtual];
                                }

                                // Define a classe de cor de cada status.
                                $coresStatus = [
                                    'Programado' => 'status-programado',
                                    'Em carregamento' => 'status-carregamento',
                                    'Aguardando saída' => 'status-saida',
                                    'Em trânsito' => 'status-transito',
                                    'Em parada' => 'status-parada',
                                    'Em ocorrência' => 'status-ocorrencia',
                                    'Em descarga' => 'status-descarga',
                                    'Finalizado' => 'status-concluido'
                                ];

                                $classeStatus = $coresStatus[$statusAtual] ?? 'status-programado';

                                ?>

                                <select name="status"
                                    class="form-select form-select-sm status-frete <?php echo $classeStatus; ?>"
                                    onchange="this.form.submit()">

                                    <?php foreach ($coresStatus as $opcao => $classe): ?>

                                        <option value="<?php echo htmlspecialchars($opcao); ?>"
                                            <?php echo ($statusAtual === $opcao) ? 'selected' : ''; ?>>

                                            <?php echo htmlspecialchars($opcao); ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </form>

                        </td>


                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            <!-- Mensagem de nenhum resultado -->
            <tr id="linha-sem-resultado-frete"
                <?php echo !empty($fretesFiltrados) ? 'class="d-none"' : ''; ?>>

                <td colspan="9" class="text-center text-muted">
                    Nenhum frete encontrado.
                </td>

            </tr>

            <?php if (isset($_GET['status_atualizado'])): ?>

                <div class="alert alert-success">
                    Status do frete atualizado com sucesso!
                </div>

            <?php endif; ?>

        </tbody>

    </table>

</div>

    <a href="home.php">Voltar</a>

</main>
