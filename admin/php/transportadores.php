
<?php

require_once __DIR__ . '/../partesCodigo/verificarLogin.php';

if (!isset($_SESSION['transportadores'])) {
    $_SESSION['transportadores'] = [];
}

// ==========================================
// ANEXAR DOCUMENTO AO TRANSPORTADOR
// ==========================================

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['acao'] ?? '') === 'anexar_documento'
) {

    $idTransportador = (int)($_POST['idTransportador'] ?? 0);

    foreach ($_SESSION['transportadores'] as $indice => $transportador) {

        if ((int)$transportador['id'] === $idTransportador) {

            if (!isset($_SESSION['transportadores'][$indice]['documentos'])) {
                $_SESSION['transportadores'][$indice]['documentos'] = [];
            }

            if (
                isset($_FILES['documento']) &&
                $_FILES['documento']['error'] === UPLOAD_ERR_OK
            ) {

                $arquivo = $_FILES['documento'];
                $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

                $permitidos = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'csv'];

                if (!in_array($extensao, $permitidos)) {
                    http_response_code(400);
                    echo json_encode(['erro' => 'Formato de arquivo não permitido.']);
                    exit;
                }

                $pasta = __DIR__ . '/../documentos/';

                $nomeArquivo = uniqid('documento_', true) . '.' . $extensao;

                if (move_uploaded_file($arquivo['tmp_name'], $pasta . $nomeArquivo)) {

                    $_SESSION['transportadores'][$indice]['documentos'][] = [
                        'id' => uniqid(),
                        'nome' => $arquivo['name'],
                        'arquivo' => $nomeArquivo
                    ];

                    echo json_encode(['sucesso' => true]);
                    exit;
                }
            }

            http_response_code(400);
            echo json_encode(['erro' => 'Não foi possível anexar o documento.']);
            exit;
        }
    }

    http_response_code(404);
    echo json_encode(['erro' => 'Transportador não encontrado.']);
    exit;
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
