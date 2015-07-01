<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");


$params = array(":usuario" => GetWindowsLoginName(true));
$sql =
	"SELECT se_nombre, se_piso
		 FROM use_usuarios
		WHERE se_usuario = :usuario";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$body = $row["SE_NOMBRE"]." le avisa que el tacho del Garrahan del piso ".$row["SE_PISO"]." se encuentra lleno.";
SendEmail($body, "Aviso Intranet", "Tacho del Garrahan lleno", array("vdominguez@provart.com.ar"), array(), array(), "T");
?>
<html>
	<head>
		<title>Gracias por ayudar  -  Provincia ART</title>
		<link href="\styles\style.css" rel="stylesheet" type="text/css" />
		<style>
			html, body {
				background-color: #E7E7E7;
				margin-left: 40px;
				margin-top: 36px;
			}
		</style>
	</head>
	<body onLoad="setTimeout('close()', 3000)">
		<span class="VolantaArticulo">Gracias por su cooperación.</span>
	</body>
</html>