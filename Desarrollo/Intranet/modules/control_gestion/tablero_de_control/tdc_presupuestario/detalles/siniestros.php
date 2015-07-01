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


$presupuesto = valorSql("SELECT cont.get_presupuestoactual FROM DUAL", "", array());

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
				<td class="SubTitulo">SINIESTROS DEVENGADOS</td>
			</tr>
			<tr>
				<td height="15"></td>
			</tr>
			<tr>
				<td>
					<table align="center" cellpadding="0" cellspacing="0" width="95%">
						<tr>
							<td class="encabezadoTabla" colspan="6">>> SINIESTROS PAGADOS</td>
						</tr>					
						<tr>
							<td class="CornerIzqTabla"></td>
							<td class="TituloTablaAlignRight"></td>
							<td class="TituloTablaAlignRight">REAL</td>
							<td class="TituloTablaAlignRight">PRESUPUESTO</td>
							<td class="TituloTablaAlignRight">VARIACIÓN</td>
							<td class="CornerDerTabla"></td>
						</tr>					
<?
$params = array(":numero" => $numero,
								":periodo" => $periodo,
								":presupuesto" => $presupuesto);
$sql =
	"SELECT concepto,
					TRIM(TO_CHAR(REAL / 1000, '$999G999G990')) REAL,
					TRIM(TO_CHAR(presupuestado / 1000, '$999G999G990')) presupuestado,
					TRIM(DECODE(REAL, 0, '-- ', DECODE(presupuestado, 0, '-- ', TO_CHAR(ROUND((REAL - presupuestado) /(ABS(presupuestado) + 1), 2) * 100, '999G990')))) || '%' variacion,
					art.cont.get_semaforoconcepto(pc_id,(REAL - presupuestado) /(ABS(presupuestado) + 1), :periodo, :numero) semaforo
		 FROM (SELECT pc_descripcion concepto, pc_id, art.cont.get_importeconceptopresupuesto(:presupuesto, pc_id, :periodo, :numero) presupuestado,
									art.cont.get_importeconceptoreal(:presupuesto, pc_id, :periodo, :numero) REAL
						 FROM opc_presupuestoconcepto
						WHERE pc_fechabaja IS NULL
							AND pc_nivel = 4
							AND pc_id <= 18
			 START WITH pc_id = 10
 CONNECT BY PRIOR pc_id = pc_sumaen
				 ORDER BY pc_orden)";
$stmt = DBExecSql($conn, $sql, $params);
while ($row = DBGetQuery($stmt)) {
?>
						<tr>
							<td class="LateralIzqTabla"></td>
							<td class="TxtTabla"><?= $row["CONCEPTO"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["REAL"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["PRESUPUESTADO"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["VARIACION"]?></td>	
							<td class="LateralDerTabla"><?= $row["SEMAFORO"]?></td>
						</tr>
<?
}
?>
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
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" align="center" width="95%">
						<tr>
							<td class="encabezadoTabla" colspan="6">>> VARIACIÓN DE RESERVAS</td>
						</tr>					
						<tr>
							<td class="CornerIzqTabla"></td>
							<td class="TituloTablaAlignRight"></td>
							<td class="TituloTablaAlignRight">REAL</td>
							<td class="TituloTablaAlignRight">PRESUPUESTO</td>
							<td class="TituloTablaAlignRight">VARIACIÓN</td>
							<td class="CornerDerTabla"></td>
						</tr>					
<?
$params = array(":numero" => $numero,
								":periodo" => $periodo,
								":presupuesto" => $presupuesto);
$sql =
	"SELECT concepto,
					TRIM(TO_CHAR(REAL / 1000, '$999G999G990')) REAL,
					TRIM(TO_CHAR(presupuestado / 1000, '$999G999G990')) presupuestado,
					TRIM(DECODE(REAL, 0, '-- ', DECODE(presupuestado, 0, '-- ', TO_CHAR(ROUND((REAL - presupuestado) /(ABS(presupuestado) + 1), 2) * 100, '999G990')))) || '%' variacion,
					art.cont.get_semaforoconcepto(pc_id,(REAL - presupuestado) /(ABS(presupuestado) + 1), :periodo, :numero) semaforo
		 FROM (SELECT pc_descripcion concepto, pc_id, art.cont.get_importeconceptopresupuesto(:presupuesto, pc_id, :periodo, :numero) presupuestado,
									art.cont.get_importeconceptoreal(:presupuesto, pc_id, :periodo, :numero) REAL
						 FROM opc_presupuestoconcepto
						WHERE pc_fechabaja IS NULL
							AND pc_nivel = 3
							AND pc_id <= 25
			 START WITH pc_id = 20
 CONNECT BY PRIOR pc_id = pc_sumaen
				 ORDER BY pc_orden)";
$stmt = DBExecSql($conn, $sql, $params);
while ($row = DBGetQuery($stmt)) {
?>
						<tr>
							<td class="LateralIzqTabla"></td>
							<td class="TxtTabla"><?= $row["CONCEPTO"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["REAL"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["PRESUPUESTADO"]?></td>
							<td class="TxtTablaAlignRight"><?= $row["VARIACION"]?></td>	
							<td class="LateralDerTabla"><?= $row["SEMAFORO"]?></td>
						</tr>
<?
}
?>
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
		</table>
	</body>
</html>