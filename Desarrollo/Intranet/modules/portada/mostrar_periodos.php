<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


$ano = $_REQUEST["a"];
$mes = $_REQUEST["m"] - 3;
if ($mes < 1) {
	$mes = 12 + $mes;
	$ano--;
}

$html = "";
for ($i=1; $i<=7; $i++) {
	if ($i <> 4) {
		$js = "onClick=\"cambiarPeriodoCalendario(0, ".($i - 4).")\" onMouseOut=\"cerrarMenuCalendario = true; ocultarPeriodos()\" onMouseOver=\"cerrarMenuCalendario = false;\"";
		$html.= "<div class=\"divPeriodosItem\" ".$js.">".strtoupper(getMonthName($mes))." ".$ano."</div>";
	}
	else
		$html.= "<div style=\"padding-left:20px;\" onMouseOut=\"cerrarMenuCalendario = true; ocultarPeriodos()\" onMouseOver=\"cerrarMenuCalendario = false;\">-</div>";

	$mes++;
	if ($mes > 12) {
		$mes = 1;
		$ano++;
	}
}
?>
<script>
	with (window.parent.document) {
		getElementById('divBusquedaEmpleadoCampo').style.zIndex = '0';
		getElementById('divBusquedaEmpleadoFondo').style.zIndex = '0';
		getElementById('divPeriodos').innerHTML = '<?= $html?>';
		getElementById('divPeriodos').style.display = 'block';
	}
</script>