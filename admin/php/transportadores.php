
<?php

require_once __DIR__ . '/../partesCodigo/verificarLogin.php';

if (!isset($_SESSION['transportadores'])) {
    $_SESSION['transportadores'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $documento = trim($_POST['documento'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');
    $obs = trim($_POST['obs'] ?? '');

    if ($nome !== '' && $documento !== '') {

        // EDIÇÃO
        if (($_POST['acao'] ?? '') === 'atualizar') {

            $id = (int)($_POST['id'] ?? 0);

            foreach ($_SESSION['transportadores'] as $indice => $transportador) {

                if ((int)$transportador['id'] === $id) {

                    $_SESSION['transportadores'][$indice]['nome'] = $nome;
                    $_SESSION['transportadores'][$indice]['documento'] = $documento;
                    $_SESSION['transportadores'][$indice]['telefone'] = $telefone;
                    $_SESSION['transportadores'][$indice]['email'] = $email;
                    $_SESSION['transportadores'][$indice]['endereco'] = $endereco;
                    $_SESSION['transportadores'][$indice]['obs'] = $obs;

                    break;
                }
            }

            header("Location: ../paginas/transportadores.php?matricula=" . $id);
            exit;

        } else {

            // NOVO CADASTRO
            $maiorId = 0;

            foreach ($_SESSION['transportadores'] as $transportador) {
                $maiorId = max($maiorId, (int)$transportador['id']);
            }

            $id = $maiorId + 1;

            $_SESSION['transportadores'][] = [
                'id' => $id,
                'nome' => $nome,
                'documento' => $documento,
                'telefone' => $telefone,
                'email' => $email,
                'endereco' => $endereco,
                'obs' => $obs
            ];

            header("Location: ../paginas/transportadores.php?matricula=" . $id);
            exit;
        }
    }
}

?>
