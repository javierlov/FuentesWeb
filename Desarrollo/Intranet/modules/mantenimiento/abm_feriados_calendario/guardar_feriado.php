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
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["fecha"] == "") {
		echo "errores+= '- El campo Fecha Feriado es obligatorio.<br />';";
		$errores = true;
	}
	elseif (!isFechaValida($_POST["fecha"])) {
		echo "errores+= '- El campo Fecha Feriado debe ser una fecha válida.<br />';";
		$errores = true;
	}

	if ($_POST["delegacion"] == -1) {
		echo "errores+= '- El campo Delegación es obligatorio.<br />';";
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
	if (!hasPermiso(96))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");

	if (!validar())
		exit;

	if ($_POST["id"] == 0) {		// Es un alta..
		$params = array(":descripcion" => $_POST["descripcion"],
										":fecha" => $_POST["fecha"],
										":iddelegacion" => $_POST["delegacion"],
										":usualta" => getWindowsLoginName(true));
		$sql =
			"INSERT INTO comunes.cfd_feriadosdelegaciones (fd_descripcion, fd_fecha, fd_fechaalta, fd_id, fd_iddelegacion, fd_usualta)
																						 VALUES (:descripcion, TO_DATE(:fecha, 'DD/MM/YYYY'), SYSDATE, -1, :iddelegacion, :usualta)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(fd_id) FROM comunes.cfd_feriadosdelegaciones";
		$_POST["id"] = valorSql($sql, -1, array(), 0);
	}
	else {		// Es una modificación..
		$params = array(":descripcion" => $_POST["descripcion"],
										":fecha" => $_POST["fecha"],
										":id" => $_POST["id"],
										":iddelegacion" => $_POST["delegacion"],
										":usumodif" => getWindowsLoginName(true));
		$sql =
			"UPDATE comunes.cfd_feriadosdelegaciones
					SET fd_descripcion = :descripcion,
							fd_fecha = TO_DATE(:fecha, 'DD/MM/YYYY'),
							fd_fechamodif = SYSDATE,
							fd_iddelegacion = :iddelegacion,
							fd_usumodif = :usumodif
				WHERE fd_id = :id";
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
	showMsgOk('/calendario-feriados-abm-busqueda/<?= $_POST["id"]?>', window.parent);
</script>