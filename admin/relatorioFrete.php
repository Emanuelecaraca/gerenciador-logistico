<?php

require_once __DIR__ . '/partesCodigo/verificarLogin.php';

include(__DIR__ . '/partesCodigo/head.php');

echo '<link rel="stylesheet" href="/gerenciador-logistico/admin/css/relatorio.css">';

?>

<link rel="stylesheet" href="/gerenciador-logistico/admin/css/relatorio.css">


<body>

    <div class="container-principal">

        <?php include(__DIR__ . '/partesCodigo/menu.php'); ?>

        <div class="area-conteudo">

            <?php include(__DIR__ . '/partesCodigo/header.php'); ?>

            <?php include(__DIR__ . '/partesCodigo/relatorioFrete.php'); ?>

        </div>

    </div>

</body>
</html>