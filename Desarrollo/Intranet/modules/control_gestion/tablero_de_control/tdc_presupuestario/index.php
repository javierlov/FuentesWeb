<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function setMes($mes) {
	$result = "images/submenu/mes_".$mes.".gif";
	if ($mes == $_SESSION["tablero"]["mes"])
		$result = "images/submenu/mes_".$mes."_a.gif";

	return $result;
}

function setNivel($nivel) {
	$result = "images/detalle/det_".$nivel.".gif";
	if ($nivel == $_SESSION["tablero"]["nivel"])
		$result = "images/detalle/det_".$nivel."_si.gif";

	return $result;
}

function setPeriodo($periodo) {
	$result = "";
	if ($periodo == $_SESSION["tablero"]["periodo"])
		$result = "style='color:#00adef;'";

	return $result;
}

function setTrimestre($trimestre) {
	$result = "images/submenu/trimestre_".$trimestre.".gif";
	if ($trimestre == $_SESSION["tablero"]["trimestre"])
		$result = "images/submenu/trimestre_".$trimestre."_a.gif";

	return $result;
}

if (!isset($_SESSION["tablero"]))
	$_SESSION["tablero"] = array("mes" => date("n"), "nivel" => 1, "periodo" => "um", "trimestre" => 1);

if (isset($_REQUEST["mes"]))
	$_SESSION["tablero"]["mes"] = $_REQUEST["mes"];
if (isset($_REQUEST["nivel"]))
	$_SESSION["tablero"]["nivel"] = $_REQUEST["nivel"];
if (isset($_REQUEST["periodo"]))
	$_SESSION["tablero"]["periodo"] = $_REQUEST["periodo"];
if (isset($_REQUEST["trimestre"]))
	$_SESSION["tablero"]["trimestre"] = $_REQUEST["trimestre"];


$params = array(":usuario" => getWindowsLoginName());
$sql =
	"SELECT pt_nivelejecutivo
		 FROM web.wpt_permisostablerocontrol
		WHERE pt_ejecutivo = 'S'
			AND pt_fechabaja IS NULL
			AND pt_usuario = UPPER(:usuario)";
$nivel = ValorSql($sql, 0, $params);
if ($nivel < 1) {
	echo "<span style='color:red;'>Usted no tiene permiso para ver datos dentro del Sistema de Información Ejecutiva a ningún nivel.</span>";
	exit;
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<title>.:: Tablero de Control Presupuestario | Provincia ART ::.</title>
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<script src="js/detalle.js" type="text/javascript"></script>
	</head>
	<body onLoad="recargar()">
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<table cellpadding="0" cellspacing="0" align="center" width="80%">
			<tr>
				<td colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td class="LatIzqTitular"></td>
							<td class="Titular">Tablero de Control Presupuestario</td>
							<td class="LatDerTitular"></td>
						</tr>
					</table>		
				</td>
			</tr>
			<tr>
				<td colspan="2" height="5"></td>
			</tr>
			<tr>
				<td align="center">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td class="CornerIzqMenu"></td>
							<td class="OpcionesMenu"><a id="imgPeriodo1" href="#" <?= setPeriodo("um")?> onClick="recargar('periodo=um')">Último Mes</a></td>
							<td class="OpcionesMenu"><a id="imgPeriodo2" href="#" <?= setPeriodo("a")?> onClick="recargar('periodo=a')">Año</a></td>
							<td class="OpcionesMenu"><a id="imgPeriodo3" href="#" <?= setPeriodo("t")?> onClick="mostrarMenu('t')">Trimestre</a></td>
							<td class="OpcionesMenu"><a id="imgPeriodo4" href="#" <?= setPeriodo("m")?> onClick="mostrarMenu('m')">Mes</a></td>
							<td class="CornerDerMenu"></td>
						</tr>
					</table>
				</td>
				<td align="right" style="padding-right:30px">
					<table cellpadding="2" cellspacing="2">
						<tr>
							<td class="detalle">&nbsp;<b>NIVEL DE DETALLE </b>>&gt;</td>
<?
for ($i=1; $i<=$nivel; $i++) {
?>
							<td>
								<a href="#" onClick="recargar('nivel=<?= $i?>')">
									<img id="imgNivel<?= $i?>" src="<?= setNivel($i)?>" onmouseover="if (this.src==imagen[<?= ($i - 1)?>].no.src){this.src=imagen[<?= ($i - 1)?>].pincha.src}" onmouseout="if (this.src==imagen[<?= ($i - 1)?>].pincha.src){this.src=imagen[<?= ($i - 1)?>].no.src}">
								</a>
							</td>
<?
}
?>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" height="5"></td>
			</tr>
			<tr>
				<td colspan="2">
					<table align="center" cellpadding="0" cellspacing="0" width="95%">
						<td bgcolor="#e7e7e7" style="border-top-style:solid; border-top-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;">
							<table align="center" cellpadding="0" cellspacing="0">
								<tr id="trTrimestre" style="display:<?= ($_SESSION["tablero"]["periodo"] == "t")?"inline":"none"?>">
									<td><a href="#" onClick="recargar('periodo=t&trimestre=1')"><img id="imgTrimestre1" src="<?= setTrimestre(1)?>" onmouseout="javascript:this.src='images/submenu/trimestre_1.gif'" onmouseover="javascript:this.src='images/submenu/trimestre_1_a.gif'"></a></td>
									<td><a href="#" onClick="recargar('periodo=t&trimestre=2')"><img id="imgTrimestre2" src="<?= setTrimestre(2)?>" onmouseout="javascript:this.src='images/submenu/trimestre_2.gif'" onmouseover="javascript:this.src='images/submenu/trimestre_2_a.gif'"></a></td>
									<td><a href="#" onClick="recargar('periodo=t&trimestre=3')"><img id="imgTrimestre3" src="<?= setTrimestre(3)?>" onmouseout="javascript:this.src='images/submenu/trimestre_3.gif'" onmouseover="javascript:this.src='images/submenu/trimestre_3_a.gif'"></a></td>
									<td><a href="#" onClick="recargar('periodo=t&trimestre=4')"><img id="imgTrimestre4" src="<?= setTrimestre(4)?>" onmouseout="javascript:this.src='images/submenu/trimestre_4.gif'" onmouseover="javascript:this.src='images/submenu/trimestre_4_a.gif'"></a></td>
								</tr>					
								<tr id="trMes1" style="display:<?= ($_SESSION["tablero"]["periodo"] == "m")?"inline":"none"?>">
									<td><a href="#" onClick="recargar('periodo=m&mes=1')"><img id="imgMes1" src="<?= setMes(1)?>" onmouseout="javascript:this.src='images/submenu/mes_1.gif'" onmouseover="javascript:this.src='images/submenu/mes_1_a.gif'"></a></td>
									<td><a href="#" onClick="recargar('periodo=m&mes=2')"><img id="imgMes2" src="<?= setMes(2)?>" onmouseout="javascript:this.src='images/submenu/mes_2.gif'" onmouseover="javascript:this.src='images/submenu/mes_2_a.gif'"></a></td>
									<td><a href="#" onClick="recargar('periodo=m&mes=3')"><img id="imgMes3" src="<?= setMes(3)?>" onmouseout="javascript:this.src='images/submenu/mes_3.gif'" onmouseover="javascript:this.src='images/submenu/mes_3_a.gif'"></a></td>
									<td><a href="#" onClick="recargar('periodo=m&mes=4')"><img id="imgMes4" src="<?= setMes(4)?>" onmouseout="javascript:this.src='images/submenu/mes_4.gif'" onmouseover="javascript:this.src='images/submenu/mes_4_a.gif'"></a></td>
									<td><a href="#" onClick="recargar('periodo=m&mes=5')"><img id="imgMes5" src="<?= setMes(5)?>" onmouseout="javascript:this.src='images/submenu/mes_5.gif'" onmouseover="javascript:this.src='images/submenu/mes_5_a.gif'"></a></td>
									<td><a href="#" onClick="recargar('periodo=m&mes=6')"><img id="imgMes6" src="<?= setMes(6)?>" onmouseout="javascript:this.src='images/submenu/mes_6.gif'" onmouseover="javascript:this.src='images/submenu/mes_6_a.gif'"></a></td>
								</tr>
								<tr id="trMes2" style="display:<?= ($_SESSION["tablero"]["periodo"] == "m")?"inline":"none"?>">
									<td><a href="#" onClick="recargar('periodo=m&mes=7')"><img id="imgMes7" src="<?= setMes(7)?>" onmouseout="javascript:this.src='images/submenu/mes_7.gif'" onmouseover="javascript:this.src='images/submenu/mes_7_a.gif'"></a></td>
									<td><a href="#" onClick="recargar('periodo=m&mes=8')"><img id="imgMes8" src="<?= setMes(8)?>" onmouseout="javascript:this.src='images/submenu/mes_8.gif'" onmouseover="javascript:this.src='images/submenu/mes_8_a.gif'"></a></td>
									<td><a href="#" onClick="recargar('periodo=m&mes=9')"><img id="imgMes9" src="<?= setMes(9)?>" onmouseout="javascript:this.src='images/submenu/mes_9.gif'" onmouseover="javascript:this.src='images/submenu/mes_9_a.gif'"></a></td>
									<td><a href="#" onClick="recargar('periodo=m&mes=10')"><img id="imgMes10" src="<?= setMes(10)?>" onmouseout="javascript:this.src='images/submenu/mes_10.gif'" onmouseover="javascript:this.src='images/submenu/mes_10_a.gif'"></a></td>
									<td><a href="#" onClick="recargar('periodo=m&mes=11')"><img id="imgMes11" src="<?= setMes(11)?>" onmouseout="javascript:this.src='images/submenu/mes_11.gif'" onmouseover="javascript:this.src='images/submenu/mes_11_a.gif'"></a></td>
									<td><a href="#" onClick="recargar('periodo=m&mes=12')"><img id="imgMes12" src="<?= setMes(12)?>" onmouseout="javascript:this.src='images/submenu/mes_12.gif'" onmouseover="javascript:this.src='images/submenu/mes_12_a.gif'"></a></td>
								</tr>	
							</table>
						</td>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
		</table>
		<div id="divGrilla"></div>
		<div class="encabezadoTabla" id="divProcesando" style="background-color:#086b94; border:2px solid #8fc2f7; display:none; height:24px; left:320px; padding:4px; position:absolute; top:240px; width:376px;">
			<img src="/images/loading.gif" />
			<span style="color:#fff; margin-left:4px; vertical-align:2;">Procesando su consulta, aguarde por favor...</span>
		</div>
	</body>
</html>