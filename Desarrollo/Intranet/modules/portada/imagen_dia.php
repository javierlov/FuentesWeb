<?
header("Content-type: image/jpg");
putenv("GDFONTPATH=".realpath("."));


function getLeftMes($mes) {
	switch ($mes) {
		case "01":
		case "02":
			return 20;
			break;
		case "03":
			return 19;
			break;
		case "04":
			return 20;
			break;
		case "05":
			return 19;
			break;
		case "06":
			return 20;
			break;
		case "07":
			return 21;
			break;
		case "08":
			return 20;
			break;
		case "09":
		case "10":
			return 21;
			break;
		case "11":
			return 20;
			break;
		case "12":
			return 22;
			break;
	}
}

function getMes($mes) {
	switch ($mes) {
		case "01":
			return "ENE";
			break;
		case "02":
			return "FEB";
			break;
		case "03":
			return "MAR";
			break;
		case "04":
			return "ABR";
			break;
		case "05":
			return "MAY";
			break;
		case "06":
			return "JUN";
			break;
		case "07":
			return "JUL";
			break;
		case "08":
			return "AGO";
			break;
		case "09":
			return "SEP";
			break;
		case "10":
			return "OCT";
			break;
		case "11":
			return "NOV";
			break;
		case "12":
			return "DIC";
			break;
	}
}


if (!isset($_REQUEST["d"]))
	$_REQUEST["d"] = date("d/m");

$dia = intval(substr($_REQUEST["d"], 0, 2));
$mes = intval(substr($_REQUEST["d"], 3, 2));

if ($dia < 10)
	$left = 22;
else
	$left = 17;

$im = imagecreatefromjpeg($_SERVER["DOCUMENT_ROOT"]."/modules/portada/images/calendario.jpg");
$color = imagecolorallocate($im, 255, 255, 255);
$fuente = "NeoSan copy.ttf";

imagettftext($im, 7, 0, getLeftMes($mes), 24, $color, $fuente, getMes($mes));
imagettftext($im, 12, 0, $left, 39, $color, $fuente, $dia);
imagejpeg($im);
imagedestroy($im);
?>