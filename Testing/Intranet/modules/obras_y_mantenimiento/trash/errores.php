<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/DataBase/DB.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/DataBase/DB_Funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/Miscellaneous/General.php");


$sql =
	"SELECT se_nombre
		 FROM use_usuarios
		WHERE se_id = ".$_REQUEST["id"];
$body = GetWindowsLoginName()." informa que los datos de ".ValorSql($sql)." son incorrectos.";

$curs = null;
$sql = "BEGIN art.varios.do_insertartablamails('Intranet', 'vdominguez@provart.com.ar', 'Error Datos Internos', '".$body."', NULL, NULL, NULL); END;";
$stmt = DBExecSP($conn, $curs, $sql, false);
?>
<html>
<head>
	<meta http-equiv="Content-Language" content="es-ar">
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<title>Aviso Registrado</title>
	<script> 
		setTimeout("window.close();", 3000);
	</script>
</head>
<body bgcolor="#C0C0C0">
<table border="0" width="100%" height="100%">
	<tr>
		<td style="padding-left: 4px; padding-right: 4px"><p align="center"><b><font face="Verdana" color="#FFFFFF" size="3">Aviso Registrado</font></b></td>
	</tr>
</table>
</body>
</html>