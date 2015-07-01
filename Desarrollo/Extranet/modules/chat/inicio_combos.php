<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$params = array();
$sql =
	"SELECT se_id id, se_nombre detalle
		 FROM web.wse_sectoreschat
		WHERE se_fechabaja IS NULL
 ORDER BY 2";
$comboSector = new Combo($sql, "sector");
$comboSector->setClass("campo");
$comboSector->setOnChange("cambiarSector(this.value)");
$comboSector->setOnFocus("cambiarFondo(this)");
?>