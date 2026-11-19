<?php

    session_start();

    // Verifica se o ID do transportador foi recebido.
    if (isset($_GET['id'])) {

        // Recebe o ID que será excluído.
        $id = (int)$_GET['id'];

        // Verifica se existem veículos vinculados ao transportador.
        $possuiVeiculos = false;

        if (!empty($_SESSION['veiculos'])) {

            // Percorre os veículos cadastrados.
            foreach ($_SESSION['veiculos'] as $veiculo) {

                // Verifica se o veículo pertence ao transportador.
                if ($veiculo['transportador'] == $id) {
                    $possuiVeiculos = true;
                    break;
                }
            }
        }

        // Só permite excluir se não houver veículos vinculados.
        if (!$possuiVeiculos) {

            // Percorre os transportadores cadastrados.
            foreach ($_SESSION['transportadores'] as $indice => $transportador) {

                // Verifica se encontrou o transportador.
                if ($transportador['id'] == $id) {

                    // Remove o transportador.
                    unset($_SESSION['transportadores'][$indice]);

                    break;
                }
            }

            // Reorganiza os índices da lista.
            $_SESSION['transportadores'] = array_values($_SESSION['transportadores']);

            // Retorna informando que a exclusão foi realizada.
            header("Location: ../paginas/transportadores.php?excluido=1");
            exit;

        } else {

            // Retorna informando que existem veículos vinculados.
            header("Location: ../paginas/transportadores.php?erro=veiculos");
            exit;
        }
    }

    // Retorna à página de transportadores.
    header("Location: ../paginas/transportadores.php");
    exit;

?>