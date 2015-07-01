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
$comboEstado2 = new Combo($sql, "estado2", $_SESSION["BUSQUEDA_ADMINISTRACION_RESPONSABLES_CONTRATO"]["estado2"]);
?>