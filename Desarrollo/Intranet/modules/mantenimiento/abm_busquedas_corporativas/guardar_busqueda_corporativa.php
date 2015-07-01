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
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function updateFileName($id, $archPath) {
	global $conn;

	$params = array(":nombrearchivo" => $archPath, ":id" => $id);
	$sql =
		"UPDATE rrhh.rbc_busquedascorporativas
				SET bc_nombrearchivo = :nombrearchivo
		  WHERE bc_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);
}

function uploadFile($arch, $folder, $id, &$archPath) {
	$tempfile = $arch["tmp_name"];
	$partes_ruta = pathinfo($arch["name"]);
	$filename = StringToLower($id.".".$partes_ruta["extension"]);

	$uploadOk = false;
	if (is_uploaded_file($tempfile))
		if (move_uploaded_file($tempfile, $folder.$filename)) {
			$uploadOk = true;
			$archPath = $partes_ruta["basename"];
		}

	if (!$uploadOk)
		echo "<script>alert('Ocurrió un error al guardar el archivo.');</script>";

	return $uploadOk;
}

function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";


	if ($_POST["puesto"] == "") {
		echo "errores+= '- El campo Puesto es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["empresa"] == -1) {
		echo "errores+= '- El campo Empresa es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["estado"] == -1) {
		echo "errores+= '- El campo Estado es obligatorio.<br />';";
		$errores = true;
	}

	if ($_FILES["archivo"]["name"] != "") {
		$partes_ruta = pathinfo($_FILES["archivo"]["name"]);
		$ext = StringToLower($partes_ruta["extension"]);
		if (($ext != "doc") and ($ext != "docx") and ($ext != "pdf")) {
			echo "errores+= '- El Archivo debe ser de Word o Acrobat Reader.<br />';";
			$errores = true;
		}
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
	if (!hasPermiso(80))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");

	if (!validar())
		exit;

	if ($_POST["id"] == 0) {		// Es un alta..
		$params = array(":fechavigenciadesde" => $_POST["vigenciaDesde"],
										":fechavigenciahasta" => $_POST["vigenciaHasta"],
										":idempresa" => $_POST["empresa"],
										":idestado" => $_POST["estado"],
										":puesto" => $_POST["puesto"],
										":usualta" => GetWindowsLoginName(true));
		$sql =
			"INSERT INTO rrhh.rbc_busquedascorporativas(bc_fechaalta, bc_fechavigenciadesde, bc_fechavigenciahasta, bc_idempresa, bc_idestado, bc_puesto, bc_usualta)
																					VALUES (SYSDATE, TO_DATE(:fechavigenciadesde, 'DD/MM/YYYY'), TO_DATE(:fechavigenciahasta, 'DD/MM/YYYY'), :idempresa, :idestado, :puesto, :usualta)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(bc_id) FROM rrhh.rbc_busquedascorporativas";
		$_POST["id"] = ValorSql($sql, -1, array(), 0);
	}
	else {		// Es una modificación..
		$params = array(":fechavigenciadesde" => $_POST["vigenciaDesde"],
										":fechavigenciahasta" => $_POST["vigenciaHasta"],
										":id" => $_POST["id"],
										":idempresa" => $_POST["empresa"],
										":idestado" => $_POST["estado"],
										":puesto" => $_POST["puesto"],
										":usumodif" => GetWindowsLoginName(true));
		$sql =
			"UPDATE rrhh.rbc_busquedascorporativas
					SET bc_fechamodif = SYSDATE,
							bc_fechavigenciadesde = TO_DATE(:fechavigenciadesde, 'DD/MM/YYYY'),
							bc_fechavigenciahasta = TO_DATE(:fechavigenciahasta, 'DD/MM/YYYY'),
							bc_idempresa = :idempresa,
							bc_idestado = :idestado,
							bc_puesto = :puesto,
							bc_usumodif = :usumodif
				WHERE bc_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	if ($_FILES["archivo"]["name"] != "")		// Si existe el archivo, lo subo..
		if (uploadFile($_FILES["archivo"], DATA_BUSQUEDAS_CORPORATIVAS_PATH, $_POST["id"], $archPath))
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
	showMsgOk('/busquedas-corporativas-abm-busqueda/<?= $_POST["id"]?>', window.parent);
</script>