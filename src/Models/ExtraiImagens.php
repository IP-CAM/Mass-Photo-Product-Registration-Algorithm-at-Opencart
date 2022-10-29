<?php
namespace models;

class ExtraiImagens {

	private $_bd;

	function __construct() {
		$this->_bd = new Banco();
	}

	public function extrairImagensZip() {

		$pastaUploads = "uploads/";
		$arquivosZip = array();

/* IDENTIFICA ZIP #########################
*  Lê arquivo zip e extrai informações do nome
*/
		if(is_dir($pastaUploads)){
			$arquivos = scandir($pastaUploads);
			unset($arquivos[0]); //remove .
			unset($arquivos[1]); //remove ..
			// var_dump($arquivos);
			foreach($arquivos as $arquivo){
				if( ($ext = pathinfo($arquivo, PATHINFO_EXTENSION)) === 'zip'){
					$arquivosZip[] = $arquivo;
				}
			}
		} else {
				echo("Pasta não encontrada");
		}

		foreach($arquivosZip as $arquivoZip) {

			$informacoesZip = explode('_', explode('.', $arquivoZip)[0]);

			$autor = $informacoesZip[0];
			$evento = $informacoesZip[1];
			$dataEvento = $informacoesZip[2];

			// var_dump("Arquivo: ", $arquivoZip);
			// var_dump("Autor: ", $autor);
			// var_dump("Evento: ", $evento);
			// var_dump("Data: ", $dataEvento);
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
			// echo "<br/><br/><br/>";
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
				// var_dump("Total de fotos");
				// var_dump($listaDeFotos);
				// die;
				// var_dump($listaDeFotos);
				// var_dump(count($listaDeFotos));
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
				$formatosAceitos = array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG');
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
	}

}
