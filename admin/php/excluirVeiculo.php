
<?php

    session_start();

    // Verifica o ID do veículo.
    if (isset($_GET['id'])) {

        // Recebe o ID do veículo que será excluído.
        $id = (int)$_GET['id'];

        // Percorre a lista de veículos cadastrados.
        foreach ($_SESSION['veiculos'] as $indice => $veiculo) {

            // Verifica se encontrou o veículo selecionado.
            if ($veiculo['id'] == $id) {

                // Remove o veículo da lista.
                unset($_SESSION['veiculos'][$indice]);

                // Encerra a busca após encontrar o registro.
                break;
            }
        }

        // Reorganiza os índices da lista após a exclusão.
        $_SESSION['veiculos'] = array_values($_SESSION['veiculos']);
    }

    // Retorna para a página de transportadores.
    header("Location: ../paginas/transportadores.php");
    exit;

?>
