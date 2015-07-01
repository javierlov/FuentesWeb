<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">

<div>
	<img border="0" src="/modules/arteria_noticias/images/title.jpg">
<?
$list = new ListOfItems("", "");

$sql =
	"SELECT ba_ano, ba_id, ba_numero,
				  TRIM(TO_CHAR(ba_fecha, 'Day')) || ' ' || TO_NUMBER(TO_CHAR(ba_fecha, 'DD')) || ' de ' || TRIM(TO_CHAR(ba_fecha, 'Month')) || ' de ' || TO_CHAR(ba_fecha, 'YYYY') fecha
		FROM rrhh.rba_boletinesarteria
	 WHERE ba_estadoenvio = 'E'
	 AND ba_fecha >= TO_DATE('01/01/2011', 'dd/mm/yyyy')
		  AND ba_fechabaja IS NULL
 ORDER BY ba_fecha DESC";
$stmt = DBExecSql($conn, $sql);
$row = DBGetQuery($stmt);		// Hago esta llamada acá para obviar el boletín actual..

while ($row = DBGetQuery($stmt))
	$list->addItem(new ItemList("/modules/arteria_noticias/envio.php?id=".$row["BA_ID"], $row["FECHA"]." - Año ".decimalToRomana($row["BA_ANO"])." Número ".decimalToRomana($row["BA_NUMERO"]), "_blank", false, true));

$list->setColsWidth(340);
$list->setImagePath("/modules/arteria_noticias/images/flecha.gif");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="index.php?pageid=52" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>