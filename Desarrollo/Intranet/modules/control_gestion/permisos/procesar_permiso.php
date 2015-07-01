<?
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


function setDefaults(&$values) {
	if ($values[0] == "")
		$values[0] = "N";
	if ($values[1] == "")
		$values[1] = 0;
	if ($values[2] == "")
		$values[2] = "N";
	if ($values[3] == "")
		$values[3] = "N";
	if ($values[4] == "")
		$values[4] = "N";
}

function validar() {
	$errores = false;

	echo "<script>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["nivel"] != "")
		if (!validarEntero($_POST["nivel"])) {
			echo "errores+= '- El campo Nivel debe ser numérico.<br />';";
			$errores = true;
		}


	if ($errores) {
		echo "getElementById('errores').innerHTML = errores;";
		echo "getElementById('divErrores').style.display = 'inline';";
		echo "getElementById('foco').style.display = 'block';";
		echo "getElementById('foco').focus();";
		echo "getElementById('foco').style.display = 'none';";
	}
	else {
		echo "getElementById('divErrores').style.display = 'none';";
	}

	echo "}";
	echo "</script>";

	return !$errores;
}


try {
	if (!validar())
		exit;

	// Borro todos los registros..
	$sql = "DELETE FROM web.wpt_permisostablerocontrol";
	DBExecSql($conn, $sql, array(), OCI_DEFAULT);

	// Agrego los permisos para los usuarios seleccionados..
	for ($i=0; $i<count($_REQUEST["usuariosConPermiso"]); $i++) {
		setDefaults($_SESSION["permisosControlGestion"][$_REQUEST["usuariosConPermiso"][$i]]);
		$params = array(":ejecutivo" => $_SESSION["permisosControlGestion"][$_REQUEST["usuariosConPermiso"][$i]][0],
										":gestion" => $_SESSION["permisosControlGestion"][$_REQUEST["usuariosConPermiso"][$i]][2],
										":informesgestion" => $_SESSION["permisosControlGestion"][$_REQUEST["usuariosConPermiso"][$i]][4],
										":nivelejecutivo" => $_SESSION["permisosControlGestion"][$_REQUEST["usuariosConPermiso"][$i]][1],
										":operativo" => $_SESSION["permisosControlGestion"][$_REQUEST["usuariosConPermiso"][$i]][3],
										":usualta" => strtoupper(GetWindowsLoginName()),
										":usuario" => $_REQUEST["usuariosConPermiso"][$i]);
		$sql =
			"INSERT INTO web.wpt_permisostablerocontrol (pt_ejecutivo, pt_fechaalta, pt_gestion, pt_informesgestion, pt_nivelejecutivo, pt_operativo, pt_usualta, pt_usuario)
																					 VALUES (:ejecutivo, SYSDATE, :gestion, :informesgestion, :nivelejecutivo, :operativo, :usualta, :usuario)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);


		// Permisos para las páginas de informes de gestión..
		$params = array(":usuario" => $_REQUEST["usuariosConPermiso"][$i]);
		$sql =
			"DELETE FROM web.wpe_permisosintranet
						 WHERE pe_idpagina IN(33, 35)
							 AND pe_idusuario = (SELECT se_id
																		 FROM use_usuarios
																		WHERE se_usuario = :usuario)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		if ($_SESSION["permisosControlGestion"][$_REQUEST["usuariosConPermiso"][$i]][4] == "S") {
			$params = array(":usuario" => $_REQUEST["usuariosConPermiso"][$i]);
			$sql =
				"INSERT INTO web.wpe_permisosintranet (pe_idpagina, pe_idusuario)
																			 VALUES (33, (SELECT se_id
																											FROM use_usuarios
																										 WHERE se_usuario = :usuario))";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);

			$params = array(":usuario" => $_REQUEST["usuariosConPermiso"][$i]);
			$sql =
				"INSERT INTO web.wpe_permisosintranet (pe_idpagina, pe_idusuario)
																			 VALUES (35, (SELECT se_id
																											FROM use_usuarios
																										 WHERE se_usuario = :usuario))";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}
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
	window.parent.document.getElementById('guardadoOk').style.display = 'block';
</script>