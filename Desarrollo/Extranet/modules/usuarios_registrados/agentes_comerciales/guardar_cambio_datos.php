<?
session_start();


// Valido los datos..
try {
	if ($_POST["canal"] == -1)
		throw new Exception("Debe seleccionar un canal.");

	if ($_POST["entidad"] == -1)
		throw new Exception("Debe seleccionar una entidad.");
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}

if (($_SESSION["nivel"] == 99) and (isset($_POST["guardar"])) and ($_POST["guardar"] == "t")) {		// Si es true cambio los valores..
	$_SESSION["canal"] = $_POST["canal"];
	$_SESSION["entidad"] = $_POST["entidad"];

	if ($_POST["sucursal"] == -1)
		$_SESSION["sucursal"] = "";
	else
		$_SESSION["sucursal"] = $_POST["sucursal"];

	if ($_POST["vendedor"] == -1)
		$_SESSION["vendedor"] = "";
	else
		$_SESSION["vendedor"] = $_POST["vendedor"];

	$_SESSION["entidadReal"] = $_SESSION["entidad"];
?>
<script type="text/javascript">
	function hideMsg() {
		with (window.parent.document) {
			alto = getElementById('divDatosCambiados').style.height.replace('px', '');
			if (alto > 0) {
				alto-= 1;
				getElementById('divDatosCambiados').style.height = alto + 'px';
				setTimeout('hideMsg()', 70);
			}
		}
	}

	// Actualizo los menues..
	window.parent.document.getElementById('divMenu').innerHTML = window.parent.document.getElementById('divMenu').innerHTML;

	with (window.parent.document.getElementById('divDatosCambiados')) {
		style.display = 'block';
		style.height = '20px';
	}
	setTimeout('hideMsg()', 1500);
</script>
<?
}
?>