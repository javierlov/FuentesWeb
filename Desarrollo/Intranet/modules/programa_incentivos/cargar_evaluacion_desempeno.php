<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


$params = array(":fecha_proceso" => $_REQUEST["f"], ":usuario" => $_SESSION["USUARIO_PROGRAMA_INCENTIVOS"]);
$sql =
	"SELECT TRUNC(od_fecha_proceso)
		 FROM rrhh.rod_objetivos_desempeno
		WHERE od_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND od_fechabaja IS NULL
			AND od_usuario = :usuario
			AND TRUNC(od_fecha_proceso) < TO_DATE(:fecha_proceso, 'dd/mm/yyyy')
 GROUP BY TRUNC(od_fecha_proceso)
 ORDER BY 1 DESC";
$fechaAnterior = valorSql($sql, -1, $params);

$params = array(":fecha_proceso" => $_REQUEST["f"], ":usuario" => $_SESSION["USUARIO_PROGRAMA_INCENTIVOS"]);
$sql =
	"SELECT TRUNC(od_fecha_proceso)
		 FROM rrhh.rod_objetivos_desempeno
		WHERE od_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND od_fechabaja IS NULL
			AND od_usuario = :usuario
			AND TRUNC(od_fecha_proceso) > TO_DATE(:fecha_proceso, 'dd/mm/yyyy')
 GROUP BY TRUNC(od_fecha_proceso)
 ORDER BY 1";
$fechaSiguiente = valorSql($sql, -1, $params);

$params = array(":fecha_proceso" => $_REQUEST["f"], ":usuario" => $_SESSION["USUARIO_PROGRAMA_INCENTIVOS"]);
$sql =
	"SELECT od_descripcion
		 FROM rrhh.rod_objetivos_desempeno
		WHERE od_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND od_fechabaja IS NULL
			AND od_usuario = :usuario
			AND TRUNC(od_fecha_proceso) = :fecha_proceso";
$descripcionObjetivos = valorSql($sql, -1, $params);

$html = '<table cellspacing="0" cellpadding="0" class="tabla2" align="center">';
$html.=		'<tr>';
$html.=			'<td class="encabezado" width="180px">EVALUACIÓN DE DESEMPEÑO</td>';
$html.=			'<td class="txtActualizacion">';
$html.=				'Última actualización<br/>';
if ($fechaAnterior != -1)
	$html.=				'<span class="spanCambiarPeriodo" title="Período Anterior" onClick="cargarEvaluacionDesempeño(\\\''.$fechaAnterior.'\\\')"><<</span>';
$html.=				'<input id="fechaEvaluacionDesempeño" name="fechaEvaluacionDesempeño" readonly style="width:67px;" type="text" value="'.$_REQUEST["f"].'" />';
if ($fechaSiguiente != -1)
	$html.=				'<span class="spanCambiarPeriodo" title="Período Siguiente" onClick="cargarObjetivoIndividual(\\\''.$fechaSiguiente.'\\\')">>></span>';
$html.=			'</td>';
$html.=		'</tr>';
$html.=		'<tr>';
$html.=			'<td class="titulo"><b>RESULTADO</b></td>';
$html.=			'<td class="texto">'.$descripcionObjetivos.'</td>';
$html.=		'</tr>';
$html.=	'</table>';
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divEvaluacionDesempeño').innerHTML = '<?= str_replace(array("\t", "\n", "\r", "\0", "\x0B"), " ", $html)?>';
	}
</script>