<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/encryptation.php");


function DBBeginTrans($connection) {
	// Inicia una transacción...

	mysql_query("START TRANSACTION", $connection);
	return mysql_query("BEGIN", $connection);
}

function DBCloseConnection($id) {
	// Libera todos los recursos asociados a la sentencia..

	mysql_close($id);
}

function DBCommit($connection) {
	// Confirma transacciones pendientes..

	mysql_query("COMMIT", $connection);
}

/*
HACER PARA MYSQL..
function DBExecSP($connection, &$cursor, $sql, $params, $return = true) {
	global $dbError;

	$cursor = oci_new_cursor($connection);
  $stmt = oci_parse($connection, $sql);

	if ($return)
		oci_bind_by_name($stmt, "data", $cursor, -1, OCI_B_CURSOR);
	oci_execute($stmt);
	oci_execute($cursor);
  $dbError = OCIError($stmt);

	if ($dbError["offset"])
		throw new Exception($dbError["message"]);

  return $stmt;
}
*/

function DBExecSql($sql) {
	// Ejecuta el query pasado como parámetro..

	$result = mysql_query($sql);
	if (!$result) {
		throw new Exception(mysql_error());

	return $result;
}

/*
HACER PARA MYSQL..
function DBExecSqlRawValue($connection, $sql, $rawParamName, $rawParamValue, $mode = OCI_COMMIT_ON_SUCCESS) {
	// Ejecuta el query con los valores pasados como parámetro, entre ellos un campo de tipo raw..

	global $dbError;

  $stmt = OCIParse($connection, $sql);
  OCIBindByName($stmt, ":".$rawParamName, $rawParamValue, strlen($rawParamValue));
  OCIExecute($stmt, $mode);
  $dbError = OCIError($stmt);

	if ($dbError)
		throw new Exception($dbError["message"]);

  return $stmt;
}
*/

function DBGetConnection() {
	// Establece una conexión con el servidor MySql..

	if (!$db = mysql_connect(DB_HOST, DB_USER, decrypt(DB_PASS, "PROVART")))
		throw new Exception(mysql_error());

	if (!mysql_select_db(DB_DATABASE_NAME))
		throw new Exception(mysql_error());

	return $db;
}

function DBGetQuery($resource) {
	// Devuelve la siguiente fila del recurso pasado como parámetro

	return mysql_fetch_assoc($resource);
}

function DBGetRecordCount($resource) {
	// Devuelve el número de filas buscadas..

	return mysql_num_rows($resource);
}

function DBRollback($connection) {
	// Vuelve para atrás las transacciones pendientes..

	return mysql_query("ROLLBACK", $connection);
}

/*
HACER PARA MYSQL..
function DBSaveLob($connection, $sql, $blobParamName, $data, $lobType) {
	// Guarda datos en un clob..

	global $dbError;

	$lob = OCINewDescriptor($connection, OCI_D_LOB);
	$stmt = OCIParse($connection, $sql);
	OCIBindByName($stmt, ":".$blobParamName, $lob, -1, $lobType);
	OCIExecute($stmt, OCI_DEFAULT);

	$result = $lob->write($data);
	if ($result)
		OCICommit($connection);
  $dbError = OCIError($stmt);

	if ($dbError["offset"])
		throw new Exception($dbError["message"]);

  return $result;
}
*/

/*
HACER PARA MYSQL..
function DBGetSP($cursor) {
	// Devuelve la siguiente fila del cursor pasado como parámetro..

  return oci_fetch_array($cursor, OCI_RETURN_NULLS);
}
*/

function ExisteSqlMySql($sql, $params, $mode) {
	// Devuelve true si el query pasado como parámetro tiene resultados..

	$res = DBExecSql($sql, $params);
	$row = DBGetQuery($res);

	return (count($row) > 0);
}

/*
HACER PARA MYSQL..
function SqlDateMySql($fecha) {
	return "TO_DATE('".$fecha."', 'dd/mm/yyyy')";
}
*/

/*
HACER PARA MYSQL..
function SetDateFormatOracle($format) {
	// Configura el formato de los campos de tipo Date que devuelven los querys..

	global $conn;

	$sql = "ALTER SESSION SET NLS_DATE_FORMAT = '".$format."'";
	DBExecSql($conn, $sql);
}
*/

function ValorSqlMySql($sql, $default, $params, $mode) {
	// Devuelve el valor del primer campo del primer registro del query pasado como parámetro..

	$res = DBExecSql($sql, $params);
	$row = DBGetQuery($res);

	if ($row[0] == "")
		return $default;
	else
		return $row[0];
}
?>