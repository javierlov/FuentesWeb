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

function ExisteSql($sql, $params = array(), $commitMode = 1) {
	if (DB_ENGINE == "mysql")
		return ExisteSqlMySql($sql, $params, $commitMode);
	if (DB_ENGINE == "mssql")
		return ExisteSqlMSSQL($sql, $params, $commitMode);
	if (DB_ENGINE == "oracle")
		return ExisteSqlOracle($sql, $params, $commitMode);
}

function formatFloat($number, $nullIfCero = false) {
	// Formatea un número para poder ser pasado como parámetro de un query..
	if ($nullIfCero)
		return nullIfCero($number, false, false);
	else
		return "0".trim(str_replace(array("%"), array(""), $number));
}

function GetSecNextVal($sec) {
	if (DB_ENGINE == "oracle")
		return GetSecNextValOracle($sec);
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

function SqlDate($fecha) {
	if (DB_ENGINE == "mysql")
		return SqlDateMySql($fecha);
	elseif (DB_ENGINE == "mssql")
		return SqlDateMSSQL($fecha);
	elseif (DB_ENGINE == "oracle")
		return SqlDateOracle($fecha);
	else
		return $fecha;
}

function SetDateFormat($format) {
	if (DB_ENGINE == "mysql")
		return SetDateFormatMySql($format);
	if (DB_ENGINE == "mssql")
		return SetDateFormatMSSQL($format);
	if (DB_ENGINE == "oracle")
		return SetDateFormatOracle($format);
}

function ValorSql($sql, $default = "", $params = array(), $commitMode = 1) {
	// 0 = NO commit..
	// 1 = Autocommit..
	if (DB_ENGINE == "mysql")
		return ValorSqlMySql($sql, $default, $params, $commitMode);
	elseif (DB_ENGINE == "mssql")
		return ValorSqlMSSQL($sql, $default, $params, $commitMode);
	elseif (DB_ENGINE == "oracle")
		return ValorSqlOracle($sql, $default, $params, $commitMode);
	else
		return "";
}
?>