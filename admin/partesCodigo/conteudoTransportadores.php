
<?php

// INICIALIZA AS LISTAS
$_SESSION['transportadores'] = $_SESSION['transportadores'] ?? [];
$_SESSION['veiculos'] = $_SESSION['veiculos'] ?? [];
$_SESSION['endereco'] = $_SESSION['endereco'] ?? [];
$_SESSION['fretes'] = $_SESSION['fretes'] ?? [];

// ==================================================
// TRANSPORTADOR SELECIONADO
// ==================================================

$matriculaSelecionada = (int)($_GET['matricula'] ?? 0);
$transportadorExibido = null;

foreach ($_SESSION['transportadores'] as $transportador) {

    if ((int)$transportador['id'] === $matriculaSelecionada) {
        $transportadorExibido = $transportador;
        break;
    }
}

// ==================================================
// TRANSPORTADOR EM EDIÇÃO
// ==================================================

$transportadorEdicao = null;

if (isset($_GET['editar'])) {

    $idEdicao = (int)$_GET['editar'];

    foreach ($_SESSION['transportadores'] as $transportador) {

        if ((int)$transportador['id'] === $idEdicao) {
            $transportadorEdicao = $transportador;
            break;
        }
    }
}

// ==================================================
// VEÍCULO EM EDIÇÃO
// ==================================================

$veiculoEdicao = null;

if (isset($_GET['editarVeiculo'])) {

    $idEdicao = (int)$_GET['editarVeiculo'];

    foreach ($_SESSION['veiculos'] as $veiculo) {

        if ((int)$veiculo['id'] === $idEdicao) {
            $veiculoEdicao = $veiculo;
            break;
        }
    }
}

// ==================================================
// VEÍCULOS DO TRANSPORTADOR SELECIONADO
// ==================================================

$veiculosParaExibir = [];

if ($transportadorExibido !== null) {

    foreach ($_SESSION['veiculos'] as $veiculo) {

        if ((int)$veiculo['transportador'] === (int)$transportadorExibido['id']) {
            $veiculosParaExibir[] = $veiculo;
        }
    }
}


// ENDEREÇOS DO TRANSPORTADOR SELECIONADO
$enderecosParaExibir = [];

if ($transportadorExibido !== null) {
    foreach ($_SESSION['endereco'] as $endereco) {
        if (
            (int)($endereco['transportador'] ?? 0) ===
            (int)$transportadorExibido['id']
        ) {
            $enderecosParaExibir[] = $endereco;
        }
    }
}

// FRETES DO TRANSPORTADOR SELECIONADO
$fretesParaExibir = [];

if ($transportadorExibido !== null) {

    foreach ($_SESSION['fretes'] as $frete) {

        if (
            (int)($frete['transportador'] ?? 0) ===
            (int)$transportadorExibido['id']
        ) {
            $fretesParaExibir[] = $frete;
        }
    }
}
?>



<!-- CONTEÚDO PRINCIPAL -->
<div class="container mt-3 pagina-transportadores">

    <!-- MODAL DE ERRO DE EXCLUSÃO -->
    <?php if (isset($_GET['erro']) && $_GET['erro'] == 'veiculos') { ?>

        <div class="modal fade" id="modalErroExclusao" tabindex="-1" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-sm">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title text-danger">
                            Exclusão não permitida
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        Este transportador possui veículos vinculados.
                        Remova ou transfira os veículos antes de continuar.
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            Entendi
                        </button>
                    </div>

                </div>

            </div>
        </div>

    <?php } ?>


    <!-- TÍTULO E BOTÕES -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2 class="titulo-pagina">
            <i class="bi bi-truck"></i>
            Transportadores
        </h2>
        <div class="d-flex gap-2">

            

            <button type="button" id="btn-novo-transportador" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Novo Transportador
            </button>

        </div>

    </div>


    <!-- ==================================================
         MODAL DE CADASTRO DE TRANSPORTADORES
    ================================================== -->

    <div class="modal fade" id="modalTransportadores" tabindex="-1"
         aria-labelledby="tituloModalTransportadores" aria-hidden="true"
         data-editando="<?php echo $transportadorEdicao ? '1' : '0'; ?>">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <form method="post" action="../php/transportadores.php">

                    <div class="modal-header">

                        <h5 class="modal-title" id="tituloModalTransportadores">
                            <?php echo $transportadorEdicao ? 'Editar Transportador' : 'Cadastrar Transportador'; ?>
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                    </div>

                    <div class="modal-body">

                        <?php if ($transportadorEdicao) { ?>

                            <input type="hidden" name="acao" value="atualizar">

                            <input type="hidden" name="id"
                                   value="<?php echo $transportadorEdicao['id']; ?>">

                        <?php } ?>

                        <div class="mb-3">

                            <label for="nome" class="form-label">
                                Razão Social / Nome
                            </label>

                            <input type="text" name="nome" id="nome"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($transportadorEdicao['nome'] ?? ''); ?>"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label for="documento" class="form-label">
                                CPF / CNPJ
                            </label>

                            <input type="text" name="documento" id="documento"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($transportadorEdicao['documento'] ?? ''); ?>"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label for="telefone" class="form-label">
                                Telefone
                            </label>

                            <input type="text" name="telefone" id="telefone"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($transportadorEdicao['telefone'] ?? ''); ?>"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label for="email" class="form-label">
                                E-mail
                            </label>

                            <input type="email" name="email" id="email"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($transportadorEdicao['email'] ?? ''); ?>"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label for="endereco" class="form-label">
                                Endereço do Transportador
                            </label>

                            <input type="text" name="endereco" id="endereco"
                                   class="form-control"
                                   placeholder="Digite o endereço completo"
                                   value="<?php echo htmlspecialchars($transportadorEdicao['endereco'] ?? ''); ?>"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label for="obs" class="form-label">
                                Observações
                            </label>

                            <textarea name="obs" id="obs"
                                      class="form-control" rows="3"
                                      placeholder="Digite uma observação sobre o transportador"><?php echo htmlspecialchars($transportadorEdicao['obs'] ?? ''); ?></textarea>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>

                        <button type="submit" class="btn btn-primary">
                            <?php echo $transportadorEdicao ? 'Salvar Alterações' : 'Cadastrar Transportador'; ?>
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>


    <!-- ==================================================
         MODAL DE CADASTRO DE VEÍCULOS
    ================================================== -->

    <div class="modal fade" id="modalVeiculo" tabindex="-1"
         aria-labelledby="tituloModalVeiculo" aria-hidden="true"
         data-editando="<?php echo $veiculoEdicao ? '1' : '0'; ?>">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <form method="post" action="../php/veiculos.php">

                    <div class="modal-header">

                        <h5 class="modal-title" id="tituloModalVeiculo">
                            <?php echo $veiculoEdicao ? 'Editar Veículo' : 'Cadastrar Veículo'; ?>
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="matricula"
                               value="<?php echo $transportadorExibido['id'] ?? ''; ?>">

                        <?php if ($veiculoEdicao) { ?>

                            <input type="hidden" name="acao" value="atualizarVeiculo">

                            <input type="hidden" name="id"
                                   value="<?php echo $veiculoEdicao['id']; ?>">

                        <?php } ?>

                        <div class="mb-3">

                            <label for="transportador" class="form-label">
                                Transportador
                            </label>

                            <select name="transportador" id="transportador"
                                    class="form-select" required>

                                <option value="">Selecione o transportador</option>

                                <?php foreach ($_SESSION['transportadores'] as $transportador) { ?>

                                    <option value="<?php echo $transportador['id']; ?>"
                                        <?php
                                        $idTransportadorVeiculo = $veiculoEdicao['transportador']
                                            ?? ($transportadorExibido['id'] ?? '');

                                        echo ((string)$idTransportadorVeiculo === (string)$transportador['id'])
                                            ? 'selected' : '';
                                        ?>>

                                        <?php echo htmlspecialchars($transportador['nome']); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label for="placa" class="form-label">
                                Placa
                            </label>

                            <input type="text" name="placa" id="placa"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($veiculoEdicao['placa'] ?? ''); ?>"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label for="tipo" class="form-label">
                                Tipo de veículo
                            </label>

                            <select name="tipo" id="tipo" class="form-select" required>

                                <option value="">Selecione o tipo</option>

                                <option value="Caminhão" <?php echo (($veiculoEdicao['tipo'] ?? '') == 'Caminhão') ? 'selected' : ''; ?>>
                                    Caminhão
                                </option>

                                <option value="Carreta" <?php echo (($veiculoEdicao['tipo'] ?? '') == 'Carreta') ? 'selected' : ''; ?>>
                                    Carreta
                                </option>

                                <option value="Van" <?php echo (($veiculoEdicao['tipo'] ?? '') == 'Van') ? 'selected' : ''; ?>>
                                    Van
                                </option>

                                <option value="Utilitário" <?php echo (($veiculoEdicao['tipo'] ?? '') == 'Utilitário') ? 'selected' : ''; ?>>
                                    Utilitário
                                </option>

                                <option value="Outro" <?php echo (($veiculoEdicao['tipo'] ?? '') == 'Outro') ? 'selected' : ''; ?>>
                                    Outro
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <?php if ($veiculoEdicao) { ?>

                            <a href="transportadores.php?matricula=<?php echo $transportadorExibido['id'] ?? ''; ?>&aba=veiculos"
                               class="btn btn-secondary">
                                Cancelar
                            </a>

                        <?php } else { ?>

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>

                        <?php } ?>

                        <button type="submit" class="btn btn-primary">
                            <?php echo $veiculoEdicao ? 'Salvar Alterações' : 'Cadastrar Veículo'; ?>
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>


    <!-- ==================================================
         MODAL DE PESQUISA DE TRANSPORTADORES
    ================================================== -->

    <div class="modal fade" id="modalBuscaTransportador" tabindex="-1"
         aria-labelledby="tituloBuscaTransportador" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="tituloBuscaTransportador">
                        Selecionar Transportador
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="input-group mb-3">

                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text"
                               id="pesquisaTransportadorModal"
                               class="form-control"
                               placeholder="Pesquise por matrícula, nome ou CPF/CNPJ">

                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover table-bordered align-middle">

                            <thead class="table-light">

                                <tr>
                                    <th>Matrícula</th>
                                    <th>Nome / Razão Social</th>
                                    <th>CPF / CNPJ</th>
                                    <th>Ação</th>
                                </tr>

                            </thead>

                            <tbody id="listaTransportadoresModal">

                                <tr id="mensagemPesquisaTransportador">
                                    <td colspan="4" class="text-center text-muted">
                                        Digite a matrícula, o nome ou o CPF/CNPJ para pesquisar.
                                    </td>
                                </tr>

                                <?php foreach ($_SESSION['transportadores'] as $transportador) { ?>

                                    <tr class="linha-transportador"
                                        style="display: none;"
                                        data-pesquisa="<?php echo htmlspecialchars(mb_strtolower(
                                            $transportador['id'] . ' ' .
                                            $transportador['nome'] . ' ' .
                                            $transportador['documento'],
                                            'UTF-8'
                                        ), ENT_QUOTES, 'UTF-8'); ?>">

                                        <td><?php echo $transportador['id']; ?></td>

                                        <td><?php echo htmlspecialchars($transportador['nome']); ?></td>

                                        <td><?php echo htmlspecialchars($transportador['documento']); ?></td>

                                        <td>
                                            <a href="transportadores.php?matricula=<?php echo $transportador['id']; ?>"
                                               class="btn btn-primary btn-sm">
                                                Selecionar
                                            </a>
                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>
        </div>
    </div>


    <!-- ==================================================
         ABAS DE NAVEGAÇÃO
    ================================================== -->

    <ul class="nav nav-tabs mb-3" id="abasTransportadores" role="tablist">

        <li class="nav-item" role="presentation">

            <button class="nav-link <?php echo (!in_array(($_GET['aba'] ?? ''), ['veiculos', 'enderecos', 'produtos', 'fretes', 'documentos'])) ? 'active' : ''; ?>"
                    id="aba-transportadores"
                    data-bs-toggle="tab"
                    data-bs-target="#conteudo-transportadores"
                    type="button" role="tab">

                <i class="bi bi-person"></i> Transportadores

            </button>

        </li>

        <li class="nav-item" role="presentation">

            <button class="nav-link <?php echo (($_GET['aba'] ?? '') === 'veiculos') ? 'active' : ''; ?>"
                    id="aba-veiculos"
                    data-bs-toggle="tab"
                    data-bs-target="#conteudo-veiculos"
                    type="button" role="tab">

                <i class="bi bi-truck"></i> Veículos

            </button>

        </li>

        <li class="nav-item" role="presentation">

            <button class="nav-link <?php echo (($_GET['aba'] ?? '') === 'enderecos') ? 'active' : ''; ?>"
                    id="aba-enderecos"
                    data-bs-toggle="tab"
                    data-bs-target="#conteudo-enderecos"
                    type="button" role="tab">

                <i class="bi bi-geo-alt"></i> Endereços

            </button>

        </li>

        <!-- ABA FRETES -->
        <li class="nav-item" role="presentation">

            <button class="nav-link <?php echo (($_GET['aba'] ?? '') === 'fretes') ? 'active' : ''; ?>"
                    id="aba-fretes"
                    data-bs-toggle="tab"
                    data-bs-target="#conteudo-fretes"
                    type="button" role="tab">

                <i class="bi bi-file-earmark-text"></i> Fretes

            </button>

        </li>

        <!-- ABA DOCUMENTOS -->
        <li class="nav-item" role="presentation">

            <button class="nav-link <?php echo (($_GET['aba'] ?? '') === 'documentos') ? 'active' : ''; ?>"
                    id="aba-documentos"
                    data-bs-toggle="tab"
                    data-bs-target="#conteudo-documentos"
                    type="button" role="tab">

                <i class="bi bi-folder2-open"></i> Documentos

            </button>

        </li>

    </ul>


    <div class="tab-content" id="conteudoAbas">


        <!-- ==================================================
             ABA TRANSPORTADORES
        ================================================== -->

        <div class="tab-pane fade <?php echo (!in_array(($_GET['aba'] ?? ''), ['veiculos', 'enderecos', 'produtos', 'fretes', 'documentos'])) ? 'show active' : ''; ?>"
             id="conteudo-transportadores" role="tabpanel">

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">Dados do Transportador</h5>

                    <?php if ($transportadorExibido) { ?>

                        <a href="transportadores.php?matricula=<?php echo $transportadorExibido['id']; ?>&editar=<?php echo $transportadorExibido['id']; ?>"
                           class="btn btn-outline-primary btn-sm">

                            <i class="bi bi-pencil-square"></i> Editar cadastro

                        </a>

                    <?php } ?>

                </div>

                <div class="card-body">

                    <!-- MATRÍCULA E OBSERVAÇÃO -->

                    <div class="row g-3 mb-3">

                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                Matrícula
                            </label>

                            <div class="input-group">

                                <input type="text"
                                       class="form-control bg-light"
                                       value="<?php echo $transportadorExibido['id'] ?? ''; ?>"
                                       readonly>

                                <button type="button"
                                        class="btn btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalBuscaTransportador"
                                        title="Pesquisar transportador">

                                    <i class="bi bi-search"></i>

                                </button>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                Obs.
                            </label>

                                <input type="text"
                                       class="form-control bg-light"
                                       value="<?php echo $transportadorExibido['obs'] ?? ''; ?>"
                                       readonly>
                        </div>

                    </div>


                    <!-- DADOS CADASTRAIS -->

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                Nome / Razão Social
                            </label>

                            <div class="form-control bg-light py-3 fs-6">
                                <?php echo htmlspecialchars($transportadorExibido['nome'] ?? ''); ?>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                CPF / CNPJ
                            </label>

                            <div class="form-control bg-light py-3 fs-6">
                                <?php echo htmlspecialchars($transportadorExibido['documento'] ?? ''); ?>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                Telefone
                            </label>

                            <div class="form-control bg-light py-3 fs-6">
                                <?php echo htmlspecialchars($transportadorExibido['telefone'] ?? ''); ?>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                E-mail
                            </label>

                            <div class="form-control bg-light py-3 fs-6">
                                <?php echo htmlspecialchars($transportadorExibido['email'] ?? ''); ?>
                            </div>

                        </div>

                        <div class="col-md-12">

                            <label class="form-label text-muted">
                                Endereço fixo
                            </label>

                            <div class="form-control bg-light py-3 fs-6">
                                <?php echo htmlspecialchars($transportadorExibido['endereco'] ?? ''); ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- ==================================================
             ABA VEÍCULOS
        ================================================== -->

        <div class="tab-pane fade <?php echo (($_GET['aba'] ?? '') === 'veiculos') ? 'show active' : ''; ?>"
             id="conteudo-veiculos" role="tabpanel">

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">Veículos vinculados</h5>

                    <button type="button" id="btn-novo-veiculo" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-lg"></i> Cadastrar Veículo
                    </button>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped align-middle">

                            <thead>

                                <tr>
                                    <th>Código</th>
                                    <th>Placa</th>
                                    <th>Tipo</th>
                                    <th>Ações</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php if (!empty($veiculosParaExibir)) { ?>

                                    <?php foreach ($veiculosParaExibir as $veiculo) { ?>

                                        <tr>

                                            <td>
                                                <?php echo $veiculo['id']; ?>
                                            </td>

                                            <td>
                                                <?php echo htmlspecialchars($veiculo['placa']); ?>
                                            </td>

                                            <td>
                                                <?php echo htmlspecialchars($veiculo['tipo']); ?>
                                            </td>

                                            <td>

                                                <a href="transportadores.php?matricula=<?php echo $transportadorExibido['id']; ?>&aba=veiculos&editarVeiculo=<?php echo $veiculo['id']; ?>"
                                                   class="btn btn-warning btn-sm">

                                                    <i class="bi bi-pencil-square"></i>

                                                </a>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                <?php } else { ?>

                                    <tr>

                                        <td colspan="4" class="text-center text-muted">

                                            <?php if ($transportadorExibido) { ?>

                                                Nenhum veículo vinculado a este transportador.

                                            <?php } else { ?>

                                                Selecione um transportador para visualizar seus veículos.

                                            <?php } ?>

                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        <!-- ==================================================
             ABA ENDEREÇOS
        ================================================== -->

        <div class="tab-pane fade <?php echo (($_GET['aba'] ?? '') === 'enderecos') ? 'show active' : ''; ?>"
     id="conteudo-enderecos" role="tabpanel">

    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0">Endereços vinculados</h5>
        </div>
        <div class="card-body">

            

            <?php if (!empty($enderecosParaExibir)): ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>CEP</th>
                                <th>Rua</th>
                                <th>Número</th>
                                <th>Bairro</th>
                                <th>Cidade</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($enderecosParaExibir as $endereco): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($endereco['cep']); ?></td>
                                    <td><?php echo htmlspecialchars($endereco['rua']); ?></td>
                                    <td><?php echo htmlspecialchars($endereco['numero']); ?></td>
                                    <td><?php echo htmlspecialchars($endereco['bairro']); ?></td>
                                    <td><?php echo htmlspecialchars($endereco['cidade']); ?></td>
                                    <td><?php echo htmlspecialchars($endereco['estado']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>

                <p class="text-muted text-center py-3">
                    Nenhum endereço cadastrado para este transportador.
                </p>

            <?php endif; ?>

        </div>
    </div>
</div>


        <!-- ==================================================
             ABA FRETES
        ================================================== -->
        <div class="tab-pane fade <?php echo (($_GET['aba'] ?? '') === 'fretes') ? 'show active' : ''; ?>"
     id="conteudo-fretes" role="tabpanel">

        
        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">Fretes vinculados</h5>
            </div>

            <div class="card-body">

                <?php if (!$transportadorExibido) { ?>

                    <p class="text-center text-muted mb-0">
                        Selecione um transportador para visualizar seus fretes.
                    </p>

                <?php } elseif (!empty($fretesParaExibir)) { ?>

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped align-middle">

                            <thead>
                                <tr>
                                    <th>ID</th>
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

                                <?php foreach ($fretesParaExibir as $frete) { ?>

                                    <?php

                                    $placaVeiculo = 'Não encontrado';

                                    foreach ($_SESSION['veiculos'] as $veiculo) {
                                        if ((int)$veiculo['id'] === (int)$frete['veiculo']) {
                                            $placaVeiculo = $veiculo['placa'];
                                            break;
                                        }
                                    }

                                    $origemFrete = 'Não encontrado';

                                    foreach ($_SESSION['endereco'] as $endereco) {
                                        if ((int)$endereco['id'] === (int)$frete['origem']) {
                                            $origemFrete = ($endereco['rua'] ?? '') . ', ' .
                                                ($endereco['numero'] ?? '') . ' - ' .
                                                ($endereco['cidade'] ?? '');
                                            break;
                                        }
                                    }

                                    $destinoFrete = 'Não encontrado';

                                    foreach ($_SESSION['endereco'] as $endereco) {
                                        if ((int)$endereco['id'] === (int)$frete['destino']) {
                                            $destinoFrete = ($endereco['rua'] ?? '') . ', ' .
                                                ($endereco['numero'] ?? '') . ' - ' .
                                                ($endereco['cidade'] ?? '');
                                            break;
                                        }
                                    }

                                    ?>

                                    <tr>

                                        <td><?php echo (int)$frete['id']; ?></td>

                                        <td><?php echo htmlspecialchars($placaVeiculo); ?></td>

                                        <td><?php echo htmlspecialchars($origemFrete); ?></td>

                                        <td><?php echo htmlspecialchars($destinoFrete); ?></td>

                                        <td>
                                            <?php echo !empty($frete['dataFrete'])
                                                ? date('d/m/Y', strtotime($frete['dataFrete']))
                                                : 'Não informada'; ?>
                                        </td>

                                        <td>
                                            <?php echo !empty($frete['prazoChegada'])
                                                ? date('d/m/Y', strtotime($frete['prazoChegada']))
                                                : 'Não informado'; ?>
                                        </td>

                                        <td>
                                            R$ <?php echo number_format((float)$frete['valor'], 2, ',', '.'); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($frete['status'] ?? 'Programado'); ?>
                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                <?php } else { ?>

                    <p class="text-center text-muted mb-0">
                        Nenhum frete vinculado a este transportador.
                    </p>

                <?php } ?>

            </div>

        </div>

        </div>


        <!-- ==================================================
             ABA DOCUMENTOS
        ================================================== -->

        <div class="tab-pane fade <?php echo (($_GET['aba'] ?? '') === 'documentos') ? 'show active' : ''; ?>"
             id="conteudo-documentos" role="tabpanel">

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">Documentos cadastrados</h5>

                    <input type="file" id="input-documento" hidden
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.csv">

                    <button type="button" id="btn-novo-documento" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Novo Documento
                    </button>

                </div>

                
                <div class="card-body">

                    <?php
                    $documentos = $transportadorExibido['documentos'] ?? [];
                    ?>

                    <?php if (!$transportadorExibido) { ?>

                        <p class="text-center text-muted mb-0">
                            Selecione um transportador para continuar.
                        </p>

                    <?php } elseif (empty($documentos)) { ?>

                        <p class="text-muted mb-0">
                            Nenhum documento anexado a este transportador.
                        </p>

                    <?php } else { ?>

                        <div class="list-group">

                            <?php foreach ($documentos as $documento) { ?>

                                <div class="list-group-item d-flex justify-content-between align-items-center">

                                    <div>
                                        <i class="bi bi-file-earmark-text me-2"></i>

                                        <?php echo htmlspecialchars($documento['nome']); ?>
                                    </div>

                                    <a href="../documentos/<?php echo rawurlencode($documento['arquivo']); ?>"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Abrir documento">

                                        <i class="bi bi-download"></i>

                                    </a>

                                </div>

                            <?php } ?>

                        </div>

                    <?php } ?>

                </div>


            </div>

        </div>

    </div>

</div>
