<header>
    <div class="titulo-sistema">
        Gerenciamento Logístico
    </div>

    <div class="header-usuario">

        <button type="button" class="botao-usuario" id="botaoUsuario">

            <i class="bi bi-person-circle"></i>

            <span>
                <?= htmlspecialchars($_SESSION['usuario']['nome']) ?>
            </span>

            <i class="bi bi-chevron-down"></i>

        </button>

        <div class="menu-usuario" id="menuUsuario">

            <a href="#">
                <i class="bi bi-person"></i>
                Meu perfil
            </a>

            <a href="../php/sair.php" class="opcao-sair">
                <i class="bi bi-box-arrow-right"></i>
                Sair
            </a>

        </div>

    </div>
</header>

<script src="../js/menuUsuario.js"></script>