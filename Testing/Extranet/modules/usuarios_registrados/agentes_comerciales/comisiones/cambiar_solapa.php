<?
$idLiq = -1;
$idVen = -1;

if ($_REQUEST["s"] == "m") {
	$arr = explode("_", $_REQUEST["id"]);
	$idLiq = $arr[0];

	if (isset($arr[1]))
		$idVen = $arr[1];
}
?>
<script src="/modules/usuarios_registrados/agentes_comerciales/comisiones/js/comisiones.js" type="text/javascript"></script>
<script type="text/javascript">
	id = '<?= $_REQUEST["id"]?>';
	solapa = '<?= $_REQUEST["s"]?>';

	cambiarSolapa(solapa);

	if (solapa == 'm') {
		id = <?= $idLiq?>;
		idVen = <?= $idVen?>;
		top.iframeProcesando.location.href = '/modules/usuarios_registrados/agentes_comerciales/comisiones/cargar_movimientos.php?id=' + id + '&idven=' + idVen;
	}

	if (solapa == 'p') {
		top.iframeProcesando.location.href = '/modules/usuarios_registrados/agentes_comerciales/comisiones/cargar_pendientes.php?id=-1';
<?
if ((isset($_REQUEST["s2"])) and ($_REQUEST["s2"] == "l")) {
?>
	cambiarSolapa('l');
<?
}
?>
	}

	if (solapa == 'r')
		top.iframeProcesando.location.href = '/modules/usuarios_registrados/agentes_comerciales/comisiones/cargar_retenciones.php?id=' + id;

	if (solapa == 'v')
		top.iframeProcesando.location.href = '/modules/usuarios_registrados/agentes_comerciales/comisiones/cargar_vendedores.php?id=' + id;
</script>