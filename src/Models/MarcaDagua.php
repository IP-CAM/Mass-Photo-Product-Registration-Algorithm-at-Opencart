<?php

class MarcaDagua 
{
	private $_imagemOriginal;
	private $_oringialWidth;
	private $_oringialHeight;
	private $_memoriaImagemOriginal;

	private $_imagemMarcaDagua;
	private $_marcaDaguaWidth;
	private $_marcaDaguaHeight;
	private $_memoriaImagemMarcaDagua;

	function __construct()
	{

	}

	public function setImagens($imagemOriginal, $imagemMarcaDagua) {
		$this->setImagemOriginal($imagemOriginal);
		$this->setImagemMarcaDagua($imagemMarcaDagua);
	}

	public function setImagemOriginal($imagem) {
		$this->_imagemOriginal = $imagem;
		list($this->_oringialWidth, $this->_oringialHeight) = getimagesize($imagem);
		$this->_memoriaImagemOriginal = imagecreatefromjpeg($imagem);
	}

	public function getImagemOriginal() {
		return $this->_imagemOriginal;
	}

	public function setImagemMarcaDagua($imagem) {
		$this->_imagemMarcaDagua = $imagem;
		list($this->_marcaDaguaWidth, $this->_marcaDaguaHeight) = getimagesize($imagem);
		$this->_memoriaImagemMarcaDagua = imagecreatefrompng($imagem);
	}

	public function getImagemMarcaDagua() {
		return $this->_imagemMarcaDagua;
	}

	public function criarImagemComMarcaDagua() {
		imagecopyresized(
			$this->_memoriaImagemOriginal, 
			$this->_memoriaImagemMarcaDagua, 
			0, 0, 0, 0, 
			$this->_oringialWidth, $this->_oringialHeight, 
			$this->_marcaDaguaWidth, $this->_marcaDaguaHeight
		);
	}

	public function resizeImagem($proporcao) {
		$widthFinal = $this->_oringialWidth / $proporcao;
		$heightFinal = $this->_oringialHeight / $proporcao;

		$imagem_temp = imagecreatetruecolor($widthFinal, $heightFinal);

		imagecopyresampled(
			$imagem_temp, 
			$this->_memoriaImagemOriginal, 
			0, 0, 0, 0, 
			$widthFinal, $heightFinal, 
			$this->_oringialWidth, $this->_oringialHeight
		);

		$this->_memoriaImagemOriginal = $imagem_temp;
	}

	public function salvarNovaImagem($nomeImagem, $qualidade) {

		imagejpeg($this->_memoriaImagemOriginal, $nomeImagem, $qualidade);
	}

	public function imprimirResultado() {
		header('Content-Type: image/gif');
		imagegif($this->_memoriaImagemOriginal);
	}

	public function destroiImagens() {
		imagedestroy($this->_memoriaImagemOriginal);
		imagedestroy($this->_memoriaImagemMarcaDagua);
	}
}

?>