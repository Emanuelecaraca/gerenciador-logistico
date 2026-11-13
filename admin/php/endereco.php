<?php

	session_start();

	// Verifica se a lista de endereços já existe. Se não existir, cria uma lista vazia.
	if (!isset($_SESSION['endereco'])) {
		$_SESSION['endereco'] = [];
	}

	// Verifica se o formulário foi enviado pelo método POST.
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

		// Se sim, recebe os dados enviados pelo formulário.
		//trim() remove espaços desnecessários no início e no final.
		$cep = trim($_POST['cep'] ?? '');
		$rua = trim($_POST['rua'] ?? '');
		$numero = trim($_POST['numero'] ?? '');
		$bairro = trim($_POST['bairro'] ?? '');
		$cidade = trim($_POST['cidade'] ?? '');
		$estado = trim($_POST['estado'] ?? '');

		//verificar se todos foram preenchidos
		if (
			$cep != '' &&
			$rua != '' &&
			$numero != '' &&
			$bairro != '' &&
			$cidade != '' &&
			$estado != ''

		) {

			//IDENTIFICAR UM ID PRA EDIÇÃO
			$id = $_POST['id'] ??'';

	if ($id != '') {
			//produra endereço pra editar
			foreach($_SESSION['endereco'] as $chave => $endereco){

				if ($endereco["id"] == $id) {

					// atualiza os dados
					$_SESSION['endereco'][$chave] = [
						'id' => $endereco['id'],
		                'cep' => $cep,
		                'rua' => $rua,
		                'numero' => $numero,
		                'bairro' => $bairro,
		                'cidade' => $cidade,
		                'estado' => $estado
		            ];

			break;
		}
	}

} else {

		// CRIA IDENTIFICADOR ENDEREÇO
		$id = count($_SESSION['endereco']) + 1;

		//Adicionar o endereço na lista
		$_SESSION['endereco'][]=[
			'id' => $id,
	        'cep' => $cep,
	        'rua' => $rua,
	        'numero' => $numero,
	        'bairro' => $bairro,
	        'cidade' => $cidade,
	        'estado' => $estado
		];
	}

	//retornar para página enredeço
	header("Location: ../paginas/endereco.php");
	exit;

}

?>