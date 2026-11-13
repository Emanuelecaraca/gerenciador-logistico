
<?php

session_start();     // Verifica se a lista de produtos já existe

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


    
    //$_SESSION['produtos'] serve pra guardar os produtos temporariamente 
    // isset() verifica se essa lista já foi criada, se não, cria uma lista vazia 
    if (!isset($_SESSION['produtos'])) {
        $_SESSION['produtos'] = [];
    }


    // Verifica se o formulário foi enviado.
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Recebe o nome e o ID do formulário.
        $nome = trim($_POST['nome'] ?? '');
        $id = $_POST['id'] ?? '';

        if ($nome != '') {

            // Se recebeu um ID, significa que é uma edição.
            if ($id != '') {

                foreach ($_SESSION['produtos'] as &$produto) {

                    if ($produto['id'] == $id) {
                        $produto['nome'] = $nome;
                        break;
                    }
                }

                unset($produto);
                unset($_SESSION['produto_edicao']);

            } else {

                // Sem ID, significa que é um novo cadastro.
                $novoId = count($_SESSION['produtos']) + 1;

                $_SESSION['produtos'][] = [
                    'id' => $novoId,
                    'nome' => $nome
                ];
            }
        }

        // Retorna para a página de produtos.
        header("Location: ../paginas/produtos.php");
    exit;
}
?>
