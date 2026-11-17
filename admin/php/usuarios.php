
<?php

// Lista de usuários autorizados a acessar o sistema.
$usuarios = [

    [
        'nome' => 'Administrador',
        'email' => 'admin@gerenciador.com',
        'senha' => password_hash('Admin@123', PASSWORD_DEFAULT)
    ]

];

?>
