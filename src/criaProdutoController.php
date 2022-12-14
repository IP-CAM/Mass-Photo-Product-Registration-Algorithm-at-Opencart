<?php
namespace controller;

require "Models/CriaProduto.php";

use models\CriaProduto as Produto;

class criaProdutoController {

	private $_produto;

	function __construct() {

		$this->_produto = new Produto();
	}

	/**
	 * criaProduto: Cria um novo produto na loja Opencart com informações extraída da imagem a ser
	 * cadastrada para venda como download.
	 * Params: 
	 * - idCategoria:    integer  Id identificador da categoria que o produto será inserido.
	 * - nomeFotografo:  string   Nome do fotografo utilizado para identificar quem é autor da foto.
	 * - imagemProduto:  string   Caminho da imagem com marca d'água que será exibida para o usuário na compra.
	 * - valor:          float    Valor a ser comercializado na foto.
	 * - tituloProduto   string   Título para identificar o produto a ser vendido.
	 * - tituloDownload  string   Título do cadastro do download que será vinculado ao produto.
	 * - arquivoDownload string   Nome e extensão do arquivo que será disponibilizado após a compra, o arquivo deve ser 
	 * armazenado no diretório "/storage/download/".
	 * - maskDownload    string   Nome para mascarar o nome e endereço originalo do arquivo para evitar downloads indevidos.
	 * Return:
	 * - Not Return
	 */
	public function criaProduto($idCategoria, $nomeFotografo, $imagemProduto, $valor, $tituloProduto, $tituloDownload, $arquivoDownload, $maskDownload) { 

		/*
		* VERIFICAÇÃO
		* Verificar se todos os parâmetros necessários estão disponíveis
		* 1 - Verificar se o id da categoria foi enviado;
		* 2 - Fazer uma chamada para verificar se a categoria informada de fato existe;
		* 3 - 
		*/



		//Criação de produto
		$idProduto = $this->_produto->criaProduto($nomeFotografo, $imagemProduto, $valor);
		$this->_produto->criaDescricaoProduto($idProduto, $tituloProduto);
		$this->_produto->insereCategoria($idProduto, $idCategoria);

		//Criação de download
		$idDownload = $this->_produto->criaDownload($tituloDownload, $arquivoDownload, $maskDownload);
		$this->_produto->vinculaDownloadAoProduto($idProduto, $idDownload);
	}
}

?>