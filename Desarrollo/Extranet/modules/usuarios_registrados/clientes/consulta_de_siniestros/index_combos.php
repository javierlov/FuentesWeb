<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT 'FA' id, 'Fecha de Accidente' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'FUC', 'Fecha de Último Control'
		 FROM DUAL";
$comboFecha = new Combo($sql, "fecha", "");
$comboFecha->setAddFirstItem(false);
?>