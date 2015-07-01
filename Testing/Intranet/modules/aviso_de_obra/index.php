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

	$filename = StringToLower($filename.".".$ext);

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
		$filename = date("Ymdhmi")."_".GetWindowsLoginName();
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
		var coorX = _x_en_div;
		var coorY = _y_en_div;

		with (document) {
			if (clickEnCuadro) {
				coorX+= parseInt(getElementById('cuadro').style.left);
				coorY+= parseInt(getElementById('cuadro').style.top);
			}

			getElementById('cuadro').style.left = coorX - 30;
			getElementById('cuadro').style.top = coorY - 25;
			getElementById('x').value = coorX - 30;
			getElementById('y').value = coorY - 25;

			clickEnCuadro = false;

			generar(accion);
		}
	}

	var clickEnCuadro = false;

	showTitle(true, 'Aviso de Obra');
</script>
<div style="height:412px; left:0px; position:relative; top:0px;">
	<div style="height:412px; left:0px; position:relative; top:0px; width:174px;">
		<form action="<?= $_SERVER["PHP_SELF"]?>" enctype="multipart/form-data" id="formAvisoObra" method="post" name="formAvisoObra">
			<input id="cargar" name="cargar" type="hidden" value="t" />
			<input id="pageid" name="pageid" type="hidden" value="42" />
			<input id="x" name="x" type="hidden" value="<?= $x?>" />
			<input id="y" name="y" type="hidden" value="<?= $y?>" />
			<div>
				<label class="FormLabelAzul">Fecha</label>
				<input class="FormInputTextDate" id="fecha" maxlength="10" name="fecha" style="width:72px;" type="text" value="<?= $fecha?>" />
				<input class="BotonFecha" id="btnFecha" name="btnFecha" style="margin-right:16px; vertical-align:-6px;" type="button" />
			</div>
			<div style="margin-top:8px;">
				<label class="FormLabelAzul">Extendido</label>
				<input <?= ($tipoSello=="e")?"checked":""?> id="tipoSello" name="tipoSello" style="margin-left:31px; vertical-align:-3px;" type="radio" value="e" />
			</div>
			<div>
				<label class="FormLabelAzul">No corresponde</label>
				<input <?= ($tipoSello=="n")?"checked":""?> id="tipoSello" name="tipoSello" style="vertical-align:-3px;" type="radio" value="n" />
			</div>
			<div>
				<label class="FormLabelAzul">Rechazado</label>
				<input <?= ($tipoSello=="h")?"checked":""?> id="tipoSello" name="tipoSello" style="margin-left:26px; vertical-align:-3px;" type="radio" value="h" />
			</div>
			<div>
				<label class="FormLabelAzul">Recibido</label>
				<input <?= ($tipoSello=="i")?"checked":""?> id="tipoSello" name="tipoSello" style="margin-left:40px; vertical-align:-3px;" type="radio" value="i" />
			</div>
			<div>
				<label class="FormLabelAzul">Suspendido</label>
				<input <?= ($tipoSello=="s")?"checked":""?> id="tipoSello" name="tipoSello" style="margin-left:23px; vertical-align:-3px;" type="radio" value="s" />
			</div>
			<div style="margin-top:8px;">
				<label class="FormLabelAzul">Imagen</label>
				<input class="FormInputTextDate" id="imagen" name="imagen" style="margin-right:16px;" type="file" value="" />
			</div>
			<div align="center" style="margin-top:16px;">
				<input class="BotonBlanco" type="submit" value="GENERAR PDF" />
			</div>
<?
if ($mostrarImagen) {
?>
			<div align="center" style="margin-top:24px;">
				<input class="BotonBlanco" type="button" value="GUARDAR" onClick="generar('g')" />
				<input class="BotonBlanco" style="margin-left:8px;" type="button" value="IMPRIMIR" onClick="generar('i')" />
			</div>
<?
}
?>
			<div style="background-color:#e7e7e7; border:1px solid #807f84; margin-top:32px; padding:2px; width:168px;">Haga un clic en el área pintada de celeste para ir colocando el sello en el pdf.</div>
		</form>
	</div>
<?
if ($mostrarImagen) {
?>
	<div id="panelCeleste" style="background-color:#84ddff; cursor:crosshair; height:412px; left:180px; position:relative; top:-412px; width:292px;" onClick="moverCuadro('v')">
		<div id="cuadro" style="background-color:#000; height:52px; left:<?= $x?>px; position:relative; top:<?= $y?>px; width:64px;" onClick="clickEnCuadro = true;">
			<img border="0" src="/modules/aviso_de_obra/images/sello.jpg" />
		</div>
	</div>
	<div style="left:472px; position:relative; top:-824px; width:4px;">&nbsp;</div>
	<div style="height:412px; left:476px; position:relative; top:-838px; width:292px;">
		<iframe frameborder="0" id="iframePdf" name="iframePdf" src="" style="height:412px; width:292px;"></iframe>
	</div>
	<div>
<?
}
?>
</div>
<script>
Calendar.setup (
	{
		inputField: "fecha",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFecha"
	}
);

generar('v');
</script>