<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


function validar() {
	if ($_POST["usuarios".$_POST["valor"]] < 1) {
		echo "<script>alert('Debe seleccionar un compañero.'); window.parent.document.getElementById('usuarios".$_POST["valor"]."').focus();</script>";
		return false;
	}

	if ($_POST["motivo".$_POST["valor"]] == "") {
		echo "<script>alert('Debe indicar el motivo por el cual eligió a su compañero.'); window.parent.document.getElementById('motivo".$_POST["valor"]."').focus();</script>";
		return false;
	}

	return true;
}

try {
	$_POST["motivo".$_POST["valor"]] = trim($_POST["motivo".$_POST["valor"]]);
	if (!validar())
		exit;

	$params = array(":fase" => $_POST["fase"],
									":idvotante" => GetUserID(),
									":valor" => $_POST["valor"]);
	$sql =
		"SELECT 1
			 FROM rrhh.rjo_jjoo2012
			WHERE jo_idvotante = :idvotante
				AND jo_valor = :valor
				AND jo_fase = :fase";
	if (!ExisteSql($sql, $params)) {		// Alta
		$params = array(":fase" => $_POST["fase"],
										":idvotante" => GetUserID(),
										":motivo" => substr($_POST["motivo".$_POST["valor"]], 0, 2048),
										":valor" => $_POST["valor"],
										":votado" => $_POST["usuarios".$_POST["valor"]]);
		$sql =
			"INSERT INTO rrhh.rjo_jjoo2012
									(jo_fase, jo_fechaalta, jo_idvotante, jo_motivo, jo_valor, jo_votado)
					 VALUES (:fase, SYSDATE, :idvotante, :motivo, :valor, :votado)";
		DBExecSql($conn, $sql, $params);
	}
	else {		// Modificación..
		$params = array(":fase" => $_POST["fase"],
										":idvotante" => GetUserID(),
										":motivo" => substr($_POST["motivo".$_POST["valor"]], 0, 2048),
										":valor" => $_POST["valor"],
										":votado" => $_POST["usuarios".$_POST["valor"]]);
		$sql =
			"UPDATE rrhh.rjo_jjoo2012
					SET jo_fechamodif = SYSDATE,
							jo_motivo = :motivo,
							jo_votado = :votado
				WHERE jo_idvotante = :idvotante
					AND jo_valor = :valor
					AND jo_fase = :fase";
		DBExecSql($conn, $sql, $params);
	}
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script>
	function ocultarOk() {
		window.parent.document.getElementById('voto<?= $_POST["valor"]?>Ok').style.display = 'none';
	}

	window.parent.document.getElementById('voto<?= $_POST["valor"]?>Ok').style.display = 'inline';
	setTimeout('ocultarOk()', 1500);
</script>