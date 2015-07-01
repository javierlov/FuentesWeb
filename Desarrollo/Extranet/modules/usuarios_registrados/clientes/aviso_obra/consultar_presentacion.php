<?
validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 95));

$titulo = "";
switch ($_REQUEST["t"]) {
	case "e":
		$titulo = "Extender una Obra";
		break;
	case "m":
		$titulo = "Modificar un Aviso de Obra";
		break;
	case "n":
		$titulo = "Nuevo Aviso";
		break;
	case "p":
		$titulo = "Consultar Presentaciones";
		break;
	case "s":
		$titulo = "Suspender una Obra";
		break;
	case "sd":
		$titulo = "Suspensión Definitiva de Obra";
		break;
}

if (!isset($_SESSION["BUSQUEDA_AVISOS_OBRA"]))
	$_SESSION["BUSQUEDA_AVISOS_OBRA"] = array("buscar" => "S",
																						"ob" => (($_REQUEST["t"]) == "p")?3:2,
																						"pagina" => 1);
?>
<script type="text/javascript">
	function submitForm() {
		document.getElementById('divContentGrid').style.display = 'none';
		document.getElementById('divProcesando').style.display = 'block';
		document.getElementById('formAvisoObra').submit();
	}
</script>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/aviso_obra/consultar_presentacion_busqueda.php" id="formAvisoObra" method="post" name="formAvisoObra" target="iframeProcesando">
	<input id="t" name="t" type="hidden" value="<?= $_REQUEST["t"]?>" />
	<div class="TituloSeccion" style="display:block; width:728px;">Aviso de Obra - <?= $titulo?></div>
	<div class="ContenidoSeccion" style="margin-top:12px;"></div>
	<div align="center" id="divContentGrid" name="divContentGrid" style="height:100%; margin-left:10px; margin-top:8px; overflow:auto; width:720px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:32px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	<div style="margin-top:8px;"><input class="btnVolver" type="button" value="" onClick="history.back(-1);" /></div>
</form>
<script>
	submitForm();
</script>