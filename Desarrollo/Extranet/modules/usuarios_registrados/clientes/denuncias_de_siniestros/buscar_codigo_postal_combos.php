<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT pv_codigo id, pv_descripcion detalle
		 FROM cpv_provincias
		WHERE pv_fechabaja IS NULL
 ORDER BY 2";
$comboProvincia = new Combo($sql, "provincia");
?>