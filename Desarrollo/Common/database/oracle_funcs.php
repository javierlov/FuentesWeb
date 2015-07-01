<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/encryptation.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");


function addComillas($cadena, $nullIsEmpty = false) {
	// Le agrega comillas a la cadena al principio y al final y si el segundo parámetro es true devuelve NULL en caso de ser vacío..

	$result = $cadena;

	if ($nullIsEmpty)
		$result = nullIsEmpty($result);

	if ($result == NULL)		// Si es null, no pongo las comillas..
		return $result;
	else
		return DB_QUOTE.$result.DB_QUOTE;
}

function addComillasFormat($format) {
	$sqltext = addQuotes($format);
	return $sqltext;
}

function adminXSS($arr, $encode) {
	// Función que encodea o desencodea caracteres especiales desde y hacia la DB..
	if ($arr != NULL)
		foreach ($arr as $key => $val)
			if (!is_object($arr[$key]))
				if ($encode)
					$arr[$key] = htmlspecialchars($arr[$key], ENT_QUOTES, CHARSET);
				else{					
					$arr[$key] = htmlspecialcharsDecodeUpper($arr[$key]);
				}

	return $arr;
}

function DBCloseConnection($statement) {
	// Libera todos los recursos asociados a la sentencia..

	oci_free_statement($statement);
}

function DBCommit($connection) {
	// Confirma transacciones pendientes..

	oci_commit($connection);
}

function DBExecSP($connection, &$cursor, $sql, $params = array(), $return = true, $mode = 1) {
	// Ejecuta un store procedure..
	// 0 = NO commit..
	// 1 = Autocommit..

	global $dbError;

	$commit = OCI_COMMIT_ON_SUCCESS;
	switch ($mode) {
		case 0:
			$commit = OCI_DEFAULT;
			break;
		case 1:
			$commit = OCI_COMMIT_ON_SUCCESS;
			break;
	}

	$cursor = oci_new_cursor($connection);
	$stmt = oci_parse($connection, $sql);

	foreach ($params as $key => $val) {
		$parametro = htmlspecialcharsDecodeUpper($params[$key]);
		oci_bind_by_name($stmt, $key, $parametro, strlen($parametro));
		unset($parametro);
	}

	if ($return)
		oci_bind_by_name($stmt, "data", $cursor, -1, OCI_B_CURSOR);

	$error = (!oci_execute($stmt, $commit));
	$error = (($error) or (!oci_execute($cursor, $commit)));

	if ($error) {
		$dbError = oci_error($stmt);
		if (isset($dbError["offset"])) {
			DBRollback($connection);		// Hago un rollback por si el query con el error venía sin transacción..
			saveSqlError($connection, $dbError["message"], $sql, $params);
			throw new Exception("Error inesperado. [".date("d/m/Y H:i:s")."]");
		}
	}

	return $stmt;
}

function DBExecSql($connection, $sql, $params = array(), $mode = OCI_COMMIT_ON_SUCCESS) {
	// Ejecuta el query pasado como parámetro..
	global $dbError;

	$stmt = oci_parse($connection, $sql);

	foreach ($params as $key => $val) {
		$parametro = htmlspecialcharsDecodeUpper($params[$key]);
		oci_bind_by_name($stmt, $key, $parametro, strlen($parametro));
		unset($parametro);
	}

	$error = (!oci_execute($stmt, $mode));

	if ($error) {
		$dbError = oci_error($stmt);
		if (isset($dbError["offset"])) {
			DBRollback($connection);		// Hago un rollback por si el query con el error venía sin transacción..
			saveSqlError($connection, $dbError["message"], $sql, $params);

			$mensajeError = "Error inesperado. [".date("d/m/Y H:i:s")."]";
			throw new Exception($mensajeError);
		}
	}

	return $stmt;
}

function DBExecSqlRawValue($connection, $sql, $params, $rawParamName, $rawParamValue, $mode = OCI_COMMIT_ON_SUCCESS) {
	// Ejecuta el query con los valores pasados como parámetro, entre ellos un campo de tipo raw..

	global $dbError;

	$stmt = oci_parse($connection, $sql);
	oci_bind_by_name($stmt, ":".$rawParamName, $rawParamValue, strlen($rawParamValue));

	foreach ($params as $key => $val) {
		$parametro = htmlspecialcharsDecodeUpper($params[$key]);
		oci_bind_by_name($stmt, $key, $parametro, strlen($parametro));
		unset($parametro);
	}

	$error = (!oci_execute($stmt, $mode));

	if ($error) {
		$dbError = oci_error($stmt);
		if (isset($dbError["offset"]))
			throw new Exception($dbError["message"]);
	}

	return $stmt;
}

function DBGetConnection() {
	// Establece una conexión con el servidor Oracle..

	global $servidorContingenciaActivo;

	$conn = oci_connect(DB_USER, decrypt(DB_PASS, "PROVART"), DB_SERV, "WE8ISO8859P1");
	if (!$conn) {		// Si no me puedo conectar al servidor principal, me conecto al servidor de contingencia..
		$conn = oci_connect(DB_USER_CONTINGENCIA, decrypt(DB_PASS_CONTINGENCIA, "PROVART"), DB_SERV_CONTINGENCIA, "WE8ISO8859P1");
		$servidorContingenciaActivo = true;
	}
/*
	if (!$conn) {
		$e = oci_error();   // For oci_connect errors pass no handle
		echo $e["message"];
	}
*/
	return $conn;
}

function DBGetQuery($result, $arrayType = 1, $encode = true) {
	// Devuelve la siguiente fila dentro del result-array..
/*
  if ($arrayType == 0)
    $row = oci_fetch_array($result, OCI_NUM + OCI_RETURN_NULLS);
  if ($arrayType == 1)
    $row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS);
  if ($arrayType == 2)
    $row = oci_fetch_array($result, OCI_ASSOC + OCI_NUM + OCI_RETURN_NULLS);

LO IDEAL SERIA USAR ESTO QUE ESTA COMENTADO EN LUGAR DE "OCIFETCHINTO" (QUE QUEDÓ OBSOLETO), PERO HAY QUE TENER CUIDADO, AL MENOS, CON LA FUNCION EXISTESQLORACLE QUE FALLA 
EN LA ULTIMA LINEA (RETURN (COUNT($ROW) > 0);), SI SE CORRIGE ESO, SE PODRIA USAR..

*/
  if ($arrayType == 0)
    OCIFetchInto($result, $row, OCI_NUM + OCI_RETURN_NULLS);
  if ($arrayType == 1)
    OCIFetchInto($result, $row, OCI_ASSOC + OCI_RETURN_NULLS);
  if ($arrayType == 2)
    OCIFetchInto($result, $row, OCI_ASSOC + OCI_NUM + OCI_RETURN_NULLS);

	return adminXSS($row, $encode);
}

function DBGetRecordCount($stmt, $commitMode = 0) {
	// Devuelve el número de filas buscadas..

	$commit = OCI_COMMIT_ON_SUCCESS;
	switch ($commitMode) {
		case 0:
			$commit = OCI_DEFAULT;
			break;
		case 1:
			$commit = OCI_COMMIT_ON_SUCCESS;
			break;
	}

	$recordCount = oci_fetch_all($stmt, $results);
	oci_execute($stmt, $commit);

	return $recordCount;
}

function DBGetSP($cursor, $encode = true) {
	// Devuelve la siguiente fila del cursor pasado como parámetro..

	$row = oci_fetch_array($cursor, OCI_RETURN_NULLS);

	return adminXSS($row, $encode);
}

function DBRollback($connection) {
	// Vuelve para atrás las transacciones pendientes..

	oci_rollback($connection);
}

function DBSaveLob($connection, $sql, $blobParamName, $data, $lobType) {
	// Guarda datos en un clob..

	global $dbError;

	$lob = oci_new_descriptor($connection, OCI_D_LOB);
	$stmt = oci_parse($connection, $sql);
	oci_bind_by_name($stmt, ":".$blobParamName, $lob, -1, $lobType);
	$error = (!oci_execute($stmt, OCI_DEFAULT));

	$result = $lob->write($data);
	if ($result)
		oci_commit($connection);

	if ($error) {
		$dbError = oci_error($stmt);
		if (isset($dbError["offset"]))
			throw new Exception($dbError["message"]);
	}

	return $result;
}

function existeSqlOracle($sql, $params, $commitMode) {
	// Devuelve true si el query pasado como parámetro tiene resultados..

	global $conn;

	$commit = OCI_COMMIT_ON_SUCCESS;
	switch ($commitMode) {
		case 0:
			$commit = OCI_DEFAULT;
			break;
		case 1:
			$commit = OCI_COMMIT_ON_SUCCESS;
			break;
	}

	$stmt = DBExecSql($conn, $sql, $params, $commit);
	$row = DBGetQuery($stmt, 0);

	return (count($row) > 0);
}

function getSecNextValOracle($sec, $commit = OCI_COMMIT_ON_SUCCESS) {
	// Devuelve el siguiente valor de la secuencia pasada como parámetro..

	global $conn;
	global $dbError;

	$stmt = DBExecSql($conn, "SELECT ".$sec.".NEXTVAL FROM DUAL", array(), $commit);
	$row = DBGetQuery($stmt, 0);

	$dbError = oci_error($stmt);
	if (isset($dbError["offset"]))
		throw new Exception($dbError["message"]);

	return $row[0];
}

function htmlspecialcharsDecodeUpper($str) {
	// Hice esta función para corregir el bug que tiene la función htmlspecialchars_decode que no decodifica las mayúsculas..
	$htmlU = array("&AMP;", "&QUOT;", "&#039;", "&LT;", "&GT;");
	$htmlL = array("&amp;", "&quot;", "&#039;", "&lt;", "&gt;");

	return htmlspecialchars_decode(str_replace($htmlU, $htmlL, $str), ENT_QUOTES);
}

function saveSqlError($conn, $error, $sql, $params) {
	// Guarda el error que generó un query en la tabla de errores web..

	global $servidorContingenciaActivo;

	try {
		$sql = str_replace("\t", " ", $sql);
		while (strpos($sql, "  "))
			$sql = str_replace("  ", " ", $sql);
		$url = $_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"];

		if (!$servidorContingenciaActivo) {
			$params = adminXSS($params, false);
			$sqlError =
				"INSERT INTO web.wew_erroreswebsql (ew_error, ew_fechaalta, ew_files, ew_get, ew_id, ew_parametros, ew_post, ew_remotehost, ew_session, ew_sql, ew_url)
																		VALUES (:error, SYSDATE, :files, :get, -1, :parametros, :post, :remotehost, :sesion, :sql, :url)";
			$stmtError = oci_parse($conn, $sqlError);

			$substring = substr($error, 0, 512);
			oci_bind_by_name($stmtError, ":error", $substring, strlen($substring));

			$valor = nullIsEmpty(substr(print_r($_FILES, true), 0, 1024));
			oci_bind_by_name($stmtError, ":files", $valor, strlen($valor));

			unset($valor);
			$valor = nullIsEmpty(substr(print_r($_GET, true), 0, 1024));
			oci_bind_by_name($stmtError, ":get", $valor, strlen($valor));

			unset($valor);
			$valor = nullIsEmpty(substr(print_r($params, true), 0, 4000));
			oci_bind_by_name($stmtError, ":parametros", $valor, strlen($valor));

			unset($valor);
			$valor = nullIsEmpty(substr(print_r($_POST, true), 0, 1024));
			oci_bind_by_name($stmtError, ":post", $valor, strlen($valor));

			unset($valor);
			$valor = nullIsEmpty(substr(gethostbyaddr($_SERVER['REMOTE_ADDR']), 0, 128));
			oci_bind_by_name($stmtError, ":remotehost", $valor, strlen($valor));

			unset($valor);
			$valor = nullIsEmpty(substr(str_replace("    ", " ", ((isset($_SESSION))?print_r($_SESSION, true):"")), 0, 1024));
			oci_bind_by_name($stmtError, ":sesion", $valor, strlen($valor));

			unset($substring);
			$substring = substr($sql, 0, 4000);
			oci_bind_by_name($stmtError, ":sql", $substring, strlen($substring));

			unset($substring);
			$substring = substr($url, 0, 512);
			oci_bind_by_name($stmtError, ":url", $substring, strlen($substring));

			oci_execute($stmtError);
		}

		$dbError = oci_error($stmtError);
		if (isset($dbError["offset"])) {
			$body ="<html><body>";
			$body.= "<div>El siguiente error hay ocurrido mientras se intentaba guardar un error de Oracle desde la web:<span style='color:red;'>".$dbError["message"]."</span></div>";
			$body.= "<div>Los datos que se intentaban guardar son:";
			$body.= "<p>URL: <b>".$url."</b></p>";
			$body.= "<p>ERROR: <b>".$error."</b></p>";
			$body.= "<p>SQL: <b>".$sql."</b></p>";
			$body.= "<p>PARÁMETROS: <b>".print_r($params, true)."</b></p>";
			$body.= "<p>REMOTE HOST: <b>".substr(gethostbyaddr($_SERVER['REMOTE_ADDR']), 0, 128)."</b></p>";
			$body.= "<p>SESSION: <b>".((isset($_SESSION))?print_r($_SESSION, true):"")."</b></p>";
			$body.= "<p>POST: <b>".print_r($_POST, true)."</b></p>";
			$body.= "<p>GET: <b>".print_r($_GET, true)."</b></p>";
			$body.= "<p>FILES: <b>".print_r($_FILES, true)."</b></p>";
			$body.= "</div></body></html>";
			sendEmail($body, "Provincia ART Web", "Error al intentar guardar un error sql web", array("alapaco@provart.com.ar"), array(), array(), "H");
		}
	}
	catch (Exception $e) {
		//
	}
}

function setDateFormatOracle($format) {
	// Configura el formato de los campos de tipo Date que devuelven los querys..

	global $conn;

	$sql = "ALTER SESSION SET NLS_DATE_FORMAT = '".$format."'";
	DBExecSql($conn, $sql);
}

function sqlDateOracle($fecha) {
	if ($fecha == "")
		return NULL;
	else
		return "TO_DATE('".$fecha."', 'dd/mm/yyyy')";
}

function setNumberFormatOracle($format = ".,") {
	// Configura el formato de los campos de tipo Number que devuelven los querys..

	global $conn;

	$sql = "ALTER SESSION SET NLS_NUMERIC_CHARACTERS = ".addComillas($format);
	DBExecSql($conn, $sql);
}

function setTerritoryFormatOracle($format = "AMERICA") {
	// Configura el parámetro NLS_TERRITORY de la sesión..

	global $conn;

	$sql = "ALTER SESSION SET nls_territory = ".addComillas($format);
	DBExecSql($conn, $sql);
}

function valorSqlOracle($sql, $default, $params, $commitMode) {
	// Devuelve el valor del primer campo del primer registro del query pasado como parámetro..

	global $conn;

	$commit = OCI_COMMIT_ON_SUCCESS;
	switch ($commitMode) {
		case 0:
			$commit = OCI_DEFAULT;
			break;
		case 1:
			$commit = OCI_COMMIT_ON_SUCCESS;
			break;
	}

	$stmt = DBExecSql($conn, $sql, $params, $commit);
	$row = DBGetQuery($stmt, 0);

	if ($row[0] == "")
		return $default;
	else
		return $row[0];
}
?>