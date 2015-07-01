<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");


function getImage($file, $width = -1, $maxWidth = -1, $maxHeight = -1) {
	header("Expires: Mon, 20 Dec 1998 01:00:00 GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	ini_set("memory_limit", "-1");

	if (!file_exists($file))
		$file = DEFAULT_IMAGE;

	$ext = stringToUpper(substr($file, strrpos($file, "."), 20));

	if (($ext == ".JPG") or ($ext == ".JPEG"))
		getImageJpg($file, $width, $maxWidth, $maxHeight);

	if ($ext == ".GIF")
		getImageGif($file, $width, $maxWidth, $maxHeight);

	if ($ext == ".PNG")
		getImagePng($file, $width, $maxWidth, $maxHeight);
}

function getImageGif($file, $width, $maxWidth, $maxHeight) {
	header("Content-type: image/gif");

	$size = getimagesize($file);		/* Propiedades de la imagen */

	if ($width == -1) {
		$height = $size[1];
		$width = $size[0];
	}
	else {
		$porc = $width * 100 / $size[0];		// Calculo el porcentaje en que se reduce la imagen..
		$height = $porc * $size[1] / 100;		// Calculo el alto de la nueva imagen..
	}

	if ($maxWidth != -1)		// Si la imagen tiene que tener un ancho máximo..
		if ($width > $maxWidth) {		// Si el ancho es mayor al ancho máximo..
			$height = $maxWidth * $height / $width;		// Calculo el alto proporcional al ancho máximo..
			$width = $maxWidth;		// Le asigno el ancho máximo..
		}

	if ($maxHeight != -1)		// Si la imagen tiene que tener un alto máximo..
		if ($height > $maxHeight) {		// Si el alto es mayor al ancho máximo..
			$width = $maxHeight * $width / $height;		// Calculo el alto proporcional al ancho máximo..
			$height = $maxHeight;		// Le asigno el ancho máximo..
		}

	$im = imagecreatefromgif($file);		/* Tomo la imagen de origen */
	$img = imagecreatetruecolor($width, $height);		/* [0] Nuevo ancho, [1] ALTO, me creo un CANVAS, algo similar que en Firework */
//	imagecopyresized($img, $im, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);		/* Copio en mi CANVAS la imagen $im en la dimensión que deseo */
	imagecopyresampled($img, $im, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
	imagegif($img);		/* Exporto la CANVAS a GIF */
	imagedestroy($img);		/* La Borro de la Cache */
}

function getImageJpg($file, $width, $maxWidth, $maxHeight) {
	header("Content-type: image/jpeg");

	$size = getimagesize($file);		/* Propiedades de la imagen */
	if ($width == -1) {
		$height = $size[1];
		$width = $size[0];
	}
	else {
		$porc = $width * 100 / $size[0];		// Calculo el porcentaje en que se reduce la imagen..
		$height = $porc * $size[1] / 100;		// Calculo el ancho de la nueva imagen..
	}

	if ($maxWidth != -1)		// Si la imagen tiene que tener un ancho máximo..
		if ($width > $maxWidth) {		// Si el ancho es mayor al ancho máximo..
			$height = $maxWidth * $height / $width;		// Calculo el alto proporcional al ancho máximo..
			$width = $maxWidth;		// Le asigno el ancho máximo..
		}

	if ($maxHeight != -1)		// Si la imagen tiene que tener un alto máximo..
		if ($height > $maxHeight) {		// Si el alto es mayor al ancho máximo..
			$width = $maxHeight * $width / $height;		// Calculo el alto proporcional al ancho máximo..
			$height = $maxHeight;		// Le asigno el ancho máximo..
		}

	$im = imagecreatefromjpeg($file);		/* Tomo la imagen de origen */
	$img = imagecreatetruecolor($width, $height);		/* [0] Nuevo ancho, [1] ALTO, me creo un CANVAS, algo similar que en Firework */
//	imagecopyresized($img, $im, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);		/* Copio en mi CANVAS la imagen $im en la dimensión que deseo */
	imagecopyresampled($img, $im, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
	imagejpeg($img);		/* Exporto la CANVAS a JPG */
	imagedestroy($img);		/* La Borro de la Cache */
}

function getImagePng($file, $width, $maxWidth, $maxHeight) {
	header("Content-type: image/png");

	$size = getimagesize($file);		/* Propiedades de la imagen */
	if ($width == -1) {
		$height = $size[1];
		$width = $size[0];
	}
	else {
		$porc = $width * 100 / $size[0];		// Calculo el porcentaje en que se reduce la imagen..
		$height = $porc * $size[1] / 100;		// Calculo el ancho de la nueva imagen..
	}

	if ($maxWidth != -1)		// Si la imagen tiene que tener un ancho máximo..
		if ($width > $maxWidth) {		// Si el ancho es mayor al ancho máximo..
			$height = $maxWidth * $height / $width;		// Calculo el alto proporcional al ancho máximo..
			$width = $maxWidth;		// Le asigno el ancho máximo..
		}

	if ($maxHeight != -1)		// Si la imagen tiene que tener un alto máximo..
		if ($height > $maxHeight) {		// Si el alto es mayor al ancho máximo..
			$width = $maxHeight * $width / $height;		// Calculo el alto proporcional al ancho máximo..
			$height = $maxHeight;		// Le asigno el ancho máximo..
		}

	$im = imagecreatefrompng($file);		/* Tomo la imagen de origen */
	$img = imagecreatetruecolor($width, $height);		/* [0] Nuevo ancho, [1] ALTO, me creo un CANVAS, algo similar que en Firework */
//	imagecopyresized($img, $im, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);		/* Copio en mi CANVAS la imagen $im en la dimensión que deseo */
	imagecopyresampled($img, $im, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
	imagepng($img);		/* Exporto la CANVAS a PNG */
	imagedestroy($img);		/* La Borro de la Cache */
}

function validarExtension($archivo, $extensionesValidas = array("bmp", "gif", "jpg", "jpeg", "png")) {
	$ext = stringToLower(substr($archivo, strrpos($archivo, ".") + 1, 20));
	return in_array($ext, $extensionesValidas);
}
?>