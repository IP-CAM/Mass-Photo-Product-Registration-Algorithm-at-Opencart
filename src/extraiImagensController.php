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

	}
}

?>