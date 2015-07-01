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
		return '<img src="images/tabla/'.$imagen.'.gif" title="'.$alt.'">';
}

function setTitulo() {
	if ($_SESSION["tablero"]["periodo"] == "um") {
		$presupuesto = valorSql("SELECT cont.get_presupuestoactual FROM DUAL", "", array());
		$numero = valorSql("SELECT cont.get_ultimomespresupuesto(:presupuesto) FROM DUAL", "", array(":presupuesto" => $presupuesto));
		return "Último Mes (".getMonthName($numero).")";
	}
	if ($_SESSION["tablero"]["periodo"] == "t")
		return "Trimestre ".$_SESSION["tablero"]["trimestre"];
	if ($_SESSION["tablero"]["periodo"] == "m")
		return getMonthName($_SESSION["tablero"]["mes"]);
	if ($_SESSION["tablero"]["periodo"] == "a")
		return "Año";
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
?>
	<script>
		with (window.parent.document){
			getElementById('divGrilla').innerHTML = '<span style="color:red;">Usted no tiene permiso para ver datos dentro del Sistema de Información Ejecutiva a ningún nivel.</span>';
			getElementById('divProcesando').style.display = 'none';
			body.style.cursor = 'default';
		}
	</script>
<?
	exit;
}
?>
<html>
	<head>
		<script>
			function copiarContenido() {
				with (window.parent.document) {
					mes = parseInt('0<?= $_SESSION["tablero"]["mes"]?>');
					nivel = parseInt('0<?= $_SESSION["tablero"]["nivel"]?>');
					periodo = '<?= $_SESSION["tablero"]["periodo"]?>';
					trimestre = parseInt('0<?= $_SESSION["tablero"]["trimestre"]?>');

					for (i=1; i<=12; i++)
						if (i == mes)
							getElementById('imgMes' + i).src = 'images/submenu/mes_' + i + '_a.gif';
						else
							getElementById('imgMes' + i).src = 'images/submenu/mes_' + i + '.gif';

					for (i=1; i<=5; i++)
						if (i == nivel) {
							if (getElementById('imgNivel' + i) != null)
								getElementById('imgNivel' + i).src = 'images/detalle/det_' + i + '_si.gif';
						}
						else
							if (getElementById('imgNivel' + i) != null)
								getElementById('imgNivel' + i).src = 'images/detalle/det_' + i + '.gif';

					for (i=1; i<=4; i++)
						if (i == periodo)
							getElementById('imgPeriodo' + i).style.color = '#00adef';
						else
							getElementById('imgPeriodo' + i).style.color = '';

					for (i=1; i<=4; i++)
						if (i == trimestre)
							getElementById('imgTrimestre' + i).src = 'images/submenu/trimestre_' + i + '_a.gif';
						else
							getElementById('imgTrimestre' + i).src = 'images/submenu/trimestre_' + i + '.gif';

					getElementById('trMes1').style.display = '<?= ($_SESSION["tablero"]["periodo"] == "m")?"inline":"none"?>';
					getElementById('trMes2').style.display = '<?= ($_SESSION["tablero"]["periodo"] == "m")?"inline":"none"?>';
					getElementById('trTrimestre').style.display = '<?= ($_SESSION["tablero"]["periodo"] == "t")?"inline":"none"?>';
				}

				window.parent.document.getElementById('divGrilla').innerHTML = document.getElementById('divGrillaTmp').innerHTML;
				window.parent.document.getElementById('divProcesando').style.display = 'none';
				window.parent.document.body.style.cursor = 'default';
			}
		</script>
	</head>
	<body onLoad="copiarContenido()">
		<div id="divGrillaTmp">
			<table cellpadding="0" cellspacing="0" align="center" width="80%">
				<tr>
					<td colspan="2">
						<table cellpadding="0" cellspacing="0" width="700" align="center">
							<tr>
								<td colspan="8"><span class="encabezadoTabla" id="spanTitulo">>> <?= setTitulo()?> - en miles de pesos</span></td>
							</tr>
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


$params = array(":nivel" => $_SESSION["tablero"]["nivel"],
								":numero" => $numero,
								":periodo" => $periodo,
								":presupuesto" => $presupuesto);
$sql =
	"SELECT concepto,
					TO_CHAR(REAL / 1000, '$9g999g999g990') REAL, TO_CHAR(presupuestado / 1000, '$9g999g999g990') presupuestado,
					DECODE(REAL, 0, '-- ', DECODE(presupuestado, 0, '-- ', TO_CHAR(ROUND((REAL - presupuestado) /(ABS(presupuestado) + 1), 2) * 100, '9g999g999g990'))) || '%' variacion,
					cont.get_semaforoconcepto(pc_id,(REAL - presupuestado) /(ABS(presupuestado) + 1), :periodo, :numero) semaforo,
					cont.get_moduloindicadorpresupuesto(pc_id) detalle
		 FROM (SELECT LPAD(' ', 1 + (pc_nivel * 24), '&nbsp;') || DECODE(pc_nivel, 0, '<b>') || pc_descripcion || DECODE(pc_nivel, 0, '</b>') concepto, pc_id,
									cont.get_importeconceptopresupuesto(:presupuesto, pc_id, :periodo, :numero) presupuestado, cont.get_importeconceptoreal(:presupuesto, pc_id, :periodo, :numero) REAL
						 FROM opc_presupuestoconcepto
						WHERE pc_fechabaja IS NULL
							AND pc_nivel <= :nivel
				 ORDER BY pc_orden)";
$stmt = DBExecSql($conn, $sql, $params);
while ($row = DBGetQuery($stmt)) {
?>
							<tr>
								<td class="LateralIzqTabla"></td>
								<td class="TxtTabla"><?= htmlspecialchars_decode($row["CONCEPTO"], ENT_QUOTES)?></td>
								<td class="TxtTablaAlignRight"><?= $row["REAL"]?></td>
								<td class="TxtTablaAlignRight"><?= $row["PRESUPUESTADO"]?></td>
								<td class="TxtTablaAlignRight"><?= $row["VARIACION"]?></td>
								<td><?= setImagenGrilla($row["SEMAFORO"])?></td>
								<td><a href="/modules/control_gestion/tablero_de_control/tdc_presupuestario/detalles/<?= $row["DETALLE"]?>.php" target="_blank"><img src="images/tabla/VerMas.gif" style="display:<?= ($row["DETALLE"] == "")?"none":"block"?>;" title="Ver Más" onMouseOut="javascript:this.src='images/tabla/VerMas.gif'" onMouseOver="javascript:this.src='images/tabla/VerMas_a.gif'"></a></td>
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