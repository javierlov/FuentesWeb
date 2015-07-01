<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");


for ($i=1; $i<=$_REQUEST["TotalRegistros"]; $i++) {
	// Guardo los datos en la tabla..
	$params = array(":id" => $_REQUEST["id".$i],
									":idjefe" => $_REQUEST["Usuario".$i],
									":informado" => $_REQUEST["Acciones".$i],
									":usumodif" => GetWindowsLoginName());
	$sql =
		"UPDATE rrhh.rha_ausencias
				SET ha_fechaavisojefe = SYSDATE,
						ha_idjefe = :idjefe,
						ha_informado = :informado,
						ha_usumodif = UPPER(:usumodif)
			WHERE ha_id = :id";
	DBExecSql($conn, $sql, $params);

	$params = array(":id" => $_REQUEST["Usuario".$i]);
	$sql =
		"SELECT NVL(se_mail, se_usuario)
			 FROM use_usuarios
			WHERE se_id = :id";
	$email = ValorSql($sql, "", $params);

	$params = array(":id" => $_REQUEST["id".$i]);
	$sql = 
		"SELECT ha_idmotivoausencia
			 FROM rrhh.rha_ausencias
			WHERE ha_id = :id";
	$idMotivo = ValorSql($sql, "", $params);

	$params = array(":id" => $idMotivo);
	$sql = 
		"SELECT ma_detalle
			 FROM rrhh.rma_motivosausencia
			WHERE ma_id = :id";
	$motivo = ValorSql($sql, "", $params);

	if ($_REQUEST["Acciones".$i] == "T") {
		if ($idMotivo == 1) {		// Enfermedad..
			// Envío el e-mail al usuario seleccionado..
			$params = array(":id" => $_REQUEST["Usuario".$i]);
			$sql =
				"SELECT se_usuario
					 FROM use_usuarios
					WHERE se_id = :id";
			$usuario = ValorSql($sql, "", $params);

			$curs = null;
			$params = array(":idausencia" => $_REQUEST["id".$i], ":usuario" => GetWindowsLoginName(true), ":destinatario" => $usuario);
			$sql = "BEGIN art.intraweb.do_confirmarenviomedico(:idausencia, :usuario, :destinatario); END;";
			$stmt = DBExecSP($conn, $curs, $sql, $params, false);
		}
		else {
			$body = "Se informa que se ha reportado la ausencia de ".$_REQUEST["empleado".$i]." por ".$motivo.".\n".
							"Por cualquier duda o consulta, favor de comunicarse con RRHH.";
			SendEmail($body, "Aviso Intranet", "Aviso de Ausencia", array($email), array(), array());
		}
	}
}
?>
<html>
<head>
<script>
<?
if ($dbError["offset"]) {
?>
	alert('<?= $dbError["message"]?>');
<?
}
else {
?>
	alert('Los datos se guardaron exitosamente.');
	window.parent.location.href = window.parent.location.href;
<?
}
?>
</script>
</head>
<body>
	ok
</body>
</html>