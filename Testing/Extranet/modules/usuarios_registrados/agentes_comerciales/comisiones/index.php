<?
validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion($_SESSION["comisiones"]);
?>
<script src="/modules/usuarios_registrados/agentes_comerciales/comisiones/js/comisiones.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<div class="TituloSeccion" style="display:block; width:730px;">Comisiones</div>
<div class="ContenidoSeccion" style="margin-top:10px;">Aqu� encontrar� las liquidaciones y facturas relacionadas de sus comisiones actuales e hist�ricas, utilizando los filtros correspondientes.</div>
<div align="center" style="margin-top:64px; position:relative;">
	<input class="btnCelesteGrande" type="button" value="LIQUIDACIONES" onClick="window.location.href = '/index.php?pageid=87';" />
	<input class="btnCelesteGrande" style="margin-left:40px;" type="button" value="FACTURAS" onClick="window.location.href = '/index.php?pageid=86';" />
</div>
<div id="banner1HomePage" style="height:110px; left:0px; position:absolute; top:320px; width:240px;">
	<embed height="110" name="obj1" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner1.swf" type="application/x-shockwave-flash" width="240">
</div>
<div id="banner2HomePage" style="height:110px; left:246px; position:absolute; top:320px; width:240px;">
	<embed height="110" name="obj2" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner2.swf" type="application/x-shockwave-flash" width="240">
</div>
<div id="banner3HomePage" style="height:110px; left:508px; position:absolute; top:320px; width:240px;">
	<embed height="110" name="obj3" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner3.swf" type="application/x-shockwave-flash" width="240">
</div>