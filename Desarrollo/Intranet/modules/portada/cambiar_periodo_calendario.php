<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


if (!isset($_REQUEST["vp"]))
	$_REQUEST["vp"] = "f";

$dia = 1;
$mes = $_REQUEST["m"];
$ano = $_REQUEST["a"];
$date = mktime(0, 0, 0, $mes, $dia, $ano);

// Obtengo el primer día de la semana..
$primerDia = date("N", $date);
if ($primerDia == 7)
	$primerDia = 0;
$date = strtotime("-".$primerDia." day", $date);

// Obtengo los feriados..
$params = array(":fecha" => "01/".$_REQUEST["m"]."/".$_REQUEST["a"]);
$sql =
	"SELECT fe_fecha
		 FROM cfe_feriados
		WHERE fe_fecha BETWEEN TRUNC(TO_DATE(:fecha, 'DD/MM/YYYY'), 'month') AND TRUNC(LAST_DAY(TO_DATE(:fecha, 'DD/MM/YYYY')))
UNION ALL
	 SELECT fd_fecha
		 FROM comunes.cfd_feriadosdelegaciones, del_delegacion
		WHERE fd_iddelegacion = el_id
			AND fd_fechabaja IS NULL
			AND fd_fecha BETWEEN TRUNC(TO_DATE(:fecha, 'DD/MM/YYYY'), 'month') AND TRUNC(LAST_DAY(TO_DATE(:fecha, 'DD/MM/YYYY')))";
$stmt = DBExecSql($conn, $sql, $params);
$fechasFeriado = array();
while ($row = DBGetQuery($stmt))
	$fechasFeriado[] = $row["FE_FECHA"];

// Obtengo los eventos..
$params = array(":fechaevento" => date("d/m/Y", $date));
if ($_REQUEST["vp"] == "t")
	$sql =
		"SELECT DISTINCT cl_fechaevento
			 FROM rrhh.rcl_calendario
			WHERE cl_vistaprevia = 'S'
				AND cl_fechabaja IS NULL
				AND cl_fechaevento BETWEEN TRUNC(TO_DATE(:fechaevento, 'DD/MM/YYYY')) AND TRUNC(TO_DATE(:fechaevento, 'DD/MM/YYYY') + 35)";
else
	$sql =
		"SELECT DISTINCT cl_fechaevento
			 FROM rrhh.rcl_calendario
			WHERE art.actualdate BETWEEN TRUNC(cl_fechavigenciadesde) AND cl_fechavigenciahasta
				AND cl_fechabaja IS NULL
				AND cl_fechaevento BETWEEN TRUNC(TO_DATE(:fechaevento, 'DD/MM/YYYY')) AND TRUNC(TO_DATE(:fechaevento, 'DD/MM/YYYY') + 35)";
$stmt = DBExecSql($conn, $sql, $params);
$fechasEvento = array();
while ($row = DBGetQuery($stmt))
	$fechasEvento[] = $row["CL_FECHAEVENTO"];

$html = '<table align="center" class="tableCalendario"><tr id="divCalendarioTituloDias"><th>DOM</th><th>LUN</th><th>MAR</th><th>MIÉR</th><th>JUE</th><th>VIE</th><th>SÁB</th></tr><tr id="divCalendarioTituloSeparador"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
for ($i=1; $i<=6; $i++) {
	$html.= '<tr>';
	for ($j=1; $j<=7; $j++) {
		$class = "";
		$js = "";
		if (date("m", $date) != $mes)
			$class.= " divCalendarioDiaOtroMes";

		if (date("dmY") == date("dmY", $date))
			$class.= " divCalendarioDiaHoy";

		if (in_array(date("d/m/Y", $date), $fechasEvento)) {
			$class.= " divCalendarioDiaConEvento";
			$js = " onMouseOut=\"resaltarEvento(".date("d", $date).", false)\" onMouseOver=\"resaltarEvento(".date("d", $date).", true)\"";
		}

		if (in_array(date("d/m/Y", $date), $fechasFeriado)) {
			$class.= " divCalendarioDiaFeriado";
			$js = " onMouseOut=\"resaltarFeriado(".date("d", $date).", false)\" onMouseOver=\"resaltarFeriado(".date("d", $date).", true)\"";
		}

		$html.= "<td align=\"center\" class=\"".$class."\" ".$js.">".date("j", $date)."</td>";
		$date = strtotime("+1 day", $date);
	}
	$html.= '</tr>';

	// Este if determina si corresponde dibujar 5 o 6 filas al calendario..
	if (($i > 4) and (date("j", $date) < 10))
		break;
}
$html.= '</table>';
?>
<script>
	with (window.parent.document) {
		getElementById('spanCalendarioPeriodo').innerHTML = '<?= strtoupper(getMonthName($mes))." ".$ano?>';
		getElementById('divTableCalendario').innerHTML = '<?= $html?>';
		getElementById('iframeEventos').src = '/modules/portada/cargar_eventos_calendario.php?m=<?= $mes?>&a=<?= $ano?>&vp=<?= $_REQUEST["vp"]?>';
	}
</script>