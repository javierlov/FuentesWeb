<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function moverImagen($img) {
	global $conn;

	if (($img != "") and ($img != "old")) {
		$fileOrigen = IMAGES_EDICION_PATH.$img;
		$partes_ruta = pathinfo($img);
		$filename = $_POST["id"].".".$partes_ruta["extension"];
		$fileDest = DATA_CELEBRACIONES_PATH.$filename;

		if (!file_exists(DATA_CELEBRACIONES_PATH.$_POST["id"]))
			makeDirectory(DATA_CELEBRACIONES_PATH.$_POST["id"]);

		unlink($fileDest);
		if (!rename($fileOrigen, $fileDest))
			unlink($fileOrigen);
	}
}

function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["texto"] == "") {
		echo "errores+= '- El campo Texto es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["fileImg"] == "") {
		echo "errores+= '- El campo Imagen es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["vigenciaDesde"] == "") {
		echo "errores+= '- El campo Vigencia Desde es obligatorio.<br />';";
		$errores = true;
	}
	elseif (!isFechaValida($_POST["vigenciaDesde"])) {
		echo "errores+= '- El campo Vigencia Desde debe ser una fecha válida.<br />';";
		$errores = true;
	}

	if ($_POST["vigenciaHasta"] == "") {
		echo "errores+= '- El campo Vigencia Hasta es obligatorio.<br />';";
		$errores = true;
	}
	elseif (!isFechaValida($_POST["vigenciaHasta"])) {
		echo "errores+= '- El campo Vigencia Hasta debe ser una fecha válida.<br />';";
		$errores = true;
	}

	if (dateDiff($_POST["vigenciaHasta"], $_POST["vigenciaDesde"]) > 0) {
		echo "errores+= '- La Vigencia Hasta debe ser mayor a la Vigencia Desde.<br />';";
		$errores = true;
	}


	if ($errores) {
		echo "body.style.cursor = 'default';";
		echo "getElementById('btnGuardar').style.display = 'inline';";
		echo "getElementById('imgProcesando').style.display = 'none';";
		echo "getElementById('errores').innerHTML = errores;";
		echo "getElementById('divErroresForm').style.display = 'block';";
		echo "getElementById('foco').style.display = 'block';";
		echo "getElementById('foco').focus();";
		echo "getElementById('foco').style.display = 'none';";
	}
	else {
		echo "getElementById('divErroresForm').style.display = 'none';";
	}

	echo "}";
	echo "</script>";

	return !$errores;
}


try {
	$vistaPrevia = (isset($_POST["vistaPrevia"]))?"S":"N";

	if (!hasPermiso(13))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");

	if (!validar())
		exit;

	if ($_POST["id"] == 0) {		// Es un alta..
		$params = array(":fechavigenciadesde" => $_POST["vigenciaDesde"],
										":fechavigenciahasta" => $_POST["vigenciaHasta"],
										":texto" => substr($_POST["texto"], 0, 512),
										":usualta" => getWindowsLoginName(true),
										":vistaprevia" => $vistaPrevia);
		$sql =
			"INSERT INTO rrhh.rnp_novedadespersonales (np_fechaalta, np_fechavigenciadesde, np_fechavigenciahasta, np_id, np_texto, np_tiponovedad, np_usualta, np_vistaprevia)
																				 VALUES (SYSDATE, :fechavigenciadesde, :fechavigenciahasta, -1, :texto, 'N', :usualta, :vistaprevia)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(np_id) FROM rrhh.rnp_novedadespersonales";
		$_POST["id"] = valorSql($sql, -1, array(), 0);
	}
	else {		// Es una modificación..
		$params = array(":fechavigenciadesde" => $_POST["vigenciaDesde"],
										":fechavigenciahasta" => $_POST["vigenciaHasta"],
										":id" => $_POST["id"],
										":texto" => substr($_POST["texto"], 0, 512),
										":usumodif" => getWindowsLoginName(true),
										":vistaprevia" => $vistaPrevia);
		$sql =
			"UPDATE rrhh.rnp_novedadespersonales
					SET np_fechamodif = SYSDATE,
							np_fechavigenciadesde = :fechavigenciadesde,
							np_fechavigenciahasta = :fechavigenciahasta,
							np_texto = :texto,
							np_usumodif = :usumodif,
							np_vistaprevia = :vistaprevia
				WHERE np_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	moverImagen($_POST["fileImg"]);

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script language="JavaScript" src="/js/functions.js"></script>
	<script type='text/javascript'>
		showError(unescape('<?= rawurlencode($e->getMessage())?>'), window.parent);
	</script>
<?
	exit;
}
?>
<script language="JavaScript" src="/js/functions.js"></script>
<script type="text/javascript">
	showMsgOk('/nacimientos-abm-busqueda/<?= $_POST["id"]?>', window.parent);
</script>