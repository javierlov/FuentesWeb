<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT pv_codigo id, pv_descripcion detalle
		 FROM cpv_provincias
 ORDER BY 2";
$comboIdprovincia = new Combo($sql, "idprovincia");

$sql =
	"SELECT pa_codigo id, pa_descripcion detalle
		 FROM art.cpa_paises
		WHERE pa_fechabaja IS NULL
 ORDER BY 2";
$comboPais = new Combo($sql, "pais");
?>