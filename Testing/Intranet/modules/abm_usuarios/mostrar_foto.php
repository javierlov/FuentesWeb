<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");

validarParametro(isset($_REQUEST["nombrefoto"]));
?>
<html>
	<head>
	<?= GetHead(GetPageTitle(2), array("style.css?today=".date("Ymd")))?>
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta http-equiv="Expires" content="0" />
		<meta http-equiv="Pragma" content="no-cache" />
		<script>
		function ShowPicture() {
			if (window.opener.document.getElementById('NombreFoto').value == '') {
				window.moveTo(20000, 20000);
				window.resizeTo(1, 1);
				if (window.opener.document.getElementById('Nombre').innerText == '')
					alert('Debe seleccionar un usuario primero.')
				else
					alert(window.opener.document.getElementById('Nombre').innerText + ' no tiene ninguna foto cargada.');
				window.close();
			}

			document.getElementById('foto').src = '<?= "/functions/get_image.php?file=".base64_encode(IMAGES_FOTOS_PATH.$_REQUEST["nombrefoto"])?>';
		}
		</script>
	</head>
	<body onLoad="ShowPicture()">
		<table border="0" cellpadding="0" cellspacing="0" id="tableMain" width="100%">
			<tr>
				<td><img border="0" id="foto" name="foto" src="" /></td>
			</tr>
		</table>
	</body>
	<script>
		document.getElementById('foto').onload = function() {
			var height = document.getElementById('foto').height;
			var width = document.getElementById('foto').width;
			window.resizeTo(width + 10, height + 29);
		}
	</script>
</html>