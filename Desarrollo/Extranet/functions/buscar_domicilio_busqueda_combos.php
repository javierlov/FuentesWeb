<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT DISTINCT pv_codigo id, pv_descripcion detalle
							FROM cpv_provincias
					ORDER BY 2";
$comboProvincia2 = new Combo($sql, "provincia2", $_REQUEST["provincia"]);
$comboProvincia2->setOnChange("cambiaProvincia2(this.value)");
?>