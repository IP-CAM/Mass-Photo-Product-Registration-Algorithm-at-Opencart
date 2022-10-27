<?php
	declare(strict_types=1);
	header('Content-Type: application/json');

	require "./src/extraiImagensController.php";
	require "./src/marcaDaguaController.php";
	// require "./src/Models/BancoDeDados.php";
	require "./src/criaProdutoController.php";

	use controller\ExtraiImagensController as Extracao;
	use controller\MarcaDaguaController as MarcaDagua;
	use controller\criaProdutoController as Produto;


	/* 1 - EXTRAIR ARQUIVOS E INFORMACOES
	* Cria um arquito .txt com informações de produtos
	* Objetivo: Criar de um arquivo texto contendo um json que será utilizado para cadastro de produtos.
	*/
	$extrair = new Extracao();
	$extrair->extrairArquivos();




	// Pŕoximos passos:
	// - Encontrar o arquico txt; 
	// - extrair o json dele;
	// - Rodar o resto do código em cima desse json para criar imagens;
	// - Mover imagens para as pastas corretas;






	var_dump('');
	die("OK");
	/* 1 - IMAGEM MARCA DAGUA
	* Criação de imagens com marca dagua.
	* Inicialização configura o conversor.
	* Objetivo: criar um serviço de leitura de imagen para conversão em massa das imagens
	*/
	$iMage = new MarcaDagua(4, 35, "marca-dagua.png");
	//Cria imagem com a marca dagua
	$iMage->aplicarMarcaDagua("a.jpg");

	/* 2 - CADASTRO DE PRODUTO
	* Cria produto na loja opencart com os dados fornecido.
	* Cria arquivo de download e vincula ao produto cadastrado.
	* Objetivo: Cadastrar uma foto como produto para venda que possibilite o cliente realizar
	* download da imagem sem marca d'água e boa qualidade.
	*/
	$produto = new Produto();
	//cadastro o produto
	$produto->criaProduto(
			'JHONE BERING', 
			'catalog/mascara-peste-negra.jpeg', 
			4.9, 
			'Titulo do produto 13', 
			'Arquivo para venda de Download', 
			'cachoeira-bras-gomes.jpeg', 
			'mask-cachoeira-bras-gomes.jpeg'
		);

	echo("Próximos passos: conexão com banco de dados");

	/*
		Planejamento:
		- Criar imagens com marca d'água: OK
		- Criar um serviço recursivo para criar as imagens em massa: Pendente
		- Criar query no banco de dados para criar produto: OK
		- Criar um json para alimentar em massa as querys que serão executadas: Pendente
			- Identificar em cada produto quem é o autor da foto: Pendente;
		- Criar conexão com banco de dados no script para executar as query: OK
		- Criar conexão com ftp para enviar as imagens criadas: Pendente 
		- Testar o resultado dentro do Opencart: Pendente
	*/
?>