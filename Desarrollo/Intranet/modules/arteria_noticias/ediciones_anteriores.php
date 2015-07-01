<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>ARTeria Noticias</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Description" content="ARTeria Noticas" />
		<meta name="Language" content="Spanish" />
		<meta name="Subject" content="ARTeria Noticas" />
		<link href="/css/form_elements.css" rel="stylesheet" type="text/css">
		<link href="/css/general.css" rel="stylesheet" type="text/css">
		<link href="/css/grid.css" rel="stylesheet" type="text/css">
		<link href="/css/header.css" rel="stylesheet" type="text/css">
		<link href="/css/list_of_items.css" rel="stylesheet" type="text/css">
		<link href="/css/style.css" rel="stylesheet" type="text/css">
		<link href="/modules/arteria_noticias/css/style.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div align="center">
			<div style="width:800px;">
				<img src="/modules/arteria_noticias/images/title.jpg" style="margin-bottom:8px;" usemap="#header" />
<?
$list = new ListOfItems("", "");

$sql =
	"SELECT ba_ano, ba_id, ba_numero, TRIM(TO_CHAR(ba_fecha, 'Day')) || ' ' || TO_NUMBER(TO_CHAR(ba_fecha, 'DD')) || ' de ' || TRIM(TO_CHAR(ba_fecha, 'Month')) || ' de ' || TO_CHAR(ba_fecha, 'YYYY') fecha
		 FROM rrhh.rba_boletinesarteria
		WHERE ba_estadoenvio = 'E'
			AND ba_fecha >= TO_DATE('01/01/2011', 'dd/mm/yyyy')
			AND ba_fechabaja IS NULL
 ORDER BY ba_fecha DESC";
$stmt = DBExecSql($conn, $sql);
$row = DBGetQuery($stmt);		// Hago esta llamada acá para obviar el boletín actual..

while ($row = DBGetQuery($stmt))
	$list->addItem(new ItemList("/arteria-noticias-ediciones-anteriores/".$row["BA_ID"], $row["FECHA"]." - Año ".decimalToRomana($row["BA_ANO"])." Número ".decimalToRomana($row["BA_NUMERO"]), "_self", false, true));

$list->setColsWidth(340);
$list->setImagePath("/modules/arteria_noticias/images/flecha.gif");
$list->draw();
?>
				<a href="/arteria-noticias"><input class="btnVolver" type="button" value="" /></a>
			</div>
		</div>
		<map id="header" name="header">
			<area alt="ARTeria Noticias" coords="16, 16, 196, 92" href="/arteria-noticias" shape="rect" />
		</map>
	</body>
</html>