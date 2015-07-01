<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion($_SESSION["comisiones"]);

$file = "/modules/usuarios_registrados/agentes_comerciales/comisiones/reporte_orden_pago.php?id=".$_REQUEST["id"];
?>
<script type="text/javascript">
	window.open('<?= $file?>', 'extranetWindow', 'location=0');
</script>