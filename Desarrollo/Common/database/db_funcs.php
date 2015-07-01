<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


function addQuotes($cadena, $nullIsEmpty = false) {
	// Le agrega comillas a la cadena al principio y al final y si el segundo parámetro es true devuelve NULL en caso de ser vacío..

	$result = $cadena;

	if ($nullIsEmpty)
		$result = nullIsEmpty($result);

	if ($result == NULL)		// Si es null, no pongo las comillas..
		return $result;
	else
		return DB_QUOTE.$result.DB_QUOTE;
}

function correctQuotes($cadena) {
	// Corrige las comillas que podría haber dentro de una cadena..

	return str_replace(DB_QUOTE, DB_QUOTE.DB_QUOTE, $cadena);
}

function existeSql($sql, $params = array(), $commitMode = 1) {
	if (DB_ENGINE == "mysql")
		return existeSqlMySql($sql, $params, $commitMode);
	if (DB_ENGINE == "mssql")
		return existeSqlMSSQL($sql, $params, $commitMode);
	if (DB_ENGINE == "oracle")
		return existeSqlOracle($sql, $params, $commitMode);
}

function formatFloat($number, $nullIfCero = false) {
	// Formatea un número para poder ser pasado como parámetro de un query..
	if ($nullIfCero)
		return nullIfCero($number, false, false);
	else
		return "0".trim(str_replace(array("%"), array(""), $number));
}

function getSecNextVal($sec) {
	if (DB_ENGINE == "oracle")
		return getSecNextValOracle($sec);
}

function nullIfCero($valor, $addQuotes = false, $menosUnoIsNullToo = true) {
	if (($valor == "0") or ($valor == "") or (($menosUnoIsNullToo) and ($valor == "-1")))
		return NULL;
	elseif ($addQuotes)
		return addQuotes($valor);
	else
		return $valor;
}

function nullIsEmpty($valor) {
	if ($valor == "")
		return NULL;
	else
		return $valor;
}

function sqlDate($fecha) {
	if (DB_ENGINE == "mysql")
		return sqlDateMySql($fecha);
	elseif (DB_ENGINE == "mssql")
		return sqlDateMSSQL($fecha);
	elseif (DB_ENGINE == "oracle")
		return sqlDateOracle($fecha);
	else
		return $fecha;
}

function setDateFormat($format) {
	if (DB_ENGINE == "mysql")
		return setDateFormatMySql($format);
	if (DB_ENGINE == "mssql")
		return setDateFormatMSSQL($format);
	if (DB_ENGINE == "oracle")
		return setDateFormatOracle($format);
}

function valorSql($sql, $default = "", $params = array(), $commitMode = 1) {
	// 0 = NO commit..
	// 1 = Autocommit..
	if (DB_ENGINE == "mysql")
		return valorSqlMySql($sql, $default, $params, $commitMode);
	elseif (DB_ENGINE == "mssql")
		return valorSqlMSSQL($sql, $default, $params, $commitMode);
	elseif (DB_ENGINE == "oracle")
		return valorSqlOracle($sql, $default, $params, $commitMode);
	else
		return "";
}
?>