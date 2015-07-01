<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/evaluacion_puesto/descripcion_de_puesto/get_grid.php");


if (!isset($_SESSION["idEvaluado"])) {
	echo "<div style='color:#f00'>La sesión ha caducado, vuelva a loguearse por favor.</div>";
	exit;
}
?>
<html>
	<head>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<script language="JavaScript" src="js/tareas.js"></script>
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