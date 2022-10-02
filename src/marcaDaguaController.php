<?php
	namespace controller;
    require "Models/MarcaDagua.php";
	
	use models\MarcaDagua;

    class MarcaDaguaController {

    	private $_iMage;
    	private $_imagem;
    	private $_porporcaoResize;
		private $_qualidadeResize;
		private $_imagemResultado;

    	public function __construct(int $porporcaoResize, int $qualidadeResize, string $imagemMarcaDagua)
		{
			$this->_iMage = new MarcaDagua();
			$this->_porporcaoResize = $porporcaoResize;
			$this->_qualidadeResize = $qualidadeResize;
			$this->_imagemResultado = "resultado.jpg";

			$this->_iMage->setImagemMarcaDagua($imagemMarcaDagua);
		}

		public function aplicarMarcaDagua($imagemOriginal) {
			$this->_iMage->setImagemOriginal($imagemOriginal);
			$this->_iMage->criarImagemComMarcaDagua();
			$this->_iMage->resizeImagem($this->_porporcaoResize);
			$this->_iMage->salvarNovaImagem($this->_imagemResultado , $this->_qualidadeResize);
			$this->_iMage->imprimirResultado();

			$this->_iMage->destroiImagens();
		}
    }
?>