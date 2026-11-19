
<?php
// Inicializa as listas caso ainda não existam na sessão.
$_SESSION['transportadores'] = $_SESSION['transportadores'] ?? [];
$_SESSION['veiculos'] = $_SESSION['veiculos'] ?? [];

// Inicializa a variável que guardará o transportador selecionado.
$transportadorEdicao = null;

// Verifica se algum transportador foi selecionado para edição.
if (isset($_GET['editar'])) {

    // Recebe o ID enviado pela URL.
    $idEdicao = (int)$_GET['editar'];

    // Percorre os transportadores cadastrados.
    foreach ($_SESSION['transportadores'] as $transportador) {

        // Compara o ID selecionado com o ID de cada registro.
        if ($transportador['id'] == $idEdicao) {

            // Guarda os dados do transportador encontrado.
            $transportadorEdicao = $transportador;

            break;
        }
    }
}

// Inicializa a variável que guardará o veículo selecionado.
$veiculoEdicao = null;

// Verifica se algum veículo foi selecionado.
if (isset($_GET['editarVeiculo'])) {

    // Recebe o ID.
    $idEdicao = (int)$_GET['editarVeiculo'];

    foreach ($_SESSION['veiculos'] as $veiculo) {

        // Verifica se encontrou o veículo selecionado.
        if ($veiculo['id'] == $idEdicao) {

            // Guarda os dados.
            $veiculoEdicao = $veiculo;

            break;
        }
    }
}

    // Inicializa as variáveis da pesquisa.
    $termoPesquisa = trim($_GET['pesquisa'] ?? '');

    $transportadorEncontrado = null;
    $veiculosEncontrados = [];

    // Verifica se foi realizada uma pesquisa.
    if ($termoPesquisa != '') {

        // Procura o transportador pelo ID, nome ou CPF/CNPJ.
        if (!empty($_SESSION['transportadores'])) {

            foreach ($_SESSION['transportadores'] as $transportador) {

                if (
                    (string)$transportador['id'] == $termoPesquisa ||
                    stripos($transportador['nome'], $termoPesquisa) !== false ||
                    stripos($transportador['documento'], $termoPesquisa) !== false
                ) {

                    $transportadorEncontrado = $transportador;

                    break;
                }
            }
        }

        // Procura veículos pela placa.
        if (!empty($_SESSION['veiculos'])) {

            foreach ($_SESSION['veiculos'] as $veiculo) {

                if (stripos($veiculo['placa'], $termoPesquisa) !== false) {

                    $veiculosEncontrados[] = $veiculo;
                }
            }
        }
    }

    // Define qual transportador será exibido na ficha.
        $transportadorExibido = $transportadorEncontrado;

        // Se a pesquisa foi feita pela placa, procura o transportador vinculado.
        if ($transportadorExibido === null && !empty($veiculosEncontrados)) {

            // Identifica os transportadores dos veículos encontrados.
            $idsTransportadores = [];

            foreach ($veiculosEncontrados as $veiculo) {
                $idsTransportadores[$veiculo['transportador']] = true;
            }

            // Só preenche a ficha se todos os veículos encontrados
            // pertencerem ao mesmo transportador.
            if (count($idsTransportadores) === 1) {

                foreach ($_SESSION['transportadores'] as $transportador) {

                    if ($transportador['id'] == array_key_first($idsTransportadores)) {

                        $transportadorExibido = $transportador;

                        break;
                    }
                }
            }
        }

        // Define os veículos que serão exibidos na tabela.
        $veiculosParaExibir = [];

        if ($transportadorEncontrado !== null) {

            // Pesquisa por transportador: mostra todos os veículos vinculados.
            foreach ($_SESSION['veiculos'] as $veiculo) {

                if ($veiculo['transportador'] == $transportadorEncontrado['id']) {
                    $veiculosParaExibir[] = $veiculo;
                }
            }

        } else {

            // Pesquisa por placa: mostra os veículos encontrados.
            $veiculosParaExibir = $veiculosEncontrados;
        }

?>

<!-- CONTEÚDO PRINCIPAL -->
<div class="container mt-3 pagina-transportadores">

    <?php if (isset($_GET['erro']) && $_GET['erro'] == 'veiculos') { ?>

        <div class="modal fade" id="modalErroExclusao" tabindex="-1"
             aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-sm">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title text-danger">
                            Exclusão não permitida
                        </h5>

                        <button type="button" class="btn-close"
                                data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        Este transportador possui veículos vinculados.
                        Remova ou transfira os veículos antes de continuar.
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">
                            Entendi
                        </button>
                    </div>

                </div>

            </div>
        </div>

    <?php } ?>

    <!-- TÍTULO E BOTÕES -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2 class="mb-0">Transportadores</h2>

        <div class="d-flex gap-2">

            <button type="button" id="btn-novo-veiculo" class="btn btn-outline-primary">
                <i class="bi bi-plus-lg"></i> Cadastrar Veículo
            </button>

            <button type="button" id="btn-novo-transportador" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Novo Transportador
            </button>

        </div>

    </div>

    <!-- MODAL DO FORMULÁRIO DE TRANSPORTADORES -->
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

                        <button type="button" class="btn-close"
                                data-bs-dismiss="modal" aria-label="Fechar"></button>

                    </div>

                    <div class="modal-body">

                        <?php if ($transportadorEdicao) { ?>

                            <input type="hidden" name="pesquisa" value="<?php echo htmlspecialchars($_GET['pesquisa'] ?? ''); ?>">

                            <input type="hidden" name="acao" value="atualizar">
                            <input type="hidden" name="id" value="<?php echo $transportadorEdicao['id']; ?>">

                        <?php } ?>

                        <div class="mb-3">

                            <label for="nome" class="form-label">
                                Razão Social / Nome
                            </label>

                            <input type="text" name="nome" id="nome" class="form-control" value="<?php echo htmlspecialchars($transportadorEdicao['nome'] ?? ''); ?>"required>

                        </div>

                        <div class="mb-3">

                            <label for="documento" class="form-label">
                                CPF / CNPJ
                            </label>

                            <input type="text" name="documento" id="documento" class="form-control" value="<?php echo htmlspecialchars($transportadorEdicao['documento'] ?? ''); ?>"required>

                        </div>

                        <div class="mb-3">

                            <label for="telefone" class="form-label">
                                Telefone
                            </label>

                            <input type="text" name="telefone" id="telefone" class="form-control" value="<?php echo htmlspecialchars($transportadorEdicao['telefone'] ?? ''); ?>"required>

                        </div>

                        <div class="mb-3">

                            <label for="email" class="form-label">
                                E-mail
                            </label>

                            <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($transportadorEdicao['email'] ?? ''); ?>"required>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">
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

    
<!-- MODAL DE CADASTRO DE VEÍCULOS -->
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

                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Fechar"></button>

                </div>

                <div class="modal-body">

                    <?php if ($veiculoEdicao) { ?>

                        <!-- Identifica que o formulário está realizando uma edição. -->
                        <input type="hidden" name="acao" value="atualizarVeiculo">

                        <!-- Identifica qual veículo será atualizado. -->
                        <input type="hidden" name="id" value="<?php echo $veiculoEdicao['id']; ?>">


                        <input type="hidden" name="pesquisa" value="<?php echo htmlspecialchars($_GET['pesquisa'] ?? ''); ?>">

                    <?php } ?>

                    <div class="mb-3">

                        <label for="transportador" class="form-label">
                            Transportador
                        </label>

                        <!-- Selecionar o transportador -->
                        <select name="transportador" id="transportador" class="form-select">

                            <option value="">Selecione o transportador</option>

                            <?php

                            // Verifica se tem transportadores já cadastrados.
                            if (!empty($_SESSION['transportadores'])) {

                                // Percorre todos os transportadores cadastrados.
                                foreach ($_SESSION['transportadores'] as $transportador) {

                            ?>

                                    <!-- Exibe cada transportador como uma opção. -->
                                    <option value="<?php echo $transportador['id']; ?>"
                                        <?php
                                        if (($veiculoEdicao['transportador'] ?? '') == $transportador['id']) {
                                            echo 'selected';
                                        }
                                        ?>
                                    >
                                        <?php echo htmlspecialchars($transportador['nome']); ?>
                                    </option>

                            <?php

                                }

                            } else {

                            ?>

                                <option value="">Nenhum transportador cadastrado</option>

                            <?php

                            }

                            ?>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label for="placa" class="form-label">
                            Placa
                        </label>

                        <input type="text" name="placa" id="placa" class="form-control" value="<?php echo htmlspecialchars($veiculoEdicao['placa'] ?? ''); ?>" required>

                    </div>

                    <div class="mb-3">

                        <label for="tipoModelo" class="form-label">
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

                        <a href="transportadores.php?pesquisa=<?php echo urlencode($_GET['pesquisa'] ?? ''); ?>"
                           class="btn btn-secondary">
                            Cancelar
                        </a>

                    <?php } else { ?>

                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">
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


<!-- CONSULTA INTEGRADA DE TRANSPORTADORES E VEÍCULOS -->

<!-- CAMPO DE PESQUISA -->
<div class="card mb-4">

    <div class="card-header">
        <h5 class="mb-0">Consultar Transportadores e Veículos</h5>
    </div>

    <div class="card-body">

        <form method="get" action="transportadores.php">

            <div class="input-group">

                <input
                    type="text"
                    name="pesquisa"
                    class="form-control"
                    placeholder="Digite o ID, nome, CPF/CNPJ ou placa"
                    value="<?php echo htmlspecialchars($termoPesquisa); ?>"
                >

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Pesquisar
                </button>

                <a href="transportadores.php" class="btn btn-outline-secondary">
                    Limpar
                </a>

            </div>

        </form>

    </div>
</div>

<!-- ABAS DE NAVEGAÇÃO -->
<ul class="nav nav-tabs mb-3" id="abasTransportadores" role="tablist">

    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="aba-transportadores"data-bs-toggle="tab" data-bs-target="#conteudo-transportadores"type="button"role="tab">
            <i class="bi bi-person"></i> Transportadores
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"id="aba-veiculos" data-bs-toggle="tab"data-bs-target="#conteudo-veiculos"type="button"role="tab">
            <i class="bi bi-truck"></i> Veículos
        </button>
    </li>

</ul>

<div class="tab-content" id="conteudoAbas">

<div class="tab-pane fade show active"id="conteudo-transportadores"role="tabpanel">

<!-- FICHA CADASTRAL DO TRANSPORTADOR -->
<div class="card mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">Dados do Transportador</h5>

        <?php if ($transportadorExibido) { ?>

            <a href="transportadores.php?pesquisa=<?php echo urlencode($termoPesquisa); ?>&editar=<?php echo $transportadorExibido['id']; ?>"
               class="btn btn-outline-primary btn-sm">

                <i class="bi bi-pencil-square"></i> Editar cadastro

            </a>

        <?php } ?>

    </div>



    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-6">

                <label class="form-label text-muted">Nome / Razão Social</label>

                <div class="form-control bg-light py-3 fs-6">
                    <?php echo htmlspecialchars($transportadorExibido['nome'] ?? ''); ?>
                </div>

            </div>

            <div class="col-md-6">

                <label class="form-label text-muted">CPF / CNPJ</label>

                <div class="form-control bg-light py-3 fs-6">
                    <?php echo htmlspecialchars($transportadorExibido['documento'] ?? ''); ?>
                </div>

            </div>

            <div class="col-md-6">

                <label class="form-label text-muted">Telefone</label>

                <div class="form-control bg-light py-3 fs-6">
                    <?php echo htmlspecialchars($transportadorExibido['telefone'] ?? ''); ?>
                </div>

            </div>

            <div class="col-md-6">

                <label class="form-label text-muted">E-mail</label>

                <div class="form-control bg-light py-3 fs-6">
                    <?php echo htmlspecialchars($transportadorExibido['email'] ?? ''); ?>
                </div>

            </div>

            <div class="mb-3">
                <label for="endereco" class="form-label">
                    Endereço
                </label>

                <input
                    type="text"
                    name="endereco"
                    id="endereco"
                    class="form-control"
                    value="<?php echo htmlspecialchars($transportadorEdicao['endereco'] ?? ''); ?>"
                >
            </div>

        </div>

        

        <?php if ($termoPesquisa != '' && !$transportadorExibido) { ?>

            <p class="text-muted mt-3 mb-0">
                Nenhum transportador correspondente encontrado.
            </p>

        <?php } ?>

    </div>

</div>
</div>

<div class="tab-pane fade" id="conteudo-veiculos" role="tabpanel">

<!-- VEÍCULOS VINCULADOS -->
<div class="card mb-4">

    <div class="card-header">
        <h5 class="mb-0">Veículos vinculados</h5>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

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

                        <?php foreach ($veiculosParaExibir as $veiculo) {

                            $nomeTransportador = 'Não encontrado';
                            $documentoTransportador = '';

                            foreach ($_SESSION['transportadores'] as $transportador) {

                                if ($transportador['id'] == $veiculo['transportador']) {

                                    $nomeTransportador = $transportador['nome'];
                                    $documentoTransportador = $transportador['documento'];

                                    break;
                                }
                            }
                        ?>

                            <tr>

                                <td>
                                    <?php echo $veiculo['id']; ?>
                                </td>

                                <td>

                                    <a href="transportadores.php?pesquisa=<?php echo urlencode($termoPesquisa); ?>&editarVeiculo=<?php echo $veiculo['id']; ?>"
                                       class="text-decoration-none fw-semibold">

                                        <?php echo htmlspecialchars($veiculo['placa']); ?>

                                    </a>

                                </td>

                                <td>
                                    <?php echo htmlspecialchars($veiculo['tipo']); ?>
                                </td>

                                <?php if ($transportadorEncontrado === null && !empty($veiculosEncontrados)) { ?>

                                

                                <?php } ?>

                                <td>

                                    <a href="transportadores.php?pesquisa=<?php echo urlencode($termoPesquisa); ?>&editarVeiculo=<?php echo $veiculo['id']; ?>"
                                       class="btn btn-warning btn-sm">

                                        <i class="bi bi-pencil-square"></i> Editar

                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>

                            <td colspan="5" class="text-center text-muted">

                                <?php if ($termoPesquisa == '') { ?>

                                    Nenhum veículo carregado.

                                <?php } else { ?>

                                    Nenhum veículo encontrado.

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
</div>


</div>