<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");

function subirArchivo($arch, $folder, $filename, $extensionesPermitidas, &$error, &$ext) {
	$tmpfile = $arch["tmp_name"];
	$partes_ruta = pathinfo(strtolower($arch["name"]));
	$ext = $partes_ruta["extension"];

	if (!in_array($ext, $extensionesPermitidas)) {
		$error = "El archivo debe tener alguna de las siguientes extensiones: ".implode(" o ", $extensionesPermitidas).".";
		return false;
	}

	$filename = stringToLower($filename.".".$ext);

	if (!is_uploaded_file($tmpfile)) {
		$error = "El archivo no subió correctamente.";
		return false;
	}

	if (!move_uploaded_file($tmpfile, $folder.$filename)) {
		$error = "El archivo no pudo ser guardado.";
		return false;
	}

	return true;
}


$extension = "";
$filename = "";
$mostrarImagen = false;
if ((isset($_REQUEST["cargar"])) and ($_REQUEST["cargar"] == "t")) {
	if (!isFechaValida($_POST["fecha"], false))
		echo "<span style='color:#f00;'>Debe ingresar una fecha válida.</span>";
	elseif (!isset($_REQUEST["tipoSello"]))
		echo "<span style='color:#f00;'>Debe indicar el tipo de sello.</span>";
	elseif ($_FILES["imagen"]["name"] == "")
		echo "<span style='color:#f00;'>Debe seleccionar un archivo.</span>";
	else {
		$error = "";
		$filename = date("Ymdhmi")."_".getWindowsLoginName();
		if (!subirArchivo($_FILES["imagen"], DATA_AVISO_OBRA_PATH, $filename, array("jpg", "jpeg", "pdf", "png"), $error, $extension))
			echo "<span style='color:#f00;'>".$error."</span>";
		else		// Si entra acá es porque pasó todas las validaciones..
			$mostrarImagen = true;
	}
}


$fecha = date("d/m/Y");
if (isset($_REQUEST["fecha"]))
	$fecha = $_REQUEST["fecha"];

$tipoSello = "";
if (isset($_REQUEST["tipoSello"]))
	$tipoSello = $_REQUEST["tipoSello"];

$x = 0;
if ((isset($_REQUEST["x"])) and ($_REQUEST["x"] > 0))
	$x = $_REQUEST["x"];
elseif (isset($_COOKIE["AvisoDeObra_X"]))
	$x = $_COOKIE["AvisoDeObra_X"];

$y = 0;
if ((isset($_REQUEST["y"])) and ($_REQUEST["y"] > 0))
	$y = $_REQUEST["y"];
elseif (isset($_COOKIE["AvisoDeObra_Y"]))
	$y = $_COOKIE["AvisoDeObra_Y"];
?>
<style>
	label {display:inline-block; text-align:right; width:104px;}
</style>
<script>
	function generar(accion) {
		with (document) {
<?
if ($mostrarImagen) {
?>
			getElementById('iframePdf').src = '/modules/aviso_de_obra/generar_sello.php?rnd=' + Math.random() + '&accion=' + accion + '&filename=<?= $filename?>&extension=<?= $extension?>&x=' + getElementById('x').value + '&y=' + getElementById('y').value + '&fecha=<?= $fecha?>&tipoSello=<?= $tipoSello?>#toolbar=0&navpanes=0&scrollbar=0';
<?
}
?>
		}
	}

	function moverCuadro(accion) {
//		var coorX = _x_en_div;
//		var coorY = _y_en_div;
		var coorX = event.offsetX + document.body.scrollLeft;
		var coorY = event.offsetY + document.body.scrollTop;

		with (document) {
			if (clickEnCuadro) {
				coorX+= parseInt(getElementById('cuadro').style.left);
				coorY+= parseInt(getElementById('cuadro').style.top);
			}

			getElementById('cuadro').style.left = (coorX - 30) + 'px';
			getElementById('cuadro').style.top = (coorY - 25) + 'px';
			getElementById('x').value = coorX - 30;
			getElementById('y').value = coorY - 25;

			clickEnCuadro = false;

			generar(accion);
		}
	}

	var clickEnCuadro = false;
</script>
<div style="height:412px; left:0px; position:relative; top:0px;">
	<div style="height:412px; left:0px; position:relative; top:0px; width:420px;">
		<form action="<?= $_SERVER["PHP_SELF"]?>" enctype="multipart/form-data" id="formAvisoObra" method="post" name="formAvisoObra">
			<input id="cargar" name="cargar" type="hidden" value="t" />
			<input id="pageid" name="pageid" type="hidden" value="42" />
			<input id="x" name="x" type="hidden" value="<?= $x?>" />
			<input id="y" name="y" type="hidden" value="<?= $y?>" />
			<div class="fila">
				<label>Fecha</label>
				<input class="fecha" id="fecha" maxlength="10" name="fecha" type="text" value="<?= $fecha?>" />
				<input class="botonFecha" id="btnFecha" name="btnFecha" type="button" value="" />
			</div>
			<div class="fila">
				<label>Extendido</label>
				<input <?= ($tipoSello=="e")?"checked":""?> id="tipoSello" name="tipoSello" type="radio" value="e" />
			</div>
			<div class="fila">
				<label>No corresponde</label>
				<input <?= ($tipoSello=="n")?"checked":""?> id="tipoSello" name="tipoSello" type="radio" value="n" />
			</div>
			<div class="fila">
				<label>Rechazado</label>
				<input <?= ($tipoSello=="h")?"checked":""?> id="tipoSello" name="tipoSello" type="radio" value="h" />
			</div>
			<div class="fila">
				<label>Recibido</label>
				<input <?= ($tipoSello=="i")?"checked":""?> id="tipoSello" name="tipoSello" type="radio" value="i" />
			</div>
			<div class="fila">
				<label>Suspendido</label>
				<input <?= ($tipoSello=="s")?"checked":""?> id="tipoSello" name="tipoSello" type="radio" value="s" />
			</div>
			<div class="fila">
				<label>Imagen</label>
				<input id="imagen" name="imagen" type="file" value="" />
			</div>
			<div class="fila">
				<input type="submit" value="GENERAR PDF" />
			</div>
<?
if ($mostrarImagen) {
?>
			<div class="fila">
				<input id="btnGuardar" type="button" onClick="generar('g')" />
				<input id="btnImprimir" type="button" onClick="generar('i')" />
			</div>
<?
}
?>
			<div style="background-color:#e7e7e7; border:1px solid #807f84; margin-top:32px; padding:2px; width:272px;">Haga un clic en el área pintada de celeste para ir colocando el sello en el pdf.</div>
		</form>
	</div>
<?
if ($mostrarImagen) {
?>
	<div id="panelCeleste" style="background-color:#84ddff; cursor:crosshair; height:412px; left:288px; position:relative; top:-412px; width:292px;" onClick="moverCuadro('v')">
		<div id="cuadro" style="background-color:#000; height:52px; left:<?= $x?>px; position:relative; top:<?= $y?>px; width:64px;" onClick="clickEnCuadro = true;">
			<img border="0" src="/modules/aviso_de_obra/images/sello.jpg" />
		</div>
	</div>
	<div style="left:472px; position:relative; top:-824px; width:4px;">&nbsp;</div>
	<div style="height:412px; left:620px; position:relative; top:-841px; width:292px;">
		<iframe frameborder="0" id="iframePdf" name="iframePdf" src="" style="height:412px; width:292px;"></iframe>
	</div>
	<div>
<?
}
?>
</div>
<script>
	Calendar.setup ({
		inputField: "fecha",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFecha"
	});

	generar('v');
</script>