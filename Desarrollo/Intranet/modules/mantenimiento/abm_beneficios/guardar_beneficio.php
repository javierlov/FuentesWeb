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

	if ($_POST["nombre"] == "") {
		echo "errores+= '- El campo Nombre es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["htmlVisible"] == "") {
		echo "errores+= '- El campo HTML es obligatorio.<br />';";
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
	if (!hasPermiso(85))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");

	if (!validar())
		exit;

	if ($_POST["id"] == 0) {		// Es un alta..
		// Agrego el item en la tabla de menues..
		$params = array(":texto" => $_POST["nombre"],
										":usualta" => getWindowsLoginName(true));
		$sql =
			"INSERT INTO web.wmi_menuintranet (mi_activo, mi_fechaalta, mi_id, mi_idpadre, mi_texto, mi_url, mi_usualta)
																 VALUES ('N', SYSDATE, -1, 3, :texto, '/beneficios/', :usualta)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(mi_id) FROM web.wmi_menuintranet";
		$idMenu = valorSql($sql, -1, array(), 0);


		// Actualizo el link del menú..
		$params = array(":id" => $idMenu);
		$sql =
			"UPDATE web.wmi_menuintranet
					SET mi_url = mi_url || mi_id
				WHERE mi_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);


		// Inserto el beneficio..
		$params = array(":idmenu" => $idMenu,
										":nombre" => $_POST["nombre"],
										":usualta" => getWindowsLoginName(true));
		$sql =
			"INSERT INTO rrhh.rbn_beneficios(bn_fechaalta, bn_html, bn_id, bn_idmenu, bn_nombre, bn_usualta)
															 VALUES (SYSDATE, '.', -1, :idmenu, :nombre, :usualta)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(bn_id) FROM rrhh.rbn_beneficios";
		$_POST["id"] = valorSql($sql, -1, array(), 0);
	}
	else {		// Es una modificación..
		// Actualizo el texto del item del menú..
		$params = array(":id" => $_POST["id"],
										":texto" => $_POST["nombre"],
										":usumodif" => getWindowsLoginName(true));
		$sql =
			"UPDATE web.wmi_menuintranet
					SET mi_fechabaja = NULL,
							mi_fechamodif = SYSDATE,
							mi_texto = :texto,
							mi_usubaja = NULL,
							mi_usumodif = :usumodif
				WHERE mi_id = (SELECT bn_idmenu
												 FROM rrhh.rbn_beneficios
												WHERE bn_id = :id)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);


		// Actualizo el beneficio..
		$params = array(":id" => $_POST["id"],
										":nombre" => $_POST["nombre"],
										":usumodif" => getWindowsLoginName(true));
		$sql =
			"UPDATE rrhh.rbn_beneficios
					SET bn_fechabaja = NULL,
							bn_fechamodif = SYSDATE,
							bn_nombre = :nombre,
							bn_usubaja = NULL,
							bn_usumodif = :usumodif
				WHERE bn_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	// Guardo el html del beneficio..
	$blobParamName = "the_clob";
	$sql =
		"UPDATE rrhh.rbn_beneficios
				SET bn_html = EMPTY_CLOB()
			WHERE bn_id = ".$_POST["id"]."
	RETURNING bn_html INTO :".$blobParamName;
	DBSaveLob($conn, $sql, $blobParamName, $_POST["htmlVisible"], OCI_B_CLOB);

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
	showMsgOk('/beneficios-abm-busqueda/<?= $_POST["id"]?>', window.parent);
</script>