<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT em_id id, em_nombre detalle
		 FROM aem_empresa
		WHERE em_idgrupoeconomico = 88
UNION ALL
	 SELECT -2, 'PROVINCIA A.R.T.'
		 FROM DUAL
UNION ALL
	 SELECT -3, 'INVIERTA BUENOS AIRES'
		 FROM DUAL
 ORDER BY 2";
$comboEmpresaBusqueda = new Combo($sql, "empresaBusqueda", $_SESSION["BUSQUEDA_BUSQUEDA_CORPORATIVA"]["empresaBusqueda"]);

$sql =
	"SELECT ec_id id, ec_detalle detalle
		 FROM rrhh.rec_estadosbusquedacorporativa
		WHERE ec_fechabaja IS NULL
 ORDER BY 2";
$comboEstadoBusqueda = new Combo($sql, "estadoBusqueda", $_SESSION["BUSQUEDA_BUSQUEDA_CORPORATIVA"]["estadoBusqueda"]);
?>