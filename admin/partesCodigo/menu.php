<?php
// Identifica qual página está aberta
$paginaAtual = basename($_SERVER['PHP_SELF']);
?>

<nav>

    <button type="button" id="botaoMenu" class="botao-menu">
        <i class="bi bi-list"></i>
    </button>

    <ul>
        <li>
            <!-- O PHP compara a página atual com 'home.php'. -->
            <a href="home.php" class="<?= $paginaAtual == 'home.php' ? 'active' : '' ?>">
                <i class="bi bi-house-door-fill"></i>
                <span>Início</span>
            </a>
        </li>
         <li>
            <a href="transportadores.php" class="<?= $paginaAtual == 'transportadores.php' ? 'active' : '' ?>">
                <i class="bi bi-truck"></i>
                <span>Transportadores</span>
            </a>
        </li>
        <li>
            <a href="produtos.php" class="<?= $paginaAtual == 'produtos.php' ? 'active' : '' ?>">
                <i class="bi bi-box-seam-fill"></i>
                <span>Produtos</span>
            </a>
        </li>

        <li>
            <a href="endereco.php" class="<?= $paginaAtual == 'endereco.php' ? 'active' : '' ?>">
                <i class="bi bi-geo-alt-fill"></i>
                <span>Endereço</span>
            </a>
        </li>

        <li>
            <a href="fretes.php" class="<?= $paginaAtual == 'fretes.php' ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-text-fill"></i>
                <span>Fretes</span>
            </a>
        </li>

        <li>
            <a href="relatorios.php" class="<?= $paginaAtual == 'relatorios.php' ? 'active' : '' ?>">
                <i class="bi bi-bar-chart-fill"></i>
                <span>Relatórios</span>
            </a>
        </li>

        
    </ul>
</nav>

<script src="../js/menuLateral.js"></script>