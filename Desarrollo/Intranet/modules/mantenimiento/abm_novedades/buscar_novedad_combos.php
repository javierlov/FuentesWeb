<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT 'A' id, 'Ingreso' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'B', 'Egreso'
		 FROM DUAL
UNION ALL
	 SELECT 'M', 'Pase de Sector'
		 FROM DUAL
 ORDER BY 2";
$comboTipoMovimientoBusqueda = new Combo($sql, "tipoMovimientoBusqueda", $_SESSION["BUSQUEDA_NOVEDAD"]["tipoMovimiento"]);
?>