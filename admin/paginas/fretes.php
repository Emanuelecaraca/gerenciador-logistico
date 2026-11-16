
<?php

session_start();

// Processa o cadastro de fretes antes de carregar a página.
include("../php/fretes.php");

include("../partesCodigo/head.php");

?>

<body>

    <?php include("../partesCodigo/header.php"); ?>

    <?php include("../partesCodigo/menu.php"); ?>

    <?php include("../partesCodigo/conteudoFrete.php"); ?>

</body>
</html>
