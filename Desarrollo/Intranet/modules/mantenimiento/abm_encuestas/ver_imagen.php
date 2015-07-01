<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


if ($_REQUEST["tipo"] == "C")
	$path = IMAGES_ENCUESTAS_CABECERA_PATH;
if ($_REQUEST["tipo"] == "O")
	$path = IMAGES_ENCUESTAS_OPCIONES_PATH;
?>
<html>
	<head>
		<?= getHead("Imagen de encuesta", array("style.css", "new_style.css"))?>
		<meta http-equiv="Cache-Control" content="no-cache">
		<meta http-equiv="Expires" content="0">
		<meta http-equiv="Pragma" content="no-cache">
		<script>
			function showPicture() {
				document.getElementById('imagen').src = '<?= "/functions/get_image.php?file=".base64_encode($path.$_REQUEST["file"])?>';
			}
		</script>
	</head>

	<body onLoad="showPicture()">
		<img border="0" id="imagen" src="" />
	</body>

	<script>
		document.getElementById('imagen').onload = function() {
			var height = document.getElementById('imagen').height;
			var width = document.getElementById('imagen').width;
			window.resizeTo(width + 10, height + 29);
		}
	</script>
</html>