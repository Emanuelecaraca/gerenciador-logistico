
<?php

    // Inicia a sessão para permitir o acesso aos transportadores cadastrados.
    session_start();
    

    // Inclui o conteúdo do cabeçalho da página.
    include("../partesCodigo/head.php");

?>
<body>


    <?php include("../partesCodigo/header.php"); ?>

    <?php include("../partesCodigo/menu.php"); ?>

    <?php include("../partesCodigo/conteudoTransportadores.php"); ?>


</body>
</html>