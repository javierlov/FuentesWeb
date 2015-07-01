<?php
//require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");


function DBCloseConnection($resource) {
	// Libera todos los recursos asociados a la sentencia..

	mssql_close($resource);
}

function DBCommit() {
	// Confirma transacciones pendientes..

	mssql_query("COMMIT"); 
}

function DBExecSP($connection, &$cursor, $sql, $params, $return = true, $mode = 1) {
	// Ejecuta un store procedure..

	//
}

function DBExecSql($connection, $sql, $params) {
	// Ejecuta el select pasado como parámetro..

	$results = mssql_query($sql);

	if (!$results)
		throw new Exception("Error en la consulta.");

	return $results;
}

function DBGetConnection($server, $db, $user, $password) {
	// Establece una conexión con el servidor Oracle..

	$conn = mssql_connect($server, $user, $password);

	if (!$conn)
		echo "Error al conectarse al servidor de la base de datos.";

	if (!mssql_select_db($db, $conn))
		echo "Error al seleccionar la base de datos.";

	return $conn;
}

function DBGetQuery($result, $arrayType = 1) {
	// Devuelve la siguiente fila dentro del result-array..

	if ($arrayType == 0)
		$row = mssql_fetch_array($result, MSSQL_NUM);
	if ($arrayType == 1)
		$row = mssql_fetch_array($result, MSSQL_ASSOC);
	if ($arrayType == 2)
		$row = mssql_fetch_array($result, MSSQL_BOTH);

	return $row;
}

function DBGetRecordCount($resourceQuery) {
	// Devuelve el número de filas buscadas..

	return mssql_num_rows($resourceQuery);
}

function DBGetSP($cursor) {
	// Devuelve la siguiente fila del cursor pasado como parámetro..

	//
}

function DBRollback($connection) {
	// Vuelve para atrás las transacciones pendientes..

	//
}

function ExisteSqlMSSQL($sql, $params) {
	// Devuelve true si el query pasado como parámetro tiene resultados..

	global $conn;

	$query = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($query, 0);

	return (count($row) > 0);
}

function ValorSqlMSSQL($sql, $default, $params) {
	// Devuelve el valor del primer campo del primer registro del query pasado como parámetro..

	global $conn;

	$query = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($query, 0);

	if ($row[0] == "")
		return $default;
	else
		return $row[0];
}
?>