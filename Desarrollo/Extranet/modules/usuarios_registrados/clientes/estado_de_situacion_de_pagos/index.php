<?
validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 58));

require_once("index_combos.php");
?>
<script src="/modules/usuarios_registrados/clientes/js/estado_situacion_pagos.js" type="text/javascript"></script>
<div class="TituloSeccion" style="display:block; width:736px;">Estado de Situación de Pagos</div>
<div class="ContenidoSeccion" align=right style="margin-top:5px;"><i>>> <a href="/estado-situacion-pagos/terminos-y-condiciones">Términos y Condiciones de uso</a></i></div>
<div class="ContenidoSeccion" style="margin-top:8px;">	
	<div>Seleccione el período y obtenga el reporte del estado de situación de pagos.</div>
	<div style="margin-bottom:16px; margin-top:8px;">
		<label for="periodo">Período</label>
		<?= $comboPeriodo->draw();?>
		<img id="btnGenerarPdf" src="/modules/usuarios_registrados/images/ver_pdf.jpg" style="cursor:pointer; margin-left:16px; vertical-align:-4px;" onClick="generarEstadoSituacionPago()" />
	</div>
	<iframe id="iframePdf" name="iframePdf" src="" style="display:none; height:312px; width:732px;"></iframe>
</div>