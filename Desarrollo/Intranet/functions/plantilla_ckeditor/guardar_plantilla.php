<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


try {
	if ($_REQUEST["idPlantilla"] == -1) {		// Alta..
		$_REQUEST["nombrePlantilla"] = strtoupper($_REQUEST["nombrePlantilla"]);

		if ($_REQUEST["nombrePlantilla"] == "")
			throw new Exception("Debe ingresar el nombre de la plantilla.");

		$params = array(":modulo" => $_REQUEST["modulo"], ":nombre" => $_REQUEST["nombrePlantilla"]);
		$sql =
			"SELECT 1
				 FROM web.wpn_plantillasintranet
				WHERE pn_modulo = :modulo
					AND pn_nombre = :nombre
					AND pn_fechabaja IS NULL";
		if (existeSql($sql, $params, 0))
			throw new Exception("Ya existe una plantilla con ese nombre.");


		$params = array(":modulo" => $_REQUEST["modulo"], ":nombre" => $_REQUEST["nombrePlantilla"], ":usualta" => getWindowsLoginName(true));
		$sql =
			"INSERT INTO web.wpn_plantillasintranet (pn_fechaalta, pn_id, pn_nombre, pn_modulo, pn_usualta)
																			 VALUES (SYSDATE, -1, :nombre, :modulo, :usualta)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(pn_id) FROM web.wpn_plantillasintranet";
		$id = valorSql($sql, -1, array(), 0);
	}
	else {		// Modificación..
		$params = array(":id" => $_REQUEST["idPlantilla"], ":usumodif" => getWindowsLoginName(true));
		$sql =
			"UPDATE web.wpn_plantillasintranet
					SET pn_fechamodif = SYSDATE,
							pn_usumodif = :usumodif
				WHERE pn_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$id = $_REQUEST["idPlantilla"];
	}

	// Guardo el contenido de la plantilla..
	$blobParamName = "the_clob";
	$sql =
		"UPDATE web.wpn_plantillasintranet
				SET pn_contenido = EMPTY_CLOB()
			WHERE pn_id = ".$id."
	RETURNING pn_contenido INTO :".$blobParamName;
	DBSaveLob($conn, $sql, $blobParamName, $_POST["cuerpoPlantilla"], OCI_B_CLOB);
	DBCommit($conn);

	require_once("guardar_plantilla_combos.php");
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
<script type="text/javascript">
	function ocultar() {
		window.parent.document.getElementById('imgPlantillaOk').style.display = 'none';
	}

	with (window.parent.document) {
		getElementById('plantilla').parentNode.innerHTML = '<?= $comboPlantilla->draw();?>';
		getElementById('divFondo').style.zIndex = '99';
		getElementById('divNombrePlantilla').style.display = 'none';
		getElementById('imgPlantillaOk').style.display = 'inline';
	}
	setTimeout('ocultar()', 500);
</script>