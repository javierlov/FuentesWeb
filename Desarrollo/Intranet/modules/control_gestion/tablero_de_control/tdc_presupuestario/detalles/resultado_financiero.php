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

set_time_limit(300);
?>
<html>
	<head>
		<link rel="stylesheet" href="/modules/control_gestion/tablero_de_control/tdc_presupuestario/css/style.css" type="text/css" />
	</head>
	<body>
		<div id="divGrillaTmp">
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
					<td class="SubTitulo">RESULTADO FINANCIERO</td>
				</tr>
				<tr>
					<td height="15"></td>
				</tr>
				<tr>
					<td colspan="2">
						<table align="center" cellpadding="0" cellspacing="0" width="700">
							<tr>
								<td colspan="5" height="5"></td>
							</tr>
							<tr>
								<td class="CornerIzqTabla"></td>
								<td class="TituloTabla">SECTOR</td>
								<td class="TituloTablaAlignRight">MOROSIDAD</td>
								<td class="TituloTablaAlignRight">INCOBRABILIDAD</td>
								<td class="CornerDerTabla"></td>
							</tr>
<?
$presupuesto = valorSql("SELECT cont.get_presupuestoactual FROM DUAL", "", array());

if ($_SESSION["tablero"]["periodo"] == "um") {
	$numero = ValorSql("SELECT cont.get_ultimomespresupuesto(:presupuesto) FROM DUAL", "", array(":presupuesto" => $presupuesto));
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


$params = array(":corte" => $periodo, ":nrocorte" => $numero);
$sql = "SELECT pr_año || LPAD(:nrocorte * :corte, 2, '0') FROM opr_presupuesto WHERE pr_id = 1";
$periodo2 = valorSql($sql, "", $params);

$params = array(":periodo" => $periodo2);
$sql =
	"SELECT sector,
					TO_CHAR((emision - cobranza) / emision * 100, '99990D000') || '%' morosidad,
					TO_CHAR((amortizacion - recupero) / emision * 100, '99990D000') || '%' incobrabilidad
		 FROM (SELECT DECODE(em_sector, '4', 'Privado', 'Público') sector, SUM(sc_devengadocuota) + SUM(sc_devengadootros) emision, SUM(sc_pagocuota) + SUM(sc_pagootros) cobranza,
									SUM(sc_amortizacuota) amortizacion, SUM(sc_recuperocuota) + SUM(sc_recuperootros) recupero
						 FROM osc_saldocontable, aco_contrato, aem_empresa
						WHERE em_id = co_idempresa
							AND co_contrato = sc_contrato
							AND sc_periododist <= :periodo
				 GROUP BY DECODE(em_sector, '4', 'Privado', 'Público'))";
$stmt = DBExecSql($conn, $sql, $params);
while ($row = DBGetQuery($stmt)) {
?>
							<tr>
								<td class="LateralIzqTabla"></td>
								<td class="TxtTabla"><?= $row["SECTOR"]?></td>
								<td class="TxtTablaAlignRight"><?= $row["MOROSIDAD"]?></td>
								<td class="TxtTablaAlignRight"><?= $row["INCOBRABILIDAD"]?></td>
								<td class="LateralDerTabla"></td>
							</tr>
<?
}
?>
							<tr>
								<td class="LateralIzqTabla"></td>
								<td colspan="3"></td>
								<td class="LateralDerTabla"></td>
							</tr>
							<tr>
								<td class="CornerInfIzqTabla"></td>
								<td class="PieTabla" colspan="3"></td>
								<td class="CornerInfDerTabla"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>