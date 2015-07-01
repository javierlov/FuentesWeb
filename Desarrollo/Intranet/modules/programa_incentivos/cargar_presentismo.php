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
	"SELECT TRUNC(op_fecha_proceso)
		 FROM rrhh.rop_objetivos_presentismo
		WHERE op_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND op_fechabaja IS NULL
			AND op_usuario = :usuario
			AND TRUNC(op_fecha_proceso) < TO_DATE(:fecha_proceso, 'dd/mm/yyyy')
 GROUP BY TRUNC(op_fecha_proceso)
 ORDER BY 1 DESC";
$fechaAnterior = valorSql($sql, -1, $params);

$params = array(":fecha_proceso" => $_REQUEST["f"], ":usuario" => $_SESSION["USUARIO_PROGRAMA_INCENTIVOS"]);
$sql =
	"SELECT TRUNC(op_fecha_proceso)
		 FROM rrhh.rop_objetivos_presentismo
		WHERE op_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND op_fechabaja IS NULL
			AND op_usuario = :usuario
			AND TRUNC(op_fecha_proceso) > TO_DATE(:fecha_proceso, 'dd/mm/yyyy')
 GROUP BY TRUNC(op_fecha_proceso)
 ORDER BY 1";
$fechaSiguiente = valorSql($sql, -1, $params);

$params = array(":fecha_proceso" => $_REQUEST["f"], ":usuario" => $_SESSION["USUARIO_PROGRAMA_INCENTIVOS"]);
$sql =
	"SELECT op_dias_licencia, op_situacion
		 FROM rrhh.rop_objetivos_presentismo
		WHERE op_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND op_fechabaja IS NULL
			AND op_usuario = :usuario
			AND TRUNC(op_fecha_proceso) = :fecha_proceso";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$html =	'<table cellspacing="0" cellpadding="0" class="tabla2" align="center">';
$html.=		'<tr>';
$html.=			'<td class="encabezado">PRESENTISMO</td>';
$html.=			'<td class="txtActualizacion">';
$html.=				'Última actualización<br/>';
if ($fechaAnterior != -1)
	$html.=				'<span class="spanCambiarPeriodo" title="Período Anterior" onClick="cargarPresentismo(\\\''.$fechaAnterior.'\\\')"><<</span>';
$html.=				'<input id="fechaPresentismo" name="fechaPresentismo" readonly style="width:67px;" type="text" value="'.$_REQUEST["f"].'" />';
if ($fechaSiguiente != -1)
	$html.=				'<span class="spanCambiarPeriodo" title="Período Siguiente" onClick="cargarObjetivoIndividual(\\\''.$fechaSiguiente.'\\\')">>></span>';
$html.=			'</td>';
$html.=		'</tr>';
$html.=		'<tr>';
$html.=			'<td colspan="2" height="5px"></td>';
$html.=		'</tr>';
$html.=		'<tr>';
$html.=			'<td colspan="2" class="titulo"><b>DÍAS DE LICENCIA: <input readonly style="width:20px;" type="text" value="'.$row["OP_DIAS_LICENCIA"].'" />&nbsp;&nbsp;&nbsp;&nbsp; SITUACIÓN: <input readonly style="width:400px;" type="text" value="'.$row["OP_SITUACION"].'" /></b></td>';
$html.=		'</tr>';
$html.=	'</table>';
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divPresentismo').innerHTML = '<?= str_replace(array("\t", "\n", "\r", "\0", "\x0B"), " ", $html)?>';
	}
</script>