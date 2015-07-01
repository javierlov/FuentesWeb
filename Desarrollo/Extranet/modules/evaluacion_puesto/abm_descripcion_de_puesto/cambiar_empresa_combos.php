<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_empresa = :empresa
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$comboReporta = new Combo($sql, "reporta", $_REQUEST["idreporta"]);
$comboReporta->addParam(":empresa", $_REQUEST["idempresa"]);
$comboReporta->setClass("combo");

$sql =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_empresa = :empresa
			AND pl_id <> :id
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$comboReferenteRrhh = new Combo($sql, "referenteRrhh", $_REQUEST["referenterrhh"]);
$comboReferenteRrhh->addParam(":empresa", $_REQUEST["idempresa"]);
$comboReferenteRrhh->addParam(":id", $_REQUEST["idempleado"]);
$comboReferenteRrhh->setClass("combo");
?>