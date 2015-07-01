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

function get_htmlspecialchars($given, $quote_style = ENT_QUOTES){
	return htmlentities(unhtmlentities($given), $quote_style);
}

function RemoveAccents($cadena) {
	$result = ereg_replace("[����]","A", $cadena);
	$result = ereg_replace("[����]","E", $result);
	$result = ereg_replace("[����]","I", $result);
	$result = ereg_replace("[����]","O", $result);
	$result = ereg_replace("[����]","U", $result);

	return $result;
}

function StringToLower($cadena) {
	$result = ereg_replace("[�]","�", strtolower($cadena));
	$result = ereg_replace("[��]","�", $result);
	$result = ereg_replace("[��]","�", $result);
	$result = ereg_replace("[��]","�", $result);
	$result = ereg_replace("[��]","�", $result);
	$result = ereg_replace("[��]","�", $result);

	return $result;
}

function StringToUpper($cadena) {
	$result = ereg_replace("[�]","�", strtoupper($cadena));
	$result = ereg_replace("[��]","�", $result);
	$result = ereg_replace("[��]","�", $result);
	$result = ereg_replace("[��]","�", $result);
	$result = ereg_replace("[��]","�", $result);
	$result = ereg_replace("[��]","�", $result);

	return $result;
}

function unhtmlentities($string){
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	$ret = strtr($string, $trans_tbl);
	return preg_replace('/&#(\d+);/me', "chr('\\1')", $ret);
}
?>