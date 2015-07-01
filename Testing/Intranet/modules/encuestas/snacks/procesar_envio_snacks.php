<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


for ($i=0; $i<=4; $i++)
	if (isset($_REQUEST["opcion".$i])) {
		$observaciones = "";
		if (isset($_REQUEST["Observacion".$_REQUEST["opcion".$i]]))
			$observaciones = $_REQUEST["Observacion".$_REQUEST["opcion".$i]];

		$sql =
			"INSERT INTO rrhh.rrp_respuestaspreguntas (rp_idencuesta, rp_idpregunta, rp_idopcion, rp_usuario, rp_fechaalta, rp_observaciones)
																		VALUES (:idencuesta, :idpregunta, :idopcion, :usuario, SYSDATE, :observaciones)";
		$params = array(":idencuesta" => 1,
									":idpregunta" => $_REQUEST["pregunta".$i],
									":idopcion" => $_REQUEST["opcion".$i],
									":usuario" => GetUserID(),
									":observaciones" => $observaciones);
		DBExecSql($conn, $sql, $params);
	}

if ($dbError["offset"]) {
?>
<script>
	alert('<?= $dbError["message"]?>');
</script>
<?
}
else
	header("Refresh: 0; url=/modules/encuestas/snacks/index.php?std=f");		// estado=fin..
?>