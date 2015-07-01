<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s"). " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
?>
<html>
	<head>
		<link rel="stylesheet" href="/modules/solicitud_cotizacion/css/grid.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<script src="/js/grid.js" type="text/javascript"></script>
		<script type="text/javascript">
			function submitForm(frm) {
				document.getElementById('divContentGrid').style.display = 'none';
				document.getElementById('divProcesando').style.display = 'block';
			}
		</script>
	</head>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/solicitud_cotizacion/buscar_ciiu_busqueda.php" id="formBuscarCiiu" method="post" name="formBuscarCiiu" target="iframeProcesando" onSubmit="submitForm(this)">
			<input id="trgt" name="trgt" type="hidden" value="<?= $_REQUEST["trgt"]?>" />
			<div style="margin-left:16px;">
				<div style="margin-top:8px;">
					<label for="codigo" style="margin-right:24px;"><font face="Trebuchet MS" style="font-size: 8pt">Código</font></label>
					<input id="codigo" maxlength="10" name="codigo" type="text" value="" />
				</div>
				<div>
					<label for="descripcion"><font face="Trebuchet MS" style="font-size: 8pt">Descripción</font></label>
					<input id="descripcion" maxlength="128" name="descripcion" style="width:440px;" type="text" value="" />
				</div>
				<div style="margin-left:67px; margin-top:8px;">
					<input class="btnBuscar" type="submit" value="" />
				</div>
			</div>
		</form>
		<div align="center" id="divContentGrid" name="divContentGrid"></div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<script type="text/javascript">
			document.getElementById('codigo').focus();
		</script>
	</body>
</html>