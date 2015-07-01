<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


function getQueryImagenes() {
	$dir = IMAGES_ARTICULOS_PATH;
	$imgs = array();
	if (is_dir($dir))
		if ($gd = opendir($dir)) {
			while (($img = readdir($gd)) !== false)
				if (($img != ".") and ($img != ".."))
					array_push($imgs, $img);
			closedir($gd);
		}

	$result = "";
	foreach($imgs as $value)
		$result.= "SELECT ".addQuotes($value)." ID, ".addQuotes($value)." Detalle FROM DUAL UNION ALL ";
	$result = substr($result, 0, strrpos($result, "UNION ALL"));

	return $result;
}

function getQueryLinks() {
	$dir = DATA_PORTADA_PATH;
	$folders = array();
	if (is_dir($dir))
		if ($gd = opendir($dir)) {
			while (($folder = readdir($gd)) !== false)
				if (is_dir($dir.$folder) and ($folder != ".") and ($folder != ".."))
					array_push($folders, array(filemtime($dir.$folder), $folder));
			closedir($gd);
		}
	rsort($folders);		// Ordeno el array por fecha descendente..

	$result = "";
	foreach($folders as $value)
		$result.= "SELECT ".addQuotes($value[1])." ID, ".addQuotes($value[1])." Detalle FROM DUAL UNION ALL ";
	$result = substr($result, 0, strrpos($result, "UNION ALL"));

	return $result;
}


$displayImagen = "none";
if (($_REQUEST["seccion"] == 1) or ($_REQUEST["seccion"] == 4) or ($_REQUEST["seccion"] == 6))
	$displayImagen = "block";

$params = array(":posicion" => $_REQUEST["seccion"]);
$sql = 
	"SELECT *
		 FROM tmp.tai_articulosintranet
		WHERE ai_posicion = :posicion";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Description" content="Intranet de Provincia ART" />
		<meta name="Language" content="Spanish" />
		<meta name="Subject" content="Intranet" />
		<title>Artículo Sección <?= $_REQUEST["seccion"]?></title>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<script language="JavaScript" src="/js/functions.js"></script>
		<script language="JavaScript" src="/js/validations.js"></script>
	</head>
	<body bgcolor="#DDDDDD" leftmargin="3">
		<form action="procesar_articulo.php" id="formArticulo" method="post" name="formArticulo" onSubmit="return ValidarForm(formArticulo)">
			<input id="Seccion" name="Seccion" type="hidden" value="<?= $_REQUEST["seccion"]?>" />
			<div align="center">
				<table border="0" cellpadding="0" cellspacing="3" width="100%">
					<tr>
						<td align="center" bgcolor="#00539B" class="FormLabelBlancoGrande" colspan="2">CARGA DE DATOS - SECCIÓN <?= $_REQUEST["seccion"]?></td>
					</tr>
					<tr>
						<td colspan="2" height="8"></td>
					</tr>
					<tr>
						<td class="FormLabelAzul" width="112">Volanta:</td>
						<td><input class="FormInputText" id="Volanta" maxlength="30" name="Volanta" type="text" value="<?= $row["AI_VOLANTA"]?>" size="53"></td>
					</tr>
					<tr>
						<td class="FormLabelAzul">Título:</td>
						<td><input class="FormInputText" id="Titulo" name="Titulo" maxlength="50" value="<?= $row["AI_TITULO"]?>" size="53"></td>
					</tr>
					<tr>
						<td class="FormLabelAzul" valign="top">Cuerpo:</td>
						<td width="88%"><textarea class="FormTextArea" cols="53" id="Cuerpo" name="Cuerpo" rows="5"><?= $row["AI_CUERPO"]?></textarea></td>
					</tr>
					<tr>
						<td class="FormLabelAzul">Link:</td>
						<td width="88%"><select class="Combo" id="Link" name="Link"></select></td>
					</tr>
					<tr>
						<td class="FormLabelAzul">Destino:</td>
						<td width="88%"><select class="Combo" id="Destino" name="Destino"></select></td>
					</tr>
					<tr style="display:<?= $displayImagen?>">
						<td class="FormLabelAzul">Imagen:</td>
						<td width="88%"><select class="Combo" id="Imagen" name="Imagen"></select></td>
					</tr>
					<tr>
						<td align="right" colspan="2"><input class="BotonBlanco" name="btnGuardar" type="submit" value="GUARDAR">&nbsp;&nbsp;&nbsp;</td>
					</tr>
				</table>
			</div>
		</form>
		<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "Link";
$RCparams = array();
$RCquery = getQueryLinks();
$RCselectedItem = (isset($row["AI_LINK"]))?$row["AI_LINK"]:-1;
FillCombo();

$RCfield = "Destino";
$RCparams = array();
$RCquery =
	"SELECT '_blank' id, 'Ventana nueva' detalle
		 FROM dual
UNION ALL
	 SELECT '_self' id, 'Misma Ventana' detalle
		 FROM dual";
$RCselectedItem = $row["AI_DESTINO"];
FillCombo(false);

$RCfield = "Imagen";
$RCparams = array();
$RCquery = getQueryImagenes();
$RCselectedItem = (isset($row["AI_RUTAIMAGEN"]))?$row["AI_RUTAIMAGEN"]:-1;
FillCombo();
?>
			document.getElementById('Volanta').focus();
		</script>
	</body>
</html>