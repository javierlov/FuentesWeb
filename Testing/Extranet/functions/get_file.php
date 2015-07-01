<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/encryptation.php");


function archivoValido($file, $ext) {
	// Si el archivo no esta dentro de STORAGE_DATA_PATH o dentro de STORAGE_EXTRANET lo consideramos inválido..
	if ((strtolower(substr($file, 0, strlen(STORAGE_DATA_PATH))) != strtolower(STORAGE_DATA_PATH)) and
			(strtolower(substr($file, 0, strlen(STORAGE_EXTRANET))) != strtolower(STORAGE_EXTRANET)))
		return false;

	// Si el nombre del archivo es igual a la extensión probablemente no tenga extensión, asi que es inválido..
	if ($file == $ext)
		return false;

	// Solo se pueden descargar algunas de estas extensiones..
	if (($ext != ".doc") and ($ext != ".docx") and ($ext != ".mpeg") and ($ext != ".mpg") and ($ext != ".pdf") and ($ext != ".xls") and ($ext != ".xlsx"))
		return false;

	return true;
}


// Revierto la pseudoencriptación que le aplico ademas del base64..
$file = substr_replace($_REQUEST["fl"], "", 17, 1);
$file = StringToLower(base64_decode(substr_replace($file, strrev(substr($file, 7, 7)), 7, 7)));
$ext = substr($file, strrpos($file, "."));


//******* INICIO VALIDACIÓN EL ARCHIVO QUE SE QUIERE VER/DESCARGAR *******
if (!archivoValido($file, $ext))
	$file = "http://".$_SERVER["HTTP_HOST"]."/archivo_invalido.html";
//******* FIN VALIDACIÓN EL ARCHIVO QUE SE QUIERE VER/DESCARGAR *******


$mode = "i";
if (isset($_REQUEST["md"]))
	$mode = $_REQUEST["md"];

if ($ext == ".pdf")
	header("Content-type: application/pdf");
elseif (($ext == ".doc") or ($ext == ".docx"))
	header("Content-type: application/msword");
elseif (($ext == ".htm") or ($ext == ".html"))
	header("Content-type: text/html");
elseif ($ext == ".jpg")
	header("Content-type: image/jpeg");
elseif (($ext == ".mpeg") or ($ext == ".mpg"))
	header("Content-Type: video/mpeg");
elseif ($ext == ".ppt")
	header("Content-type: application/vnd.ms-powerpoint");
elseif (($ext == ".xls") or ($ext == ".xlsx"))
	header("Content-type: application/vnd.ms-excel");
else
	header("Content-Type: application/octet-stream");

//if ($ext == ".bmp")

if ($mode == "a")
	header("Content-Disposition: attachment; filename=".basename($file));
else
	header("Content-Disposition: inline; filename=".basename($file));

$tamano = filesize($file);
header("Content-Length: ".$tamano);

readfile($file);
?>