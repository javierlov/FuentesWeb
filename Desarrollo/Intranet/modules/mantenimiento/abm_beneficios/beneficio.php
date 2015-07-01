<?
if (!hasPermiso(85)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}


$carpetaImagenes = date("YmdHis").substr(getWindowsLoginName(true), 0, 3);
$isAlta = ($_REQUEST["id"] == 0);
$moduloPlantilla = 2;

if (!$isAlta) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT *
			 FROM rrhh.rbn_beneficios
			WHERE bn_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$html = "";
	if (is_object($row["BN_HTML"]))
		$html = preg_replace("/[\n|\r|\n\r]/i", "", $row["BN_HTML"]->load());
}
?>
<link href="/modules/mantenimiento/css/abm_beneficios.css" rel="stylesheet" type="text/css" />
<script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="/modules/mantenimiento/js/abm_beneficios.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_beneficios/guardar_beneficio.php" id="formAbmBeneficio" method="post" name="formAbmBeneficio" target="iframeProcesando">
	<input id="baja" name="baja" type="hidden" value="<?= ($isAlta)?"f":($row["BN_FECHABAJA"]=="")?"f":"t"?>" />
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div>
		<div class="fila">
			<label for="nombre">Nombre</label>
			<input id="nombre" maxlength="255" name="nombre" type="text" value="<?= ($isAlta)?"":$row["BN_NOMBRE"]?>" />
		</div>
		<div class="fila">
			<label for="btnEditar">HTML</label>
			<textarea id="htmlVisible" name="htmlVisible" readonly onClick="editarHtml('/beneficios_imagenes/<?= $carpetaImagenes?>/')"><?= ($isAlta)?"":$html?></textarea>
			<input id="btnEditar" name="btnEditar" title="Editar" type="button" onClick="editarHtml('/beneficios_imagenes/<?= $carpetaImagenes?>/')" />
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

<div id="divHtml">
	<br />
	<label id="tituloHtml"><b>EDICIÓN DEL HTML</b></label>
	<br />
	<br />
	<div id="divHtmlImagenes">
		<form action="/modules/mantenimiento/abm_beneficios/subir_imagen.php" enctype="multipart/form-data" id="formSubirImagen" method="post" name="formSubirImagen" target="iframeProcesando">
			<input id="carpeta" name="carpeta" type="hidden" value="<?= $carpetaImagenes?>" />
			<label id="labelSubirImagen">Subir imagen</label>
			<input id="imagen" name="imagen" type="file" value="" />
			<input id="btnEnviar" name="btnEnviar" type="submit" value="" onClick="enviar()" />
			<img id="imgSubiendoImagen" src="/images/loading.gif" title="Enviando, aguarde un instante por favor..." />
			<img id="imgSubidaOk" src="/images/btn_ok.gif" title="Imagen subida exitosamente!" />
		</form>
		<div id="divHtmlRutaImagenes">La ruta de las imagenes es la siguiente: http://<?= $_SERVER["HTTP_HOST"]?>/beneficios_imagenes/<?= $carpetaImagenes?>/</div>
	</div>
	<form action="/modules/mantenimiento/abm_beneficios/guardar_html.php" id="formHtml" method="post" name="formHtml" target="iframeProcesando">
		<input id="idBeneficio" name="idBeneficio" type="hidden" value="-1" />
		<textarea class="ckeditor" id="html" name="html"></textarea>
		<br />
<!--		<div id="divHtmlGuardar">
			<input id="btnGuardar" name="btnGuardar" type="submit" value="" />
		</div>-->
	</form>
	<?require_once($_SERVER["DOCUMENT_ROOT"]."/functions/plantilla_ckeditor/bloque_plantilla.php");?>
	<img id="imgCerrarVentanaHtml" src="/images/cerrar.png" onClick="cerrarVentanaHtml()" />
</div>

<?require_once($_SERVER["DOCUMENT_ROOT"]."/functions/plantilla_ckeditor/ventana_plantilla.php");?>