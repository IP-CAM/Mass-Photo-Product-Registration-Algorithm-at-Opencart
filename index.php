<?php
	declare(strict_types=1);
	header('Content-Type: application/json');

	require "./src/marcaDaguaController.php";

	use controller\MarcaDaguaController as MarcaDagua;

	$iMage = new MarcaDagua(4, 35, "marca-dagua.png");
	$iMage->aplicarMarcaDagua("a.jpg");
?>