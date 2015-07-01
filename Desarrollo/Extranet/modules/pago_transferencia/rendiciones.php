<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/pago_transferencia/get_grid_rendiciones.php");
?>
<html>
	<head>
		<link href="css/style.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<div align="center" id="divContent" name="divContent">
<?
echo getGrid();
?>
		</div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<script type="text/javascript">
			function CopyContent() {
				try {
					window.parent.document.getElementById('divContent').innerHTML = document.getElementById('divContent').innerHTML;
				}
				catch(err) {
					//
				}
			}
			CopyContent();
		</script>
	</body>
</html>