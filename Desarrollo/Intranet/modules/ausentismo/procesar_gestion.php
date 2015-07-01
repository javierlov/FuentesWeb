<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");


function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";


	$accionesOk = true;
	$usuariosOk = true;
	for ($i=1; $i<=$_REQUEST["totalRegistros"]; $i++) {
		if ($_REQUEST["acciones".$i] == -1)
			$accionesOk = false;
		if ($_REQUEST["usuario".$i] == -1)
			$usuariosOk = false;
	}

	if (!$accionesOk) {
		echo "errores+= '- Debe seleccionar todas las acciones.<br />';";
		$errores = true;
	}

	if (!$usuariosOk) {
		echo "errores+= '- Debe seleccionar todos los usuarios.<br />';";
		$errores = true;
	}


	if ($errores) {
		echo "body.style.cursor = 'default';";
		echo "getElementById('btnEnviar').style.display = 'inline';";
		echo "getElementById('imgProcesando').style.display = 'none';";
		echo "getElementById('errores').innerHTML = errores;";
		echo "getElementById('divErroresForm').style.display = 'block';";
		echo "getElementById('foco').style.display = 'block';";
		echo "getElementById('foco').focus();";
		echo "getElementById('foco').style.display = 'none';";
	}
	else {
		echo "getElementById('divErroresForm').style.display = 'none';";
	}

	echo "}";
	echo "</script>";

	return !$errores;
}


if (!validar())
	exit;


for ($i=1; $i<=$_REQUEST["totalRegistros"]; $i++) {
	// Guardo los datos en la tabla..
	$params = array(":id" => $_REQUEST["id".$i],
									":idjefe" => $_REQUEST["usuario".$i],
									":informado" => $_REQUEST["acciones".$i],
									":usumodif" => getWindowsLoginName());
	$sql =
		"UPDATE rrhh.rha_ausencias
				SET ha_fechaavisojefe = SYSDATE,
						ha_idjefe = :idjefe,
						ha_informado = :informado,
						ha_usumodif = UPPER(:usumodif)
			WHERE ha_id = :id";
	DBExecSql($conn, $sql, $params);

	$params = array(":id" => $_REQUEST["usuario".$i]);
	$sql =
		"SELECT NVL(se_mail, se_usuario)
			 FROM use_usuarios
			WHERE se_id = :id";
	$email = valorSql($sql, "", $params);

	$params = array(":id" => $_REQUEST["id".$i]);
	$sql = 
		"SELECT ha_idmotivoausencia
			 FROM rrhh.rha_ausencias
			WHERE ha_id = :id";
	$idMotivo = valorSql($sql, "", $params);

	$params = array(":id" => $idMotivo);
	$sql = 
		"SELECT ma_detalle
			 FROM rrhh.rma_motivosausencia
			WHERE ma_id = :id";
	$motivo = valorSql($sql, "", $params);

	if ($_REQUEST["acciones".$i] == "T") {
		if ($idMotivo == 1) {		// Enfermedad..
			// Envío el e-mail al usuario seleccionado..
			$params = array(":id" => $_REQUEST["usuario".$i]);
			$sql =
				"SELECT se_usuario
					 FROM use_usuarios
					WHERE se_id = :id";
			$usuario = valorSql($sql, "", $params);

			$curs = null;
			$params = array(":idausencia" => $_REQUEST["id".$i], ":usuario" => getWindowsLoginName(true), ":destinatario" => $usuario);
			$sql = "BEGIN art.intraweb.do_confirmarenviomedico(:idausencia, :usuario, :destinatario); END;";
			$stmt = DBExecSP($conn, $curs, $sql, $params, false);
		}
		else {
			$body = "Se informa que se ha reportado la ausencia de ".$_REQUEST["empleado".$i]." por ".$motivo.".\n". "Por cualquier duda o consulta, favor de comunicarse con RRHH.";
			sendEmail($body, "Aviso Intranet", "Aviso de Ausencia", array($email), array(), array());
		}
	}
}
?>
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