<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


// Valido que se haya logueado o que sea administrador..
if ((!isset($_SESSION["idUsuario"])) or (!$_SESSION["esAdministrador"])) {
	header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."login.php");
	exit;
}

try {
	if (isset($_REQUEST["empresa"]))
		$empresa = $_REQUEST["empresa"];
	else
		$empresa = $_REQUEST["idempresa"];


	if (($_REQUEST["tipoOp"] == "A") or ($_REQUEST["tipoOp"] == "M")) {
		$params1 = array(":id" => $_REQUEST["estadoAnterior"]);
		$sql =
			"SELECT es_orden
				 FROM rrhh.res_estadossistemasgestion
				WHERE es_id = :id";

		$params2 = array(":id" => $_REQUEST["estado"]);
		$sql2 =
			"SELECT es_orden
				 FROM rrhh.res_estadossistemasgestion
				WHERE es_id = :id";

		if (ValorSql($sql2, "", $params2, 0) > ValorSql($sql, "", $params1, 0))
			throw new Exception("No puede seleccionar un estado posterior al que ya tenía.");
	}

	$administrador = "F";
	if (isset($_REQUEST["administrador"]))
		$administrador = "T";

	$referente = "N";
	if (isset($_REQUEST["referente"]))
		$referente = "S";

	if ($_REQUEST["tipoOp"] == "A") {		// Alta..
		$params = array(":empresa" => $empresa,
										":gerencia" => nullIfCero($_REQUEST["gerencia"]),
										":puesto" => nullIfCero($_REQUEST["puesto"]),
										":empleado" => $_REQUEST["empleado"],
										":documento" => $_REQUEST["numeroDocumento"],
										":email" => $_REQUEST["email"],
										":jefe" => nullIfCero($_REQUEST["reporta"]),
										":referente" => $referente,
										":activardesde" => $_REQUEST["activarDesde"],
										":activarhasta" => $_REQUEST["activarHasta"],
										":grupo" => nullIfCero($_REQUEST["grupo"]),
										":referenterrhh" => nullIfCero($_REQUEST["referenteRrhh"]),
										":password" => md5($_REQUEST["numeroDocumento"]),
										":administrador" => $administrador,
										":estado" => nullIfCero($_REQUEST["estado"]),
										":departamento" => $_REQUEST["departamento"]);
		$sql =
			"INSERT INTO rrhh.dpl_login
									 (pl_id, pl_empresa, pl_gerencia, pl_puesto, pl_empleado, pl_documento, pl_mail, pl_jefe, pl_referente, pl_fechadesde, pl_fechahasta, pl_idgrupo,
										pl_rrhh, pl_cambiopassword, pl_password, pl_administrador, pl_idestado, pl_departamento)
						VALUES (-1, :empresa, :gerencia, :puesto, :empleado, :documento, :email, :jefe, :referente, TO_DATE(:activardesde, 'dd/mm/yyyy'), TO_DATE(:activarhasta, 'dd/mm/yyyy'), :grupo,
										:referenterrhh, 0, :password, :administrador, :estado, :departamento)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	if ($_REQUEST["tipoOp"] == "M") {		// Modificación..
		$params = array(":empresa" => $empresa,
										":gerencia" => nullIfCero($_REQUEST["gerencia"]),
										":puesto" => nullIfCero($_REQUEST["puesto"]),
										":empleado" => $_REQUEST["empleado"],
										":documento" => $_REQUEST["numeroDocumento"],
										":email" => $_REQUEST["email"],
										":jefe" => nullIfCero($_REQUEST["reporta"]),
										":referente" => $referente,
										":activardesde" => $_REQUEST["activarDesde"],
										":activarhasta" => $_REQUEST["activarHasta"],
										":grupo" => nullIfCero($_REQUEST["grupo"]),
										":administrador" => $administrador,
										":referenterrhh" => nullIfCero($_REQUEST["referenteRrhh"]),
										":estado" => nullIfCero($_REQUEST["estado"]),
										":departamento" => $_REQUEST["departamento"]);
		$sql =
	  		"UPDATE rrhh.dpl_login
	  				SET pl_empresa = :empresa,
  							pl_gerencia = :gerencia,
	  						pl_puesto = :puesto,
	  						pl_empleado = :empleado,
  							pl_documento = :documento,
  							pl_mail = :email,
	  						pl_jefe = :jefe,
  							pl_referente = :referente,
  							pl_fechadesde = :activardesde,
		  					pl_fechahasta = :activarhasta,
  							pl_idgrupo = :grupo,
  							pl_idestado = :estado,
  							pl_departamento = :departamento,
	  						pl_administrador = :administrador,";

		if (isset($_REQUEST["resetearClave"])){
			$params[":password"] = md5($_REQUEST["numeroDocumento"]);
			$sql.= " pl_cambiopassword = 0, pl_password = :password,";
		}

		$params[":referenterrhh"] = nullIfCero($_REQUEST["referenteRrhh"]);
		$params[":id"] = $_REQUEST["id"];
		$sql.= "pl_rrhh = :referenterrhh WHERE pl_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	if ($_REQUEST["tipoOp"] == "B") {		// Baja..
		$params = array(":usubaja" => $_SESSION["idUsuario"], ":id" => $_REQUEST["id"]);
		$sql =
			"UPDATE rrhh.dpl_login
					SET pl_fechabaja = SYSDATE,
							pl_usubaja = :usubaja
				WHERE pl_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	window.parent.location.href = '/modules/evaluacion_puesto/abm_descripcion_de_puesto/buscar_usuario.php?buscar=yes';
</script>