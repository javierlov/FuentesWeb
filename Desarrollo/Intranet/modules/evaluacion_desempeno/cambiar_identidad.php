<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once("cambiar_identidad_combos.php");


$permisoCambioIdentidad = array("ALAPACO", "EVILA", "JBALESTRINI");
if (!in_array(getWindowsLoginName(true), $permisoCambioIdentidad))
	exit;
	
if (isset($_POST["procesar"])) {
	$_SESSION["identidad"] = $_POST["usuario"];
	echo "<script>parent.divWin.close(); parent.window.location.reload();</script>";
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta http-equiv="Expires" content="0" />
		<meta http-equiv="Pragma" content="no-cache" />

		<link href="/modules/evaluacion_desempeno/css/evaluacion_desempeno.css" rel="stylesheet" type="text/css" />

<!--		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>-->
		<script>
			function ocultarMostrarClick() {
				document.getElementById('iframeTmp').src = '/modules/evaluacion_desempeno/mostrar_usuarios_dados_de_baja.php?std=' + mostrarUsuariosDadosDeBaja;
			}

			var mostrarUsuariosDadosDeBaja = false;
		</script>
	</head>
	<body>
		<iframe id="iframeTmp" name="iframeTmp" src="" style="display:none;"></iframe>

		<form action="<?= $_SERVER["PHP_SELF"]?>" id="formUsuarios" method="post" name="formUsuarios">
			<input id="procesar" name="procesar" type="hidden" value="t" />
			<span>Seleccione un usuario</span>
			<?= $comboUsuario->draw();?>
<!--			<br />
			<a href="#" id="ocultarMostrar" onClick="ocultarMostrarClick()">Mostrar usuarios dados de baja</a>-->
			<br /><br />
			<input id="btnAceptar" name="btnAceptar" type="submit" value="Aceptar" />
		</form>

		<script>
			document.getElementById('usuario').size = 10;
		</script>
	</body>
</html>