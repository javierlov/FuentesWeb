<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["texto"] == "") {
		echo "errores+= '- El campo Texto es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["orden"] != "")
		if (!validarEntero($_POST["orden"])) {
			echo "errores+= '- El campo Orden debe ser un número entero mayor a cero.<br />';";
			$errores = true;
		}

	if ($_POST["menuPadre"] != -1) {
		if ($_POST["link"] == "") {
			echo "errores+= '- El campo Link es obligatorio.<br />';";
			$errores = true;
		}

		if ($_POST["destino"] == -1) {
			echo "errores+= '- El campo Destino es obligatorio.<br />';";
			$errores = true;
		}
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
	if (!hasPermiso(87))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");


	$activo = (isset($_POST["activo"]))?"S":"N";

	if (!validar())
		exit;

	if ($_POST["id"] == 0) {		// Es un alta..
		$params = array(":activo" => $activo,
										":color" => $_POST["color"],
										":idpadre" => $_POST["menuPadre"],
										":imagencabecera" => $_POST["encabezado"],
										":orden" => zeroIfEmpty($_POST["orden"]),
										":target" => $_POST["destino"],
										":texto" => $_POST["texto"],
										":url" => $_POST["link"],
										":usualta" => getWindowsLoginName(true));
		$sql =
			"INSERT INTO web.wmi_menuintranet (mi_activo, mi_color, mi_fechaalta, mi_id, mi_idpadre, mi_imagencabecera, mi_orden, mi_target, mi_texto, mi_url, mi_usualta)
																 VALUES (:activo, :color, SYSDATE, -1, :idpadre, :imagencabecera, :orden, :target, :texto, :url, :usualta)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(mi_id) FROM web.wmi_menuintranet";
		$_POST["id"] = valorSql($sql, -1, array(), 0);
	}
	else {		// Es una modificación..
		$params = array(":activo" => $activo,
										":color" => $_POST["color"],
										":id" => $_POST["id"],
										":idpadre" => $_POST["menuPadre"],
										":imagencabecera" => $_POST["encabezado"],
										":orden" => zeroIfEmpty($_POST["orden"]),
										":target" => $_POST["destino"],
										":texto" => $_POST["texto"],
										":url" => $_POST["link"],
										":usumodif" => getWindowsLoginName(true));
		$sql =
			"UPDATE web.wmi_menuintranet
					SET mi_activo = :activo,
							mi_color = :color,
							mi_fechamodif = SYSDATE,
							mi_idpadre = :idpadre,
							mi_imagencabecera = :imagencabecera,
							mi_orden = :orden,
							mi_target = :target,
							mi_texto = :texto,
							mi_url = :url,
							mi_usumodif = :usumodif
				WHERE mi_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	// Actualizo el orden de los items..
	$params = array(":id" => $_POST["id"],
									":idpadre" => $_POST["menuPadre"],
									":orden" => zeroIfEmpty($_POST["orden"]));
	$sql =
		"UPDATE web.wmi_menuintranet
				SET mi_orden = mi_orden + 1
			WHERE mi_idpadre = :idpadre
				AND mi_orden >= :orden
				AND mi_id <> :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

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
	showMsgOk('/mantenimiento-menu', window.parent);
</script>