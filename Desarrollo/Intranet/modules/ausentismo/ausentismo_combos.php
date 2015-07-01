<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT se_id id, se_nombre detalle
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuariogenerico = 'N'
 ORDER BY se_buscanombre";
$comboEmpleadoAusente = new Combo($sql, "empleadoAusente");
$comboEmpleadoAusente->setFocus(true);

$sql =
	"SELECT ma_id id, ma_detalle detalle
		 FROM rrhh.rma_motivosausencia
		WHERE ma_fechabaja IS NULL
 ORDER BY 2";
$comboMotivoAusencia = new Combo($sql, "motivoAusencia");
$comboMotivoAusencia->setOnChange("mostrarEnviarMedico()");

$sql =
	"SELECT 'T' id, 'Sí' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'F', 'No'
		 FROM DUAL";
$comboEnviarMedico = new Combo($sql, "enviarMedico");
$comboEnviarMedico->setOnChange("mostrarJustificacion()");
?>