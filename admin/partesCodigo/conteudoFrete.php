
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

    $correspondeData = (
        $filtroData === '' ||
        ($frete['dataFrete'] ?? '') === $filtroData
    );

    $correspondeTransportador = (
        $filtroTransportador === '' ||
        (int)($frete['transportador'] ?? 0) === (int)$filtroTransportador
    );

    if ($correspondeData && $correspondeTransportador) {
        $fretesFiltrados[] = $frete;
    }
}

// Localiza um cadastro pelo ID.
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

    <!-- TÍTULO E BOTÃO NOVO FRETE -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2 class="mb-0">Fretes</h2>

        <button type="button" id="btn-novo-frete" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Novo frete
        </button>

    </div>

    <!-- MODAL DE CADASTRO E EDIÇÃO -->
    <div class="modal fade" id="modalFrete" tabindex="-1"
         aria-labelledby="tituloModalFrete" aria-hidden="true"
         data-editando="0">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <form action="../php/fretes.php" method="post" id="formFrete">

                    <div class="modal-header">

                        <h5 class="modal-title" id="tituloModalFrete">
                            Cadastrar Frete
                        </h5>

                        <button type="button" class="btn-close"
                                data-bs-dismiss="modal" aria-label="Fechar"></button>

                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="acao" id="acaoFrete" value="cadastrar">
                        <input type="hidden" name="id" id="idFrete" value="">

                        <!-- PRODUTOS COM PESQUISA E SELEÇÃO -->
                        <div class="mb-3">

                            <label class="form-label">
                                Produtos
                            </label>

                            <input type="search"
                                   id="busca-produtos-frete"
                                   class="form-control mb-2"
                                   placeholder="Pesquisar produto pelo nome...">

                            <div id="lista-produtos-frete"
                                 class="border rounded p-2"
                                 style="max-height: 180px; overflow-y: auto;">

                                <?php if (!empty($produtos)): ?>

                                    <?php foreach ($produtos as $produto): ?>

                                        <div class="item-produto-frete form-check mb-2">

                                            <input type="checkbox"
                                                   class="form-check-input checkbox-produto-frete"
                                                   name="produtos[]"
                                                   id="produto-frete-<?php echo (int)$produto['id']; ?>"
                                                   value="<?php echo (int)$produto['id']; ?>">

                                            <label class="form-check-label"
                                                   for="produto-frete-<?php echo (int)$produto['id']; ?>">

                                                <?php echo htmlspecialchars($produto['nome']); ?>

                                            </label>

                                        </div>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <p class="text-muted mb-0">
                                        Nenhum produto cadastrado.
                                    </p>

                                <?php endif; ?>

                            </div>

                            

                        </div>

                        <!-- TRANSPORTADOR E VEÍCULO -->
                        <div class="row">

     
<div class="col-md-6 mb-3">

    <label for="busca-transportador" class="form-label">
        Transportador
    </label>

    <div class="position-relative">

        <input
            type="search"
            id="busca-transportador"
            class="form-control"
            placeholder="Digite o ID ou nome do transportador..."
            autocomplete="off"
            required
        >

        <div
            id="lista-transportadores"
            class="list-group shadow"
            style="
                max-height: 200px;
                overflow-y: auto;
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                z-index: 1060;
            "
        >

            <?php foreach ($transportadores as $transportadorItem): ?>

                <button
                    type="button"
                    class="list-group-item list-group-item-action item-transportador"
                    data-id="<?php echo (int)$transportadorItem['id']; ?>"
                    data-nome="<?php echo htmlspecialchars($transportadorItem['nome'], ENT_QUOTES, 'UTF-8'); ?>"
                >
                    <?php
                    echo (int)$transportadorItem['id'] . ' - ' .
                        htmlspecialchars($transportadorItem['nome'], ENT_QUOTES, 'UTF-8');
                    ?>
                </button>

            <?php endforeach; ?>

        </div>

    </div>

    <input
        type="hidden"
        name="transportador"
        id="transportador"
        value=""
    >

</div>


                            <div class="col-md-6 mb-3">

                                <label for="veiculo" class="form-label">
                                    Veículo
                                </label>

                                <select name="veiculo" id="veiculo"
                                        class="form-select" required>

                                    <option value="">Selecione primeiro o transportador</option>

                                    <?php foreach ($veiculos as $veiculo): ?>

                                        <option
                                            value="<?php echo (int)$veiculo['id']; ?>"
                                            data-transportador="<?php echo (int)$veiculo['transportador']; ?>"
                                            hidden>

                                            <?php echo htmlspecialchars($veiculo['placa']); ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>

                        <!-- ORIGEM E DESTINO -->
                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label for="origem" class="form-label">Origem</label>

                                <select name="origem" id="origem"
                                        class="form-select" required>

                                    <option value="">Selecione a origem</option>

                                    <?php foreach ($enderecos as $endereco): ?>

                                        <option value="<?php echo (int)$endereco['id']; ?>">
                                            <?php echo htmlspecialchars(
                                                ($endereco['rua'] ?? '') . ', ' .
                                                ($endereco['numero'] ?? '') . ' - ' .
                                                ($endereco['cidade'] ?? '')
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

                                        <option value="<?php echo (int)$endereco['id']; ?>">
                                            <?php echo htmlspecialchars(
                                                ($endereco['rua'] ?? '') . ', ' .
                                                ($endereco['numero'] ?? '') . ' - ' .
                                                ($endereco['cidade'] ?? '')
                                            ); ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>

                        <!-- DATA, PRAZO E VALOR -->
                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label for="dataFrete" class="form-label">
                                    Data do frete
                                </label>

                                <input type="date" name="dataFrete" id="dataFrete"
                                       class="form-control" required>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label for="prazoChegada" class="form-label">
                                    Prazo de chegada
                                </label>

                                <input type="date" name="prazoChegada" id="prazoChegada"
                                       class="form-control" required>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label for="valor" class="form-label">
                                    Valor do frete (R$)
                                </label>

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

                        <button type="submit" class="btn btn-primary"
                                id="btnSalvarFrete">
                            Cadastrar Frete
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <!-- PESQUISA -->
    <div class="input-group mb-2">

        <input type="search" id="busca-frete" class="form-control"
               placeholder="Pesquisar por ID ou produto">

        <button type="button" id="btn-pesquisar-frete" class="btn btn-primary">
            <i class="bi bi-search"></i> Pesquisar
        </button>

    </div>


    

    <!-- MENSAGENS -->
    <?php if (isset($_GET['sucesso'])): ?>

        <div class="alert alert-success">
            Frete cadastrado com sucesso!
        </div>

    <?php endif; ?>

    <?php if (isset($_GET['editado'])): ?>

        <div class="alert alert-success">
            Frete atualizado com sucesso!
        </div>

    <?php endif; ?>

    <?php if (isset($_GET['excluido'])): ?>

        <div class="alert alert-success">
            Frete excluído com sucesso!
        </div>

    <?php endif; ?>

    <?php if (isset($_GET['erro'])): ?>

        <div class="alert alert-danger">

            <?php if ($_GET['erro'] === 'veiculo_ocupado'): ?>

                Este veículo já possui um frete em andamento. Finalize a rota atual antes de utilizá-lo novamente.

            <?php elseif ($_GET['erro'] === 'relacionamento'): ?>

                Confira os cadastros selecionados. O veículo deve pertencer ao transportador escolhido e a origem deve ser diferente do destino.

            <?php elseif ($_GET['erro'] === 'frete_nao_encontrado'): ?>

                O frete selecionado não foi encontrado.

            <?php else: ?>

                Preencha todos os campos corretamente.

            <?php endif; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($_GET['status_atualizado'])): ?>

        <div class="alert alert-success">
            Status do frete atualizado com sucesso!
        </div>

    <?php endif; ?>

    <!-- TABELA DE FRETES CADASTRADOS -->
    <div class="table-responsive mt-4">

        <table class="table table-bordered table-hover align-middle">

            <thead class="table-light">

                <tr>
                    <th>ID</th>
                    <th>Transportador</th>
                    <th>Veículo</th>
                    <th>Origem</th>
                    <th>Destino</th>
                    <th>Data do frete</th>
                    <th>Prazo de chegada</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>

            </thead>

            <tbody>

                <?php if (!empty($fretesFiltrados)): ?>

                    <?php foreach ($fretesFiltrados as $frete): ?>

                        <?php

                        $produtosFrete = $frete['produtos'] ?? [];

                        if (empty($produtosFrete) && isset($frete['produto'])) {
                            $produtosFrete = [$frete['produto']];
                        }

                        ?>

                        <tr class="linha-frete">

                            <td>
                                <?php echo htmlspecialchars((string)$frete['id']); ?>
                            </td>

                            <td>
                                <?php
                                $idTransportador = (int)($frete['transportador'] ?? 0);
                                $nomeTransportador = nomeCadastroFrete(
                                    $transportadores,
                                    $idTransportador
                                );

                                echo htmlspecialchars(
                                    $nomeTransportador . ' (ID: ' . $idTransportador . ')'
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    nomeCadastroFrete($veiculos, $frete['veiculo'], 'placa')
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    enderecoCadastroFrete($enderecos, $frete['origem'])
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    enderecoCadastroFrete($enderecos, $frete['destino'])
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo !empty($frete['dataFrete'])
                                    ? date('d/m/Y', strtotime($frete['dataFrete']))
                                    : 'Não informada';
                                ?>
                            </td>

                            <td>
                                <?php
                                echo !empty($frete['prazoChegada'])
                                    ? date('d/m/Y', strtotime($frete['prazoChegada']))
                                    : 'Não informado';
                                ?>
                            </td>

                            <td>
                                R$ <?php echo number_format((float)$frete['valor'], 2, ',', '.'); ?>
                            </td>

                            <!-- STATUS -->
                            <td style="min-width: 220px;">

                                <form action="../php/fretes.php" method="post">

                                    <input type="hidden" name="acao" value="atualizar_status">

                                    <input type="hidden" name="id"
                                           value="<?php echo (int)$frete['id']; ?>">

                                    <?php

                                    $statusAtual = $frete['status'] ?? 'Programado';

                                    $statusAntigo = [
                                        'No destino / Em descarga' => 'Em descarga',
                                        'Rota concluída / Finalizado' => 'Finalizado',
                                        'Rota concluída' => 'Finalizado'
                                    ];

                                    if (isset($statusAntigo[$statusAtual])) {
                                        $statusAtual = $statusAntigo[$statusAtual];
                                    }

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

                            <!-- AÇÕES -->
                            <td class="text-center text-nowrap">

                                <a href="../relatorioFrete.php?id=<?php echo (int)$frete['id']; ?>"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-success"
                                   title="Gerar relatório">

                                    <i class="bi bi-file-earmark-text"></i>

                                </a>

                                <button type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalProdutosFrete<?php echo (int)$frete['id']; ?>"
                                        title="Visualizar produtos">

                                    <i class="bi bi-eye"></i>

                                </button>

                                <button type="button"
                                        class="btn btn-sm btn-outline-warning btn-editar-frete"
                                        title="Editar frete"

                                        data-id="<?php echo (int)$frete['id']; ?>"

                                        data-produtos='<?php echo htmlspecialchars(
                                            json_encode(array_map('intval', $produtosFrete)),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>'

                                        data-transportador="<?php echo (int)$frete['transportador']; ?>"
                                        data-veiculo="<?php echo (int)$frete['veiculo']; ?>"
                                        data-origem="<?php echo (int)$frete['origem']; ?>"
                                        data-destino="<?php echo (int)$frete['destino']; ?>"

                                        data-data="<?php echo htmlspecialchars($frete['dataFrete'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"

                                        data-prazo="<?php echo htmlspecialchars($frete['prazoChegada'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"

                                        data-valor="<?php echo htmlspecialchars((string)($frete['valor'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">

                                    <i class="bi bi-pencil"></i>

                                </button>

                                <form action="../php/fretes.php" method="post"
                                      class="d-inline"
                                      onsubmit="return confirm('Deseja realmente excluir este frete?');">

                                    <input type="hidden" name="acao" value="excluir">

                                    <input type="hidden" name="id"
                                           value="<?php echo (int)$frete['id']; ?>">

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Excluir frete">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                <tr id="linha-sem-resultado-frete"
                    <?php echo !empty($fretesFiltrados) ? 'class="d-none"' : ''; ?>>

                    <td colspan="10" class="text-center text-muted">
                        Nenhum frete encontrado.
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

    <!-- MODAIS PARA VISUALIZAR OS PRODUTOS -->
    <?php foreach ($fretesFiltrados as $frete): ?>

        <?php

        $produtosFrete = $frete['produtos'] ?? [];

        if (empty($produtosFrete) && isset($frete['produto'])) {
            $produtosFrete = [$frete['produto']];
        }

        ?>

        <div class="modal fade"
             id="modalProdutosFrete<?php echo (int)$frete['id']; ?>"
             tabindex="-1"
             aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Produtos do Frete #<?php echo (int)$frete['id']; ?>
                        </h5>

                        <button type="button" class="btn-close"
                                data-bs-dismiss="modal" aria-label="Fechar"></button>

                    </div>

                    <div class="modal-body">

                        <?php if (!empty($produtosFrete)): ?>

                            <ul class="list-group">

                                <?php foreach ($produtosFrete as $idProduto): ?>

                                    <li class="list-group-item">

                                        <?php
                                        echo htmlspecialchars(
                                            nomeCadastroFrete($produtos, $idProduto)
                                        );
                                        ?>

                                    </li>

                                <?php endforeach; ?>

                            </ul>

                        <?php else: ?>

                            <p class="text-muted mb-0">
                                Nenhum produto vinculado a este frete.
                            </p>

                        <?php endif; ?>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Fechar
                        </button>

                    </div>

                </div>

            </div>

        </div>

    <?php endforeach; ?>

    <a href="home.php">Voltar</a>

</main>
