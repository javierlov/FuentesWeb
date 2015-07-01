<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT 'A' id, 'Activo' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'I' id, 'Inactivo' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'P' id, 'Pendiente' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'S' id, 'Suspendido' detalle
		 FROM DUAL";
$comboEstado = new Combo($sql, "estado", (!$isAlta)?$row["UE_ESTADO"]:-1);
?>