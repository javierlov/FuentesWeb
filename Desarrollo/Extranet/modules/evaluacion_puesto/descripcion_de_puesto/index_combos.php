<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_jefe = :idusuario
			AND pl_fechabaja IS NULL
UNION ALL
	 SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_id = :idusuario
			AND pl_esrrhh = 'F'
			AND pl_fechabaja IS NULL
UNION ALL
	 SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_rrhh = :idusuario
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$comboUsuarioAEvaluar = new Combo($sql, "UsuarioAEvaluar", $_SESSION["idEvaluado"]);
$comboUsuarioAEvaluar->addParam(":idusuario", $_SESSION["idUsuario"]);
$comboUsuarioAEvaluar->setAddFirstItem(false);
$comboUsuarioAEvaluar->setFocus(true);
$comboUsuarioAEvaluar->setOnChange("cambiarEvaluado(this.value)");
?>