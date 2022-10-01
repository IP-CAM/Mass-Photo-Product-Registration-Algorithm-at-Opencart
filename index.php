<?php
	declare(strict_types=1);

	require "./src/Models/MarcaDagua.php";

	$imagemOriginal = "a.jpg";
	$imagemMarcaDagua = "marca-dagua.png";
	$imagemResultado = "resultado.jpg";
	$porporcaoResize = 4;
	$qualidadeResize = 35;

	$iMage = new MarcaDagua();

	$iMage->setImagens($imagemOriginal, $imagemMarcaDagua);
			// $iMage->setImagemOriginal($imagemOriginal);
			// $iMage->setImagemMarcaDagua($imagemMarcaDagua);

	$iMage->criarImagemComMarcaDagua();

	$iMage->resizeImagem($porporcaoResize);
	$iMage->salvarNovaImagem($imagemResultado , $qualidadeResize);

	$iMage->imprimirResultado();

	$iMage->destroiImagens();

?>


