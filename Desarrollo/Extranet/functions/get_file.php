<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/encryptation.php");


function archivoValido($file, $ext) {
	// Si el archivo no esta dentro de STORAGE_DATA_PATH o dentro de STORAGE_EXTRANET lo consideramos inv�lido..

	if ($_SERVER["HTTP_HOST"] == "www.provinciart.com.ar") {
		$path1 = "//ntwebart1/Storage_Data/";
		$path2 = "//ntwebart1/Storage_Extranet/";
	}
	else {
		$path1 = "//ntwebart3/Storage_Data/";
		$path2 = "//ntwebart3/Storage_Extranet/";
	}

	if ((strtolower(substr($file, 0, strlen(STORAGE_DATA_PATH))) != strtolower(STORAGE_DATA_PATH)) and
			(strtolower(substr($file, 0, strlen(STORAGE_EXTRANET))) != strtolower(STORAGE_EXTRANET)) and
			(strtolower(substr($file, 0, strlen($path1))) != strtolower($path1)) and
			(strtolower(substr($file, 0, strlen($path2))) != strtolower($path2)))
		return false;

	// Si el nombre del archivo es igual a la extensi�n probablemente no tenga extensi�n, asi que es inv�lido..
	if ($file == $ext)
		return false;

	// Solo se pueden descargar algunas de estas extensiones..
	if (($ext != ".bmp") and ($ext != ".doc") and ($ext != ".docx") and ($ext != ".jpeg") and ($ext != ".jpg") and ($ext != ".mpeg") and ($ext != ".mpg") and ($ext != ".pdf") and ($ext != ".png") and ($ext != ".ppt") and ($ext != ".xls") and ($ext != ".xlsx"))
		return false;

	return true;
}

// Revierto la pseudoencriptaci�n que le aplico ademas del base64..
$file = substr_replace($_REQUEST["fl"], "", 17, 1);
$file = stringToLower(base64_decode(substr_replace($file, strrev(substr($file, 7, 7)), 7, 7)));
if ((substr($file, 0, 10) == "\\ntwebart") or (substr($file, 0, 10) == "//ntwebart"))
	$file = substr_replace($file, "D:", 0, 11);		// Es 11 en vez de 10, porque ser�a //ntwebart3 o //ntwebart1..
$ext = substr($file, strrpos($file, "."));

//******* INICIO VALIDACI�N EL ARCHIVO QUE SE QUIERE VER/DESCARGAR *******
if (!archivoValido($file, $ext))
	$file = $_SERVER["DOCUMENT_ROOT"]."/archivo_invalido.html";
//******* FIN VALIDACI�N EL ARCHIVO QUE SE QUIERE VER/DESCARGAR *******

$mode = "i";
if (isset($_REQUEST["md"]))
	$mode = $_REQUEST["md"];

if ($ext == ".pdf")
	header("Content-type: application/pdf");
elseif ($ext == ".bmp")
	header("Content-Type: image/bmp");
elseif (($ext == ".doc") or ($ext == ".docx"))
	header("Content-type: application/msword");
elseif (($ext == ".htm") or ($ext == ".html"))
	header("Content-type: text/html");
elseif (($ext == ".jpeg") or ($ext == ".jpg"))
	header("Content-type: image/jpeg");
elseif (($ext == ".mpeg") or ($ext == ".mpg"))
	header("Content-Type: video/mpeg");
elseif ($ext == ".png")
	header("Content-Type: image/png");
elseif ($ext == ".ppt")
	header("Content-type: application/vnd.ms-powerpoint");
elseif (($ext == ".xls") or ($ext == ".xlsx"))
	header("Content-type: application/vnd.ms-excel");
else
	header("Content-Type: application/octet-stream");

if ($mode == "a")
	header("Content-Disposition: attachment; filename=".basename($file));
else
	header("Content-Disposition: inline; filename=".basename($file));

$tamano = filesize($file);
header("Content-Length: ".$tamano);

readfile($file);
?>