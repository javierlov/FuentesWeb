<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/adodb-time.inc.php");

function dateDiff($fecha1, $fecha2, $type = "D") {
	// $type puede ser D (dias), M (meses) o A (años)..

	//Convertimos ambas fechas a su unix timestamp con adodb_mktime..
	$tmp = explode("/", $fecha1);
	$fecha1 = $tmp[1]."/".$tmp[0]."/".$tmp[2];
	$time1 = adodb_mktime(0, 0, 0, $tmp[1], $tmp[0], $tmp[2]);

	$tmp = explode("/", $fecha2);
	$fecha2 = $tmp[1]."/".$tmp[0]."/".$tmp[2];
	$time2 = adodb_mktime(0, 0, 0, $tmp[1], $tmp[0], $tmp[2]);

	$diferencia = $time2 - $time1;
	$result = 0;

	// Ahora ya tengo la cantidad de segundos entre las dos fechas..
	// Dividamoslas entre X para obtener ya sean la fecha en años, meses o dias.

	if ($type == "A") {
		list($mes1, $dia1, $ano1) = explode("/", $fecha1);
		list($mes2, $dia2, $ano2) = explode("/", $fecha2);

		$ano_dif = $ano2 - $ano1;
		$mes_dif = $mes2 - $mes1;
		$dia_dif = $dia2 - $dia1;
		if (($mes_dif < 0) or (($mes_dif == 0) and ($dia_dif < 0)))
			$ano_dif--;
		$result = $ano_dif;
	}

	if ($type == "M") {
		$meses = $diferencia / (60 * 60 * 24 * 30);		// 30 días como promedio por mes..
		$meses = ceil($meses);
		$result = $meses;
	}

	if ($type == "D") {
		$dias = $diferencia / (60 * 60 * 24);
		$dias = ceil($dias);
		$result = $dias;
	}

	return $result;
}

function fechaEnRango($fecha, $fechaDesde, $fechaHasta) {
	$arrFecha = explode("/", $fecha);
	$arrFechaDesde = explode("/", $fechaDesde);
	$arrFechaHasta = explode("/", $fechaHasta);

	return (($arrFecha[2].$arrFecha[1].$arrFecha[0] >= $arrFechaDesde[2].$arrFechaDesde[1].$arrFechaDesde[0]) and ($arrFecha[2].$arrFecha[1].$arrFecha[0] <= $arrFechaHasta[2].$arrFechaHasta[1].$arrFechaHasta[0]));
}

function formatDate($formatoSalida, $fechaEntrada, $formatoEntrada = "d/m/y") {
	// El formato de entrada tiene que ser 'd', 'm' o 'y' en minúscula..
	// El formato de salida tiene que ser igual al formato de la función date de PHP..

	if (!isFechaValida($fechaEntrada))
		return $fechaEntrada;

	$arrFecha = explode("/", $fechaEntrada);
	$arrFormatoEntrada = explode("/", $formatoEntrada);

	switch ($arrFormatoEntrada[0]) {
		case "d":
			$dia = $arrFecha[0];
			break;
		case "m":
			$mes = $arrFecha[0];
			break;
		case "y":
			$ano = $arrFecha[0];
			break;
	}

	switch ($arrFormatoEntrada[1]) {
		case "d":
			$dia = $arrFecha[1];
			break;
		case "m":
			$mes = $arrFecha[1];
			break;
		case "y":
			$ano = $arrFecha[1];
			break;
	}

	switch ($arrFormatoEntrada[2]) {
		case "d":
			$dia = $arrFecha[2];
			break;
		case "m":
			$mes = $arrFecha[2];
			break;
		case "y":
			$ano = $arrFecha[2];
			break;
	}

	return date($formatoSalida, mktime(0, 0, 0, $mes, $dia, $ano));
}

function GetDayName($dia) {
	$dias = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
	return $dias[$dia - 1];
}

function GetMonthName($mes) {
	if (!is_numeric($mes))
		return "";

	$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	return $meses[$mes - 1];
}

function incDays($fecha, $dias) {
	list($day, $mon, $year) = explode("/", $fecha);
	return date("d/m/Y", mktime(0, 0, 0, $mon, $day + $dias, $year));
}

function incMonths($fecha, $meses) {
	list($day, $mon, $year) = explode("/", $fecha);
	return date("d/m/Y", mktime(0, 0, 0, $mon + $meses, $day, $year));
}

function isFechaValida($fecha, $anoMayorIgualA1900 = true) {
	$arr = explode("/", $fecha);
	try {
		if (count($arr) != 3 )
			return false;

		if (($arr[0] != strval(intval($arr[0]))) or ($arr[1] != strval(intval($arr[1]))) or ($arr[2] != strval(intval($arr[2]))))
			return false;

		if (@checkdate($arr[1], $arr[0], $arr[2])) {
			if ($anoMayorIgualA1900)
				return (intval($arr[2]) >= 1900);
			else
				return true;
		}
		else
			return false;
	}
	catch (Exception $e) {
		return false;
	}
}
?>