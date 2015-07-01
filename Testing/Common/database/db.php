<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");

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
?>