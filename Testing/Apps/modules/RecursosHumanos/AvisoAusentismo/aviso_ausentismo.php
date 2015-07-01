<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/table_funcs.php");


$title = "INFORME DE AUSENTISMO";
if (!isset($_REQUEST["TRANSACCION"])) {
	ShowError($title, "Esta p�gina s�lo puede ser accedida desde el correo electr�nico que le ha sido enviado.");
	exit;
}

$params = array(":idtransaccion" => $_REQUEST["TRANSACCION"]);
$sql =
	"SELECT 1
		 FROM web.waa_avisosausentismo
		WHERE aa_idtransaccionweb = :idtransaccion
			AND aa_fecharespuesta IS NOT NULL";
if (ExisteSql($sql, $params)) {
	ShowError($title, "Esta operaci�n ya fue realizada.");
	exit;
}
?>
<html>
	<head>
		<title>IntraWEB | <?= $title?></title>
		<script src="aviso_ausentismo.js" type="text/javascript"></script>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
	</head>
<body>
<?
$sql = 
	"SELECT ha_empleado, ma_detalle, se_nombre, ha_observaciones, '                                                            ' observaciones, null permite
		 FROM rrhh.rha_ausencias, web.waa_avisosausentismo, rrhh.rma_motivosausencia, use_usuarios
		WHERE ha_id = aa_idausencia
			AND ma_id = ha_idmotivoausencia
			AND se_usuario = ha_usualta
			AND aa_idtransaccionweb = ".$_REQUEST["TRANSACCION"];
BuildTable($title, $conn, $sql,
					 array("EMPLEADO AUSENTE", "MOTIVO", "REPORTADO POR", "OBSERVACIONES", "SUS OBSERVACIONES", ""),
					 array(0, 0, 0, 0, 1, 0),
					 array(1, 1, 1, 1, 1, 0),
					 "procesar_aviso_ausentismo.php",
					 array("SI", "NO"),
					 array("si", "no"),
					 "� Desea enviar m�dico ?",
					 array(0, 0, 0, 0, 0, 0),
					 array(0, 0, 0, 0, 0, 0));
?>
	</body>
</html>