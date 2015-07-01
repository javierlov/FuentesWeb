<?
if (!hasPermiso(13)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}


$isAlta = ($_REQUEST["id"] == 0);
$img = "/modules/mantenimiento/images/agregar_grande.png";

if (!$isAlta) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT *
			 FROM rrhh.rnp_novedadespersonales
			WHERE np_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$img = DATA_CELEBRACIONES_PATH.$row["NP_ID"];
	if (file_exists($img.".gif"))
		$img.= ".gif";
	elseif (file_exists($img.".jpeg"))
		$img.= ".jpeg";
	elseif (file_exists($img.".jpg"))
		$img.= ".jpg";
	elseif (file_exists($img.".png"))
		$img.= ".png";
	$img = "/functions/get_image.php?file=".base64_encode($img);
}
?>
<link href="/modules/mantenimiento/css/abm_nacimientos.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_nacimientos.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_nacimientos/guardar_nacimiento.php" id="formAbmNacimiento" method="post" name="formAbmNacimiento" target="iframeProcesando">
	<input id="baja" name="baja" type="hidden" value="<?= ($isAlta)?"f":($row["NP_FECHABAJA"]=="")?"f":"t"?>" />
	<input id="caracteres2" name="caracteres2" type="hidden" value="<?= ($isAlta)?0:strlen($row["NP_TEXTO"])?>" />
	<input id="fileImg" name="fileImg" type="hidden" value="<?= ($isAlta)?"":"old"?>" />
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div>
		<div class="fila">
			<label for="texto" id="labelTexto">Texto</label>
			<textarea autofocus id="texto" name="texto" onKeyDown="validarLongitud(this)"><?= ($isAlta)?"":$row["NP_TEXTO"]?></textarea>
		</div>
		<div class="fila">
			<label for="imagen" id="labelImagen">Imagen</label>
			<a href="/functions/edit_image.php?finalFunction=setImagen&minWidth=250&minHeight=100&mantenerProporcion=t" target="_blank">
				<img id="img" name="img" src="<?= $img?>" title="Clic aquí para cambiar la imagen" />
			</a>
		</div>
		<div class="fila">
			<label for="vigenciaDesde">Vigencia Desde</label>
			<input class="fecha" id="vigenciaDesde" maxlength="10" name="vigenciaDesde" type="text" value="<?= ($isAlta)?"":$row["NP_FECHAVIGENCIADESDE"]?>" />
			<input class="botonFecha" id="btnVigenciaDesde" name="btnVigenciaDesde" type="button" value="" />
			<label for="vigenciaHasta" id="labelVigenciaHasta">Vigencia Hasta</label>
			<input class="fecha" id="vigenciaHasta" maxlength="10" name="vigenciaHasta" type="text" value="<?= ($isAlta)?"":$row["NP_FECHAVIGENCIAHASTA"]?>" />
			<input class="botonFecha" id="btnVigenciaHasta" name="btnVigenciaHasta" type="button" value="" />
		</div>
			<div class="fila">
				<label for="vistaPrevia">Vista Previa</label>
				<input <?= ($isAlta)?"":(($row["NP_VISTAPREVIA"] == "S")?"checked":"")?> id="vistaPrevia" name="vistaPrevia" type="checkbox" value="ok" />
			</div>
	</div>
	<div id="divBotones">
<?
if (!$isAlta) {
?>
		<input id="btnDarBaja" name="btnDarBaja" type="button" onClick="darBaja(<?= $_REQUEST["id"]?>)" />
<?
}
?>
		<input id="btnGuardar" name="btnGuardar" type="button" onClick="guardar()" />
		<img id="imgProcesando" src="/images/loading.gif" title="Procesando, aguarde unos segundos por favor..." />
		<input id="btnCancelar" name="btnCancelar" type="button" onClick="cancelar()" />
	</div>
	<div id="divErroresForm">
		<img src="/images/atencion.png" />
		<span>No es posible continuar mientras no se corrijan los siguientes errores:</span>
		<br />
		<br />
		<span id="errores"></span>
		<input id="foco" name="foco" readonly type="checkbox" />
	</div>
</form>

<div id="divFondo"></div>

<script type="text/javascript">
	Calendar.setup ({
		inputField: "vigenciaDesde",
		ifFormat  : "%d/%m/%Y",
		button    : "btnVigenciaDesde"
	});
	Calendar.setup ({
		inputField: "vigenciaHasta",
		ifFormat  : "%d/%m/%Y",
		button    : "btnVigenciaHasta"
	});
</script>