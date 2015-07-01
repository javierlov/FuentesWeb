<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");


function tamanoArchivo($fileSize) {
	if ($fileSize < 1024)
		return $fileSize." bytes";
	elseif ($fileSize < 1048576)
		return ($fileSize / 1024)." KB";
	else
		return ($fileSize / 1024 / 1024)." MB";
}


try {
	$extensionesPermitidas = array("gif", "jpg", "jpeg", "png");
	$maxFileSize = 5242880;
	$maxReintentos = 20;
	$msgError = "";

	$filename = "";
	$tmpfile = $_FILES["imagen"]["tmp_name"];
	$partes_ruta = pathinfo(strtolower($_FILES["imagen"]["name"]));

	if (!in_array($partes_ruta["extension"], $extensionesPermitidas))
		$msgError = "El archivo debe tener alguna de las siguientes extensiones: ".implode(" o ", $extensionesPermitidas).".";
	else {
		if (!is_uploaded_file($tmpfile))
			$msgError = "El archivo no subió correctamente.";
		elseif (filesize($tmpfile) > $maxFileSize)
			$msgError = "El archivo no puede ser mayor a ".tamanoArchivo($maxFileSize).".";
		else {
			$filename = IMAGES_ARTICULOS_PATH.$_POST["carpeta"]."\\".$partes_ruta["filename"].".".$partes_ruta["extension"];

			// Intento obtener un nombre de archivo que no exista en el servidor..
			$i = 0;
			while (file_exists($filename)) {
				$i++;
				if ($i > $maxReintentos) {
					$msgError = "El archivo ya existe en el servidor.";
					break;
				}
				$filename = IMAGES_ARTICULOS_PATH.$_POST["carpeta"]."\\".$partes_ruta["filename"]."_".$i.".".$partes_ruta["extension"];
			}

			if ($msgError == "") {		// Si pudimos obtener el nombre con el que va a quedar el archivo en el servidor..
				if (!MakeDirectory(IMAGES_ARTICULOS_PATH.$_POST["carpeta"]))
					$msgError = "No se pudo crear la carpeta de imagenes.";
				elseif (!move_uploaded_file($tmpfile, $filename))
					$msgError = "El archivo no pudo ser guardado.";
			}
		}
	}

	if ($msgError != "") {
		if (file_exists($filename))
			unlink($filename);
?>
		<script type="text/javascript">
			alert('<?= $msgError?>');
			with (window.parent.document) {
				getElementById('imgSubiendoImagen').style.display = 'none';
				getElementById('btnEnviar').style.display = 'inline';
			}
		</script>
<?
		exit;
	}
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	function finalizar() {
		with (window.parent.document) {
			getElementById('imgSubidaOk').style.display = 'none';
			getElementById('btnEnviar').style.display = 'inline';
		}
	}

	setTimeout('finalizar()', 2000);

	with (window.parent.document) {
		getElementById('imgSubiendoImagen').style.display = 'none';
		getElementById('imgSubidaOk').style.display = 'inline';
	}
</script>