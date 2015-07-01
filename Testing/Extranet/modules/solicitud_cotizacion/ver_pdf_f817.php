<?
validarSesion(isset($_SESSION["isAgenteComercial"]));
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<div class="TituloSeccion" style="display:block; margin-bottom:8px; width:730px;">Cotizaciones y Afiliaciones - Formulario 817</div>
<iframe id="iframeProcesando" name="iframeProcesando" src="/modules/solicitud_cotizacion/reporte_formulario_817.php?id=<?= $_REQUEST["id"]?>" style="height:376px; width:740px;"></iframe>
<div style="margin-top:8px;"><input class="btnVolver" type="button" value="" onClick="history.back();" /></div>