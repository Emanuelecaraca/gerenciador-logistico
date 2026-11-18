
<main>
    <section class="boas-vindas">

        <div class="texto-boas-vindas">

            <h2>Olá, Admin!</h2>

            <h3>Bem-vindo ao sistema de gerenciamento logístico.</h3>

            <p>
                Gerencie seus produtos, endereços e transportes
                de forma simples e eficiente.
            </p>

        </div>

        
    </section>

    <?php
        // Recupera os registros armazenados na sessão.
        $produtos = $_SESSION['produtos'] ?? [];
        $transportadores = $_SESSION['transportadores'] ?? [];
        $enderecos = $_SESSION['endereco'] ?? [];
        $fretes = $_SESSION['fretes'] ?? [];

        // Conta a quantidade de registros cadastrados.
        $totalProdutos = count($produtos);
        $totalTransportadores = count($transportadores);
        $totalEnderecos = count($enderecos);
        $totalFretes = count($fretes);
    ?>

    <section class="indicadores">

        <div class="card-indicador">
            <i class="bi-box-seam-fill"></i>
            <div>
                <h2><?= $totalProdutos ?></h2>
                <p>Produtos cadastrados</p>
            </div>
        </div>

        <div class="card-indicador">
            <i class="bi bi-truck"></i>
            <div>
                <h2><?= $totalTransportadores ?></h2>
                <p>Transportadores cadastrados</p>
            </div>
        </div>

        <div class="card-indicador">
            <i class="bi bi-geo-alt-fill"></i>
            <div>
                <h2><?= $totalEnderecos ?></h2>
                <p>Endereços cadastrados</p>
            </div>
        </div>

        <div class="card-indicador">
            <i class=" bi-file-earmark-text-fill"></i>
            <div>
                <h2><?= $totalFretes ?></h2>
                <p>Fretes cadastrados</p>
            </div>
        </div>

    </section>

    <?php
        // Recupera os fretes cadastrados na sessão.
        $fretesRecentes = $_SESSION['fretes'] ?? [];

        // Organiza os fretes do mais recente para o mais antigo.
        $fretesRecentes = array_reverse($fretesRecentes);

        // Limita a exibição aos últimos 5 fretes.
        $fretesRecentes = array_slice($fretesRecentes, 0, 5);
    
        // Recupera os cadastros das sessões.
        $listaTransportadores = $_SESSION['transportadores'] ?? [];
        $listaEnderecos = $_SESSION['endereco'] ?? [];
        $listaProdutos = $_SESSION['produtos'] ?? [];
    ?>

<div class="painel-inferior">

    <section class="ultimos-fretes">
        <h3>Últimos Fretes</h3>

        <div class="tabela-fretes">

            <table>
                <thead>
                    <tr>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Transportador</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        // Liga cada status do frete a uma classe do CSS (cada uma tem uma cor)
                        $classesStatus = [
                            'Pendente'    => 'status-pendente',
                            'Em trânsito' => 'status-transito',
                            'Concluído'   => 'status-concluido'
                        ];
                    ?>
                    <?php foreach ($fretesRecentes as $frete): ?>

                        <?php
                        // Localiza o transportador pelo ID.
                        $nomeTransportador = 'Não encontrado';

                        foreach ($listaTransportadores as $transportador) {
                            if ($transportador['id'] == $frete['transportador']) {
                                $nomeTransportador = $transportador['nome'];
                                break;
                            }
                        }

                        // Localiza a cidade de origem.
                        $cidadeOrigem = 'Não encontrada';

                        foreach ($listaEnderecos as $endereco) {
                            if ($endereco['id'] == $frete['origem']) {
                                $cidadeOrigem = $endereco['cidade'];
                                break;
                            }
                        }

                        // Localiza a cidade de destino.
                        $cidadeDestino = 'Não encontrada';

                        foreach ($listaEnderecos as $endereco) {
                            if ($endereco['id'] == $frete['destino']) {
                                $cidadeDestino = $endereco['cidade'];
                                break;
                            }
                        }
                        
                        ?>



                        <tr>
                            <td><?= htmlspecialchars($cidadeOrigem) ?></td>
                            <td><?= htmlspecialchars($cidadeDestino) ?></td>
                            <td><?= htmlspecialchars($nomeTransportador) ?></td>
                            <td>
                                <span class="status-frete status-<?= strtolower($frete['status']) ?>">
                                    <?= htmlspecialchars($frete['status']) ?>
                                </span>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>

        </div>

    </section>

        <section class="acoes-rapidas atalhos">

            <h3>Atalhos</h3>

            <ul>
                <li>
                    <a href="relatorios.php">
                        <i class="bi bi-clipboard-check atalho-icone"></i>
                        <span class="atalho-texto">Gerar relatórios</span>
                        <span class="atalho-seta">→</span>
                    </a>
                </li>

                <li>
                    <a href="fretes.php">
                        <i class="bi bi-truck atalho-icone"></i>
                        <span class="atalho-texto">Consultar fretes</span>
                        <span class="atalho-seta">→</span>
                    </a>
                </li>

                <li>
                    <a href="transportadores.php">
                        <i class="bi bi-truck-front atalho-icone"></i>
                        <span class="atalho-texto">Consultar transportadores</span>
                        <span class="atalho-seta">→</span>
                    </a>
                </li>
            </ul>

        </section>

</div>

    
</main>


