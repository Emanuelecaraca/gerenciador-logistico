
<?php 

    require_once __DIR__ . '/../partesCodigo/verificarLogin.php';

include("../partesCodigo/head.php"); 

?>




<body>

    <div class="container-principal">

        <!-- Menu lateral -->
        <?php include("../partesCodigo/menu.php"); ?>

        <!-- Área de conteúdo -->
        <div class="area-conteudo">

            <!-- Cabeçalho superior -->
            <?php include("../partesCodigo/header.php"); ?>

            <!-- Conteúdo de produtos -->
            <?php include("../partesCodigo/conteudoProdutos.php"); ?>

        </div>

    </div>

</body>

</html>