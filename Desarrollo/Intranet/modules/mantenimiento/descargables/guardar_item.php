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
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function updateFileName($id, $archPath) {
	global $conn;

	$params = array(":nombrearchivo" => $archPath, ":id" => $id);
	$sql =
		"UPDATE rrhh.rde_descargables
				SET de_nombrearchivo = :nombrearchivo
		  WHERE de_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);
}

function uploadFile($arch, $folder, &$archPath) {
	$tempfile = $arch["tmp_name"];
	$partes_ruta = pathinfo($arch["name"]);

	$uploadOk = false;
	if (is_uploaded_file($tempfile)) {
		if (!file_exists($folder))
			makeDirectory($folder);

		if (move_uploaded_file($tempfile, $folder.$partes_ruta['basename'])) {
			$uploadOk = true;
			$archPath = $partes_ruta["basename"];
		}
	}

	return $uploadOk;
}

function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["nombre"] == "") {
		echo "errores+= '- El campo Nombre es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["orden"] != "")
		if (!validarEntero($_POST["orden"])) {
			echo "errores+= '- El campo Orden debe ser un número entero mayor a cero.<br />';";
			$errores = true;
		}

	if ($_FILES["archivo"]["name"] != "")
		if (!validarExtension($_FILES["archivo"]["name"], array("doc", "docx", "jpg", "htm", "html", "pdf", "ppt", "pptx", "xls", "xlsx"))) {
			echo "errores+= '- El Archivo no tiene una extensión válida.<br />';";
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
	if (!hasPermiso(100))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");


	if (!validar())
		exit;

	if ($_POST["id"] == 0) {		// Es un alta..
		$params = array(":idpadre" => $_POST["itemPadre"],
										":nombre" => $_POST["nombre"],
										":orden" => zeroIfEmpty($_POST["orden"]),
										":usualta" => getWindowsLoginName(true));
		$sql =
			"INSERT INTO rrhh.rde_descargables (de_fechaalta, de_id, de_idpadre, de_nombre, de_orden, de_usualta)
																	VALUES (SYSDATE, -1, :idpadre, :nombre, :orden, :usualta)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(de_id) FROM rrhh.rde_descargables";
		$_POST["id"] = valorSql($sql, -1, array(), 0);
	}
	else {		// Es una modificación..
		$params = array(":id" => $_POST["id"],
										":idpadre" => $_POST["itemPadre"],
										":orden" => zeroIfEmpty($_POST["orden"]),
										":nombre" => $_POST["nombre"],
										":usumodif" => getWindowsLoginName(true));
		$sql =
			"UPDATE rrhh.rde_descargables
					SET de_fechamodif = SYSDATE,
							de_idpadre = :idpadre,
							de_nombre = :nombre,
							de_orden = :orden,
							de_usumodif = :usumodif
				WHERE de_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	// Actualizo el orden de los items..
	$params = array(":id" => $_POST["id"],
									":idpadre" => $_POST["itemPadre"],
									":orden" => zeroIfEmpty($_POST["orden"]));
	$sql =
		"UPDATE rrhh.rde_descargables
				SET de_orden = de_orden + 1
			WHERE de_idpadre = :idpadre
				AND de_orden >= :orden
				AND de_id <> :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	// Si existe el archivo, lo subo..
	if ($_FILES["archivo"]["name"] != "")
		if (uploadFile($_FILES["archivo"], DATA_DESCARGABLES_PATH.armPathFromNumber($_POST["id"]), $archPath))
			updateFileName($_POST["id"], $archPath);
		else
			throw new Exception("Ocurrió un error al guardar el archivo.");

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
	showMsgOk('/mantenimiento-descargables', window.parent);
</script>