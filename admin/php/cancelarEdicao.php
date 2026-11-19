<?php

// Inicia a sessão e verifica se o usuário está logado.
require_once __DIR__ . '/../partesCodigo/verificarLogin.php';

// Limpa a edição de produtos.
unset($_SESSION['produto_edicao']);

// Limpa a edição de endereços.
unset($_SESSION['endereco_edicao']);

// Identifica a página para a qual deve retornar.
$pagina = $_GET['pagina'] ?? 'produtos';

if ($pagina === 'endereco') {
    header("Location: ../paginas/endereco.php");
} else {
    header("Location: ../paginas/produtos.php");
}

exit;
?>