<?
putenv("GDFONTPATH=".realpath("."));
$fuente = "NeoSan copy.ttf";

ini_set("memory_limit", "-1");

$file = DATA_AVISO_OBRA_PATH.$_REQUEST["filename"].".".$_REQUEST["extension"];
$fileOutput = DATA_AVISO_OBRA_PATH.$_REQUEST["filename"]."_salida.".$_REQUEST["extension"];

if ($_REQUEST["accion"] == "g") {
?>
	<script>
		window.location.href = '/archivo/<?= base64_encode($fileOutput)?>/a';
	</script>
<?
}

if ($_REQUEST["accion"] == "i") {
?>
	<script>
		newWindow = window.open('', 'Imagenes', 'width=400, height=450, left=100, top=60');
		with (newWindow) {
			document.open();
			document.write('<html><head></head><body onload="window.print(); window.close();"><img src="/functions/get_image.php?rnd=<?= date("Ymdhisu")?>&file=<?= base64_encode($fileOutput)?>"/></body></html>');
			document.close();
			focus();
		}
	</script>
<?
}


$size = getimagesize($file);
//WIDTH  --->	$size[0];		PANEL_CELESTE: 292
//HEIGHT ---> $size[1];		PANEL_CELESTE: 412
$proporcionX = $size[0] / 292;
$proporcionY = $size[1] / 412;

if ($_REQUEST["extension"] == "png")
	$lienzo = imagecreatefrompng($file);
else		// jpg o jpeg..
	$lienzo = imagecreatefromjpeg($file);

$_REQUEST["x"] = $_REQUEST["x"] * $proporcionX;
$_REQUEST["y"] = $_REQUEST["y"] * $proporcionY;

imagecolortransparent($lienzo, imagecolorallocate($lienzo, 0, 0, 0));

if ($_REQUEST["tipoSello"] == "n") {
	$color = imagecolorallocate($lienzo, 0, 0, 0);
	imagettftext($lienzo, (5 * $proporcionX), 0, $_REQUEST["x"] + (4 * $proporcionX), $_REQUEST["y"] + (26 * $proporcionY), $color, $fuente, "NO CORRESPONDE PRESENTACIÓN");
	imagettftext($lienzo, (5 * $proporcionX), 0, $_REQUEST["x"] + (4 * $proporcionX), $_REQUEST["y"] + (36 * $proporcionY), $color, $fuente, "POR ACTIVIDAD DESARROLLADA");
}
else {
	switch ($_REQUEST["tipoSello"]) {
		case "e":
			$color = imagecolorallocate($lienzo, 0, 0, 196);
			break;
		case "h":
			$color = imagecolorallocate($lienzo, 196, 0, 0);
			break;
		case "i":
			$color = imagecolorallocate($lienzo, 0, 0, 196);
			break;
		case "n":
			$color = imagecolorallocate($lienzo, 0, 0, 0);
			break;
		case "s":
			$color = imagecolorallocate($lienzo, 0, 0, 196);
			break;
	}

	for ($i=0; $i<=(0.5*($proporcionX * $proporcionY / 2)); $i++) {
		imagerectangle($lienzo, $_REQUEST["x"] + $i, $_REQUEST["y"] + $i, ($_REQUEST["x"] + 64 * $proporcionX) - $i, ($_REQUEST["y"] + 48 * $proporcionY) - $i, $color);
		imagerectangle($lienzo, $_REQUEST["x"] + (9 * $proporcionX) + $i, $_REQUEST["y"] + (12 * $proporcionY) + $i, ($_REQUEST["x"] + (64 * $proporcionX)) - (9 * $proporcionX) - $i, ($_REQUEST["y"] + (48 * $proporcionY)) - (12 * $proporcionY) - $i, $color);
	}

	imagettftext($lienzo, (5 * $proporcionX), 0, $_REQUEST["x"] + (6 * $proporcionX), $_REQUEST["y"] + (9 * $proporcionY), $color, $fuente, "Provincia ART S.A.");
	imagettftext($lienzo, (5 * $proporcionX), 0, $_REQUEST["x"] + (11.6 * $proporcionX), $_REQUEST["y"] + (26 * $proporcionY), $color, $fuente, $arrFecha[0]." ".strtoupper(substr(GetMonthName($arrFecha[1]), 0, 3))." ".$arrFecha[2]);
	imagettftext($lienzo, (5 * $proporcionX), 0, $_REQUEST["x"] + (14 * $proporcionX), $_REQUEST["y"] + (44 * $proporcionY), $color, $fuente, getLeyendaSello($_REQUEST["tipoSello"]));
}

if ($_REQUEST["extension"] == "png")
	imagepng($lienzo, $fileOutput);
else		// jpg o jpeg..
	imagejpeg($lienzo, $fileOutput);

imagedestroy($lienzo);
?>
<html>
	<head>
		<script>
			function cargaCompletada() {
				with (document) {
					getElementById('imgArchivo').style.display = 'inline';
					getElementById('imgLoading').style.display = 'none';
				}
			}
		</script>
	</head>
	<body style="margin:0; padding:0;">
		<img border="1" id="imgArchivo" src="/functions/get_image.php?rnd=<?= date("Ymdhisu")?>&file=<?= base64_encode($fileOutput)?>&mh=410&mw=290" style="display:none;" onLoad="cargaCompletada()" />
		<img border="0" id="imgLoading" src="/images/loading_grande.gif" style="display:inline; margin-left:56px; margin-top:80px;" title="Cargando..." />
	</body>
</html>