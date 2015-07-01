<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");


if (!isset($_SESSION["idUsuario"])) {
?>
	<script type="text/javascript">
		alert('Su sesión ha caducado, por favor ingrese nuevamente para continuar.');
		window.location.href = '<?= LOCAL_PATH_PROGRAMA_INCENTIVOS?>';
	</script>
<?
	exit;
}

try {
	SetDateFormatOracle("DD/MM/YYYY");

	$puntos = 0;
	foreach ($_POST as $key => $value)
		if (substr($key, 0, 10) == "puntos_id_") {
			if ($value == "")
				$value = 0;
			if (!validarEntero($value))
				throw new Exception("Por favor, ingrese un valor entero válido.");

			$puntos+= $value;
			$arr = explode("_", $key);

			$params = array(":idbeneficio" => $arr[2], ":idusuario" => $_SESSION["idUsuario"]);
			$sql =
				"SELECT cp_id
					 FROM rrhh.rcp_canjepuntos
					WHERE cp_idusuario = :idusuario
						AND cp_idbeneficio = :idbeneficio
						AND cp_fechabaja IS NULL";
			$id = ValorSql($sql, 0, $params, 0);

			if ($id == 0) {		// Alta..
				$params = array(":idbeneficio" => $arr[2],
												":idusuario" => $_SESSION["idUsuario"],
												":puntos" => $value,
												":usualta" => $_SESSION["idUsuario"]);
				$sql =
					"INSERT INTO rrhh.rcp_canjepuntos
											 (cp_fechaalta, cp_idbeneficio, cp_idusuario, cp_puntos, cp_usualta)
								VALUES (SYSDATE, :idbeneficio, :idusuario, :puntos, :usualta)";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
			else {		// Modificación..
				$params = array(":id" => $id,
												":puntos" => $value,
												":usumodif" => $_SESSION["idUsuario"]);
				$sql =
					"UPDATE rrhh.rcp_canjepuntos
							SET cp_fechamodif = SYSDATE,
									cp_puntos = :puntos,
									cp_usumodif = :usumodif
						WHERE cp_id = :id";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
		}

	$params = array(":id" => $_SESSION["idUsuario"]);
	$sql =
		"SELECT ui_puntos
			 FROM rrhh.rui_usuarioincentivo
			WHERE ui_id = :id";
	$maxPuntos = ValorSql($sql, 0, $params, 0);

	if ($puntos > $maxPuntos)
		throw new Exception("El saldo no puede ser inferior a cero (0).");

	$params = array(":id" => $_SESSION["idUsuario"], ":saldo" => ($maxPuntos - $puntos));
	$sql =
		"UPDATE rrhh.rui_usuarioincentivo
				SET ui_saldo = :saldo
			WHERE ui_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	if ($_POST["accion"] == "c") {		// Si presionan el botón cerrar..
		if (($maxPuntos - $puntos) > 0)
			throw new Exception("Todavía le quedan puntos por asignar.");

		$params = array(":id" => $_SESSION["idUsuario"]);
		$sql =
			"UPDATE rrhh.rui_usuarioincentivo
					SET ui_fechacierre = SYSDATE
				WHERE ui_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$params = array(":id" => $_SESSION["idUsuario"]);
		$sql =
			"SELECT em_detalle, ui_empleado
				 FROM rrhh.rui_usuarioincentivo, rrhh.rem_empresas
				WHERE ui_idempresa = em_id
					AND ui_id = :id";
		$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		$row = DBGetQuery($stmt);

		$email = "rrhh@".$row["EM_DETALLE"].".com.ar";
		$subject = "Canje de Puntos del Programa de Incentivos 2012/2013";
		$body = "El empleado ".$row["UI_EMPLEADO"]." ha enviado su canje de puntos.";
		SendEmail($body, "Programa de Incentivos", $subject, array($email), array(), array(), 'H');
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
	alert('Los puntos se guardaron correctamente.');
	window.parent.location.href = window.parent.location.href;
</script>