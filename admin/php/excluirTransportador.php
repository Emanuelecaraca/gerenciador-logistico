
<?php

    session_start();

    // Verifica se o ID do transportador foi recebido.
    if (isset($_GET['id'])) {

        // Recebe o ID  que será excluído.
        $id = (int)$_GET['id'];

        // Percorre a lista de transportadores cadastrados.
        foreach ($_SESSION['transportadores'] as $indice => $transportador) {

            // Verifica se encontrou o transportador selecionado.
            if ($transportador['id'] == $id) {

                // Remove o transportador da lista.
                unset($_SESSION['transportadores'][$indice]);

                // Encerra a busca após encontrar o registro.
                break;
            }
        }

        // Reorganiza os índices da lista após a exclusão.
        $_SESSION['transportadores'] = array_values($_SESSION['transportadores']);
    }

    // Retorna para a página de transportadores.
    header("Location: ../paginas/transportadores.php");
    exit;

?>
