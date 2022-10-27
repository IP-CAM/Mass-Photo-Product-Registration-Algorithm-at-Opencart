<?php
namespace controller;

require "Models/ExtraiImagens.php";

use models\ExtraiImagens as Extrator;

class ExtraiImagensController {

	private $_extrator;

	function __construct() {

		$this->_extrator = new Extrator();
	}

	/**
	 * criaProduto: Cria um novo produto na loja Opencart com informações extraída da imagem a ser
	 * cadastrada para venda como download.
	 * Params: 
	 * - model:          string   Model utilizado para identificar quem é autor da foto (Trocar por algum outro identificador)
	 * - imagemProduto:  string   Caminho da imagem com marca d'água que será exibida para o usuário na compra
	 * - valor:          float    Valor a ser comercializado na foto
	 * - tituloProduto   string   Título para identificar o produto a ser vendido
	 * - tituloDownload  string   Título do cadastro do download que será vinculado ao produto
	 * - arquivoDownload string   Nome e extensão do arquivo que será disponibilizado após a compra, o arquivo deve ser armazenado
	 * no diretório "/storage/download/"
	 * - maskDownload    string   Nome para mascarar o nome e endereço originalo do arquivo para evitar downloads indevidos
	 * Return:
	 * - Not Return
	 */

	public function extrairArquivos() 
	{

		$this->_extrator->extrairImagensZip();

return;
		$pastaUploads = "uploads/";
		$arquivoZip = null;

/* IDENTIFICA ZIP #########################
*  Lê arquivo zip e extrai informações do nome
*/
		if(is_dir($pastaUploads)){
			$arquivos = scandir($pastaUploads);
			unset($arquivos[0]); //remove .
			unset($arquivos[1]); //remove ..
			var_dump($arquivos);
			foreach($arquivos as $arquivo){
				if( ($ext = pathinfo($arquivo, PATHINFO_EXTENSION)) === 'zip'){
					$arquivoZip = $arquivo;
				}
			}
		} else {
				echo("Pasta não encontrada");
		}

		$informacoesZip = explode('_', explode('.', $arquivoZip)[0]);

		$autor = $informacoesZip[0];
		$evento = $informacoesZip[1];
		$dataEvento = $informacoesZip[2];

		var_dump("Arquivo: ", $arquivoZip);
		var_dump("Autor: ", $autor);
		var_dump("Evento: ", $evento);
		var_dump("Data: ", $dataEvento);
//#################################

/* EXTRAIR ZIP #########################
*  Cria pasta e extrair os arquivos zip
*/
		//Criar pasta onde será extraido os arquivos
		$folderExtracted = $autor.'_'.$evento.'_'.$dataEvento;
		mkdir(getcwd().'/'.$pastaUploads.$folderExtracted, 0755, true);

		//Extrai arquivos
		$zip = new \ZipArchive;
		if($zip->open(getcwd().'/'.$pastaUploads.$arquivoZip) === TRUE) {
		    $zip->extractTo(getcwd().'/'.$pastaUploads.$folderExtracted);
		    $zip->close();
		    echo 'ok';
		}else {
		    echo 'failed';
		}
		echo "<br/><br/><br/>";
//#######################################


/* SALVAR JSON #########################
*  Extrai informações de cada arquivo de imagem
*  e salva um json em um arquivo txt
*/		
		//Listar arquivos extraidos
		if(is_dir($pastaUploads.$folderExtracted)){
			$listaDeFotos = scandir($pastaUploads.$folderExtracted);
			unset($listaDeFotos[0]); //remove .
			unset($listaDeFotos[1]); //remove ..
			var_dump($listaDeFotos);
			var_dump(count($listaDeFotos));
			// foreach($arquivos as $arquivo){
			// 	if( ($ext = pathinfo($arquivo, PATHINFO_EXTENSION)) === 'zip'){
			// 		$arquivoZip = $arquivo;
			// 	}
			// }
		} else {
				echo("Pasta não encontrada");
		}

		//Criar conteúdo json para ser gravado no arquivo
		$qtdDeImagens = count($listaDeFotos);
		$json = array();

		foreach($listaDeFotos as $foto) 
		{
			$formatosAceitos = array('jpg', 'jpeg');
			$ext = pathinfo($foto, PATHINFO_EXTENSION);
			$nomeEvento = ucwords(str_replace("-", " ", $evento));
			$nomeAutor = ucwords(str_replace("-", " ", $autor));

			if(!in_array($ext, $formatosAceitos))
				continue;

			$json[] = array(
				'evento' => $nomeEvento,
				'nomeFotografo' => $nomeAutor,
				'dataEvento' => $dataEvento,
				'arquivoFoto' => $foto,
				'nomeMascara' => rand(1000000, 9999999)."_".$foto,
				'preco' => '4.50',
				'tituloProduto' => $nomeEvento . " - cod" . rand(1000000, 9999999)
			);
		}


		//Gravar em arquivo
		$jsonFile = $pastaUploads.$folderExtracted.'/jsonFile.txt';

		$file = fopen($jsonFile, "w+");
		fwrite($file, json_encode($json));

		fclose($file);

//#######################################


	}
	// public function criaProduto($model, $imagemProduto, $valor, $tituloProduto, $tituloDownload, $arquivoDownload, $maskDownload) { 
	// 	//Criação de produto
	// 	$idProduto = $this->_produto->criaProduto($model, $imagemProduto, $valor);
	// 	$this->_produto->criaDescricaoProduto($idProduto, $tituloProduto);

	// 	//Criação de download
	// 	$idDownload = $this->_produto->criaDownload($tituloDownload, $arquivoDownload, $maskDownload);
	// 	$this->_produto->vinculaDownloadAoProduto($idProduto, $idDownload);
	// }
}

?>