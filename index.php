<?php
	declare(strict_types=1);
	// header("Content-type: text/html; charset=utf-8");
	header('Content-Type: application/json');

	require "./src/Models/MarcaDagua.php";

	$iMage = new MarcaDagua();

	$iMage->setImagemOriginal('a.jpg');
	$iMage->setImagemMarcaDagua('marca-dagua.png'); //Obrigatório ser um png

	$iMage->criarImagemComMarcaDagua();

	$iMage->resizeImagem(4);
	$iMage->salvarNovaImagem("resultado.jpg", 35);

	$iMage->imprimirResultado();

	$iMage->destroiImagens();

?>


