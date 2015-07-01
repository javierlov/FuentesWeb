<?
function caracteresValidos($texto, $cantidadCaracteresValidos) {
	// La funci�n valida que el texto pasado como par�metro tenga al menos $cantidadCaracteresValidos cantidada de letras..
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
	$result = str_replace(array("�", "�"), "a", $cadena);
	$result = str_replace(array("�", "�"), "e", $result);
	$result = str_replace(array("�", "�"), "i", $result);
	$result = str_replace(array("�", "�"), "o", $result);
	$result = str_replace(array("�", "�"), "u", $result);

	$result = str_replace(array("�", "�"), "A", $result);
	$result = str_replace(array("�", "�"), "E", $result);
	$result = str_replace(array("�", "�"), "I", $result);
	$result = str_replace(array("�", "�"), "O", $result);
	$result = str_replace(array("�", "�"), "U", $result);

	return $result;
}

function stringToLower($cadena) {
	$result = str_replace("�","�", strtolower($cadena));
	$result = str_replace(array("�", "�"), "�", $result);
	$result = str_replace(array("�", "�"), "�", $result);
	$result = str_replace(array("�", "�"), "�", $result);
	$result = str_replace(array("�", "�"), "�", $result);
	$result = str_replace(array("�", "�"), "�", $result);

	return $result;
}

function stringToUpper($cadena) {
	$result = str_replace("�", "�", strtoupper($cadena));
	$result = str_replace(array("�", "�"), "�", $result);
	$result = str_replace(array("�", "�"), "�", $result);
	$result = str_replace(array("�", "�"), "�", $result);
	$result = str_replace(array("�", "�"), "�", $result);
	$result = str_replace(array("�", "�"), "�", $result);

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