<?php
namespace models;

require "BancoDeDados.php";
use models\Banco;

class CriaProduto {

	private $_bd;

	function __construct() {
		$this->_bd = new Banco();
	}

	/**
	 * criaProduto: Insere registro de um novo produto
	 * Params: 
	 * - nomeFotografo: string  Nome do Fotogrado usado no SKU para identificar o fotografo
	 * - image: string  caminho da imagem thumbnails com a marca d'agua que será visível para o usuário
	 * - price: float   valor de venda da foto
	 * Return:
	 * - id:    integer Identificador do produto se for criado
	 */
	public function criaProduto($nomeFotografo, $image, $price) {
		$dateCriacao = date('Y-m-d H:i:s');

		$queryInsertProduto = "INSERT INTO 
			ocbr_product (
				model,
				sku, 
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
				'Fotos', 
				'$nomeFotografo',
				'$image', 
				$price, 
				'$dateCriacao', 
				'$dateCriacao', 
				'$dateCriacao',
				'100', 
				'1',
				'', '5', '0', '0', '1', '0', '0'
			)
		";

		$idProduto = $this->_bd->insert($queryInsertProduto);
		$this->_vinculaProdutoCriado($idProduto);

		return $idProduto;
	}

	/**
	 * _vinculaProdutoCriado: Insere registro que vincula produto a loja e layout
	 * Params: 
	 * - idProduto: integer Id identificar do produto
	 * Return:
	 * - Not return
	 */
	private function _vinculaProdutoCriado($idProduto) {
		//Vincula o produto a loja
		$queryInsertProdutoLoja = "INSERT INTO 
			ocbr_product_to_store 
				(product_id, store_id) 
			VALUES ($idProduto, '0')";

		$this->_bd->insert($queryInsertProdutoLoja);

		//Vincula produto a um layout
		$queryInsertLayoutProduto = "INSERT INTO 
			ocbr_product_to_layout 
				(product_id, store_id, layout_id) 
			VALUES ($idProduto, '0', '0')";

		$this->_bd->insert($queryInsertLayoutProduto);
	}

	/**
	 * criaDescricaoProduto: Insere informações ao produto com título e descrição
	 * Params: 
	 * - idProduto:       integer Id identificar do produto
	 * - tituloProduto:   string  Titulo e meta titulo do produto
	 * Return:
	 * - Not return
	 */
	public function criaDescricaoProduto($idProduto, $tituloProduto) {
		$queryInsertDescricao = "INSERT INTO 
			ocbr_product_description (
				product_id, language_id, name, description, tag, meta_title, meta_description, meta_keyword
			) 
			VALUES 
			($idProduto, '1', '$tituloProduto', '', '', '$tituloProduto', '', ''), 
			($idProduto, '2', '$tituloProduto', '', '', '$tituloProduto', '', '')
		";

		 $this->_bd->insert($queryInsertDescricao);
	}


	/**
	 * insereCategoria: Atribui uma categoria ao produto. A categoria precisa ser criada antes de fazer a 
	 * inserção das 
	 * fotos, a ausencia da categoria invalida o resto do processo.
	 * PENDECIA: Antes de tudo, verificar se a categoria informada existe, se não, interrompe o processo
	 * 
	 * Params:
	 * - idProduto:     integer    Id identificador do produto que foi recém criado;
	 * - idCategoria:   integer    Id da categoria que o produto será inserido;
	 * Return: 
	 * - Empty return
	*/
	public function insereCategoria($idProduto, $idCategoria) {
		$queryInsereCategoria = "
			INSERT INTO
				`ocbr_product_to_category`
					(`product_id`, `category_id`)
				VALUES 
					($idProduto, $idCategoria)";

		$this->_bd->insert($queryInsereCategoria);
	}

	/**
	 * criaDownload: Cria o download que será disponibilizado após a compra do produto
	 * Params: 
	 * - nomeDonwload:             string   Título do cadastro do Download
	 * - nomeArquivoDownload:      string   Nome do arquivo original armazenado que será fornecido para download
	 * - mascaraArquivoDownload:   string   Nome para mascarar o nome do arquivo para evitar download indevido
	 * Return:
	 * - idDownload:               integer  Id identificador do download criado
	 */
	public function criaDownload($nomeDonwload, $nomeArquivoDownload, $mascaraArquivoDownload) {
		$dataCriacao = date('Y-m-d H:i:s');

		$queryInsertDownload = "INSERT INTO 
			`ocbr_download` 
				(`filename`, `mask`, `date_added`) 
			VALUES 
				('$nomeArquivoDownload', '$mascaraArquivoDownload', '$dataCriacao')";
		$idDownload = $this->_bd->insert($queryInsertDownload);

		$queryDescricaoDownload = "INSERT INTO
			ocbr_download_description
				(download_id, language_id, name)
			VALUES 
				($idDownload, 1, '$nomeDonwload'),
				($idDownload, 2, '$nomeDonwload')
			";
			
		$this->_bd->insert($queryDescricaoDownload);

		return $idDownload;
	}

	/**
	 * vinculaDownloadAoProduto: Insere registro que vincula o Download a um produto, é feito separado para possibilitar
	 * que se vincule um download a diferentes produtos
	 * Params: 
	 * - idProduto:    integer    Id identificar do produto
	 * - idDownload:   integer    Id identificador do download a ser vinculado ao produto
	 * Return:
	 * - Not return
	 */
	public function vinculaDownloadAoProduto($idProduto, $idDownload) {

		$queryInsertDownloadToProduto = "INSERT INTO 
			ocbr_product_to_download 
				(`product_id`, `download_id`) 
			VALUES 
				($idProduto, $idDownload)";

		$this->_bd->insert($queryInsertDownloadToProduto);
	}
}

?>