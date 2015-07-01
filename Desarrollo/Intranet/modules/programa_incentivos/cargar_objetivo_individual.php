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
	"SELECT TRUNC(oi_fecha_proceso)
		 FROM rrhh.roi_objetivos_individuales
		WHERE oi_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND oi_fechabaja IS NULL
			AND oi_usuario = :usuario
			AND TRUNC(oi_fecha_proceso) < TO_DATE(:fecha_proceso, 'dd/mm/yyyy')
 GROUP BY TRUNC(oi_fecha_proceso)
 ORDER BY 1 DESC";
$fechaAnterior = valorSql($sql, -1, $params);

$params = array(":fecha_proceso" => $_REQUEST["f"], ":usuario" => $_SESSION["USUARIO_PROGRAMA_INCENTIVOS"]);
$sql =
	"SELECT TRUNC(oi_fecha_proceso)
		 FROM rrhh.roi_objetivos_individuales
		WHERE oi_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND oi_fechabaja IS NULL
			AND oi_usuario = :usuario
			AND TRUNC(oi_fecha_proceso) > TO_DATE(:fecha_proceso, 'dd/mm/yyyy')
 GROUP BY TRUNC(oi_fecha_proceso)
 ORDER BY 1";
$fechaSiguiente = valorSql($sql, -1, $params);

$html = '<table cellspacing="0" cellpadding="0" class="tabla1">';
$html.= 	'<tr>';
$html.= 		'<td class="encabezado" colspan="2">OBJETIVOS INDIVIDUALES</td>';
$html.= 		'<td class="txtActualizacion" colspan="3">';
$html.= 			'Última actualización<br/>';
if ($fechaAnterior != -1)
	$html.=			'<span class="spanCambiarPeriodo" title="Período Anterior" onClick="cargarObjetivoIndividual(\\\''.$fechaAnterior.'\\\')"><<</span>';
$html.= 			'<input id="fechaObjetivosIndividuales" name="fechaObjetivosIndividuales" readonly style="width:67px;" type="text" value="'.$_REQUEST["f"].'" />';
if ($fechaSiguiente != -1)
	$html.= 			'<span class="spanCambiarPeriodo" title="Período Siguiente" onClick="cargarObjetivoIndividual(\\\''.$fechaSiguiente.'\\\')">>></span>';
$html.= 		'</td>';
$html.=		'</tr>';
$html.=		'<tr>';
$html.=			'<td colspan="5" height="10px"></td>';
$html.=		'</tr>';
$html.=		'<tr>';
$html.=			'<td class="titulo2" style="width: 603px; padding-left:5px">DESCRIPCIÓN</td>';
$html.=			'<td class="titulo3" style="width: 70px">% <br>PONDERADO</td>';
$html.=			'<td class="titulo3" style="width: 99px"><br>NOTIFICADO</td>';
$html.=			'<td class="titulo3" style="width: 105px">ESTOY DE ACUERDO</td>';
$html.=			'<td class="titulo3" style="width: 70px">% DE AVANCE</td>';
$html.=		'</tr>';
$html.=		'<tr>';
$html.=			'<td colspan="5" height="5px"></td>';
$html.=		'</tr>';

$params = array(":fecha_proceso" => $_REQUEST["f"], ":usuario" => $_SESSION["USUARIO_PROGRAMA_INCENTIVOS"]);
$sql =
	"SELECT oi_avance, oi_deacuerdo, oi_id, oi_notificado, oi_objetivos, oi_ponderado
		 FROM rrhh.roi_objetivos_individuales
		WHERE oi_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND oi_fechabaja IS NULL
			AND oi_usuario = :usuario
			AND TRUNC(oi_fecha_proceso) = :fecha_proceso";
$stmt = DBExecSql($conn, $sql, $params);
$colorFondo = "";
while ($row = DBGetQuery($stmt)) {
	if ($colorFondo == "textoGris")
		$colorFondo = "texto";
	else
		$colorFondo = "textoGris";

	$html.= '<tr>';
	$html.=		'<td class="'.$colorFondo.'"><b>&gt; </b>'.$row["OI_OBJETIVOS"].'</td>';
	$html.=		'<td class="'.$colorFondo.'" align="center">'.$row["OI_PONDERADO"].'%</td>';
	$html.=		'<td class="'.$colorFondo.'" align="center"><input '.(($row["OI_NOTIFICADO"] == "S")?"checked":"").' '.(($row["OI_NOTIFICADO"] == "S")?"disabled":"").' id="notificado_'.$row["OI_ID"].'" name="notificado_'.$row["OI_ID"].'" type="checkbox" value="ok" /></td>';
	$html.=		'<td class="'.$colorFondo.'" align="center">';
	$html.= 		'<input id="oi_'.$row["OI_ID"].'" name="oi_'.$row["OI_ID"].'" type="hidden" value="'.$row["OI_ID"].'" />';
	$html.=			'Si<input '.(($row["OI_DEACUERDO"] == "S")?"checked":"").' '.(($row["OI_DEACUERDO"] != "")?"disabled":"").' id="deAcuerdo_'.$row["OI_ID"].'" name="deAcuerdo_'.$row["OI_ID"].'" type="radio" value="S" /> - ';
	$html.=			'No<input '.(($row["OI_DEACUERDO"] == "N")?"checked":"").' '.(($row["OI_DEACUERDO"] != "")?"disabled":"").' id="deAcuerdo_'.$row["OI_ID"].'" name="deAcuerdo_'.$row["OI_ID"].'" type="radio" value="N" />';
	$html.=		'</td>';
	$html.=		'<td class="'.$colorFondo.'" align="center">'.$row["OI_AVANCE"].'%</td>';
	$html.=	'</tr>';
}
$html.= '</table>';
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divObjetivosIndividuales').innerHTML = '<?= str_replace(array("\t", "\n", "\r", "\0", "\x0B"), " ", $html)?>';
	}
</script>