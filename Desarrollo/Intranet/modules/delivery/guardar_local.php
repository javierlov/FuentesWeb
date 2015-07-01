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

	if ($_POST["nombre"] == "") {
		echo "errores+= '- El campo Nombre es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["telefono"] == "") {
		echo "errores+= '- El campo Teléfono es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["link"] != "")
		if (!filter_var($_POST["link"], FILTER_VALIDATE_URL)) {
			echo "errores+= '- El campo Link no es una URL válida.<br />';";
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
	if (!validar())
		exit;

	$autorizado = "N";
	$usuarioAutorizacion = NULL;
	if (isset($_POST["autorizado"])) {
		$autorizado = "S";
		$usuarioAutorizacion = getWindowsLoginName(true);
	}

	if ($_POST["id"] == "") {		// Alta..
		$params = array(":autorizado" => $autorizado,
										":direccion" => $_POST["direccion"],
										":nombre" => $_POST["nombre"],
										":telefono" => $_POST["telefono"],
										":url" => $_POST["link"],
										":usualta" => getWindowsLoginName(true),
										":usuautorizacion" => $usuarioAutorizacion);
		$sql =
			"INSERT INTO rrhh.rhd_delivery (hd_autorizado, hd_direccion, hd_fechaalta, hd_id, hd_nombre, hd_telefono, hd_url, hd_usualta, hd_usuautorizacion)
															VALUES (:autorizado, :direccion, SYSDATE, -1, :nombre, :telefono, :url, :usualta, :usuautorizacion)";
		DBExecSql($conn, $sql, $params);
	}
	else {		// Modificación..
		$params = array(":autorizado" => $autorizado,
										":direccion" => $_POST["direccion"],
										":id" => $_POST["id"],
										":nombre" => $_POST["nombre"],
										":telefono" => $_POST["telefono"],
										":url" => $_POST["link"],
										":usumodif" => getWindowsLoginName(true),
										":usuautorizacion" => $usuarioAutorizacion);
		$sql =
			"UPDATE rrhh.rhd_delivery
					SET hd_autorizado = :autorizado,
							hd_direccion = :direccion,
							hd_fechamodif = SYSDATE,
							hd_nombre = :nombre,
							hd_telefono = :telefono,
							hd_url = :url,
							hd_usuautorizacion = :usuautorizacion,
							hd_usumodif = :usumodif
				WHERE hd_id = :id";
		DBExecSql($conn, $sql, $params);
	}
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
	showMsgOk('/delivery', window.parent);
</script>