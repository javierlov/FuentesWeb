<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/constants.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/database/db.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/database/db_funcs.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/miscellaneous/general.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/miscellaneous/send_email.php");

/* Implementación de múltiples sistemas dentro del sistema de tickets */
if (isset($_REQUEST["sistema"]))
    $sistema = $_REQUEST["sistema"];
else
    $sistema = 1;

// Guardo la calificación del pedido en la tabla..
$sql = "UPDATE computos.css_solicitudsistemas
		SET ss_fechamodif = SYSDATE,
				ss_idusumodif = :idusumodif,
				ss_idestadoactual = :idestadoactual,
				ss_idcalificacion = DECODE(:idcalificacion, -1, NULL, :idcalificacion),
				ss_resuelto = :resuelto,
				ss_comentarios_usuario = :comentarios
  WHERE ss_id = :id";
$params = array(":idusumodif" => GetUserID(),
                ":idestadoactual" => 7,
                ":idcalificacion" => $_REQUEST["calificacion"],
                ":resuelto" => $_REQUEST["resuelto"],
                ":comentarios" => $_REQUEST["comentarios"],
                ":id" => $_REQUEST["id"]);
DBExecSql($conn, $sql, $params);
?>
<html>
	<head>
		<meta http-equiv="Refresh" content="0; url=index.php?sistema=<?echo $sistema; ?>&ticket_detail=yes&id=<?= $_REQUEST["id"] ?>" />
	</head>
	<body>

	</body>
</html>