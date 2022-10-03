<?php
	declare(strict_types=1);
	header('Content-Type: application/json');

	require "./src/marcaDaguaController.php";
	require "./src/Models/BancoDeDados.php";

	use controller\MarcaDaguaController as MarcaDagua;
	use models\Banco;


	/* IMAGEM MARCA DAGUA
	* Criação de imagens com marca dagua.
	* Inicialização configura o conversor.
	* Objetivo: criar um serviço de leitura de imagen para conversão em massa das imagens
	*/
	// $iMage = new MarcaDagua(4, 35, "marca-dagua.png");

	// $iMage->aplicarMarcaDagua("a.jpg");






	$bd = new Banco();

	// $x = $bd->select("SELECT * FROM `ocbr_user` WHERE `status` != 123");

	// var_dump($x);



//QUERY CRIAR PRODUTO #########################
$queryInsertProduto = "INSERT INTO 
ocbr_product (
	product_id, 
	model, 
	image,
	price, 
	date_available, 
	date_added, 
	date_modified,
	quantity, 
	status,
	location, stock_status_id, shipping, subtract, minimum, sort_order, viewed 
)
VALUES (
	NULL, 
	'JHONE BERING', 
	'catalog/mascara-peste-negra.jpeg', 
	'4.9', 
	'2022-09-21', 
	'2022-09-21 22:28:47', 
	'2022-09-21 22:28:47',
	'100', 
	'1',
	'', '5', '0', '0', '1', '0', '0'
)";

$idProduto = $bd->insert($queryInsertProduto);
var_dump("INSERIDO produto: ", $idProduto);
//##########################################



//QUERY PARA CRIAR DESCRICAO DO PRODUTO (2 idiomas) ##########################
$queryInsertDescricao = "INSERT INTO 
	ocbr_product_description (
		product_id, language_id, name, description, tag, meta_title, meta_description, meta_keyword
	) 
	VALUES 
	($idProduto, '1', 'FOTO download 3', '', '', 'FOTO download 3', '', ''), 
	($idProduto, '2', 'FOTO download 3', '', '', 'FOTO download 3', '', '')
";


 $bd->insert($queryInsertDescricao);
 var_dump("INSERIDO descricao: ");
//###############################################





//QUERY PARA VINCULAR PRODUTO A LOJA ###########
$queryInsertProdutoLoja = "INSERT INTO 
	ocbr_product_to_store 
		(product_id, store_id) 
	VALUES ($idProduto, '0')";

 $idProdutoLoja = $bd->insert($queryInsertProdutoLoja);
 var_dump("INSERIDO produto na loja: ", $idProdutoLoja);
//###############################################

//QUERY DEFINIR LAYOUT DO PRODUTO ###########
$queryInsertLayoutProduto = "INSERT INTO 
	ocbr_product_to_layout 
		(product_id, store_id, layout_id) 
	VALUES ($idProduto, '0', '0')";

$idLayoutProduto = $bd->insert($queryInsertLayoutProduto);
var_dump("INSERIDO layout produto: ", $idLayoutProduto);
//###############################################





//QUERY CRIAR DOWNLOAD (diretório das imagens: /storage/download/)
$queryInsertDownload = "INSERT INTO 
	`ocbr_download` 
		(`download_id`, `filename`, `mask`, `date_added`) 
	VALUES 
		(NULL, 'cachoeira-bras-gomes.jpeg', 'mask-cachoeira-bras-gomes.jpeg', '2022-10-02 09:44:40')";

$idDownload = $bd->insert($queryInsertDownload);
var_dump("INSERIDO Download: ", $idDownload);
//##########################################

//QUERY CRIA DESCRICAO DO DOWNLOAD ###############
$queryDescricaoDownload = "INSERT INTO
	ocbr_download_description
		(download_id, language_id, name)
	VALUES 
		($idDownload, 1, 'Nome do Download 2 en'),
		($idDownload, 2, 'Nome do Download 2 pt')
	";

$bd->insert($queryDescricaoDownload);
var_dump("INSERIDO descricao do download");

//QUERY VINCULA O DOWNLOAD AO PRODUTO #######################
$queryInsertDownloadToProduto = "INSERT INTO 
	ocbr_product_to_download 
		(`product_id`, `download_id`) 
	VALUES 
		($idProduto, $idDownload)";

$idDownloadToProduto = $bd->insert($queryInsertDownloadToProduto);
var_dump("INSERIDO download to produto: ", $idDownloadToProduto);
//##########################################




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