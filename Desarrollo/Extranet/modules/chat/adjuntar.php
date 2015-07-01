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
require_once("funciones.php");

function subirArchivo($arch, $folder, $extensionesPermitidas, $maxFileSize, &$file, &$msgError) {
	$tmpfile = $arch["tmp_name"];
	$partes_ruta = pathinfo(strtolower($arch["name"]));

	$filename = date("Ymd").$_SERVER["REQUEST_TIME"];
	$ruta = $folder.armPathFromNumber($filename);
	$file = $ruta.$filename.".".$partes_ruta["extension"];

	if (!makeDirectory($ruta)) {
		$msgError = "ERROR: No se puede crear la carpeta.";
		return false;
	}

	if (!in_array($partes_ruta["extension"], $extensionesPermitidas)) {
		$msgError = "ERROR: El archivo debe tener alguna de las siguientes extensiones: ".implode(" o ", $extensionesPermitidas).".";
		return false;
	}

	if (!is_uploaded_file($tmpfile)) {
		$msgError = "ERROR: El archivo no subió correctamente.";
		return false;
	}

	if (filesize($tmpfile) > $maxFileSize) {
		$msgError = "ERROR: El archivo no puede ser mayor a ".tamanoArchivo($maxFileSize).".";
		return false;
	}

	if (!move_uploaded_file($tmpfile, $file)) {
		$msgError = "ERROR: El archivo no pudo ser guardado.";
		return false;
	}

	return true;
}
?>
<script type="text/javascript" src="/modules/chat/js/chat.js"></script>
<?
try {
	$msgError = "";

	if ($_FILES["archivoChat"]["name"] != "") {
		if (subirArchivo($_FILES["archivoChat"], DATA_CHAT_ARCHIVOS_PATH, array("bmp", "gif", "jpeg", "jpg", "pdf", "png"), 10485760, $file, $msgError)) {
			// Chequeo que la sesión este abierta..
			$params = array(":id" => $_SESSION["chatIdSession"],
			$sql =
				"SELECT 1
					 FROM web.wsc_sesionescha
					WHERE sc_estado = 4
						AND sc_id = :id";
			if (existeSql($sql, $params))		// Si la sesión está cerrada, aborto todo..
				exit;

			// Inserto el archivo..
			$params = array(":idsesion" => $_SESSION["chatIdSession"],
											":nombreoriginal" => $_FILES["archivoChat"]["name"],
											":ruta" => $file);
			$sql =
				"INSERT INTO web.wac_archivoschat (ac_fechasubida, ac_idsesion, ac_nombreoriginal, ac_ruta)
																	 VALUES (SYSDATE, :idsesion, :nombreoriginal, :ruta)";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);

			$params = array(":idsesion" => $_SESSION["chatIdSession"]);
			$sql =
				"SELECT MAX(ac_id)
					 FROM web.wac_archivoschat
					WHERE ac_idsesion = :idsesion";
			$idArchivo = valorSql($sql, -1, $params, 0);

			// Inserto un mensaje..
			$params = array(":idarchivo" => $idArchivo,
											":idsesion" => $_SESSION["chatIdSession"]);
			$sql =
				"INSERT INTO web.wmc_mensajeschat (mc_enviadopor, mc_fechaenvio, mc_idarchivo, mc_idsesion, mc_leidoporoperador, mc_leidoporusuario, mc_mensaje, mc_tipomensaje)
																	 VALUES ('U', SYSDATE, :idarchivo, :idsesion, 'N', 'S', '** ARCHIVO **', 'A')";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);

			DBCommit($conn);
		}
		else
			throw new Exception($msgError);
	}

	// Array agregado para escribir el mensaje al final..
	$row = array("MC_ENVIADOPOR" => "U", "MC_IDARCHIVO" => $idArchivo, "MC_MENSAJE" => "", "MC_TIPOMENSAJE" => "A");
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script type='text/javascript'>
		with (window.parent.document) {
			getElementById('imgSubiendoArchivo').style.display = 'none';
			getElementById('divAdjuntar').style.display = 'block';
		}

		alert(unescape('<?= rawurlencode($e->getMessage())?>'));
		escribirMensaje(window.parent, '');
	</script>
<?
	exit;
}
?>
<script type="text/javascript">
	function cambiarImagen() {
		with (window.parent.document) {
			getElementById('imgTildeArchivo').style.display = 'none';
//			getElementById('divAdjuntar').style.display = 'block';
		}
	}

	with (window.parent.document) {
		getElementById('imgSubiendoArchivo').style.display = 'none';
		getElementById('imgTildeArchivo').style.display = 'block';

		msg = '<?= getMensaje($row)?>';
		escribirMensaje(window.parent, msg);
	}

	setTimeout('cambiarImagen()', 1500);
</script>