<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/Miscellaneous/Images.php");


$cols = 3;
$dir = DATA_FOTOS_PATH."fin_de_ano_2002/";
$gridWidth = 400;
$imageStyle = "PhtoGalleryImage";
$photos = array();
$rows = 2;
$title = "Fiesta Fin de Año 2002";
$titleStyle = "PhotoGalleryTitle";

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

if (is_dir($dir))
	if ($gd = opendir($dir)) {
		while (($photo = readdir($gd)) !== false)
			if (($photo != ".") and ($photo != "..")) {
				array_push($photos, $photo);
			}
		closedir($gd);
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="Author" content="Gerencia de Sistemas">
<meta name="Description" content="Intranet de Provincia ART">
<meta name="Language" content="Spanish">
<meta name="Subject" content="Intranet">
<title><?= $title?></title>
<link href="/Styles/style.css" rel="stylesheet" type="text/css">
</head>
<body alink="#336699" link="#336699" vlink="#336699">
<div align="center">
<table border="0" cellpadding="0" cellspacing="3" width="100%">
	<tr>
		<td class="<?= $titleStyle?>"><hr color="#FFFFFF" size="1"><?= $title?><hr color="#FFFFFF" size="1"></td>
	</tr>
	<tr>
		<td align="center">
			<table border="5" cellpadding="0" cellspacing="0" width="<?= $gridWidth?>">
				<tr>
					<td>
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
$index = 0;
for ($i=1;$i<=$rows;$i++) {
?>
							<tr>
<?
	for ($j=1;$j<=$cols;$j++)
		if (isset($photos[$index])) {
			$width = floor(($gridWidth - 6) / $cols);
			$linkPhoto = "/functions/get_full_image.php?file=".base64_encode($dir.$photos[$index]);
			$urlPhoto = "/functions/get_image.php?file=".base64_encode($dir.$photos[$index])."&width=".$width;
			echo "<td align='center' class='".$imageStyle."' valign='middle'><a href='".$linkPhoto."&width=-1' target='_blank'><img border='0' src='".$urlPhoto."'></a></td>";
			$index++;
		}
?>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center">
						<input type="button" value="&lt;&lt;Anterior" name="B2" onClick="backward()" style="border:1px solid #808080; color: #808080; font-family: Verdana; font-size: 8pt; font-weight: bold; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; background-color:#FFFFFF">
						<input type="button" value="Siguiente&gt;&gt;" name="B1" onClick="forward()" style="border-style:solid; border-width:1px; color: #808080; font-family: Verdana; font-size: 8pt; font-weight: bold; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; background-color:#FFFFFF"><br>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>
</body>
</html>