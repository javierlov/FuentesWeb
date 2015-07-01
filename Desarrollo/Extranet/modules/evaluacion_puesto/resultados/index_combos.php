<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_empresa = :empresa
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$comboEmpleado = new Combo($sql, "empleado", $empleado);
$comboEmpleado->addParam(":empresa", $empresa);

$sql =
	"SELECT em_id id, em_detalle detalle
		 FROM rrhh.rem_empresas
 ORDER BY 2";
$comboEmpresa = new Combo($sql, "empresa", $empresa);
$comboEmpresa->setDisabled(!$habilitarEmpresa);
$comboEmpresa->setFocus(true);
$comboEmpresa->setOnChange("cambiarEmpresa()");

$sql =
	"SELECT es_id id, es_detalle detalle
		 FROM rrhh.res_estadossistemasgestion
 ORDER BY 1";
$comboEstado = new Combo($sql, "estado", $estado);

$sql =
	"SELECT ge_id id, ge_detalle detalle
		 FROM rrhh.rge_gerencias
 ORDER BY 2";
$comboGerencia = new Combo($sql, "gerencia", $gerencia);

$sql =
	"SELECT gr_id id, gr_detalle detalle
		 FROM rrhh.rgr_grupos
 ORDER BY 2";
$comboGrupo = new Combo($sql, "grupo", $grupo);

$sql =
	"SELECT pu_id id, pu_detalle detalle
		 FROM rrhh.rpu_puestos
 ORDER BY 2";
$comboPuesto = new Combo($sql, "puesto", $puesto);

$sql =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_empresa = :empresa
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$comboReferenteRrhh = new Combo($sql, "referenteRrhh", $referenteRrhh);
$comboReferenteRrhh->addParam(":empresa", $empresa);

$sql =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login a
		WHERE pl_empresa = :empresa
			AND EXISTS(SELECT 1
									 FROM rrhh.dpl_login b
									WHERE a.pl_id = b.pl_jefe)
			AND pl_id <> :id
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$comboRespondeA = new Combo($sql, "respondeA", $empleado);
$comboRespondeA->addParam(":empresa", $empresa);
$comboRespondeA->addParam(":id", $empleado);
?>