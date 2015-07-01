<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT 1
		 FROM DUAL
		WHERE 1 = 2";
$comboLocalidadCombo = new Combo($sql, "localidadCombo", $rowAVP["VP_LOCALIDAD"]);
$comboLocalidadCombo->setClass("localidadCombo");
$comboLocalidadCombo->setFirstItem("- INGRESE EL CÓDIGO POSTAL Y LA PROVINCIA -");
$comboLocalidadCombo->setOnChange("cambiarLocalidad(this.value, '')");

$sql =
	"SELECT pv_codigo id, pv_descripcion detalle
		 FROM cpv_provincias
		WHERE pv_fechabaja IS NULL
 ORDER BY 2";
$comboProvincia = new Combo($sql, "provincia", ($rowAVP["VP_PROVINCIA"]=="")?-1:$rowAVP["VP_PROVINCIA"]);
$comboProvincia->setOnChange("cargarComboLocalidad('')");
?>