<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");
session_start();


$user = $_SESSION["identidad"];

try {
	$sql =
		"INSERT INTO rrhh.hfs_formularioseguimiento (fs_id_formularioevaluacion, fs_positivonegativo, fs_fecha, fs_evento, fs_usualta,
																					 fs_fechaalta)
																	 VALUES (:idformularioevaluacion, :positivonegativo, SYSDATE, SUBSTR(:evento, 1, 2000),
																					 UPPER(:usualta), SYSDATE)";
	$params = array(":idformularioevaluacion" => $_POST["FormularioId"],
								":positivonegativo" => $_POST["TipoEvento"],
								":evento" => $_POST["Descripcion"],
								":usualta" => $user);
	DBExecSql($conn, $sql, $params);
}
catch (Exception $e) {
?>
	<script>
		alert('<?= $e->getMessage()?>');
	</script>
	<div align="center"><br><br><b>Ha ocurrido un error al guardar los datos.</b><br><a href="#" onClick="history.back()">Volver</a></div>
<?
	exit;
}
?>
<script>
	function closeWindow() {
		window.close();
	}

	setInterval("closeWindow()", 2000);
	window.opener.cambiarUsuarioAEvaluar(window.opener.document.getElementById('Evaluado').value, window.opener.document.getElementById('Ano').value);		// Recargo los datos..
</script>
<div align="center"><br><br><b>Los datos han sido guardados exitosamente.</b></div>