<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT ej_id id, ej_descripcion detalle
		 FROM legales.lej_estadojuicio
		WHERE ej_fechabaja IS NULL
 ORDER BY 2";
$comboEstadoJuicio = new Combo($sql, "estadoJuicio");
?>