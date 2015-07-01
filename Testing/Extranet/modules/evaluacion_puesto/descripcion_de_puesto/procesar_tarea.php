<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


try {
	if (!isset($_SESSION["idEvaluado"]))
		throw new Exception("Su sesión ha finalizado. Para seguir utilizando este módulo debe volver a loguearse.");

	if ($_POST["tmpAccion"] == "A") {		// Alta..
		$params = array(":comolohizo" => $_POST["tmpComoSeSabeLoQueHizo"],
										":idlogin" => $_SESSION["idEvaluado"],
										":paraquelohace" => $_POST["tmpParaQueLoHace"],
										":quehace" => substr($_POST["tmpQueHace"], 0, 2000));
		$sql =
			"INSERT INTO rrhh.dpd_descripcion (pd_idlogin, pd_quehace, pd_paraquelohace, pd_comolohizo)
																 VALUES (:idlogin, :quehace, :paraquelohace, :comolohizo)";
		DBExecSql($conn, $sql, $params);
	}

	if ($_POST["tmpAccion"] == "M") {		// Modificación..
		$params = array(":comolohizo" => $_POST["tmpComoSeSabeLoQueHizo"],
										":id" => $_POST["tmpId"],
										":paraquelohace" => $_POST["tmpParaQueLoHace"],
										":quehace" => substr($_POST["tmpQueHace"], 0, 2000));
		$sql =
			"UPDATE rrhh.dpd_descripcion
					SET pd_quehace = :quehace,
							pd_paraquelohace = :paraquelohace,
							pd_comolohizo = :comolohizo
			  WHERE pd_id = :id";
		DBExecSql($conn, $sql, $params);
	}

	if ($_POST["tmpAccion"] == "B") {		// Baja..
		$params = array(":id" => $_POST["tmpId"]);
		$sql = "DELETE FROM rrhh.dpd_descripcion WHERE pd_id = :id";
		DBExecSql($conn, $sql, $params);
	}
}
catch (Exception $e) {
	$dbError["offset"] = $e->getMessage();
}
?>
<script type="text/javascript">
<?
if ($dbError["offset"]) {
?>
	alert('<?= $dbError["message"]?>');
<?
}
else {
?>
	window.parent.document.getElementById('iframeTareas').src = window.parent.document.getElementById('iframeTareas').src;
<?
}
?>
</script>