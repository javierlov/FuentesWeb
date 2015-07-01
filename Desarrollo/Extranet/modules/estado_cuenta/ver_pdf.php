<?
validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion((validarContrato($_REQUEST["id"])));


switch ($_REQUEST["rpt"]) {
	case "ec":
		$file = "reporte_estado_cuenta.php";
		break;
	case "f801c":
		$file = "reporte_formulario_801c.php";
		break;
	case "f817":
		$file = "reporte_formulario_817.php";
		break;
}
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<div class="TituloSeccion" style="display:block; margin-bottom:8px; width:730px;">Estado de Cuenta</div>
<iframe id="iframeProcesando" name="iframeProcesando" src="/modules/estado_cuenta/<?= $file?>?id=<?= $_REQUEST["id"]?>" style="height:372px; width:740px;"></iframe>
<div style="margin-right:8px; margin-top:8px;">
	<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
</div>