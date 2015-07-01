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
	"SELECT TRUNC(oc_fecha_proceso)
		 FROM rrhh.roc_objetivos_curso
		WHERE oc_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND oc_fechabaja IS NULL
			AND oc_usuario = :usuario
			AND TRUNC(oc_fecha_proceso) < TO_DATE(:fecha_proceso, 'dd/mm/yyyy')
 GROUP BY TRUNC(oc_fecha_proceso)
 ORDER BY 1 DESC";
$fechaAnterior = valorSql($sql, -1, $params);

$params = array(":fecha_proceso" => $_REQUEST["f"], ":usuario" => $_SESSION["USUARIO_PROGRAMA_INCENTIVOS"]);
$sql =
	"SELECT TRUNC(oc_fecha_proceso)
		 FROM rrhh.roc_objetivos_curso
		WHERE oc_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND oc_fechabaja IS NULL
			AND oc_usuario = :usuario
			AND TRUNC(oc_fecha_proceso) > TO_DATE(:fecha_proceso, 'dd/mm/yyyy')
 GROUP BY TRUNC(oc_fecha_proceso)
 ORDER BY 1";
$fechaSiguiente = valorSql($sql, -1, $params);

$params = array(":fecha_proceso" => $_REQUEST["f"], ":usuario" => $_SESSION["USUARIO_PROGRAMA_INCENTIVOS"]);
$sql =
	"SELECT oc_fraude, oc_pla
		 FROM rrhh.roc_objetivos_curso
		WHERE oc_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND oc_fechabaja IS NULL
			AND oc_usuario = :usuario
			AND TRUNC(oc_fecha_proceso) = :fecha_proceso";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$html = '<table cellspacing="0" cellpadding="0" class="tabla2" align="center">';
$html.= 	'<tr>';
$html.=			'<td class="encabezado">CURSOS OBLIGATORIOS</td>';
$html.=			'<td class="txtActualizacion">';
$html.=				'Última actualización<br/>';
if ($fechaAnterior != -1)
	$html.=				'<span class="spanCambiarPeriodo" title="Período Anterior" onClick="cargarCursosObligatorios(\\\''.$fechaAnterior.'\\\')"><<</span>';
$html.=				'<input id="fechaCursosObligatorios" name="fechaCursosObligatorios" readonly style="width:67px;" type="text" value="'.$_REQUEST["f"].'" />';
if ($fechaSiguiente != -1)
	$html.=				'<span class="spanCambiarPeriodo" title="Período Siguiente" onClick="cargarObjetivoIndividual(\\\''.$fechaSiguiente.'\\\')">>></span>';
$html.=			'</td>';
$html.=		'</tr>';
$html.=		'<tr>';
$html.=			'<td colspan="2" height="7px"></td>';
$html.=		'</tr>';
$html.=		'<tr>';
$html.=			'<td colspan="2" class="texto" style="height: 16px;"><b>&gt;</b> PLA&nbsp; <input readonly style="width:616px;" type="text" value="'.$row["OC_PLA"].'" /></td>';
$html.=		'</tr>';
$html.=		'<tr>';
$html.=			'<td colspan="2" height="1px"></td>';
$html.=		'</tr>';
$html.=		'<tr>';
$html.=			'<td colspan="2" class="texto" style="height: 16px;"><b>&gt;</b> FRAUDE&nbsp; <input readonly style="width:591px;" type="text" value="'.$row["OC_FRAUDE"].'" /></td>';
$html.=		'</tr>';
$html.=	'</table>';
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divCursosObligatorios').innerHTML = '<?= str_replace(array("\t", "\n", "\r", "\0", "\x0B"), " ", $html)?>';
	}
</script>