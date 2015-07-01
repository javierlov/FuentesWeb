<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion($_SESSION["comisiones"]);

SetDateFormatOracle("DD/MM/YYYY");

$arr = explode("_", $_REQUEST["id"]);

$file = "/modules/usuarios_registrados/agentes_comerciales/comisiones/";
if ($arr[0] == "G")
	$file.= "reporte_afip";
elseif ($arr[0] == "IB") {
	if (file_exists($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/agentes_comerciales/comisiones/reporte_ingresos_brutos_".$arr[1].".php"))
		$file.= "reporte_ingresos_brutos_".$arr[1];
	else
		$file.= "reporte_ingresos_brutos_generico";
}
elseif ($arr[0] == "OS") {
	$file.= "reporte_obra_social";
}
$file.= ".php?id=".$arr[2]."&p=".$arr[1]."&idil=".$arr[3];
?>
<script type="text/javascript">
	window.open('<?= $file?>', 'extranetWindow', 'location=0');
</script>