<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT DISTINCT 1 orden, ub_localidad id, ub_localidad detalle
		 FROM cub_ubicacion
		WHERE 1 = 1";
if ($_REQUEST["cp"] != "")
	$sql.= " AND ub_cpostal = :cpostal";
if ($_REQUEST["p"] != -1)
	$sql.= " AND ub_provincia = :provincia";
$sql.=
	" UNION ALL
			 SELECT 2, '-1', '.:: NINGUNO DE ELLOS, PUEDE CARGAR UNO ::.' FROM DUAL
		 ORDER BY 1, 2";
$comboLocalidadCombo = new Combo($sql, "localidadCombo", $_REQUEST["l"]);
$comboLocalidadCombo->setAddFirstItem(false);
$comboLocalidadCombo->setFirstItem("- INGRESE EL CÓDIGO POSTAL Y LA PROVINCIA -");
$comboLocalidadCombo->setOnChange("cambiarLocalidad(this.value)");

if ($_REQUEST["cp"] != "")
	$comboLocalidadCombo->addParam(":cpostal", $_REQUEST["cp"]);
if ($_REQUEST["p"] != -1)
	$comboLocalidadCombo->addParam(":provincia", $_REQUEST["p"]);
?>