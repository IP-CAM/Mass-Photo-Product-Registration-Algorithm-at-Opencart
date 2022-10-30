<?php
	declare(strict_types=1);
	header('Content-Type: application/json');

	require "./src/extraiImagensController.php";
	require "./src/marcaDaguaController.php";
	require "./src/criaProdutoController.php";

	use controller\ExtraiImagensController as Extracao;
	use controller\MarcaDaguaController as MarcaDagua;
	use controller\criaProdutoController as Produto;

	/*
	* CONFIGURAR CONVERSÃO DE IMAGENS COM MARCA D'ÁGUA
	*/
	$iMage = new MarcaDagua(2, 60, "marca-dagua.png");

	/* 1 - EXTRAIR ARQUIVOS E INFORMACOES
	* Cria um arquito .txt com informações de produtos
	* Objetivo: Criar de um arquivo texto contendo um json que será utilizado para cadastro de produtos.
	*/
	$extrair = new Extracao();
	$extrair->extrairArquivos();

	/*
	* 2 - PROCURA POR POSTAGENS PARA CADASTRAR
	* Vasculha a pasta /uploads para encontrar pastas com conteúdo para cadastrar
	* MELHORIA: Criar arquivos de controle para que nessa busca não tenha retrabalho.
	*/
	$pastaPostagens = "uploads/";
	$pastaFotosMarcaDagua = "fotosMarcaDagua";
	$pastasFotografos = array();

	//Listas pastas pendentes para executar
	if(is_dir($pastaPostagens)){
		$pastas = scandir($pastaPostagens);
		unset($pastas[0]); //remove .
		unset($pastas[1]); //remove ..
		foreach($pastas as $pasta)
			if( ($ext = pathinfo($pasta, PATHINFO_EXTENSION)) === '')
				$pastasFotografos[] = $pasta;	
	} else {
			echo("Pasta não encontrada");
	}

	//Lê pasta de cada fotografo
	foreach($pastasFotografos as $pasta) {
		// Procurar pelo txt
		$caminhoPastaFotografo = $pastaPostagens . $pasta;
		$caminhoPastaFotosMarcaDagua = $caminhoPastaFotografo . '/' . $pastaFotosMarcaDagua;
		mkdir($caminhoPastaFotosMarcaDagua, 0755, true);

		if(is_dir($caminhoPastaFotografo)) {
			$arquivos = scandir($caminhoPastaFotografo);
			unset($arquivos[0]); //remove .
			unset($arquivos[1]); //remove ..

			$arquivosFotografo = array();
			foreach($arquivos as $arquivo){
				$ext = pathinfo($arquivo, PATHINFO_EXTENSION);
				// var_dump("Extensão: ", $ext);

				if(in_array($ext, array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG')))
					$arquivosFotografo['image'][] = $arquivo;
				elseif( $ext === 'txt')
					$arquivosFotografo['txt'] = $arquivo;
			}


			/*
			* LER ARQUIVO TXT COM JSON
			*/
			$caminhoTxtJsonFile = $caminhoPastaFotografo . '/' . $arquivosFotografo['txt'];
			$file = fopen($caminhoTxtJsonFile, "r");
			$jsonProdutos = json_decode(fread($file, filesize($caminhoTxtJsonFile)));
			fclose($file);

			foreach($jsonProdutos as $jsonProduto) 
			{
				$foto = $caminhoPastaFotografo . '/' . $jsonProduto->arquivoFoto;

				/* 2 - IMAGEM MARCA DAGUA
				* Criação de imagens com marca dagua.
				* Inicialização configura o conversor.
				* Objetivo: criar um serviço de leitura de imagen para conversão em massa das imagens
				*/
				$iMage->aplicarMarcaDagua($jsonProduto->arquivoFoto, $caminhoPastaFotografo, 
										  $jsonProduto->nomeMascara, $caminhoPastaFotosMarcaDagua);


				/* 3 - CADASTRO DE PRODUTO
				* Cria produto na loja opencart com os dados fornecido.
				* Cria arquivo de download e vincula ao produto cadastrado.
				* Objetivo: Cadastrar uma foto como produto para venda que possibilite o cliente realizar
				* download da imagem sem marca d'água e boa qualidade.
				*/
				$produto = new Produto();
				$produto->criaProduto(
						$jsonProduto->idCategoria,   //66
						$jsonProduto->nomeFotografo, //'JHONE BERING', 
						'/catalog/fotosMarcaDagua/' . $jsonProduto->nomeMascara, ///image/catalog/fotosMarcaDagua
						3.5, 
						$jsonProduto->tituloProduto, //'Titulo do produto 13', 
						$jsonProduto->tituloProduto. ' - Download', 
						$jsonProduto->arquivoFoto, //'cachoeira-bras-gomes.jpeg', //Em "/storage/download/"
						rand(1000000, 9999999) . "_" .$jsonProduto->nomeMascara //'mask-cachoeira-bras-gomes.jpeg'
				);
			}
		}
	}



	//APLICAR AQUI CONEXÃO FTP AQUI
	// Imagens Originais 		  =>   storage/download
	// Imagens com Marca d'água   =>   /catalog/catalog/fotosMarcaDagua

//******


	/*
		Planejamento:
		- Criar imagens com marca d'água: OK
		- Criar um serviço recursivo para criar as imagens em massa: OK
		- Criar query no banco de dados para criar produto: OK
		- Criar um json para alimentar em massa as querys que serão executadas: OK
			- Identificar em cada produto quem é o autor da foto: OK
		- Criar conexão com banco de dados no script para executar as query: OK
		- Criar conexão com ftp para enviar as imagens criadas: Pendente (por enquanto mover dentro do próprio servidor)
		- Testar o resultado dentro do Opencart: OK
	*/

	/*
		INSTRUÇÕES:
		1 - Criar arquivo zip contendo apenas as fotos que serão publicadas;
		2 - Arquivo zip deve contera seguinte estrutura no nome:
			a - nome-do-fotografo_nome-do-evento_data-do-evento.zip;
			b - Exemplo: jhone-bering_rapel-cachoeira-viana_23-10-2022.zip;
			c - obs.: Usar caixa baixa, separar palavras com hífen, separar fotografo evento e data com underline.
		3 - Colocar o arquivo zip dentro da pasta "uploads";
		4 - Executar script;
		5 - Enviar as imagens para o projeto opencart nos seguintes caminhos:
			a - Imagens originais: /storage/download;
			b - Imagens marca d'água: /catalog/catalog/fotosMarcaDagua.
		6 - Acessar cms, abrir qualquer produto e salvar;
		7 - Verificar se as imagens apareceram no site;
		8 - Se tiver tudo ok, esvaziar a pasta uploads;

	*/
?>