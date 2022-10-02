<?php
	declare(strict_types=1);
	header('Content-Type: application/json');

	require "./src/marcaDaguaController.php";

	use controller\MarcaDaguaController as MarcaDagua;


	/* IMAGEM MARCA DAGUA
	* Criação de imagens com marca dagua.
	* Inicialização configura o conversor.
	* Objetivo: criar um serviço de leitura de imagen para conversão em massa das imagens
	*/
	$iMage = new MarcaDagua(4, 35, "marca-dagua.png");
	$iMage->aplicarMarcaDagua("a.jpg");


	echo("Próximos passos: conexão com banco de dados");


	/*
		Planejamento:
		- Criar imagens com marca d'água: OK
		- Criar um serviço recursivo para criar as imagens em massa: Pendente
		- Criar query no banco de dados para criar produto: OK
		- Criar um json para alimentar em massa as querys que serão executadas: Pendente
			- Identificar em cada produto quem é o autor da foto: Pendente;
		- Criar conexão com banco de dados no script para executar as query: Pendente
		- Criar conexão com ftp para enviar as imagens criadas: Pendente 
		- Testar o resultado dentro do Opencart: Pendente
	*/
?>