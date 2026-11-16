
<main>
    <h2>Painel principal</h2>

    <p>Selecione uma opção no menu para começar.</p>

    <h3>Atalhos</h3>

    <ul>
        <li><a href="fretes.php">Cadastrar frete</a></li>
        <li><a href="fretes.php">Consultar fretes</a></li>
        <li><a href="transportadores.php">Consultar transportadores</a></li>
    </ul>

    <!-- ACESSO RÁPIDO AO RELATÓRIO -->
    <div class="card mt-4 mb-4" style="max-width: 420px;">

        <div class="card-header">
            <h5>Relatório de Fretes</h5>
        </div>

        <div class="card-body">

            <form action="relatorios.php" method="get">

                <div class="mb-3">
                    <label for="dataRelatorioHome" class="form-label">
                        Data do relatório
                    </label>

                    <input type="date"name="dataRelatorio"id="dataRelatorioHome"class="form-control"required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Consultar relatório
                </button>

            </form>

        </div>
    </div>
</main>
