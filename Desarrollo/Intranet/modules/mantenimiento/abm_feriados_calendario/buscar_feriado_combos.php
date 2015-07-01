<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT el_id id, el_nombre detalle
		 FROM del_delegacion
		WHERE el_fechabaja IS NULL
 ORDER BY 2";
$comboDelegacionBusqueda = new Combo($sql, "delegacionBusqueda", $_SESSION["BUSQUEDA_FERIADOS_CALENDARIO"]["delegacion"]);
$comboDelegacionBusqueda->setFocus(true);
?>