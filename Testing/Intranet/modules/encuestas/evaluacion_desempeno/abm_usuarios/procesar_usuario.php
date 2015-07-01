<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


try {
	$notificacion = "";
	if ($_REQUEST["notificacion1Id"] != -1)
		$notificacion = $_REQUEST["notificacion1Id"].";";
	if ($_REQUEST["notificacion2Id"] != -1)
		$notificacion.= $_REQUEST["notificacion2Id"].";";
	$notificacion = substr($notificacion, 0, -1);

	if ($_POST["tipoOp"] == "A") {		// Alta..
		$sql =
			"SELECT 1
				FROM rrhh.hue_usuarioevaluacion
			 WHERE ue_evaluado = :evaluado
				  AND ue_anoevaluacion = :ano";
		$params = array(":evaluado" => $_REQUEST["evaluadoId"], ":ano" => $_REQUEST["ano"]);
		if (ExisteSql($sql, $params, 0))
			throw new Exception("Ya existe un usuario cargado para ese año.");

		$sql =
			"INSERT INTO rrhh.hue_usuarioevaluacion (ue_anoevaluacion, ue_categoria, ue_estado, ue_evaluado, ue_fechaalta, ue_notificacion,
																					ue_evaluador, ue_supervisor, ue_usualta)
																	VALUES (:ano, :categoria, :estado, :evaluado, SYSDATE, :notificacion, :evaluador, :supervisor,
																				  :usualta)";
		$params = array(":ano" => $_REQUEST["ano"],
									":categoria" => nullIfCero($_REQUEST["competencias"], true),
									":estado" => $_REQUEST["estado"],
									":evaluado" => $_REQUEST["evaluadoId"],
									":notificacion" => $notificacion,
									":evaluador" => nullIfCero($_REQUEST["evaluadorId"], true),
									":supervisor" => nullIfCero($_REQUEST["supervisorId"], true),
									":usualta" => GetWindowsLoginName(true));
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql =
			"INSERT INTO rrhh.hfe_formularioevaluacion2008 (fe_anoevaluacion, fe_estado, fe_evaluado, fe_fechaalta, fe_fechadesde,
																							  fe_fechahasta, fe_usualta)
																				VALUES (:ano, :estado, :evaluado, SYSDATE, TO_DATE(:fechadesde, 'dd/mm/yyyy'),
																								TO_DATE(:fechahasta, 'dd/mm/yyyy'), :usualta)";
		$params = array(":ano" => $_REQUEST["ano"],
									":estado" => $_REQUEST["estado"],
									":evaluado" => $_REQUEST["evaluadoId"],
									":fechadesde" => $_REQUEST["fechaDesde"],
									":fechahasta" => $_REQUEST["fechaHasta"],
									":usualta" => GetWindowsLoginName(true));
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		// Actualizo las competencias que se espera que el usuario cumpla trayendolas de la evaluación anterior..
		$sql =
			"UPDATE rrhh.hfe_formularioevaluacion2008
					SET (fe_orientacionesp, fe_adaptabilidadesp, fe_equipoesp, fe_clienteesp, fe_liderazgoesp, fe_planificacionesp, fe_analiticoesp) =
			 (SELECT fe_orientacionfuturo, fe_adaptabilidadfuturo, fe_equipofuturo, fe_clientefuturo, fe_liderazgofuturo, fe_planificacionfuturo,
							 fe_analiticofuturo
				 FROM rrhh.hfe_formularioevaluacion2008
			  WHERE fe_evaluado = :evaluado
					AND fe_fechabaja IS NULL
					AND fe_anoevaluacion = :ano1)
				WHERE fe_evaluado = :evaluado
					AND fe_anoevaluacion = :ano2";
		$params = array(":ano" => ($_REQUEST["ano"] - 1),
									":evaluado" => $_REQUEST["evaluadoId"],
									":ano2" => $_REQUEST["ano"]);
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	if ($_POST["tipoOp"] == "M") {		// Modificación..
		$sql =
			"UPDATE rrhh.hue_usuarioevaluacion
					SET ue_categoria = :categoria,
							ue_estado = :estado,
							ue_evaluador = :evaluador,
							ue_fechamodif = SYSDATE,
							ue_notificacion = :notificacion,
							ue_supervisor = :supervisor,
							ue_usumodif = :usumodif
			  WHERE ue_id = :id";
		$params = array(":categoria" => nullIfCero($_REQUEST["competencias"], true),
									":estado" => $_REQUEST["estado"],
									":evaluador" => nullIfCero($_REQUEST["evaluadorId"], true),
									":notificacion" => $notificacion,
									":supervisor" => nullIfCero($_REQUEST["supervisorId"], true),
									":usumodif" => GetWindowsLoginName(true),
									":id" => $_REQUEST["id"]);
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql =
			"UPDATE rrhh.hfe_formularioevaluacion2008
					SET fe_estado = :estado,
							fe_evaluador = :evaluador,
							fe_fechadesde = TO_DATE(:fechahasta, 'dd/mm/yyyy'),
							fe_fechahasta = TO_DATE(:fechahasta, 'dd/mm/yyyy'),
							fe_fechamodif = SYSDATE,
							fe_supervisor = :supervisor,
							fe_usumodif = :usumodif
			  WHERE fe_evaluado = :evaluado
					AND fe_anoevaluacion = :ano";
		$params = array(":estado" => $_REQUEST["estado"],
									":evaluador" => nullIfCero($_REQUEST["evaluadorId"], true),
									":fechadesde" => $_REQUEST["fechaDesde"],
									":fechahasta" => $_REQUEST["fechaHasta"],
									":supervisor" => nullIfCero($_REQUEST["supervisorId"], true),
									":usumodif" => GetWindowsLoginName(true),
									":evaluado" => $_REQUEST["evaluadoId"],
									":ano" => $_REQUEST["ano"]);
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	if ($_POST["tipoOp"] == "B") {		// Baja..
		$sql =
			"UPDATE rrhh.hue_usuarioevaluacion
					SET ue_fechabaja = SYSDATE,
							ue_usubaja = :usubaja
			  WHERE ue_id = :id";
		$params = array(":usubaja" => GetWindowsLoginName(true), ":id" => $_REQUEST["id"]);
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql =
			"UPDATE rrhh.hfe_formularioevaluacion2008
					SET fe_fechabaja = SYSDATE,
							fe_usubaja = :usubaja
			  WHERE fe_evaluado = :evaluado
					AND fe_anoevaluacion = :ano";
		$params = array(":usubaja" => GetWindowsLoginName(true),
									":evaluado" => $_REQUEST["evaluadoId"],
									":ano" => $_REQUEST["ano"]);
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script>
	window.parent.location.href = '/index.php?pageid=57&buscar=yes&anoBusqueda=<?= $_REQUEST["ano"]?>&evaluadoBusqueda=<?= $_REQUEST["evaluado"]?>';
</script>