<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT -1 id, '' detalle
		 FROM DUAL
		WHERE 1 = 2";
for ($i=1; $i<=12; $i++)
	$sql.=
		" UNION ALL
				 SELECT ".$i.", '".getMonthName($i)."'
					 FROM DUAL";

$comboMes = new Combo($sql, "mes", $_REQUEST["mes"]);
$comboMes->setAddFirstItem(false);
$comboMes->setFocus(true);
$comboMes->setOnChange("window.location.href = '/cumpleanos/' + document.getElementById('mes').value;");
?>