<?
function decimalToRomana($numero, $case = "u") {
	$romano = "";
	$simbolos = array("I", "V", "X", "L", "C", "D", "M");
	$valores = array("1", "5", "10", "50", "100", "500", "1000", "5000");
	if ($numero <= 3999) {
		while ($numero > 0) {
			$i = 0;
			while ($i < 7) {
				while ($numero >= $valores[$i] && $numero < $valores[$i + 1]) {
					$par = $i%2;		// paridad
					if ($numero >= $valores[$i + 1] - $valores[$i - $par]) {
						$romano = $romano.$simbolos[$i - $par].$simbolos[$i + 1];
						$numero = $numero - ($valores[$i + 1] - $valores[$i - $par]);
					}
					else {
						$romano = $romano.$simbolos[$i];
						$numero = $numero - $valores[$i];
					}
				}
				$i++;
			}
		}
		if ($case == "l")
			$romano = strtolower($romano);
	}
	else
		$romano = "overflow";

	return $romano;
}

function emptyIfZero($value) {
	if ($value == "0")
		return "";
	else
		return $value;
}

function getDecimales($valor, $decimales = 2) {
	$dec = "";
	if (strpos($valor, "."))
		return substr($valor, strpos($valor, ".") + 1);
	else
		return str_pad($dec, $decimales, "0");
}

function getNumeroALetra($valor) {
	$result = "";

	$numero = intval(abs($valor));
	$i = 0;

	if ($valor == 0)
		$result = "cero";
	else
		while ($i < strlen($numero)) {
			$i++;
			$numAtPos = intval(substr($numero, strlen($numero) - $i, 1));

			switch ($i) {
				case 1:		// Unidades
					switch ($numAtPos) {
						case 0:
							$resTemp = "";
							break;
						case 1:
							$resTemp = "uno";
							break;
						case 2:
							$resTemp = "dos";
							break;
						case 3:
							$resTemp = "tres";
							break;
						case 4:
							$resTemp = "cuatro";
							break;
						case 5:
							$resTemp = "cinco";
							break;
						case 6:
							$resTemp = "seis";
							break;
						case 7:
							$resTemp = "siete";
							break;
						case 8:
							$resTemp = "ocho";
							break;
						case 9:
							$resTemp = "nueve";
							break;
					}
					break;
				case 2:		// Decenas
					switch ($numAtPos) {
						case 1:
							$result = "";
							switch (intval(substr($numero, strlen($numero) - $i, 2))) {
								case 10:
									$resTemp = "diez";
									break;
								case 11:
									$resTemp = "once";
									break;
								case 12:
									$resTemp = "doce";
									break;
								case 13:
									$resTemp = "trece";
									break;
								case 14:
									$resTemp = "catorce";
									break;
								case 15:
									$resTemp = "quince";
									break;
								case 16:
									$resTemp = "dieciseis";
									break;
								case 17:
									$resTemp = "diecisiete";
									break;
								case 18:
									$resTemp = "dieciocho";
									break;
								case 19:
									$resTemp = "diecinueve";
									break;
							}
							break;
						case 2:
							if ($resTemp == "")
								$resTemp = "veinte";
							else
								$resTemp = "veinti";
							break;
						case 3:
							$resTemp = "treinta";
							break;
						case 4:
							$resTemp = "cuarenta";
							break;
						case 5:
							$resTemp = "cincuenta";
							break;
						case 6:
							$resTemp = "sesenta";
							break;
						case 7:
							$resTemp = "setenta";
							break;
						case 8:
							$resTemp = "ochenta";
							break;
						case 9:
							$resTemp = "noventa";
							break;
						default:
							$resTemp = "";
					}
					if (($numAtPos >= 3) and (intval(substr($numero, strlen($numero) - $i + 1, 1)) > 0 ))
						$resTemp = $resTemp." y ";
					break;
				case 3:		// Centenas
					switch ($numAtPos) {
						case 0:
							$resTemp = "";
							break;
						case 1:
							if ($result == "")
								$resTemp = "cien ";
							else
								$resTemp = "ciento ";
							break;
						case 2:
							$resTemp = "doscientos ";
							break;
						case 3:
							$resTemp = "trescientos ";
							break;
						case 4:
							$resTemp = "cuatrocientos ";
							break;
						case 5:
							$resTemp = "quinientos ";
							break;
						case 6:
							$resTemp = "seiscientos ";
							break;
						case 7:
							$resTemp = "setecientos ";
							break;
						case 8:
							$resTemp = "ochocientos ";
							break;
						case 9:
							$resTemp = "novecientos ";
							break;
					}
					break;
				case 4:		// Miles
					$resTemp = getNumeroALetra(intval(substr("000".$numero, strlen($numero) - $i + 1, 3)));
					if ($resTemp == "cero")
						$resTemp = "";
					if (substr($resTemp, strlen($resTemp) - 3, 4) == "uno")
						$resTemp = substr($resTemp, 0, strlen($resTemp) - 1)." mil ";
					elseif (substr($resTemp, strlen($resTemp) - 3, 4) != "")
						$resTemp.= " mil ";
					$i = $i + 2;
					break;
				case 7:		// Millones
					$resTemp = getNumeroALetra(intval(substr("000".$numero, strlen($numero) - $i + 1, 3)));
					if ($resTemp == "uno")
						$resTemp = substr($resTemp, 0, strlen($resTemp) - 1)." millon ";
					else
						$resTemp = $resTemp." millones ";
					$i = $i + 2;
					break;
				default:
					$resTemp = "";
			}
			$result = $resTemp.$result;
		}

	$result = trim($result);
	if ($valor < 0)
		$result = "MENOS ".$result;

	return $result;
}

function numerosALetras($valor, $decimales = 2, $conBarraCien = false) {
	$result = getNumeroALetra(intval($valor));
	if ($decimales > 0) {
		$decVal = getDecimales($valor, $decimales);
		if ($decVal > 0) {
			$result.= " con ".$decVal;
			if ($conBarraCien)
				$result.= "/100";
		}
	}

	return $result;
}

function validarEntero($numero, $soloPositivo = true) {
	if ($numero != strval(intval($numero)))
		return false;
	elseif ($soloPositivo)
		return ($numero >= 0);
	else
		return true;
}

function validarNumero($numero, $soloPositivo = false) {
	if (!is_numeric(str_replace(",", ".", $numero)))
		return false;
	elseif ($soloPositivo)
		return ($numero >= 0);
	else
		return true;
}

function zeroIfEmpty($value) {
	if (trim($value) == "")
		return "0";
	else
		return $value;
}
?>