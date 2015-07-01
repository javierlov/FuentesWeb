<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");


$dbError = "";
$link = "http://".$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];

try {
	$params = array(":id" => $_SESSION["idEvaluado"]);
	$sql =
		"SELECT dpl2.pl_id
			 FROM rrhh.dpl_login dpl1, rrhh.dpl_login dpl2
			WHERE dpl1.pl_jefe = dpl2.pl_id
				AND dpl1.pl_id = :id";
	$esJefe = (($_SESSION["idUsuario"] != $_SESSION["idEvaluado"]) and ($_SESSION["idUsuario"] == ValorSql($sql, "", $params, 0)));


	// Guardo la misión..
	$params = array(":idlogin" => $_SESSION["idEvaluado"]);
	$sql =
		"SELECT 1
			 FROM rrhh.dpm_mision
			WHERE pm_idlogin = :idlogin";
	if (ValorSql($sql, 0, $params, 0) == 0) {
		$params = array(":idlogin" => $_SESSION["idEvaluado"]);
		$sql =
			"INSERT INTO rrhh.dpm_mision (pm_idlogin)
														VALUES (:idlogin)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}


	// Guardo los comentarios..
	if ($_SESSION["idUsuario"] == $_SESSION["idEvaluado"]) {		// Si el que guarda es el evaluado..
		$field = "pm_comentariousuario";
		$value = $_POST["comentariosUsuario"];
	}
	else {
		$field = "pm_comentarioresponsable";
		$value = $_POST["comentariosResponsable"];
	}
	$params = array(":valor" => substr($value, 0, 2048),
									":personalcargodirecta" => $_POST["personalCargoDirecta"],
									":personalcargoindirecta" => $_POST["personalCargoIndirecta"],
									":nivelautorizacion" => $_POST["nivelAutorizacion"],
									":idlogin" => $_SESSION["idEvaluado"]);
	$sql =
		"UPDATE rrhh.dpm_mision
				SET ".$field." = :valor,
						pm_personalcargodirecta = :personalcargodirecta,
						pm_personalcargoindirecta = :personalcargoindirecta,
						pm_nivelautorizacion = :nivelautorizacion
			WHERE pm_idlogin = :idlogin";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	if (isset($_POST["mision"])) {
		$params = array(":descripcion" => $_POST["mision"], ":idlogin" => $_SESSION["idEvaluado"]);
		$sql =
			"UPDATE rrhh.dpm_mision
					SET pm_descripcion = :descripcion
				WHERE pm_idlogin = :idlogin";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	if ($_SESSION["idUsuario"] != $_SESSION["idEvaluado"]) {		// Si el que guarda NO es el evaluado..
		// Guardo los conocimientos..
		$sql =
			"SELECT sc_id
				 FROM rrhh.dsc_subfactorconocimiento";
		$stmt = DBExecSql($conn, $sql, array(), OCI_DEFAULT);
		while ($row = DBGetQuery($stmt)) {
			if (!isset($_POST["idCombo".$row["SC_ID"]]))
				break;

			$params = array(":idlogin" => $_SESSION["idEvaluado"], ":idsubfactorconocimiento" => $row["SC_ID"]);
			$sql =
				"SELECT pc_id
					 FROM rrhh.dpc_conocimiento
					WHERE pc_idlogin = :idlogin
						AND pc_idsubfactorconocimiento = :idsubfactorconocimiento";
			$idConocimiento = ValorSql($sql, -1, $params, 0);

			if ($_POST["idCombo".$row["SC_ID"]] == -1) {		// Deleteo el conocimiento..
				$params = array(":idlogin" => $_SESSION["idEvaluado"], ":idsubfactorconocimiento" => $row["SC_ID"]);
				$sql =
					"DELETE FROM rrhh.dpc_conocimiento
								 WHERE pc_idlogin = :idlogin
									 AND pc_idsubfactorconocimiento = :idsubfactorconocimiento";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
			elseif ($idConocimiento == -1) {		// Inserto el conocimiento..
				$params = array(":idlogin" => $_SESSION["idEvaluado"],
												":idsubfactorconocimiento" => $row["SC_ID"],
												":iditemconocimiento" => $_POST["idCombo".$row["SC_ID"]]);
				$sql =
					"INSERT INTO rrhh.dpc_conocimiento (pc_idlogin, pc_idsubfactorconocimiento, pc_iditemconocimiento)
																			VALUES (:idlogin, :idsubfactorconocimiento, :iditemconocimiento)";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
			else {		// Updateo el conocimiento..
				$params = array(":iditemconocimiento" => $_POST["idCombo".$row["SC_ID"]], ":id" => $idConocimiento);
				$sql =
					"UPDATE rrhh.dpc_conocimiento
							SET pc_iditemconocimiento = :iditemconocimiento
						WHERE pc_id = :id";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
		}
	}

	if ($_POST["modo"] == "E") {		// Si terminó de modificar actualizo la fecha..
		if ($_SESSION["idUsuario"] == $_SESSION["idEvaluado"]) {		// Si es el evaluado..
			$field = "pl_fechausuariook";
			$idEstado = 2;
		}
		elseif ($esJefe) {		// Si es el jefe..
			$field = "pl_fechajefeok";
			$idEstado = 3;
		}
		else {		// Si es rrhh..
			$field = "pl_rrhh_ok";
			$idEstado = 4;
		}

		$params = array(":estado" => $idEstado, ":id" => $_SESSION["idEvaluado"]);
		$sql =
			"UPDATE rrhh.dpl_login
					SET ".$field." = SYSDATE,
							pl_idestado = :estado
				WHERE pl_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}
	if ($_POST["modo"] == "N") {		// Si se está notificando guardo la fecha..
		if ($_SESSION["idUsuario"] == $_SESSION["idEvaluado"]) {		// Si es el evaluado..
			$field = "pl_fechanotificausuario";
			$idEstado = 5;
		}
		else {		// Si es el jefe..
			$field = "pl_fechanotificajefe";
			$idEstado = 6;
		}

		$params = array(":estado" => $idEstado, ":id" => $_SESSION["idEvaluado"]);
		$sql =
			"UPDATE rrhh.dpl_login
					SET ".$field." = SYSDATE,
							pl_idestado = :estado
				WHERE pl_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	DBCommit($conn);

	if ($_POST["modo"] == "E") {		// Si terminó de modificar se envía un e-mail..
		if ($_SESSION["idUsuario"] == $_SESSION["idEvaluado"]) {		// Si es el evaluado..
			$params = array(":id" => $_SESSION["idEvaluado"]);
			$sql = 			
				"SELECT dpl1.pl_empleado, dpl2.pl_mail
					 FROM rrhh.dpl_login dpl1, rrhh.dpl_login dpl2
					WHERE dpl1.pl_jefe = dpl2.pl_id
						AND dpl1.pl_id = :id";
			$stmt = DBExecSql($conn, $sql, $params);
			$row = DBGetQuery($stmt);
			$body = "<html><body>El usuario ".$row["PL_EMPLEADO"]." ha finalizado la descripción de su puesto, <a href='".$link.LOCAL_PATH_DESCRIPCION_PUESTO."'>haga click aquí</a> para consultar.<br><br>Si el link no funciona pegue esta dirección en su navegador: ".$link.LOCAL_PATH_DESCRIPCION_PUESTO."</body></html>";
			SendEmail($body, "Sistema de Descripción de Puesto", "[SDP] Aviso de descripción cargada", array($row["PL_MAIL"]), array(), array(), "H");
		}
		elseif ($esJefe) {		// Si es el jefe..
			$params = array(":id" => $_SESSION["idEvaluado"]);
			$sql = 			
				"SELECT dpl1.pl_empleado, dpl2.pl_mail
					 FROM rrhh.dpl_login dpl1, rrhh.dpl_login dpl2
					WHERE dpl1.pl_rrhh = dpl2.pl_id
						AND dpl1.pl_id = :id";
			$stmt = DBExecSql($conn, $sql, $params);
			$row = DBGetQuery($stmt);
			$body = "<html><body>El jefe del empleado ".$row["PL_EMPLEADO"]." ha finalizado la descripción de su puesto, <a href='".$link.LOCAL_PATH_DESCRIPCION_PUESTO."'>haga click aquí</a> para consultar.<br><br>Si el link no funciona pegue esta dirección en su navegador: ".$link.LOCAL_PATH_DESCRIPCION_PUESTO."</body></html>";
			SendEmail($body, "Sistema de Descripción de Puesto", "[SDP] Aviso de descripción cargada", array($row["PL_MAIL"]), array(), array(), "H");
		}
		else {		// Si es rrhh..
			$params = array(":id" => $_SESSION["idEvaluado"]);
			$sql = 			
				"SELECT pl_mail
					 FROM rrhh.dpl_login
					WHERE pl_id = :id";
			$stmt = DBExecSql($conn, $sql, $params);
			$row = DBGetQuery($stmt);
			$body = "<html><body>La carga de la descripción de su puesto ha finalizado, <a href='".$link.LOCAL_PATH_DESCRIPCION_PUESTO."'>haga click aquí</a> para consultar.<br><br>Si el link no funciona pegue esta dirección en su navegador: ".$link.LOCAL_PATH_DESCRIPCION_PUESTO."</body></html>";
			SendEmail($body, "Sistema de Descripción de Puesto", "[SDP] Aviso de descripción finalizada", array($row["PL_MAIL"]), array(), array(), "H");
		}
	}
	if ($_POST["modo"] == "N") {		// Si se está notificando se envía un e-mail..
		if ($_SESSION["idUsuario"] == $_SESSION["idEvaluado"]) {		// Si es el evaluado..
			$params = array(":id" => $_SESSION["idEvaluado"]);
			$sql = 			
				"SELECT dpl1.pl_empleado, dpl2.pl_mail
					 FROM rrhh.dpl_login dpl1, rrhh.dpl_login dpl2
					WHERE dpl1.pl_jefe = dpl2.pl_id
						AND dpl1.pl_id = :id";
			$stmt = DBExecSql($conn, $sql, $params);
			$row = DBGetQuery($stmt);
			$body = "<html><body>El usuario ".$row["PL_EMPLEADO"]." se ha dado por notificado sobre la descripción de su puesto, <a href='".$link.LOCAL_PATH_DESCRIPCION_PUESTO."'>haga click aquí</a> para consultar.<br><br>Si el link no funciona pegue esta dirección en su navegador: ".$link.LOCAL_PATH_DESCRIPCION_PUESTO."</body></html>";
			SendEmail($body, "Sistema de Descripción de Puesto", "[SDP] Aviso de notificación efectuada", array($row["PL_MAIL"]), array(), array(), "H");
		}
		else {		// Si es el jefe..
			$params = array(":id" => $_SESSION["idEvaluado"]);
			$sql = 			
				"SELECT dpl1.pl_empleado, dpl2.pl_mail
					 FROM rrhh.dpl_login dpl1, rrhh.dpl_login dpl2
					WHERE dpl1.pl_rrhh = dpl2.pl_id
						AND dpl1.pl_id = :id";
			$stmt = DBExecSql($conn, $sql, $params);
			$row = DBGetQuery($stmt);
			$body = "<html><body>El jefe del empleado ".$row["PL_EMPLEADO"]." se ha dado por notificado sobre la descripción de su puesto, <a href='".$link.LOCAL_PATH_DESCRIPCION_PUESTO."'>haga click aquí</a> para consultar.<br><br>Si el link no funciona pegue esta dirección en su navegador: ".$link.LOCAL_PATH_DESCRIPCION_PUESTO."</body></html>";
			SendEmail($body, "Sistema de Descripción de Puesto", "[SDP] Aviso de notificación efectuada", array($row["PL_MAIL"]), array(), array(), "H");
		}
	}
}
catch (Exception $e) {
	$dbError = $e->getMessage();
	DBRollback($conn);
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>..:: Sistema de Descripción de Puesto ::..</title>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
<?
if ($dbError != "") {
?>
	<script type="text/javascript">
		alert('<?= $dbError?>');
	</script>
<?
}
else
	header("location:".LOCAL_PATH_DESCRIPCION_PUESTO."descripcion_de_puesto/index.php?modoOk=".$_POST["modo"]);		// E
?>
	</head>
	<body>
		<?= $dbError?>
	</body>
</html>