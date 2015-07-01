<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$browser = "";
if (isset($_SERVER["HTTP_USER_AGENT"]))
	$browser = $_SERVER["HTTP_USER_AGENT"];


$params = array(":browser" => substr($browser, 0, 255),
								":remotehost" => substr(gethostbyaddr($_SERVER['REMOTE_ADDR']), 0, 255));
$sql =
	"INSERT INTO web.wmw_menuweberror
							 (mw_browser, mw_fechaerror, mw_remotehost)
				VALUES (:browser, SYSDATE, :remotehost)";
DBExecSql($conn, $sql, $params);
?>
<html>
	<head>
		<meta http-equiv="refresh" content="3;url=/" />
	</head>
	<body>
		ERROR AL CARGAR EL MENÚ
	</body>
</html>