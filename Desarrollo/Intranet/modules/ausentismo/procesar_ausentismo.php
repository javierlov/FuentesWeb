<?php
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


	if ($_POST["empleadoAusente"] == -1) {
		echo "errores+= '- El campo Empleado Ausente es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["motivoAusencia"] == -1) {
		echo "errores+= '- El campo Motivo de Ausencia es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["observaciones"] == "") {
		echo "errores+= '- El campo Observaciones es obligatorio.<br />';";
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


$params = array(":id" => $_REQUEST["empleadoAusente"]);
$sql =
	"SELECT se_nombre
		 FROM use_usuarios
		WHERE se_id = :id";
$empleado = valorSql($sql, "", $params);

if ($_REQUEST["enviarMedico"] == -1)
	$enviarMedico = "";
else
	$enviarMedico = $_REQUEST["enviarMedico"];

// Guardo los datos en la tabla..
$params = array(":empleado" => $empleado,
								":enviarmedico" => $enviarMedico,
								":idmotivoausencia" => $_REQUEST["motivoAusencia"],
								":justifique" => $_REQUEST["justifique"],
								":observaciones" => $_REQUEST["observaciones"],
								":usualta" => getWindowsLoginName(true));
$sql =
	"INSERT INTO rrhh.rha_ausencias (ha_empleado, ha_enviarmedico, ha_fechaalta, ha_id, ha_idmotivoausencia, ha_motivonoenviomedico, ha_observaciones, ha_usualta)
													 VALUES (:empleado, :enviarmedico, SYSDATE, -1, :idmotivoausencia, :justifique, SUBSTR(:observaciones, 0, 255), UPPER(:usualta))";
DBExecSql($conn, $sql, $params);

$sql = "SELECT MAX(ha_id) FROM rrhh.rha_ausencias";
$id = valorSql($sql);
?>
<script language="JavaScript" src="/js/functions.js"></script>
<script type="text/javascript">
<?
if ($dbError["offset"]) {
?>
	alert('<?= $dbError["message"]?>');
<?
}
else {
	$params = array(":id" => $_REQUEST["motivoAusencia"]);
	$sql = 
		"SELECT ma_detalle
			 FROM rrhh.rma_motivosausencia
			WHERE ma_id = :id";
 	$motivo = valorSql($sql, "", $params);

	// Envío un e-mail de aviso a RRHH..
	$body = "Se registró una nueva ausencia.\n".
					"El empleado ausente es: ".$empleado.".\n".
					"Reportado por: ".getUserName().".\n".
					"Motivo: ".$motivo.".\n".
					"Enviar médico: ".(($_REQUEST["enviarMedico"] == "T")?"Sí":"No").".\n";
	if ($_REQUEST["enviarMedico"] == "F")
		$body.= "Justificación: ".$_REQUEST["justifique"].".";
	sendEmail($body, "Aviso Intranet", "Aviso de Ausencia", array("rrhh-provinciaart"), array(), array());

	echo "showMsgOk('/ausentismo', window.parent);";
}
?>
</script>