<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");

if (DB_ENGINE == "odbc")
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/odbc_funcs.php");
elseif (DB_ENGINE == "mysql")
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/mysql_funcs.php");
elseif (DB_ENGINE == "mssql")
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/mssql_funcs.php");
elseif (DB_ENGINE == "oracle")
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/oracle_funcs.php");
else
	exit("Motor de base de datos no disponble.");

$servidorContingenciaActivo = false;
$conn = @DBGetConnection();

/*******  INICIO - Llamado a SP pedido por NKuster el 28.11.2014  *******/
$curs = NULL;
$params = array(":usuario" => getWindowsLoginName(true));
$sql = "BEGIN computos.general.set_usuariologueado(:usuario); END;";
DBExecSP($conn, $curs, $sql, $params, false, 0);
/*******  FIN - Llamado a SP pedido por NKuster el 28.11.2014  *******/

// Configuro variables de entorno de oracle por defecto para todos los archivos que usen funciones de base de datos..
setTerritoryFormatOracle();
setNumberFormatOracle();
setDateFormatOracle("DD/MM/YYYY");
?>