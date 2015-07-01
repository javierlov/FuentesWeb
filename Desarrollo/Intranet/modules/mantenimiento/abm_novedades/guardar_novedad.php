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


function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["usuario"] == -1) {
		echo "errores+= '- El campo Usuario es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["tipoMovimiento"] == -1) {
		echo "errores+= '- El campo Tipo Movimiento es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["tipoMovimiento"] == "A") {
		if ($_POST["sectorHasta"] == -1) {
			echo "errores+= '- El campo Sector Hasta es obligatorio.<br />';";
			$errores = true;
		}
	}

	if ($_POST["tipoMovimiento"] == "M") {
		if ($_POST["sectorDesde"] == -1) {
			echo "errores+= '- El campo Sector Desde es obligatorio.<br />';";
			$errores = true;
		}

		if ($_POST["sectorHasta"] == -1) {
			echo "errores+= '- El campo Sector Hasta es obligatorio.<br />';";
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
	if (!isset($_POST["sectorDesde"]))
		$_POST["sectorDesde"] = NULL;
	if (!isset($_POST["sectorHasta"]))
		$_POST["sectorHasta"] = NULL;


	if (!hasPermiso(79))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");

	if (!validar())
		exit;

	if ($_POST["id"] == 0) {		// Es un alta..
		$params = array(":fechavigenciadesde" => $_POST["vigenciaDesde"],
										":fechavigenciahasta" => $_POST["vigenciaHasta"],
										":idsectordesde" => $_POST["sectorDesde"],
										":idsectorhasta" => $_POST["sectorHasta"],
										":idusuario" => $_POST["usuario"],
										":tipomovimiento" => $_POST["tipoMovimiento"],
										":usualta" => GetWindowsLoginName(true));
		$sql =
			"INSERT INTO rrhh.rhn_novedades(hn_fechaalta, hn_fechavigenciadesde, hn_fechavigenciahasta, hn_id, hn_idsectordesde, hn_idsectorhasta, hn_idusuario,
																			hn_tipomovimiento, hn_usualta)
															VALUES (SYSDATE, TO_DATE(:fechavigenciadesde, 'DD/MM/YYYY'), TO_DATE(:fechavigenciahasta, 'DD/MM/YYYY'), -1, :idsectordesde, :idsectorhasta, :idusuario,
																			:tipomovimiento, :usualta)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(hn_id) FROM rrhh.rhn_novedades";
		$_POST["id"] = ValorSql($sql, -1, array(), 0);
	}
	else {		// Es una modificación..
		$params = array(":fechavigenciadesde" => $_POST["vigenciaDesde"],
										":fechavigenciahasta" => $_POST["vigenciaHasta"],
										":id" => $_POST["id"],
										":idsectordesde" => $_POST["sectorDesde"],
										":idsectorhasta" => $_POST["sectorHasta"],
										":tipomovimiento" => $_POST["tipoMovimiento"],
										":usumodif" => GetWindowsLoginName(true));
		$sql =
			"UPDATE rrhh.rhn_novedades
					SET hn_fechamodif = SYSDATE,
							hn_fechavigenciadesde = TO_DATE(:fechavigenciadesde, 'DD/MM/YYYY'),
							hn_fechavigenciahasta = TO_DATE(:fechavigenciahasta, 'DD/MM/YYYY'),
							hn_idsectordesde = :idsectordesde,
							hn_idsectorhasta = :idsectorhasta,
							hn_tipomovimiento = :tipomovimiento,
							hn_usumodif = :usumodif
				WHERE hn_id = :id";
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
	showMsgOk('/novedades-abm-busqueda/<?= $_POST["id"]?>', window.parent);
</script>