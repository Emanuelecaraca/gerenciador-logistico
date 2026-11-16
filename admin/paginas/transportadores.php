
<?php

    // Inicia a sessão para acessar os transportadores cadastrados.
    session_start();

    // Verifica se a solicitação de exclusão de um transportador.
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($_POST['acao'] ?? '') == 'excluir') {

        // Recebe o ID q será excluído.
        $id = (int)($_POST['id'] ?? 0);

        // Verifica se existem veículos vinculados ao transportador.
        $possuiVeiculos = false;

        if (!empty($_SESSION['veiculos'])) {

            // Percorre os veículos cadastrados.
            foreach ($_SESSION['veiculos'] as $veiculo) {

                // Compara o transportador com o vínculo do veículo.
                if ($veiculo['transportador'] == $id) {
                    $possuiVeiculos = true;
                    break;
                }
            }
        }

        // Só permite excluir se não houver veiculos vinculados.
        if (!$possuiVeiculos) {

            // Percorre os transportadores cadastrados.
            foreach ($_SESSION['transportadores'] as $indice => $transportador) {

                // Localiza o transportador.
                if ($transportador['id'] == $id) {

                    // Remove o transportador.
                    unset($_SESSION['transportadores'][$indice]);

                    // Reorganiza os índices.
                    $_SESSION['transportadores'] = array_values($_SESSION['transportadores']);

                    break;
                }
            }

            // Retorna à página informando que a exclusão foi realizada.
            header("Location: transportadores.php?excluido=1");
            exit;

        } else {

            // Retorna à página informando que existem veículos vinculados.
            header("Location: transportadores.php?erro=veiculos");
            exit;
        }
    }

    // Verifica se o formulário de edição foi enviado.
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($_POST['acao'] ?? '') == 'atualizar') {

        // Recebe os dados enviados.
        $id = (int)($_POST['id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $documento = trim($_POST['documento'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // Verifica se os campos foram preenchidos.
        if ($nome != '' && $documento != '' && $telefone != '' && $email != '') {

            // Percorre os transportadores cadastrados.
            foreach ($_SESSION['transportadores'] as $indice => $transportador) {

                // Verifica se encontrou o transportador selecionado.
                if ($transportador['id'] == $id) {

                    // Atualiza os dados mantendo o mesmo ID.
                    $_SESSION['transportadores'][$indice]['nome'] = $nome;
                    $_SESSION['transportadores'][$indice]['documento'] = $documento;
                    $_SESSION['transportadores'][$indice]['telefone'] = $telefone;
                    $_SESSION['transportadores'][$indice]['email'] = $email;

                    break;
                }
            }
        }

        // Retorna à página de transportadores.
        header("Location: transportadores.php");
        exit;
    }

    // Inclui o cabeçalho da página.
    include("../partesCodigo/head.php");

        // Verifica se o formulário de edição  foi enviado.
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($_POST['acao'] ?? '') == 'atualizarVeiculo') {

            $id = (int)($_POST['id'] ?? 0);
            $transportador = (int)($_POST['transportador'] ?? 0);
            $placa = trim($_POST['placa'] ?? '');
            $tipo = trim($_POST['tipo'] ?? '');

            if ($transportador > 0 && $placa != '' && $tipo != '') {

                // Percorre os transportadores para validar o vínculo.
                $transportadorValido = false;

                foreach ($_SESSION['transportadores'] as $cadastro) {

                    if ($cadastro['id'] == $transportador) {
                        $transportadorValido = true;
                        break;
                    }
                }

                // Só atualiza se o transportador informado existir.
                if ($transportadorValido) {

                    foreach ($_SESSION['veiculos'] as $indice => $veiculo) {

                        if ($veiculo['id'] == $id) {

                            // Atualiza os dados mantendo o mesmo ID.
                            $_SESSION['veiculos'][$indice]['transportador'] = $transportador;
                            $_SESSION['veiculos'][$indice]['placa'] = $placa;
                            $_SESSION['veiculos'][$indice]['tipo'] = $tipo;

                            break;
                        }
                    }
                }
            }

            // Retorna à página de transportadores.
            header("Location: transportadores.php");
            exit;
        }

?>

<body>


    <?php include("../partesCodigo/header.php"); ?>

    <?php include("../partesCodigo/menu.php"); ?>

    <?php include("../partesCodigo/conteudoTransportadores.php"); ?>


</body>
</html>