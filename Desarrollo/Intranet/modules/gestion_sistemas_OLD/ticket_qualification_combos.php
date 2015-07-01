<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT id, detalle
		 FROM (SELECT 'S' ID, 'Sí' DETALLE
						 FROM DUAL
				UNION ALL
					 SELECT 'N' ID, 'No' DETALLE
						 FROM DUAL
				UNION ALL
					 SELECT '?' ID, 'Desconozco' DETALLE
						 FROM DUAL) PRIORIDADES
		WHERE 1 = 1 ";
$comboResuelto = new Combo($sql, "resuelto");
$comboResuelto->setClass("Combo");

$sql =
	"SELECT ca_id id, ca_descripcion detalle
		 FROM computos.cca_calificacion
 ORDER BY 1";
$comboCalificacion = new Combo($sql, "calificacion");
$comboCalificacion->setClass("Combo");
?>