<?php
function armPathFromNumber($numero) {
	$result = "";

	for ($i=0; $i<strlen($numero); $i++)
		$result = $numero[$i]."/".$result;

	return $result;
}

function getFileContent($file) {
	$f = fopen($file, "r");
	$data = fread($f, filesize($file));
	fclose($f);

	return $data;
}

function makeDirectory($dir, $mode = 0755) {
	if (is_dir($dir) || @mkdir($dir, $mode))
		return true;
	if (!makeDirectory(dirname($dir), $mode))
		return false;

	return @mkdir($dir, $mode);
}

function validarExtension($archivo, $extensionesValidas = array()) {
	$ext = stringToLower(substr($archivo, strrpos($archivo, ".") + 1, 20));
	return in_array($ext, $extensionesValidas);
}
?>