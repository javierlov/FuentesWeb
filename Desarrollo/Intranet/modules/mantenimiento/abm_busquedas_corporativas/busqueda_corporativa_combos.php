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
$comboEmpresa = new Combo($sql, "empresa", ($isAlta)?-1:$row["BC_IDEMPRESA"]);

$sql =
	"SELECT ec_id id, ec_detalle detalle
		 FROM rrhh.rec_estadosbusquedacorporativa
		WHERE ec_fechabaja IS NULL
 ORDER BY 2";
$comboEstado = new Combo($sql, "estado", ($isAlta)?-1:$row["BC_IDESTADO"]);
?>