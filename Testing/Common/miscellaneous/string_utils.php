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

function get_htmlspecialchars($given, $quote_style = ENT_QUOTES){
	return htmlentities(unhtmlentities($given), $quote_style);
}

function RemoveAccents($cadena) {
	$result = ereg_replace("[áÁàÀ]","A", $cadena);
	$result = ereg_replace("[éÉèÈ]","E", $result);
	$result = ereg_replace("[íÍìÌ]","I", $result);
	$result = ereg_replace("[óÓòÒ]","O", $result);
	$result = ereg_replace("[úÚùÙ]","U", $result);

	return $result;
}

function StringToLower($cadena) {
	$result = ereg_replace("[Ñ]","ñ", strtolower($cadena));
	$result = ereg_replace("[ÁÀ]","á", $result);
	$result = ereg_replace("[ÉÈ]","é", $result);
	$result = ereg_replace("[ÍÌ]","í", $result);
	$result = ereg_replace("[ÓÒ]","ó", $result);
	$result = ereg_replace("[ÚÙ]","ú", $result);

	return $result;
}

function StringToUpper($cadena) {
	$result = ereg_replace("[ñ]","Ñ", strtoupper($cadena));
	$result = ereg_replace("[áà]","Á", $result);
	$result = ereg_replace("[éè]","É", $result);
	$result = ereg_replace("[íì]","Í", $result);
	$result = ereg_replace("[óò]","Ó", $result);
	$result = ereg_replace("[úù]","Ú", $result);

	return $result;
}

function unhtmlentities($string){
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	$ret = strtr($string, $trans_tbl);
	return preg_replace('/&#(\d+);/me', "chr('\\1')", $ret);
}
?>