<?php

	require_once __DIR__ . '/../partesCodigo/verificarLogin.php';

	include("../partesCodigo/head.php");
?>


<body>
	
	<div class="container-principal">

        <!-- Menu lateral -->
        <?php include("../partesCodigo/menu.php"); ?>

        <div class="area-conteudo">

            <!-- Cabeçalho superior -->
            <?php include("../partesCodigo/header.php"); ?>

            <!-- Conteúdo de endereços. -->
            <?php include("../partesCodigo/conteudoRelatorios.php");?>

    </div>


</body>