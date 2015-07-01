<?
validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 61));
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
<div class="TituloSeccion" style="display:block; margin-bottom:8px; width:730px;">Denuncias de Siniestros</div>
<iframe id="iframeProcesando" name="iframeProcesando" src="/modules/usuarios_registrados/clientes/denuncias_de_siniestros/armar_pdf.php?id=<?= $_REQUEST["id"]?>" style="height:372px; width:740px;"></iframe>
<div style="margin-right:8px; margin-top:8px;"><input class="btnVolver" type="button" value="" onClick="history.back(-1);" /></div>