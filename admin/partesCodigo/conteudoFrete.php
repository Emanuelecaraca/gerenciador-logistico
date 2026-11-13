<main class="fretes">

    <h2>Fretes</h2>

    <p>Cadastro e consulta de fretes</p>


    <!-- CADASTRO DE FRETE -->
    <div class="card mb-4">

        <div class="card-header">

            <h5>Cadastrar Frete</h5>

        </div>

        <div class="card-body">

            <form method="post">

                <div class="mb-3">
                    <label for="produto" class="form-label">Produto</label>
                        <select name="produto" id="produto" class="form-select">
                            <option value="">Selecione o produto</option>
                        </select>

                </div>  

                <div class="mb-3">
                    <label for="transportador" class="form-label">Transportador</label>
                    <select name="transportador" id="transportador" class="form-select">
                        <option value="">Selecione o transportador</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="origem" class="form-label">Origem</label>
                    <select name="origem" id="origem" class="form-select">
                        <option value="">Selecione a origem</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="destino" class="form-label">Destino</label>
                    <select name="destino" id="destino" class="form-select">
                        <option value="">Selecione o destino</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="dataFrete" class="form-label">Data do Frete</label>
                    <input type="date" name="dataFrete" id="dataFrete" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">
                    Cadastrar Frete
                </button>
            </form>


    <!-- CONSULTA DE FRETE -->

    <div class="card">
        <div class="card-header">
            <h5>Consultar Fretes</h5>
        </div>

        <div class="card-body">

            <form method="post">

                <div class="mb-3">
                    <label for="filtroData" class="form-label">Data</label>
                    <input type="date" name="filtroData" id="filtroData" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="filtroTransportador" class="form-label">Transportador</label>
                    <select name="filtroTransportador" id="filtroTransportador" class="form-select">
                        <option value="">Selecione o transportador</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-secondary">
                    Pesquisar
                </button>

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
                            <th>Data</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td colspan="8" class="text-center">
                                Nenhum frete encontrado.
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <a href="home.php">Voltar</a>


</main>
