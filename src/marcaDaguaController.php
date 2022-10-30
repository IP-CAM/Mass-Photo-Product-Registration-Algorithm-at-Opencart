<?php
	namespace controller;
    require "Models/MarcaDagua.php";
	
	use models\MarcaDagua;

    class MarcaDaguaController {

    	private $_iMage;
    	private $_imagem;
    	private $_porporcaoResize;
		private $_qualidadeResize;
		private $_imagemMarcaDagua;
		// private $_imagemResultado;

    	public function __construct(int $porporcaoResize, int $qualidadeResize, string $imagemMarcaDagua)
		{
			$this->_iMage = new MarcaDagua();
			$this->_porporcaoResize = $porporcaoResize;
			$this->_qualidadeResize = $qualidadeResize;
			$this->_imagemMarcaDagua = $imagemMarcaDagua;
		}

		public function aplicarMarcaDagua($imagemOriginal, $pasta, $imagemMarcaDagua, $pastaDestino) {

			var_dump("Vai ser convertido");
			var_dump($pasta . '/' . $imagemOriginal);
			var_dump("Será enviado para");
			var_dump($pastaDestino . '/' . $imagemMarcaDagua);
			//die;
			var_dump("IMAGEM CONVERTENDO");
			var_dump($imagemOriginal);
			$ext = pathinfo($imagemOriginal, PATHINFO_EXTENSION);
			var_dump($ext);
			// if(!in_array($ext, array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG')))
			// 	return;

			$this->_iMage->setImagemMarcaDagua($this->_imagemMarcaDagua);

			$this->_iMage->setImagemOriginal($pasta . '/' . $imagemOriginal);
			$this->_iMage->criarImagemComMarcaDagua();
			$this->_iMage->resizeImagem($this->_porporcaoResize);
			$this->_iMage->salvarNovaImagem($pastaDestino . '/' . $imagemMarcaDagua , $this->_qualidadeResize);
			
			//Imprime imagem na tela
			// $this->_iMage->imprimirResultado();

			$this->_iMage->destroiImagens();
			// var_dump($imagemOriginal);die("TTT");
		}
    }
?>