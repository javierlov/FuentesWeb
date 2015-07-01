<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
?>
<html>
	<head>
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>
	</head>
	<body>
		<script>
			window.parent.mostrarUsuariosDadosDeBaja = !<?= $_REQUEST["std"]?>;

			if (window.parent.mostrarUsuariosDadosDeBaja)
				window.parent.document.getElementById('ocultarMostrar').innerHTML = 'Ocultar usuarios dados de baja';
			else
				window.parent.document.getElementById('ocultarMostrar').innerHTML = 'Mostrar usuarios dados de baja';
<?
if ($_REQUEST["std"] == "true")
	$baja = "AND se_fechabaja IS NULL";
else
	$baja = "";


// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window.parent";

$RCfield = "usuario";
$RCparams = array();
$RCquery = 
	"SELECT se_usuario ID, se_usuario detalle
		FROM use_usuarios
	 WHERE se_usuariogenerico = 'N'
				".$baja."
 ORDER BY 2";
$RCselectedItem = -1;
FillCombo(false);
?>

			window.parent.document.getElementById('spanLoading').style.visibility = 'hidden';
		</script>
	</body>
</html>