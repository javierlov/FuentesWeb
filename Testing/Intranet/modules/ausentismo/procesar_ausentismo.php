<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");


$params = array(":id" => $_REQUEST["EmpleadoAusente"]);
$sql =
	"SELECT se_nombre
		 FROM use_usuarios
		WHERE se_id = :id";
$empleado = ValorSql($sql, "", $params);

if ($_REQUEST["enviarMedico"] == -1)
	$enviarMedico = "";
else
	$enviarMedico = $_REQUEST["enviarMedico"];

// Guardo los datos en la tabla..
$params = array(":empleado" => $empleado,
								":enviarmedico" => $enviarMedico,
								":idmotivoausencia" => $_REQUEST["MotivoAusencia"],
								":justifique" => $_REQUEST["justifique"],
								":observaciones" => $_REQUEST["Observaciones"],
								":usualta" => GetWindowsLoginName(true));
$sql =
	"INSERT INTO rrhh.rha_ausencias
							(ha_empleado, ha_enviarmedico, ha_fechaalta, ha_id, ha_idmotivoausencia, ha_motivonoenviomedico,
							 ha_observaciones, ha_usualta)
			 VALUES (:empleado, :enviarmedico, SYSDATE, -1, :idmotivoausencia, :justifique,
							 SUBSTR(:observaciones, 0, 255), UPPER(:usualta))";
DBExecSql($conn, $sql, $params);

$sql = "SELECT MAX(ha_id) FROM rrhh.rha_ausencias";
$id = ValorSql($sql);
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
	$params = array(":id" => $_REQUEST["MotivoAusencia"]);
	$sql = 
		"SELECT ma_detalle
			 FROM rrhh.rma_motivosausencia
			WHERE ma_id = :id";
 	$motivo = ValorSql($sql, "", $params);

	// Envío un e-mail de aviso a RRHH..
	$body = "Se registró una nueva ausencia.\n".
					"El empleado ausente es: ".$empleado.".\n".
					"Reportado por: ".GetUserName().".\n".
					"Motivo: ".$motivo.".\n".
					"Enviar médico: ".(($_REQUEST["enviarMedico"] == "T")?"Sí":"No").".\n";
	if ($_REQUEST["enviarMedico"] == "F")
		$body.= "Justificación: ".$_REQUEST["justifique"].".";
	SendEmail($body, "Aviso Intranet", "Aviso de Ausencia", array("rrhh-provinciaart"), array(), array());

	echo "window.parent.document.getElementById('spanMensaje').style.display = 'block';";
	echo "window.parent.LimpiarForm(window.parent.document.getElementById('formAusentismo'))";
}
?>
</script>
</head>
<body>
	ok
</body>
</html>