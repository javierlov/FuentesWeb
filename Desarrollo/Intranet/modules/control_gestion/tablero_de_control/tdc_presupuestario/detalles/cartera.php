<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


set_time_limit(120);

$presupuesto = ValorSql("SELECT cont.get_presupuestoactual FROM DUAL", "", array());

if ($_SESSION["tablero"]["periodo"] == "um") {
	$numero = valorSql("SELECT cont.get_ultimomespresupuesto(:presupuesto) FROM DUAL", "", array(":presupuesto" => $presupuesto));
	$periodo = 1;
}
if ($_SESSION["tablero"]["periodo"] == "a") {
	$numero = 1;
	$periodo = 12;
}
if ($_SESSION["tablero"]["periodo"] == "t") {
	$numero = $_SESSION["tablero"]["trimestre"];
	$periodo = 3;
}
if ($_SESSION["tablero"]["periodo"] == "m") {
	$numero = $_SESSION["tablero"]["mes"];
	$periodo = 1;
}


$curs = null;
$params = array(":corte" => $periodo,
								":idpresupuesto" => $presupuesto,
								":nrocorte" => $numero);
$sql = "BEGIN art.cont.do_presupuestocartera(:idpresupuesto, :corte, :nrocorte); END;";
DBExecSP($conn, $curs, $sql, $params, false);

$params = array();
$sql =
	"SELECT pc_sector,
				  TO_CHAR(pc_realcapitas, '9G999G999G990') real_capitas,
				  TO_CHAR(pc_prescapitas, '9G999G999G990') pres_capitas,
				  art.cont.get_variacion(pc_realcapitas, pc_prescapitas) var_capitas,
				  TO_CHAR(pc_realcuota, '$9G999G999G990D00') real_cuota,
				  TO_CHAR(pc_prescuota * 1.035, '$9G999G999G990D00') pres_cuota,
				  art.cont.get_variacion(pc_realcuota, pc_prescuota * 1.035) var_cuota,
				  TO_CHAR(pc_realpreciocapita, '$9G999G999G990D00') real_precio_capita,
				  TO_CHAR(pc_prespreciocapita, '$9G999G999G990D00') pres_precio_capita,
				  art.cont.get_variacion(pc_realpreciocapita, pc_prespreciocapita) var_precio_capita,
				  TO_CHAR(pc_realsalariomedio, '$9G999G999G990D00') real_salario_medio,
				  TO_CHAR(pc_pressalariomedio, '$9G999G999G990D00') pres_salario_medio,
				  art.cont.get_variacion(pc_realsalariomedio, pc_pressalariomedio) var_salario_medio,
				  TO_CHAR(pc_realalicuotapromedio, '0D0000') real_alicuota_promedio,
				  TO_CHAR(pc_presalicuotapromedio, '0D0000') pres_alicuota_promedio,
				  art.cont.get_variacion(pc_realalicuotapromedio, pc_presalicuotapromedio) var_alicuota_promedio,
				  TO_CHAR(pc_realcapitasaltas, '9G999G999G990') real_capitas_altas,
				  TO_CHAR(pc_prescapitasaltas, '9G999G999G990') pres_capitas_altas,
				  art.cont.get_variacion(pc_realcapitasaltas, pc_prescapitasaltas) var_capitas_altas,
				  TO_CHAR(pc_realcapitasbajas, '9G999G999G990') real_capitas_bajas,
				  TO_CHAR(pc_prescapitasbajas, '9G999G999G990') pres_capitas_bajas,
				  art.cont.get_variacion(pc_realcapitasbajas, pc_prescapitasbajas) var_capitas_bajas
		 FROM tpc_presupuestocartera
 ORDER BY pc_sector DESC";
$stmt = DBExecSql($conn, $sql, $params);
?>
<html>
	<head>
		<link rel="stylesheet" href="../css/style.css" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<title>.:: Tablero de Control Presupuestario | Provincia ART ::.</title>
	</head>
	<body>
		<table align="center" cellpadding="0" cellspacing="0" width="80%">
			<tr>
				<td>
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
				<td height="25"></td>
			</tr>
			<tr>
				<td class="SubTitulo">INDICADORES DE CARTERA</td>
			</tr>
			<tr>
				<td height="15"></td>
			</tr>
<?
while ($row = DBGetQuery($stmt)) {
?>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" align="center" width="95%">
						<tr>
							<td class="encabezadoTabla" colspan="6">>> <?= strtoupper($row["PC_SECTOR"])?></td>
						</tr>					
						<tr>
							<td class="CornerIzqTabla"></td>
							<td class="TituloTablaAlignRight"></td>
							<td class="TituloTablaAlignRight">REAL</td>
							<td class="TituloTablaAlignRight">PRESUPUESTO</td>
							<td class="TituloTablaAlignRight">VARIACIÓN</td>
							<td class="CornerDerTabla"></td>
						</tr>					
						<tr>
							<td class="LateralIzqTabla"></td>
							<td class="TxtTabla"><b>Nº CÁPITAS</b></td>
							<td class="TxtTablaAlignRight"><?= $row["REAL_CAPITAS"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["PRES_CAPITAS"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["VAR_CAPITAS"]?></td>	
							<td class="LateralDerTabla"></td>							
						</tr>
						<tr>
							<td class="LateralIzqTabla"></td>
							<td class="TxtTabla"><b>CUOTA TOTAL</b></td>
							<td class="TxtTablaAlignRight"><?= $row["REAL_CUOTA"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["PRES_CUOTA"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["VAR_CUOTA"]?></td>	
							<td class="LateralDerTabla"></td>
						</tr>
						<tr>
							<td class="LateralIzqTabla"></td>
							<td class="TxtTabla"><b>PRECIO CÁPITA</b></td>
							<td class="TxtTablaAlignRight"><?= $row["REAL_PRECIO_CAPITA"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["PRES_PRECIO_CAPITA"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["VAR_PRECIO_CAPITA"]?></td>	
							<td class="LateralDerTabla"></td>
						</tr>
						<tr>
							<td class="LateralIzqTabla"></td>
							<td class="TxtTabla"><b>SALARIO MEDIO</b></td>
							<td class="TxtTablaAlignRight"><?= $row["REAL_SALARIO_MEDIO"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["PRES_SALARIO_MEDIO"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["VAR_SALARIO_MEDIO"]?></td>	
							<td class="LateralDerTabla"></td>
						</tr>
						<tr>
							<td class="LateralIzqTabla"></td>
							<td class="TxtTabla"><b>ALÍCUOTA PROMEDIO</b></td>
							<td class="TxtTablaAlignRight"><?= $row["REAL_ALICUOTA_PROMEDIO"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["PRES_ALICUOTA_PROMEDIO"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["VAR_ALICUOTA_PROMEDIO"]?></td>	
							<td class="LateralDerTabla"></td>
						</tr>
						<tr>
							<td class="LateralIzqTabla"></td>
							<td class="TxtTabla"><b>CÁPITAS - ALTAS</b></td>
							<td class="TxtTablaAlignRight"><?= $row["REAL_CAPITAS_ALTAS"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["PRES_CAPITAS_ALTAS"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["VAR_CAPITAS_ALTAS"]?></td>	
							<td class="LateralDerTabla"></td>
						</tr>
						<tr>
							<td class="LateralIzqTabla"></td>
							<td class="TxtTabla"><b>CÁPITAS - BAJAS</b></td>
							<td class="TxtTablaAlignRight"><?= $row["REAL_CAPITAS_BAJAS"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["PRES_CAPITAS_BAJAS"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["VAR_CAPITAS_BAJAS"]?></td>	
							<td class="LateralDerTabla"></td>
						</tr>
						<tr>
							<td class="LateralIzqTabla"></td>
							<td colspan="4"></td>
							<td class="LateralDerTabla"></td>
						</tr>
						<tr>
							<td class="CornerInfIzqTabla"></td>
							<td colspan="4" class="PieTabla"></td>
							<td class="CornerInfDerTabla"></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
<?
}
?>
		</table>
	</body>
</html>