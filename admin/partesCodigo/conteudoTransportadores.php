<main class="transportadores">

    <h2>Transportadores</h2>

    <p>Cadastro e consulta de transportadores e veículos</p>


    <!-- cadastro do transportador -->
    <div class="card mb-4">

        <div class="card-header">
            <h5>Cadastrar Transportador</h5>
        </div>

        <div class="card-body">

            <form method="post" action="../php/transportadores.php">

                <div class="mb-3">

                    <label for="nome" class="form-label">
                        Razão Social / Nome
                    </label>

                    <input type="text" name="nome" id="nome" class="form-control">
                    
                </div>

                <div class="mb-3">
                    
                    <label for="documento" class="form-label">
                        CPF / CNPJ
                    </label>

                    <input type="text" name="documento" id="documento" class="form-control">
                    
                </div>

                <div class="mb-3">
                    
                    <label for="telefone" class="form-label">
                        Telefone
                    </label>

                    <input type="text" name="telefone" id="telefone" class="form-control">
                    
                </div>

                <div class="mb-3">
                    
                    <label for="email" class="form-label">
                        E-mail
                    </label>

                    <input type="email" name="email" id="email" class="form-control">
                    
                </div>

                <button type="submit" class="btn btn-primary">
                    Cadastrar Transportador
                </button>
                
            </form>
            
        </div>

    </div>


<!-- CADASTRO VEICULO -->
<div class="card mb-4">

    <div class="card-header">
        <h5>Cadastrar Veículo</h5>
    </div>

    <div class="card-body">

        <form method="post">

            <div class="mb-3">

                <label for="transportador" class="form-label">
                    Transportador
                </label>

                <!-- selecionar o transportador -->
                <select name="transportador" id="transportador" class="form-select">

                    <option value="">Selecione o transportador</option>

                </select>
                
            </div>

            <div class="mb-3">
                
                <label for="placa" class="form-label">
                    Placa
                </label>
                
                <input type="text" name="placa" id="placa" class="form-control">
            </div>

             <div class="mb-3">
                
                <label for="modelo" class="form-label">
                    Modelo
                </label>
                
                <input type="text" name="modelo" id="modelo" class="form-control">
            </div>
            
            <div class="mb-3">
                
                <label for="tipoModelo" class="form-label">
                    Tipo de veículo
                </label>
                
                <select name="tipo" id="tipo" class="form-select">

                        <option value="">Selecione o tipo</option>
                        <option value="Caminhão">Caminhão</option>
                        <option value="Carreta">Carreta</option>
                        <option value="Van">Van</option>
                        <option value="Utilitário">Utilitário</option>
                        <option value="Outro">Outro</option>

                </select>

            </div>

            <button type="submit" class="btn btn-primary">
                Cadastrar Veículo
            </button>

        </form>
        
    </div>
    
</div>


<!-- CARD DE CONSULTA DE TRANSPORTADORES -->
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

                             
                            <td>Ações</td>

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

</main>