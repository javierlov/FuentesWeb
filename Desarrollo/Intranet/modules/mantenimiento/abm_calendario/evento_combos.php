<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT '_self' id, 'Misma ventana' detalle
		 FROM DUAL
UNION ALL
	 SELECT '_blank', 'Ventana nueva'
		 FROM DUAL";
$comboDestino = new Combo($sql, "destino", ($isAlta)?-1:$row["CL_DESTINO"]);
?>