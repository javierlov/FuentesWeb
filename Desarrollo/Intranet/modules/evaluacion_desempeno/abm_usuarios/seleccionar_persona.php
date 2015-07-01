<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
?>
<html>
	<head>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<link href="/modules/evaluacion_desempeno/abm_usuarios/css/style.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="/js/functions.js"></script>
		<script type="text/javascript" src="/js/validations.js"></script>
		<script type="text/javascript" src="/modules/evaluacion_desempeno/js/abm.js"></script>
	</head>
	<body>
		<p>
			<label for="usuarios"><?= $_REQUEST["titulo"]?></label>
			<span id="spanComboUsuarios"></span>
		</p>
		<p style="left:120px; position:relative;">
			<input class="BotonBlanco" id="btnAceptar" type="button" value="Aceptar" onClick="aceptar('<?= $_REQUEST["objNombre"]?>', '<?= $_REQUEST["objId"]?>')" />
			<input class="BotonBlanco" id="btnCancelar2" type="button" value="Cancelar" onClick="cancelar()" />
		</p>
		<script>
			document.getElementById('spanComboUsuarios').innerHTML = window.parent.document.getElementById('spanComboUsuarios').innerHTML;
			document.getElementById('usuarios').value = window.parent.document.getElementById('<?= $_REQUEST["objId"]?>').value;
			document.getElementById('usuarios').focus();
		</script>
	</body>
</html>