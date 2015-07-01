<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isPreventor"]));
?>
<script type="text/javascript">
function abrirVentanaOtrosFormularios() {
	var height = 368;
	var width = 720;
	var left = (screen.width - width) / 2;
	var top = ((screen.height - height) / 2) - window.screenTop;

	divWinEmpresa = null;
	divWinEmpresa = dhtmlwindow.open('divBoxFormularios', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWinEmpresa.load('iframe', '/modules/usuarios_registrados/preventores/otros_formularios.php', 'Otros Formularios');
	divWinEmpresa.show();
}
</script>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/preventores/resultado_impresion_busqueda.php" id="formEstablecimientos" method="post" name="formEstablecimientos" target="iframeProcesando">
	<div align="center" class="TituloSeccion">Impresión de Formularios</div>
	<div align="center" id="divContentGrid" name="divContentGrid" style="height:360px; left:0px; top:40px; width:736px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
</form>
<p style="position:relative; top:72px;">
	<input class="btnOtrosFormularios" type="button" value="" onClick="abrirVentanaOtrosFormularios()" />
	<input class="btnVolver" type="button" value="" onClick="window.location.href='/index.php?pageid=90'" />
</p>
<script type="text/javascript">
	document.getElementById('formEstablecimientos').submit();
</script>