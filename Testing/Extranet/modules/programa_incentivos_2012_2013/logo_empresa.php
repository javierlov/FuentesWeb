<?
session_start();
header("Content-type: image/jpeg");

$file = "http://".$_SERVER["HTTP_HOST"]."/modules/programa_incentivos_2012_2013/images/empresas.jpg";
$height = 35;

switch ($_SESSION["idEmpresa"]) {
	case 1:		// ART..
		$left = 168;
		$width = 92;
		break;
	case 2:		// Fondos..
		$left = 316;
		$width = 88;
		break;
	case 5:		// Seguros..
		$left = 0;
		$width = 88;
		break;
	case 6:		// Pagos..
		$left = 428;
		$width = 84;
		break;
	case 7:		// Leasing..
		$left = 594;
		$width = 86;
		break;
	case 8:		// Bursatil..
		$left = 256;
		$width = 90;
		break;
	case 9:		// Vida..
		$left = 84;
		$width = 90;
		break;
	case 10:	// Mandatos..
		$left = 510;
		$width = 88;
		break;
}

$im = imagecreatefromjpeg($file);		/* Tomo la imagen de origen */
$img = imagecreatetruecolor($width, $height);		/* [0] Nuevo ancho, [1] ALTO, me creo un CANVAS, algo similar que en Firework */
//	imagecopyresized($img, $im, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);		/* Copio en mi CANVAS la imagen $im en la dimensión que deseo */
imagecopyresampled($img, $im, 0, 0, $left, 0, $width, $height, $width, $height);
imagejpeg($img);		/* Exporto la CANVAS a JPG */
imagedestroy($img);		/* La Borro de la Cache */
?>