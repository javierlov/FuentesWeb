<?php
function getFileContent($file) {
	$f = fopen($file, "r");
	$data = fread($f, filesize($file));
	fclose($f);

	return $data;
}

function MakeDirectory($dir, $mode = 0755) {
	if (is_dir($dir) || @mkdir($dir, $mode))
		return true;
	if (!MakeDirectory(dirname($dir), $mode))
		return false;

	return @mkdir($dir, $mode);
}
?>