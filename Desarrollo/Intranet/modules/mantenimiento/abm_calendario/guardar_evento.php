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

	if ($_POST["texto"] == "") {
		echo "errores+= '- El campo Texto Evento es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["fecha"] == "") {
		echo "errores+= '- El campo Fecha Evento es obligatorio.<br />';";
		$errores = true;
	}
	elseif (!isFechaValida($_POST["fecha"])) {
		echo "errores+= '- El campo Fecha Evento debe ser una fecha válida.<br />';";
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

	if (!hasPermiso(82))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");

	if (!validar())
		exit;

	if ($_POST["id"] == 0) {		// Es un alta..
		$params = array(":destino" => $_POST["destino"],
										":fechaevento" => $_POST["fecha"],
										":fechavigenciadesde" => $_POST["vigenciaDesde"],
										":fechavigenciahasta" => $_POST["vigenciaHasta"],
										":link" => $_POST["link"],
										":textoevento" => $_POST["texto"],
										":usualta" => getWindowsLoginName(true),
										":vistaprevia" => $vistaPrevia);
		$sql =
			"INSERT INTO rrhh.rcl_calendario(cl_destino, cl_fechaalta, cl_fechaevento, cl_fechavigenciadesde, cl_fechavigenciahasta, cl_id,
																			 cl_link, cl_textoevento, cl_usualta, cl_vistaprevia)
															 VALUES (:destino, SYSDATE, TO_DATE(:fechaevento, 'DD/MM/YYYY'), TO_DATE(:fechavigenciadesde, 'DD/MM/YYYY'), TO_DATE(:fechavigenciahasta, 'DD/MM/YYYY'), -1,
																			 :link, :textoevento, :usualta, :vistaprevia)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(cl_id) FROM rrhh.rcl_calendario";
		$_POST["id"] = ValorSql($sql, -1, array(), 0);
	}
	else {		// Es una modificación..
		$params = array(":destino" => $_POST["destino"],
										":fechaevento" => $_POST["fecha"],
										":fechavigenciadesde" => $_POST["vigenciaDesde"],
										":fechavigenciahasta" => $_POST["vigenciaHasta"],
										":id" => $_POST["id"],
										":link" => $_POST["link"],
										":textoevento" => $_POST["texto"],
										":usumodif" => getWindowsLoginName(true),
										":vistaprevia" => $vistaPrevia);
		$sql =
			"UPDATE rrhh.rcl_calendario
					SET cl_destino = :destino,
							cl_fechamodif = SYSDATE,
							cl_fechaevento = :fechaevento,
							cl_fechavigenciadesde = :fechavigenciadesde,
							cl_fechavigenciahasta = :fechavigenciahasta,
							cl_link = :link,
							cl_textoevento = :textoevento,
							cl_usumodif = :usumodif,
							cl_vistaprevia = :vistaprevia
				WHERE cl_id = :id";
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
	showMsgOk('/calendario-eventos-abm-busqueda/<?= $_POST["id"]?>', window.parent);
</script>