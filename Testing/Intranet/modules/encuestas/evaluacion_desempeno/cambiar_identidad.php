<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


$permisoCambioIdentidad = array("ALAPACO", "GLANCHA", "VDOMINGUEZ", "VLOPEZ");
if (!in_array(GetWindowsLoginName(true), $permisoCambioIdentidad))
	exit;
	
if (isset($_POST["procesar"])) {
	$_SESSION["identidad"] = $_POST["usuario"];
	echo "<script>parent.divWin.close(); parent.window.location.reload();</script>";
	exit;
}
?>
<html>
	<head>
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta http-equiv="Expires" content="0" />
		<meta http-equiv="Pragma" content="no-cache" />
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>
		<script>
			function ocultarMostrarClick() {
				document.getElementById('spanLoading').style.visibility = 'visible';
				document.getElementById('iframeTmp').src = 'mostrar_usuarios_dados_de_baja.php?std=' + mostrarUsuariosDadosDeBaja;
			}

			var mostrarUsuariosDadosDeBaja = false;
		</script>
	</head>
	<body>
		<iframe id="iframeTmp" name="iframeTmp" src="" style="display:none;"></iframe>
		<form action="<?= $_SERVER["PHP_SELF"]?>" id="formUsuarios" method="post" name="formUsuarios">
			<input id="procesar" name="procesar" type="hidden" value="t" />
			<span>Seleccione un usuario</span>
			<select class="Combo" id="usuario" name="usuario" size="10" style="width:200px;"></select>
			<br />
			<a href="#" id="ocultarMostrar" onClick="ocultarMostrarClick()">Mostrar usuarios dados de baja</a>
			<br /><br />
			<input class="BotonBlanco" id="btnAceptar" name="btnAceptar" type="submit" value="Aceptar">
			<span id="spanLoading" style="visibility:hidden; margin-left:40px" />Refrescando...</span>
		</form>
		<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "usuario";
$RCparams = array();
$RCquery = 
	"SELECT se_usuario id, se_usuario detalle
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuariogenerico = 'N'
 ORDER BY 2";
$RCselectedItem = -1;
FillCombo(false);
?>
			document.getElementById('usuario').focus();
		</script>
	</body>
</html>