<?php
//require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");


function DBCloseConnection($resource) {
	// Libera todos los recursos asociados a la sentencia..

	odbc_close($resource);
}

function DBCommit($resource) {
	// Confirma transacciones pendientes..

	odbc_exec($resource, "COMMIT"); 
}

function DBExecSP($connection, &$cursor, $sql, $params, $return = true, $mode = 1) {
	// Ejecuta un store procedure..

	//
}

function DBExecSql($connection, $sql) {
	// Ejecuta el select pasado como parámetro..

	$results = odbc_exec($connection, $sql);

	if (!$results)
		throw new Exception("Error en la consulta.");

	return $results;
}

function DBGetConnection($name, $db, $user, $password) {
	// Establece una conexión con el servidor Oracle..

	$conn = odbc_connect($name, $user, $password);

	if (!$conn)
		echo "Error al conectarse al servidor de la base de datos.";

	return $conn;
}

function DBGetQuery($result) {
	// Devuelve la siguiente fila dentro del result-array..

	$arr = array();

	if (odbc_fetch_row($result)) {
		for ($i = 1; $i <= odbc_num_fields($result); $i++) {
			$value = odbc_result($result, $i);
			array_push($arr, $value);
		}
	}
	return $arr;

}

function DBGetRecordCount($resourceQuery) {
	// Devuelve el número de filas buscadas..

	$tot = 0;
	while ($row = DBGetQuery($resourceQuery))
		$tot++;

	return $tot;

//	return odbc_num_rows($resourceQuery);
}

function DBGetSP($cursor) {
	// Devuelve la siguiente fila del cursor pasado como parámetro..

	//
}

function DBRollback($connection) {
	// Vuelve para atrás las transacciones pendientes..

	//
}

function ExisteSqlODBC($sql, $params) {
	// Devuelve true si el query pasado como parámetro tiene resultados..

	global $conn;

	$query = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($query, 0);

	return (count($row) > 0);
}

function ValorSqlODBC($sql, $default) {
	// Devuelve el valor del primer campo del primer registro del query pasado como parámetro..

	global $conn;

	$query = DBExecSql($conn, $sql);
	$value = odbc_result($query, 1);

	if ($value == "")
		return $default;
	else
		return $value;
}
?>