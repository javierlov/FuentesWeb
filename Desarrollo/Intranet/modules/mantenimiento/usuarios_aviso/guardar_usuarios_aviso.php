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
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function validar() {
	global $mostrarEnPortada;

	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	$seleccionado = false;
	foreach ($_POST as $key => $value)
		if (substr($key, 0, 12) == "recibeEmail_") {
			$seleccionado = true;
			break;
		}

	if (!$seleccionado) {
		echo "errores+= '- Debe seleccionar al menos a un usuario.<br />';";
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
	if (!hasPermiso(99))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");

	if (!validar())
		exit;

	// Primero pongo a todos los usuarios en 'N'..
	$sql =
		"UPDATE use_usuarios
				SET se_recibeemailintranet = 'N'";
	DBExecSql($conn, $sql, array(), OCI_DEFAULT);

	foreach ($_POST as $key => $value)
		if (substr($key, 0, 12) == "recibeEmail_") {
			$params = array(":id" => substr($key, 12));
			$sql =
				"UPDATE use_usuarios
						SET se_recibeemailintranet = 'S'
					WHERE se_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}

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
	showMsgOk('/mantenimiento-intranet', window.parent);
</script>