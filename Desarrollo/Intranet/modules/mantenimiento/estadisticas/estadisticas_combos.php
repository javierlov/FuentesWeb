<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT '00' id, '00' detalle FROM DUAL
UNION ALL
	 SELECT '01', '01' FROM DUAL
UNION ALL
	 SELECT '02', '02' FROM DUAL
UNION ALL
	 SELECT '03', '03' FROM DUAL
UNION ALL
	 SELECT '04', '04' FROM DUAL
UNION ALL
	 SELECT '05', '05' FROM DUAL
UNION ALL
	 SELECT '06', '06' FROM DUAL
UNION ALL
	 SELECT '07', '07' FROM DUAL
UNION ALL
	 SELECT '08', '08' FROM DUAL
UNION ALL
	 SELECT '09', '09' FROM DUAL
UNION ALL
	 SELECT '10', '10' FROM DUAL
UNION ALL
	 SELECT '11', '11' FROM DUAL
UNION ALL
	 SELECT '12', '12' FROM DUAL
UNION ALL
	 SELECT '13', '13' FROM DUAL
UNION ALL
	 SELECT '14', '14' FROM DUAL
UNION ALL
	 SELECT '15', '15' FROM DUAL
UNION ALL
	 SELECT '16', '16' FROM DUAL
UNION ALL
	 SELECT '17', '17' FROM DUAL
UNION ALL
	 SELECT '18', '18' FROM DUAL
UNION ALL
	 SELECT '19', '19' FROM DUAL
UNION ALL
	 SELECT '20', '20' FROM DUAL
UNION ALL
	 SELECT '21', '21' FROM DUAL
UNION ALL
	 SELECT '22', '22' FROM DUAL
UNION ALL
	 SELECT '23', '23' FROM DUAL";
$comboHoraDesdeHoraria = new Combo($sql, "horaDesdeHoraria", "00");

$sql =
	"SELECT '00' id, '00' detalle FROM DUAL
UNION ALL
	 SELECT '01', '01' FROM DUAL
UNION ALL
	 SELECT '02', '02' FROM DUAL
UNION ALL
	 SELECT '03', '03' FROM DUAL
UNION ALL
	 SELECT '04', '04' FROM DUAL
UNION ALL
	 SELECT '05', '05' FROM DUAL
UNION ALL
	 SELECT '06', '06' FROM DUAL
UNION ALL
	 SELECT '07', '07' FROM DUAL
UNION ALL
	 SELECT '08', '08' FROM DUAL
UNION ALL
	 SELECT '09', '09' FROM DUAL
UNION ALL
	 SELECT '10', '10' FROM DUAL
UNION ALL
	 SELECT '11', '11' FROM DUAL
UNION ALL
	 SELECT '12', '12' FROM DUAL
UNION ALL
	 SELECT '13', '13' FROM DUAL
UNION ALL
	 SELECT '14', '14' FROM DUAL
UNION ALL
	 SELECT '15', '15' FROM DUAL
UNION ALL
	 SELECT '16', '16' FROM DUAL
UNION ALL
	 SELECT '17', '17' FROM DUAL
UNION ALL
	 SELECT '18', '18' FROM DUAL
UNION ALL
	 SELECT '19', '19' FROM DUAL
UNION ALL
	 SELECT '20', '20' FROM DUAL
UNION ALL
	 SELECT '21', '21' FROM DUAL
UNION ALL
	 SELECT '22', '22' FROM DUAL
UNION ALL
	 SELECT '23', '23' FROM DUAL";
$comboHoraHastaHoraria = new Combo($sql, "horaHastaHoraria", "23");

$sql =
	"SELECT '01' id, 'Enero' detalle FROM DUAL
UNION ALL
	 SELECT '02', 'Febrero' FROM DUAL
UNION ALL
	 SELECT '03', 'Marzo' FROM DUAL
UNION ALL
	 SELECT '04', 'Abril' FROM DUAL
UNION ALL
	 SELECT '05', 'Mayo' FROM DUAL
UNION ALL
	 SELECT '06', 'Junio' FROM DUAL
UNION ALL
	 SELECT '07', 'Julio' FROM DUAL
UNION ALL
	 SELECT '08', 'Agosto' FROM DUAL
UNION ALL
	 SELECT '09', 'Septiembre' FROM DUAL
UNION ALL
	 SELECT '10', 'Octubre' FROM DUAL
UNION ALL
	 SELECT '11', 'Noviembre' FROM DUAL
UNION ALL
	 SELECT '12', 'Diciembre' FROM DUAL";
$comboMesDesdeMensual = new Combo($sql, "mesDesdeMensual");

$sql =
	"SELECT '01' id, 'Enero' detalle FROM DUAL
UNION ALL
	 SELECT '02', 'Febrero' FROM DUAL
UNION ALL
	 SELECT '03', 'Marzo' FROM DUAL
UNION ALL
	 SELECT '04', 'Abril' FROM DUAL
UNION ALL
	 SELECT '05', 'Mayo' FROM DUAL
UNION ALL
	 SELECT '06', 'Junio' FROM DUAL
UNION ALL
	 SELECT '07', 'Julio' FROM DUAL
UNION ALL
	 SELECT '08', 'Agosto' FROM DUAL
UNION ALL
	 SELECT '09', 'Septiembre' FROM DUAL
UNION ALL
	 SELECT '10', 'Octubre' FROM DUAL
UNION ALL
	 SELECT '11', 'Noviembre' FROM DUAL
UNION ALL
	 SELECT '12', 'Diciembre' FROM DUAL";
$comboMesHastaMensual = new Combo($sql, "mesHastaMensual");

$sql =
	"SELECT 'h' id, 'Horaria' detalle FROM DUAL
UNION ALL
	 SELECT 'd', 'Diaria' FROM DUAL
UNION ALL
	 SELECT 'm', 'Mensual' FROM DUAL
UNION ALL
	 SELECT 'a', 'Anual' FROM DUAL";
$comboTipo = new Combo($sql, "tipo");
$comboTipo->setFocus(true);
$comboTipo->setOnChange("cambiarTipo(this.value)");
?>