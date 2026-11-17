<?php

	// Inicia a sessão.
	session_start();

	// Limpa todos os dados armazenados na sessão.
	$_SESSION = [];

	// Destrói a sessão.
	session_destroy();

	// Retorna para a tela de login.
	header("Location: ../../login.html");
	exit;

?>