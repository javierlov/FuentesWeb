<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT pn_id id, pn_nombre detalle
		 FROM web.wpn_plantillasintranet
		WHERE pn_modulo = 1
 ORDER BY 2";
$comboPlantilla = new Combo($sql, "plantilla", $id);
$comboPlantilla->setFirstItem("- NUEVA PLANTILLA -");
?>