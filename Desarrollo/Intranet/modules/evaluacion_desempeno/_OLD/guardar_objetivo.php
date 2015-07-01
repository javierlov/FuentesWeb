<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
session_start();


$user = $_SESSION["identidad"];

try {
	$sql =
		"UPDATE rrhh.hfo_formularioobjetivo
				SET fo_porcentajecumplimiento = :porcentajecumplimiento,
						fo_estado = :estado,
						fo_usumodif = UPPER(:usumodif),
						fo_fechamodif = SYSDATE
		  WHERE fo_id = (SELECT MAX(fo_id)
										FROM rrhh.hfo_formularioobjetivo
									 WHERE fo_id_formularioevaluacion = :idformularioevaluacion
										  AND fo_nroobjetivo = :nroobjetivo)";
	$params = array(":porcentajecumplimiento" => $_REQUEST["porcentaje"],
								":estado" => $_REQUEST["estado"],
								":usumodif" => $user,
								":idformularioevaluacion" => $_REQUEST["formularioid"],
								":nroobjetivo" => $_REQUEST["num"]);
	DBExecSql($conn, $sql, $params);
}
catch (Exception $e) {
?>
	<script>
		alert('<?= $e->getMessage()?>');
	</script>
<?
	exit;
}
?>
<script>
	medioancho = (screen.width - 320) / 2;
	medioalto = (screen.height - 200) / 2;
	divWin = window.parent.dhtmlwindow.open('divBox', 'div', 'msgOk', 'Aviso', 'width=320px,height=40px,left=' + medioancho + 'px,top=' + medioalto + 'px,resize=0,scrolling=0');
</script>