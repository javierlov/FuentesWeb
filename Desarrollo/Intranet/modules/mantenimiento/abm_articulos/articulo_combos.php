<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT '_self' id, 'Misma ventana' detalle
		 FROM DUAL
UNION ALL
	 SELECT '_blank', 'Ventana nueva'
		 FROM DUAL";
$comboDestino = new Combo($sql, "destino", ($isAlta)?-1:$row["AI_DESTINO"]);

$sql =
	"SELECT 0 id, 'Arriba (imagen grande)' detalle
		 FROM DUAL
UNION ALL
	 SELECT 1, 'Abajo (imagen chica)'
		 FROM DUAL";
$comboUbicacion = new Combo($sql, "ubicacion", ($isAlta)?-1:$row["AI_UBICACION"]);
?>