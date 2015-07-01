<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");


$user = $_SESSION["identidad"];

try {
	$params = array(":idformularioevaluacion" => $_POST["FormularioId"],
									":indicador" => $_POST["Indicador"],
									":motivoreemplazo" => $_POST["MotivoCambio"],
									":motivoreemplazootros" => $_POST["MotivoCambioOtros"],
									":nroobjetivo" => $_POST["Num"],
									":objetivo" => $_POST["Descripcion"],
									":plazo" => $_POST["PlazoEjecucion"],
									":resultado" => $_POST["Resultado"],
									":usualta" => $user);
	$sql =
		"INSERT INTO rrhh.hfo_formularioobjetivo (fo_id_formularioevaluacion, fo_nroobjetivo, fo_objetivo, fo_resultado, fo_indicador, fo_plazo,
																							fo_motivoreemplazo, fo_motivoreemplazootros, fo_usualta, fo_fechaalta)
																VALUES (:idformularioevaluacion, :nroobjetivo, SUBSTR(:objetivo, 1, 2000), SUBSTR(:resultado, 1, 2000), SUBSTR(:indicador, 1, 2000), SUBSTR(:plazo, 1, 2000),
																				:motivoreemplazo, SUBSTR(:motivoreemplazootros, 1, 2048), UPPER(:usualta), SYSDATE)";
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