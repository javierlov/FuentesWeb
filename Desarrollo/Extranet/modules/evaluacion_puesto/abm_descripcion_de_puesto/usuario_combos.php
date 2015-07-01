<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT em_id id, em_detalle detalle
		 FROM rrhh.rem_empresas
 ORDER BY 2";
$comboEmpresa = new Combo($sql, "empresa", $empresa);
$comboEmpresa->setClass("combo");
$comboEmpresa->setDisabled(!$habilitarEmpresa);
$comboEmpresa->setOnChange("cambiarEmpresa(".(($alta)?-1:$_REQUEST["id"]).", -1, -1)");

$sql =
	"SELECT es_id id, es_detalle detalle
		 FROM rrhh.res_estadossistemasgestion
 ORDER BY 1";
$comboEstado = new Combo($sql, "estado", ($alta)?1:$row["PL_IDESTADO"]);
$comboEstado->setClass("combo");

$sql =
	"SELECT ge_id id, ge_detalle detalle
		 FROM rrhh.rge_gerencias
 ORDER BY 2";
$comboGerencia = new Combo($sql, "gerencia", ($alta)?-1:$row["PL_GERENCIA"]);
$comboGerencia->setClass("combo");
$comboGerencia->setFocus(true);

$sql =
	"SELECT gr_id id, gr_detalle detalle
		 FROM rrhh.rgr_grupos
 ORDER BY 2";
$comboGrupo = new Combo($sql, "grupo", ($alta)?-1:$row["PL_IDGRUPO"]);
$comboGrupo->setClass("combo");

$sql =
	"SELECT pu_id id, pu_detalle detalle
		 FROM rrhh.rpu_puestos
 ORDER BY 2";
$comboPuesto = new Combo($sql, "puesto", ($alta)?-1:$row["PL_PUESTO"]);
$comboPuesto->setClass("combo");

$sql =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_empresa = :empresa
			AND pl_id <> :id
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$comboReferenteRrhh = new Combo($sql, "referenteRrhh", ($alta)?-1:$row["PL_RRHH"]);
$comboReferenteRrhh->addParam(":empresa", $empresa);
$comboReferenteRrhh->addParam(":id", (($alta)?-1:$_REQUEST["id"]));
$comboReferenteRrhh->setClass("combo");

$sql =
	"SELECT 1
		 FROM DUAL
		WHERE 1 = 2";
$comboReporta = new Combo($sql, "reporta");
$comboReporta->setClass("combo");
$comboReporta->setOnChange("cambiarReporta()");
?>