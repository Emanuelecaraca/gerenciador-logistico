<?php

	require_once __DIR__ . '/../partesCodigo/verificarLogin.php';

	include("../partesCodigo/head.php");
?>


<body>
	
	<?php   

    // Cabeçalho do sistema.
    include("../partesCodigo/header.php");

    // Menu de navegação.
    include("../partesCodigo/menu.php");

    // Conteúdo de endereços.
    include("../partesCodigo/conteudoRelatorios.php");

    ?>

    <script src="../js/cep.js"></script>


</body>