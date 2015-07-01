<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT DISTINCT INITCAP(TRIM(ha_empleado)) id, INITCAP(TRIM(ha_empleado)) detalle, se_nombre
							FROM rrhh.rha_ausencias, use_usuarios
						 WHERE ha_empleado = se_nombre
							 AND se_fechabaja IS NULL
					ORDER BY 2";
$comboEmpleado = new Combo($sql, "empleado", $_SESSION["HISTORICO_AUSENTISMO_BUSQUEDA"]["empleado"]);

$sql =
	"SELECT ma_id id, ma_detalle detalle
		 FROM rrhh.rma_motivosausencia
		WHERE ma_fechabaja IS NULL
 ORDER BY 2";
$comboMotivo = new Combo($sql, "motivo", $_SESSION["HISTORICO_AUSENTISMO_BUSQUEDA"]["motivo"]);
?>