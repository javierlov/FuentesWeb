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


function setImagenGrilla($color) {
	$alt = "";
	$imagen = "";

	if ($color == "R") {
		$alt = "Variación no satisfactoria";
		$imagen = "rojo";
	}
	if ($color == "A") {
		$alt = "Variación aceptable";
		$imagen = "amarillo";
	}
	if ($color == "V") {
		$alt = "Variación satisfactoria";
		$imagen = "verde";
	}

	if ($color == "")
		return "";
	else
		return '<img src="/modules/control_gestion/tablero_de_control/tdc_presupuestario/images/tabla/'.$imagen.'.gif" title="'.$alt.'">';
}

set_time_limit(120);

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
$nivel = valorSql($sql, 0, $params);
if ($nivel < 1) {
	exit;
}
?>
<html>
	<head>
		<link rel="stylesheet" href="/modules/control_gestion/tablero_de_control/tdc_presupuestario/css/style.css" type="text/css" />
	</head>
	<body>
		<div id="divGrillaTmp">
			<table cellpadding="0" cellspacing="0" align="center" width="80%">
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
					<td class="SubTitulo">GASTOS EXPLOTACIÓN</td>
				</tr>
				<tr>
					<td height="15"></td>
				</tr>
				<tr>
					<td colspan="2">
						<table cellpadding="0" cellspacing="0" width="700" align="center">
							<tr>
								<td colspan="8" height="5"></td>
							</tr>
							<tr>
								<td class="CornerIzqTabla"></td>
								<td class="TituloTabla">CONCEPTO</td>
								<td class="TituloTablaAlignRight">REAL</td>
								<td class="TituloTablaAlignRight">PRESUPUESTO</td>
								<td class="TituloTablaAlignRight">VARIACIÓN</td>
								<td class="TituloTabla" width="20"></td>
								<td class="TituloTabla" width="20"></td>
								<td class="CornerDerTabla"></td>
							</tr>
<?
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


$params = array(":numero" => $numero,
								":periodo" => $periodo,
								":presupuesto" => $presupuesto);
$sql =
	"SELECT concepto,
					TRIM(TO_CHAR(REAL / 1000, '$999G999G990')) REAL,
					TRIM(TO_CHAR(presupuestado / 1000, '$999G999G990')) presupuestado,
					TRIM(DECODE(REAL, 0, '-- ', DECODE(presupuestado, 0, '-- ', TO_CHAR(ROUND((REAL - presupuestado) /(ABS(presupuestado) + 1), 2) * 100, '999G990')))) || '%' variacion,
					cont.get_semaforoconcepto(pc_id,(REAL - presupuestado) /(ABS(presupuestado) + 1), :periodo, :numero) semaforo
		 FROM (SELECT pc_descripcion concepto, pc_id, art.cont.get_importeconceptopresupuesto(:presupuesto, pc_id, :periodo, :numero) presupuestado,
									art.cont.get_importeconceptoreal(:presupuesto, pc_id, :periodo, :numero) REAL
						 FROM opc_presupuestoconcepto
						WHERE pc_fechabaja IS NULL
							AND pc_nivel = 3
							AND pc_id <= 32
			 START WITH pc_id = 28
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
								<td><?= setImagenGrilla($row["SEMAFORO"])?></td>
								<td></td>
								<td class="LateralDerTabla"></td>
							</tr>
<?
}
?>
							<tr>
								<td class="LateralIzqTabla"></td>
								<td colspan="6"></td>
								<td class="LateralDerTabla"></td>
							</tr>
							<tr>
								<td class="CornerInfIzqTabla"></td>
								<td class="PieTabla" colspan="6"></td>
								<td class="CornerInfDerTabla"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>