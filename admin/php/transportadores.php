
<?php

    require_once __DIR__ . '/../partesCodigo/verificarLogin.php';

    // Verifica se a lista de transportadores existe.
    if (!isset($_SESSION['transportadores'])) {
        $_SESSION['transportadores'] = [];
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Recebe os dados enviados.
        $nome = trim($_POST['nome'] ?? '');
        $documento = trim($_POST['documento'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // Verifica se todos os campos foram preenchidos.
        if (
            $nome != '' &&
            $documento != '' &&
            $telefone != '' &&
            $email != ''
        ) {

            // Verifica se a ação é uma atualização.
            if (($_POST['acao'] ?? '') == 'atualizar') {

                // Recebe o ID do transportador.
                $id = (int)($_POST['id'] ?? 0);

                // Procura o transportador que será atualizado.
                foreach ($_SESSION['transportadores'] as $indice => $transportador) {

                    if ($transportador['id'] == $id) {

                        // Atualiza os dados do cadastro.
                        $_SESSION['transportadores'][$indice]['nome'] = $nome;
                        $_SESSION['transportadores'][$indice]['documento'] = $documento;
                        $_SESSION['transportadores'][$indice]['telefone'] = $telefone;
                        $_SESSION['transportadores'][$indice]['email'] = $email;

                        break;
                    }
                }

                // Recupera o termo da pesquisa.
                $pesquisa = trim($_POST['pesquisa'] ?? '');

                // Retorna para a consulta mantendo a pesquisa.
                header("Location: ../paginas/transportadores.php?pesquisa=" . urlencode($pesquisa));
                exit;

            } else {

                // Identificador para o novo transportador.
                $id = count($_SESSION['transportadores']) + 1;

                // Adiciona o novo cadastro.
                $_SESSION['transportadores'][] = [
                    'id' => $id,
                    'nome' => $nome,
                    'documento' => $documento,
                    'telefone' => $telefone,
                    'email' => $email
                ];

                // Retorna para a página de transportadores.
                header("Location: ../paginas/transportadores.php");
                exit;
            }
        }
    }

?>
