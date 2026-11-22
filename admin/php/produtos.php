
<?php

require_once __DIR__ . '/../partesCodigo/verificarLogin.php';

if (isset($_GET['editar'])) {
//serve para verificar se o parâmetro editar foi enviado pela URL antes de executar

        $idEditar = $_GET['editar']; //GUARDA O QUE FOI EDITADO

        //PROCURA O PRODUTO SIMILAR
        foreach ($_SESSION['produtos'] as $produto) {

            //VERIFICA SE O PRODUTO CORRESPONDE AO ITEM QUE QUER EDITAR
            if ($produto['id'] == $idEditar) {

                //GUARDA O PRODUTO CORRESPONDENTE
                $_SESSION['produto_edicao'] = $produto;

            break;
        }
    }

    // RETORNA PRA PAGINA DE PRODUTOS.
    header("Location: ../paginas/produtos.php");

    exit;
}

 
if (!isset($_SESSION['produtos'])) {
    $_SESSION['produtos'] = [];
}


// EXCLUIR PRODUTO
if (isset($_GET['excluir'])) {

    $idExcluir = $_GET['excluir'];

    foreach ($_SESSION['produtos'] as $indice => $produto) {

        if ($produto['id'] == $idExcluir) {
            unset($_SESSION['produtos'][$indice]);
            break;
        }
    }

    // Reorganiza os índices da lista.
    $_SESSION['produtos'] = array_values($_SESSION['produtos']);

    // Limpa a edição caso o produto excluído estivesse selecionado.
    if (isset($_SESSION['produto_edicao']) &&
        $_SESSION['produto_edicao']['id'] == $idExcluir) {
        unset($_SESSION['produto_edicao']);
    }

    // Retorna para a lista de produtos.
    header("Location: ../paginas/produtos.php");
    exit;
}
    
// Verifica se o formulário foi enviado.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Recebe os dados do formulário.
    $nome = trim($_POST['nome'] ?? '');
    $peso = $_POST['peso'] ?? '';
    $comprimento = $_POST['comprimento'] ?? '';
    $largura = $_POST['largura'] ?? '';
    $altura = $_POST['altura'] ?? '';
    $id = $_POST['id'] ?? '';

    if ($nome != '') {

        // Se recebeu um ID, significa que é uma edição.
        if ($id != '') {

            foreach ($_SESSION['produtos'] as &$produto) {

                if ($produto['id'] == $id) {
                    $produto['nome'] = $nome;
                    $produto['peso'] = $peso;
                    $produto['comprimento'] = $comprimento;
                    $produto['largura'] = $largura;
                    $produto['altura'] = $altura;
                    break;
                }
            }

            unset($produto);
            unset($_SESSION['produto_edicao']);

        } else {

            // Cadastro de um novo produto.
            $maiorId = 0;

            foreach ($_SESSION['produtos'] as $produto) {
                if ($produto['id'] > $maiorId) {
                    $maiorId = $produto['id'];
                }
            }

            $novoId = $maiorId + 1;

            $_SESSION['produtos'][] = [
                'id' => $novoId,
                'nome' => $nome,
                'peso' => $peso,
                'comprimento' => $comprimento,
                'largura' => $largura,
                'altura' => $altura
            ];

        }
    }

    // Retorna para a página de produtos.
    header("Location: ../paginas/produtos.php");
    exit;
}

?>
