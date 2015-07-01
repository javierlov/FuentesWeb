<?
session_start();


// Devuelve un caracter aleatorio..
function caracterAleatorio() {
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789";
	return substr($chars, rand() % strlen($chars), 1);
}


// Configuraci�n:
$J = 100;	// Calidad JPEG { 0, 1, 2, 3, ..., 100 }
$M = 4;		// Margen.
$L = 7;		// N�mero de letras.
$C = false;	// Case sensitive.

// Indicamos que vamos a generar una imagen �no una p�gina HTML!
header("Content-type: image/jpeg");

// Inicializamos cualquier posible valor previo de captcha
$_SESSION["captcha"] = "";

// Metemos tantos caraceteres aleatorios como sean precisos
for ($n = 0; $n < $L; $n++)
	$_SESSION["captcha"].= caracterAleatorio();

// Si no es case sensitive lo ponemos todo en min�sculas
if (!$C)
	$_SESSION["captcha"] = strtolower($_SESSION["captcha"]);

// Dimensiones del captcha
$w = 2 * $M + $L * imagefontwidth(5);
$h = 2 * $M + imagefontheight(5);

// Creamos una  imagen
$i = imagecreatetruecolor($w,$h);

// La rellenamos de blanco
imagefill($i, 0, 0, imagecolorallocate($i, 255, 255, 255));

// Elegimos aleatoriamente un �ngulo de emborronado
$A = (rand() % 180) / 3.14;

// Factor de interpolaci�n, va de 1.0 a 0.0
$t = 1.0 - 1 / (2 - 1.0);

// El radio se va centrando a medida que se hace n�tido
$r = $M * $t;

// Trazamos dos l�neas aleatorias para dificultar m�s las cosas
imageline($i, $M, rand($M, $h - $M), $w - $M, rand($M, $h - $M), imagecolorallocate($i, 202, 202, 202));
imageline($i, rand($M, $w - $M), $M, rand($M, $w - $M), $h - $M, imagecolorallocate($i, 202, 202, 202));

// Pasamos un filtro gaussiano
//imagefilter($i, IMG_FILTER_GAUSSIAN_BLUR);

// Dibujamos el texto en el sentido del �ngulo y radio de desplazamiento
imagestring($i, 5, $M + $r * cos($A), $M + $r * sin($A), $_SESSION["captcha"], imagecolorallocate($i, 255, 7, 7));

// Pasamos otro filtro gaussiano
//imagefilter($i, IMG_FILTER_GAUSSIAN_BLUR);

// Escribimos la imagen como un JPEG en el buffer de salida
imagejpeg($i, NULL, $J);

// Liberamos la imagen
imagedestroy($i);
?>