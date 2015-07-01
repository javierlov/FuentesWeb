<?
function caracteresValidos($texto, $cantidadCaracteresValidos) {
	// La función valida que el texto pasado como paràmetro tenga al menos $cantidadCaracteresValidos cantidada de letras..
	$caracteresOk = 0;

	for ($i=0; $i<strlen($texto); $i++) {
		$num = ord(strtolower($texto[$i]));
		if (($num >= 97) and ($num <= 122))
			$caracteresOk++;

		if ($caracteresOk >= $cantidadCaracteresValidos)
			return true;
	}

	return false;
}

function get_htmlspecialchars($given, $quote_style = ENT_QUOTES) {
	return htmlentities(unhtmlentities($given), $quote_style);
}

function removeAccents($cadena) {
	$result = str_replace(array("á", "à"), "a", $cadena);
	$result = str_replace(array("é", "è"), "e", $result);
	$result = str_replace(array("í", "ì"), "i", $result);
	$result = str_replace(array("ó", "ò"), "o", $result);
	$result = str_replace(array("ú", "ù"), "u", $result);

	$result = str_replace(array("Á", "À"), "A", $result);
	$result = str_replace(array("É", "È"), "E", $result);
	$result = str_replace(array("Í", "Ì"), "I", $result);
	$result = str_replace(array("Ó", "Ò"), "O", $result);
	$result = str_replace(array("Ú", "Ù"), "U", $result);

	return $result;
}

function stringToLower($cadena) {
	$result = str_replace("Ñ","ñ", strtolower($cadena));
	$result = str_replace(array("Á", "À"), "á", $result);
	$result = str_replace(array("É", "È"), "é", $result);
	$result = str_replace(array("Í", "Ì"), "í", $result);
	$result = str_replace(array("Ó", "Ò"), "ó", $result);
	$result = str_replace(array("Ú", "Ù"), "ú", $result);

	return $result;
}

function stringToUpper($cadena) {
	$result = str_replace("ñ", "Ñ", strtoupper($cadena));
	$result = str_replace(array("á", "à"), "Á", $result);
	$result = str_replace(array("é", "è"), "É", $result);
	$result = str_replace(array("í", "ì"), "Í", $result);
	$result = str_replace(array("ó", "ò"), "Ó", $result);
	$result = str_replace(array("ú", "ù"), "Ú", $result);

	return $result;
}

function unhtmlentities($string) {
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	$ret = strtr($string, $trans_tbl);

//	return preg_replace('/&#(\d+);/me', "chr('\\1')", $ret);
	return preg_replace_callback('/&#(\d+);/m', function ($coincidencias) {return strtolower($coincidencias[0]);}, $ret);
}
?>