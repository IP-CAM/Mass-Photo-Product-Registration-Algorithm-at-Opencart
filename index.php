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

	$iMage = new MarcaDagua(2, 60, "marca-dagua.png"); //PENDENTE: setar também o caminho das imagens 

	/* 1 - EXTRAIR ARQUIVOS E INFORMACOES
	* Cria um arquito .txt com informações de produtos
	* Objetivo: Criar de um arquivo texto contendo um json que será utilizado para cadastro de produtos.
	*/
	// $extrair = new Extracao();
	// $extrair->extrairArquivos();

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


	// var_dump("PASTAS: ");
	// var_dump($pastasFotografos);

	//Lê pasta de cada fotografo
	foreach($pastasFotografos as $pasta) {
		// Procurar pelo txt
		$caminhoPastaFotografo = $pastaPostagens . $pasta;
		$caminhoPastaFotosMarcaDagua = $caminhoPastaFotografo . '/' . $pastaFotosMarcaDagua;
		mkdir($caminhoPastaFotosMarcaDagua, 0755, true);

		// var_dump("XXXXX");
		// var_dump($caminhoPastaFotografo);die;
		if(is_dir($caminhoPastaFotografo)) {
			$arquivos = scandir($caminhoPastaFotografo);
			unset($arquivos[0]); //remove .
			unset($arquivos[1]); //remove ..

			$arquivosFotografo = array();
			foreach($arquivos as $arquivo){
				$ext = pathinfo($arquivo, PATHINFO_EXTENSION);
				var_dump("Extensão: ", $ext);

				if(in_array($ext, array('jpg', 'jpeg')))
					$arquivosFotografo['image'][] = $arquivo;
				elseif( $ext === 'txt')
					$arquivosFotografo['txt'] = $arquivo;
			}

			
			/*
			* Neste ponto temos uma variável com o nome do arquivo txt 
			* e um array com nomes das imagens
			*/

			/*
			* LER ARQUIVO TXT COM JSON
			*/
			$caminhoTxtJsonFile = $caminhoPastaFotografo . '/' . $arquivosFotografo['txt'];
			$file = fopen($caminhoTxtJsonFile, "r");
			// var_dump(fread($file, filesize($caminhoTxtJsonFile)));
			$jsonProdutos = json_decode(fread($file, filesize($caminhoTxtJsonFile)));
			fclose($file);


			//tenho o json, andar em loop cadastrando os produtos.
			// var_dump("Conteúdo txt");
			// var_dump($jsonProdutos);

			var_dump("Exibir JSON");
			foreach($jsonProdutos as $jsonProduto) 
			{
				var_dump($jsonProduto);
				var_dump("###############");
				$foto = $caminhoPastaFotografo . '/' . $jsonProduto->arquivoFoto;
				// die;
		

		// die("OKOKOKOK");
	// 	}
	// }

	// var_dump("Fim Busca JSON - ");
	// die("OK");


	// Pŕoximos passos:
	// - Mover imagens para as pastas corretas;


				/* 2 - IMAGEM MARCA DAGUA
				* Criação de imagens com marca dagua.
				* Inicialização configura o conversor.
				* Objetivo: criar um serviço de leitura de imagen para conversão em massa das imagens
				*/
				// $iMage = new MarcaDagua(4, 35, "marca-dagua.png"); //PENDENTE: setar também o caminho das imagens 
			//*********	
				//Cria imagem com a marca dagua
				// $iMage->aplicarMarcaDagua("a.jpg");
				// var_dump($foto);die;
				$iMage->aplicarMarcaDagua($jsonProduto->arquivoFoto, $caminhoPastaFotografo, 
										  $jsonProduto->nomeMascara, $caminhoPastaFotosMarcaDagua);

// 				var_dump("ARRAY");
// 				var_dump(
// 					array($jsonProduto->nomeFotografo, //'JHONE BERING', 
// 						'catalog/' . $jsonProduto->nomeMascara, //Depende de enviar a foto para a pasta correta
// 						4.9, 
// 						$jsonProduto->tituloProduto, //'Titulo do produto 13', 
// 						$jsonProduto->tituloProduto. ' - Download', 
// 						$jsonProduto->arquivoFoto, //'cachoeira-bras-gomes.jpeg', //Em "/storage/download/"
// 						$jsonProduto->nomeMascara. rand(1000000, 9999999) //'mask-cachoeira-bras-gomes.jpeg'
// 				));
// continue;
				/* 3 - CADASTRO DE PRODUTO
				* Cria produto na loja opencart com os dados fornecido.
				* Cria arquivo de download e vincula ao produto cadastrado.
				* Objetivo: Cadastrar uma foto como produto para venda que possibilite o cliente realizar
				* download da imagem sem marca d'água e boa qualidade.
				*/
				$produto = new Produto();
				//cadastro o produto
				// $produto->criaProduto(
				// 		'JHONE BERING', 
				// 		'catalog/mascara-peste-negra.jpeg', 
				// 		4.9, 
				// 		'Titulo do produto 13', 
				// 		'Arquivo para venda de Download', 
				// 		'cachoeira-bras-gomes.jpeg', 
				// 		'mask-cachoeira-bras-gomes.jpeg'
				// );
				$produto->criaProduto(
						$jsonProduto->nomeFotografo, //'JHONE BERING', 
						'catalog/fotosMarcaDagua/' . $jsonProduto->nomeMascara, //Depende de enviar a foto para a pasta correta
						4.9, 
						$jsonProduto->tituloProduto, //'Titulo do produto 13', 
						$jsonProduto->tituloProduto. ' - Download', 
						$jsonProduto->arquivoFoto, //'cachoeira-bras-gomes.jpeg', //Em "/storage/download/"
						$jsonProduto->nomeMascara. rand(1000000, 9999999) //'mask-cachoeira-bras-gomes.jpeg'
				);

				// Pŕoximos passos:
				// - Mover imagens para as pastas corretas;
			}
		}
	}

//******


	/*
		Planejamento:
		- Criar imagens com marca d'água: OK
		- Criar um serviço recursivo para criar as imagens em massa: Pendente
		- Criar query no banco de dados para criar produto: OK
		- Criar um json para alimentar em massa as querys que serão executadas: OK
			- Identificar em cada produto quem é o autor da foto: OK
		- Criar conexão com banco de dados no script para executar as query: OK
		- Criar conexão com ftp para enviar as imagens criadas: Pendente (por enquanto mover dentro do próprio servidor)
		- Testar o resultado dentro do Opencart: Pendente
	*/
?>