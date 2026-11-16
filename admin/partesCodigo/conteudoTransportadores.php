
<?php

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

?>


<main class="transportadores">

    <h2>Transportadores</h2>

    <p>Cadastro e consulta de transportadores e veículos</p>


    <!-- cadastro do transportador -->
    <div class="card mb-4">

        <div class="card-header">
            <h5>
                <?php echo $transportadorEdicao ? 'Editar Transportador' : 'Cadastrar Transportador'; ?>
            </h5>
        </div>

        <div class="card-body">

            <form method="post" action="<?php echo $transportadorEdicao ? 'transportadores.php' : '../php/transportadores.php'; ?>"


                <?php if ($transportadorEdicao) { ?>
                    <input type="hidden" name="acao" value="atualizar">
                    <input type="hidden" name="id" value="<?php echo $transportadorEdicao['id']; ?>">
                <?php } ?>

                <div class="mb-3">

                    <label for="nome" class="form-label">
                        Razão Social / Nome
                    </label>

                    <input type="text" name="nome" id="nome" class="form-control"  value="<?php echo htmlspecialchars($transportadorEdicao['nome'] ?? ''); ?>"required>
                    
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

                <button type="submit" class="btn btn-primary">
                    <?php echo $transportadorEdicao ? 'Salvar Alterações' : 'Cadastrar Transportador'; ?>
                </button>

                <?php if ($transportadorEdicao) { ?>

                    <a href="transportadores.php" class="btn btn-secondary">
                        Cancelar
                    </a>

                <?php } ?>
                
            </form>
            
        </div>

    </div>


<!-- CADASTRO VEICULO -->
<div class="card mb-4">

    <div class="card-header">
        <h5>
            <?php echo $veiculoEdicao ? 'Editar Veículo' : 'Cadastrar Veículo'; ?>
        </h5>
    </div>

    <div class="card-body">

        <form method="post"     action="<?php echo $veiculoEdicao ? 'transportadores.php' : '../php/veiculos.php'; ?>">
            <?php if ($veiculoEdicao) { ?>

                <!-- Identifica que o formulário está realizando uma edição. -->
                <input type="hidden" name="acao" value="atualizarVeiculo">

                <!-- Identifica qual veículo será atualizado. -->
                <input type="hidden" name="id" value="<?php echo $veiculoEdicao['id']; ?>">

            <?php } ?>

            <div class="mb-3">

                <label for="transportador" class="form-label">
                    Transportador
                </label>

                <!-- selecionar o transportador -->
                <select name="transportador" id="transportador" class="form-select">

                    <option value="">Selecione o transportador</option>

                    <?php

                        // Verifica se tem transportadores já cadastrados.
                        if (!empty($_SESSION['transportadores'])) {

                            // Percorre todos os transportadores cadastrados.
                            foreach ($_SESSION['transportadores'] as $transportador) {

                    ?>  <!-- FECHA PRA ESCREVER HTML -->

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

                    <?php // ABRE PRA VOLTAR A LÓGICA
                            }

                        }else {

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
                
                <input type="text" name="placa" id="placa" class="form-control" value="<?php echo htmlspecialchars($veiculoEdicao['placa'] ?? ''); ?>"required>
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

            <button type="submit" class="btn btn-primary">
                <?php echo $veiculoEdicao ? 'Salvar Alterações' : 'Cadastrar Veículo'; ?>
            </button>

            <?php if ($veiculoEdicao) { ?>

                <a href="transportadores.php" class="btn btn-secondary">
                    Cancelar
                </a>

            <?php } ?>

        </form>
        
    </div>
    
</div>


<!--CONSULTA DE TRANSPORTADORES -->
<div class="card mb-4">

    <!-- Cabeçalho -->
    <div class="card-header">
        <h5>Consultar Transportadores</h5>
    </div>

    <!-- Corpo-->
    <div class="card-body">

        <!-- pesquisar transportadores -->
        <form method="post">

            <!-- Campo para pesquisar nome -->
            <div class="mb-3">

                <label for="filtroNome" class="form-label">
                    Nome do Transportador
                </label>

                <input type="text" name="filtroNome" id="filtroNome" class="form-control">

            </div>

            <button type="submit" class="btn btn-secondary">
                Pesquisar
            </button>

        </form>

        <!-- Linha divisória -->
        <hr>

        <!-- rolagem em telas menores -->
        <div class="table-responsive">

            <!-- Tabela transportadores cadastrados -->
            <table class="table table-bordered table-striped">

                <!-- Cabeçalho da tabela -->
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome / Razão Social</th>
                        <th>CPF / CNPJ</th>
                        <th>Telefone</th>
                        <th>E-mail</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <!-- Corpo tabela -->
                <tbody>

                    <?php

                        // Verifica se já existe algum transportador.
                        if (!empty($_SESSION['transportadores'])) {

                            // Percorre a lista de transportadores cadastrados.
                            foreach ($_SESSION['transportadores'] as $transportador) {

                    ?>

                        <!-- Cria uma linha para cada transportador encontrado -->
                        <tr>

                            
                            <td><?php echo $transportador['id']; ?></td>
                            <td><?php echo htmlspecialchars($transportador['nome']); ?></td>
                            <td><?php echo htmlspecialchars($transportador['documento']); ?></td>
                            <td><?php echo htmlspecialchars($transportador['telefone']); ?></td>
                            <td><?php echo htmlspecialchars($transportador['email']); ?></td>
                            <td>
                                <a href="transportadores.php?editar=<?php echo $transportador['id']; ?>"class="btn btn-warning btn-sm">
                                    Editar
                                </a>

                                <a href="../php/excluirTransportador.php?id=<?php echo $transportador['id']; ?>"class="btn btn-danger btn-sm"onclick="return confirm('Tem certeza que deseja excluir este transportador?');">
                                    Excluir
                                </a>
                            </td>

                        </tr>

                    <?php

                            }

                        } else {

                            // Exibe uma mensagem caso ainda não existam transportadores.
                    ?>

                            <tr>
                                <td colspan="6" class="text-center">
                                    Nenhum transportador encontrado.
                                </td>
                            </tr>

                    <?php

                        }

                    ?>

                </tbody>

            </table>

        </div>

    </div>

</div>



<!-- CONSULTA DE VEÍCULOS -->
<div class="card mb-4">

    <div class="card-header">
        <h5>Consultar Veículos</h5>
    </div>

    <div class="card-body">
        <!-- Formulário de pesquisa de veículos -->
        <form method="get" class="mb-3">

            <!-- Campo de pesquisa pela placa -->
            <div class="mb-3">

                <label for="filtroPlaca" class="form-label">
                    Pesquisar pela placa
                </label>

                <input type="text"name="filtroPlaca"id="filtroPlaca"class="form-control" placeholder="Digite a placa do veículo" value="<?php echo htmlspecialchars($_GET['filtroPlaca'] ?? ''); ?>" >

            </div>

            <!-- Seleção de transportador -->
            <div class="mb-3">

                <label for="filtroTransportador" class="form-label">
                    Transportador
                </label>

                <select
                    name="filtroTransportador"
                    id="filtroTransportador"
                    class="form-select"
                >

                    <option value="">Todos os transportadores</option>

                    <?php

                        // Percorre os transportadores cadastrados.
                        foreach ($_SESSION['transportadores'] as $transportador) {

                    ?>

                       
                    <option
                        value="<?php echo $transportador['id']; ?>"
                        <?php
                            if (($_GET['filtroTransportador'] ?? '') == $transportador['id']) {
                                echo 'selected';
                            }
                        ?>
                    >
                        <?php echo htmlspecialchars($transportador['nome']); ?>
                    </option>


                    <?php

                        }

                    ?>

                </select>

            </div>

            <button type="submit" class="btn btn-secondary" >
                Pesquisar
            </button>

            <a href="transportadores.php" class="btn btn-outline-secondary">
                Limpar pesquisa
            </a>

        </form>

        <!-- Tabela de veículos cadastrados -->
        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <!-- Cabeçalho da tabela -->
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Transportador</th>
                        <th>Placa</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <!-- Corpo da tabela -->
                <tbody>

                    <?php

                        // Recebe os filtros enviados pelo formulário.
                        $filtroPlaca = trim($_GET['filtroPlaca'] ?? '');
                        $filtroTransportador = trim($_GET['filtroTransportador'] ?? '');

                        // Verifica se existem veículos cadastrados.
                        if (!empty($_SESSION['veiculos'])) {

                            // Percorre todos os veículos cadastrados.
                            foreach ($_SESSION['veiculos'] as $veiculo) {

                                // Procura o nome do transportador vinculado.
                                $nomeTransportador = '';

                                // Procura o transportador vinculado ao veículo.
                                foreach ($_SESSION['transportadores'] as $transportador) {

                                    if ($transportador['id'] == $veiculo['transportador']) {

                                        $nomeTransportador = $transportador['nome'];

                                        break;
                                    }
                                }

                            // Verifica se a placa corresponde à pesquisa.
                            $placaCorresponde = (
                                $filtroPlaca == '' ||
                                stripos($veiculo['placa'], $filtroPlaca) !== false
                            );
                            // Verifica se o transportador corresponde à pesquisa.
                                    $transportadorCorresponde = (
                                        $filtroTransportador == '' ||
                                        $filtroTransportador == $veiculo['transportador']
                                    );

                                    // Exibe somente os veículos que correspondem aos filtros.
                                    if ($placaCorresponde && $transportadorCorresponde) {

                                        $encontrou = true;

                        ?>

                        <!--Exibe os dados de cada veículo -->
                        <tr>
                            <td><?php echo $veiculo['id']; ?></td>
                            <td><?php echo htmlspecialchars($nomeTransportador); ?></td>
                            <td><?php echo htmlspecialchars($veiculo['placa']); ?></td>
                            <td><?php echo htmlspecialchars($veiculo['tipo']); ?></td>
                            <td>
                                <a href="transportadores.php?editarVeiculo=<?php echo $veiculo['id']; ?>"class="btn btn-warning btn-sm">
                                    Editar
                                </a>

                                <a href="../php/excluirVeiculo.php?id=<?php echo $veiculo['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este veículo?');">
                                    Excluir
                                </a>
                            </td>
                        </tr>

                    <?php

                            }

                        } 

                         // Caso nenhum veículo corresponda à pesquisa.
                        if (!$encontrou) {

                    ?>

                    <tr>
                        <td colspan="5" class="text-center">
                            Nenhum veículo encontrado.
                        </td>
                    </tr>



                    <?php

                        }

                       } else {

                    ?>

                        <tr>
                            <td colspan="5" class="text-center">
                                Nenhum veículo cadastrado.
                            </td>
                        </tr>

                    <?php

                        }

                    ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</main>