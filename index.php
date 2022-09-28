<?php
	//phpinfo();


	// imagescale('c.jpg', 200,-1, IMG_NEAREST_NEIGHBOUR);

	// die;

	//set the source image (foreground)
	$imagemOriginal = 'd.jpg';

	//set the destination image (background)
	$marcaDagua = 'marca-dagua.png';

	//get the size of the source image, needed for imagecopy()
	list($destWidth, $destHeight) = getimagesize($imagemOriginal);
	list($srcWidth, $srcHeight) = getimagesize($marcaDagua);

	// var_dump($srcWidth, $srcHeight);die;
	

	//create a new image from the source image
	$srcMarcaDagua = imagecreatefrompng($marcaDagua);

	//create a new image from the destination image
	$destImagemOriginal = imagecreatefromjpeg($imagemOriginal);


	// var_dump($destWidth, $destHeight, $srcWidth, $srcHeight);
	//merge the source and destination images
	imagecopyresized($destImagemOriginal, $srcMarcaDagua, 0, 0, 0, 0, $destWidth, $destHeight, $srcWidth, $srcHeight);
	// imagecopyresized($destImagemOriginal, $srcMarcaDagua, 0, 0, 0, 0, $destWidth, $destHeight, 1250, 860);


	$proporcao = 4;
	$finalWidth = $destWidth / $proporcao ;
	$finalheight = $destHeight / $proporcao ;


	$nova_imagem = imagecreatetruecolor( $finalWidth, $finalheight );

	// Diminui o tamanho da foto de forma proporcional
	imagecopyresampled($nova_imagem, $destImagemOriginal, 0, 0, 0, 0, $finalWidth, $finalheight, $destWidth, $destHeight);
	//output the merged images to a file
	/*
	 * '100' is an optional parameter,
	 * it represents the quality of the image to be created,
	 * if not set, the default is about '75'
	 */
	imagejpeg($nova_imagem, 'c.jpg', 35);


	// Output and free from memory
	header('Content-Type: image/gif');
	imagegif($nova_imagem);

	//destroy the source image
	imagedestroy($srcMarcaDagua);

	//destroy the destination image
	imagedestroy($destImagemOriginal);
	imagedestroy($nova_imagem);



?>


