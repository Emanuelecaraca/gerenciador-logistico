<?php
// Identifica qual página está aberta
$paginaAtual = basename($_SERVER['PHP_SELF']);
?>

<nav>

    <!-- Identificação do sistema no menu lateral -->
    <h2 class="menu-brand">
        Gerenciamento<br>
        Logístico
    </h2>

    <ul>
        <li>
            <a href="home.php" class="<?= $paginaAtual == 'home.php' ? 'active' : '' ?>">
                Início
            </a>
        </li>

        <li>
            <a href="produtos.php" class="<?= $paginaAtual == 'produtos.php' ? 'active' : '' ?>">
                Produtos
            </a>
        </li>

        <li>
            <a href="endereco.php" class="<?= $paginaAtual == 'endereco.php' ? 'active' : '' ?>">
                Endereços
            </a>
        </li>

        <li>
            <a href="transportadores.php" class="<?= $paginaAtual == 'transportadores.php' ? 'active' : '' ?>">
                Transportador
            </a>
        </li>

        <li>
            <a href="fretes.php" class="<?= $paginaAtual == 'fretes.php' ? 'active' : '' ?>">
                Fretes
            </a>
        </li>

        <li>
            <a href="relatorios.php" class="<?= $paginaAtual == 'relatorios.php' ? 'active' : '' ?>">
                Relatórios
            </a>
        </li>
    </ul>
</nav>