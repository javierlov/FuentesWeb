<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_empresa = :empresa
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$comboEmpleado = new Combo($sql, "empleado");
$comboEmpleado->addParam(":empresa", $_REQUEST["idempresa"]);

$sql =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_empresa = :empresa
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$comboReferenteRrhh = new Combo($sql, "referenteRrhh");
$comboReferenteRrhh->addParam(":empresa", $_REQUEST["idempresa"]);

$sql =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login a
		WHERE pl_empresa = :empresa
			AND EXISTS(SELECT 1
									 FROM rrhh.dpl_login b
									WHERE a.pl_id = b.pl_jefe)
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$comboRespondeA = new Combo($sql, "respondeA");
$comboRespondeA->addParam(":empresa", $_REQUEST["idempresa"]);
?>