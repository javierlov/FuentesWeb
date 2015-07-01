<?
if (!hasPermiso(76)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}


$carpetaImagenes = date("YmdHis").substr(getWindowsLoginName(true), 0, 3);
$isAlta = ($_REQUEST["id"] == 0);
$imgChica = "/modules/mantenimiento/images/agregar_grande.png";
$imgGrande = "/modules/mantenimiento/images/agregar_grande.png";
$moduloPlantilla = 1;

if (!$isAlta) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT *
			 FROM web.wai_articulosintranet
			WHERE ai_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$articulo = "";
	if (is_object($row["AI_ARTICULO"]))
		$articulo = preg_replace("/[\n|\r|\n\r]/i", "", $row["AI_ARTICULO"]->load());

	$imgChica = IMAGES_ARTICULOS_PATH.$_REQUEST["id"]."/".$row["AI_RUTAIMAGEN"];
	$imgChica = "/functions/get_image.php?file=".base64_encode($imgChica);

	$imgGrande = IMAGES_ARTICULOS_PATH.$_REQUEST["id"]."/".$row["AI_IMAGENGRANDE"];
	$imgGrande = "/functions/get_image.php?file=".base64_encode($imgGrande);
}

require_once("articulo_combos.php");
?>
<link href="/modules/mantenimiento/css/abm_articulos.css" rel="stylesheet" type="text/css" />
<script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="/modules/mantenimiento/js/abm_articulos.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_articulos/guardar_articulo.php" enctype="multipart/form-data" id="formAbmArticulo" method="post" name="formAbmArticulo" target="iframeProcesando">
	<input id="baja" name="baja" type="hidden" value="<?= ($isAlta)?"f":($row["AI_FECHABAJA"]=="")?"f":"t"?>" />
	<input id="fileImgChica" name="fileImgChica" type="hidden" value="" />
	<input id="fileImgGrande" name="fileImgGrande" type="hidden" value="" />
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div>
<?
if (!$isAlta) {
?>
		<div class="fila">
			<label for="url" id="labelUrl">URL</label>
			<input id="url" name="url" readonly type="text" value="/articulos/<?= $_REQUEST["id"]?>" />
			<a href="/articulos/<?= $_REQUEST["id"]?>" target="_blank"><input id="btnVer" name="btnVer" type="button" /></a>
		</div>
<?
}
?>
		<div class="fila">
			<label for="tipo" id="labelTipo">Tipo</label>
			<label for="tipo" id="labelTipoInput">Embebido</label>
			<input <?= ($isAlta)?"":(($row["AI_TIPO"] == "M")?"checked":"")?> id="tipo" name="tipo" type="radio" value="M" onClick="cambiarTipo(this.value)" />
			<label for="tipo" id="labelTipoInput">Externo</label>
			<input <?= ($isAlta)?"":(($row["AI_TIPO"] == "X")?"checked":"")?> id="tipo" name="tipo" type="radio" value="X" onClick="cambiarTipo(this.value)" />
		</div>

		<div class="fila" id="divEmbebido" style="display:<?= (($row["AI_TIPO"] == "M")?"block":"none")?>;">
			<label for="btnEditar" id="labelBtnEditar">Cuerpo</label>
			<textarea id="cuerpo" name="cuerpo" readonly onClick="editarCuerpo('/articulos_imagenes/<?= $carpetaImagenes?>/')"><?= ($isAlta)?"":$articulo?></textarea>
			<input id="btnEditar" name="btnEditar" title="Editar" type="button" onClick="editarCuerpo('/articulos_imagenes/<?= $carpetaImagenes?>/')" />
		</div>

		<div class="fila" id="divExterno" style="display:<?= (($row["AI_TIPO"] == "X")?"block":"none")?>;">
			<label for="archivo" id="labelArchivo">Archivo</label>
			<input id="archivo" name="archivo" type="file" />
		</div>

		<div class="fila">
			<label for="habilitarComentarios">Habilitar Comentarios</label>
			<input <?= ($isAlta)?"":(($row["AI_HABILITARCOMENTARIOS"] == "S")?"checked":"")?> id="habilitarComentarios" name="habilitarComentarios" type="checkbox" value="ok" />
		</div>
		<div class="fila">
			<label for="mostrarEnPortada" id="labelMostrarEnPortada">Mostrar en Portada</label>
			<input <?= ($isAlta)?"":(($row["AI_MOSTRARENPORTADA"] == "S")?"checked":"")?> id="mostrarEnPortada" name="mostrarEnPortada" type="checkbox" value="ok" onClick="mostrarEnPortadaClic(this.checked)" />
		</div>
		<div id="divMostrarEnPortada">
			<div class="fila">
				<label for="volanta" id="labelVolanta">Volanta</label>
				<input id="volanta" maxlength="30" name="volanta" type="text" value="<?= ($isAlta)?"":$row["AI_VOLANTA"]?>" />
			</div>
			<div class="fila">
				<label for="bajada" id="labelBajada">Bajada</label>
				<input id="bajada" maxlength="512" name="bajada" type="text" value="<?= ($isAlta)?"":$row["AI_CUERPO"]?>" />
			</div>
			<div class="fila">
				<label for="ubicacion" id="labelUbicacion">Ubicación</label>
				<?= $comboUbicacion->draw();?>
			</div>
			<div class="fila">
				<label for="titulo" id="labelTitulo">Título</label>
				<input id="titulo" maxlength="50" name="titulo" type="text" value="<?= ($isAlta)?"":$row["AI_TITULO"]?>" />
			</div>
			<div class="fila">
				<label for="imagenChica" id="labelImagenChica">Imagen Chica</label>
				<a href="/functions/edit_image.php?finalFunction=setImagenChica&minWidth=120&minHeight=80&mantenerProporcion=t" target="_blank">
					<img id="imgChica" name="imgChica" src="<?= $imgChica?>" title="Clic aquí para cambiar la imagen chica" />
				</a>
			</div>
			<div class="fila">
				<label for="imagenGrande" id="labelImagenGrande">Imagen Grande</label>
				<a href="/functions/edit_image.php?finalFunction=setImagenGrande&minWidth=620&minHeight=315&mantenerProporcion=t" target="_blank">
					<img id="imgGrande" name="imgGrande" src="<?= $imgGrande?>" title="Clic aquí para cambiar la imagen grande" />
				</a>
			</div>
			<div class="fila">
				<label for="posicion" id="labelPosicion">Posición</label>
				<input id="posicion" maxlength="2" name="posicion" type="text" value="<?= ($isAlta)?"":$row["AI_POSICION"]?>" />
			</div>
			<div class="fila">
				<label for="destino" id="labelDestino">Destino</label>
				<?= $comboDestino->draw();?>
			</div>
			<div class="fila">
				<label for="vigenciaDesde" id="labelVigenciaDesde">Vigencia Desde</label>
				<input class="fecha" id="vigenciaDesde" maxlength="10" name="vigenciaDesde" type="text" value="<?= ($isAlta)?"":$row["AI_FECHAVIGENCIADESDE"]?>" />
				<input class="botonFecha" id="btnVigenciaDesde" name="btnVigenciaDesde" type="button" value="" />
				<label for="vigenciaHasta" id="labelVigenciaHasta">Vigencia Hasta</label>
				<input class="fecha" id="vigenciaHasta" maxlength="10" name="vigenciaHasta" type="text" value="<?= ($isAlta)?"":$row["AI_FECHAVIGENCIAHASTA"]?>" />
				<input class="botonFecha" id="btnVigenciaHasta" name="btnVigenciaHasta" type="button" value="" />
			</div>
			<div class="fila">
				<label for="vistaPrevia" id="labelVistaPrevia">Vista Previa</label>
				<input <?= ($isAlta)?"":(($row["AI_VISTAPREVIA"] == "S")?"checked":"")?> id="vistaPrevia" name="vistaPrevia" type="checkbox" value="ok" />
			</div>
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

<div id="divCuerpo">
	<br />
	<label id="tituloCuerpo"><b>EDICIÓN DEL CUERPO DEL ARTÍCULO</b></label>
	<br />
	<br />
	<div id="divCuerpoImagenes">
		<form action="/modules/mantenimiento/abm_articulos/subir_imagen.php" enctype="multipart/form-data" id="formSubirImagen" method="post" name="formSubirImagen" target="iframeProcesando">
			<input id="carpeta" name="carpeta" type="hidden" value="<?= $carpetaImagenes?>" />
			<label id="labelSubirImagen">Subir imagen</label>
			<input id="imagen" name="imagen" type="file" value="" />
			<input id="btnEnviar" name="btnEnviar" type="submit" value="" onClick="enviar()" />
			<img id="imgSubiendoImagen" src="/images/loading.gif" title="Enviando, aguarde un instante por favor..." />
			<img id="imgSubidaOk" src="/images/btn_ok.gif" title="Imagen subida exitosamente!" />
		</form>
		<div id="divCuerpoRutaImagenes">La ruta de las imagenes es la siguiente: http://<?= $_SERVER["HTTP_HOST"]?>/articulos_imagenes/<?= $carpetaImagenes?>/</div>
	</div>
	<form action="/modules/mantenimiento/abm_articulos/guardar_cuerpo.php" id="formCuerpo" method="post" name="formCuerpo" target="iframeProcesando">
		<input id="idArticulo" name="idArticulo" type="hidden" value="-1" />
		<textarea class="ckeditor" id="html" name="html"></textarea>
<!--		<br />
		<div id="divCuerpoGuardar">
			<input id="btnGuardar" name="btnGuardar" type="submit" value="" />
		</div>-->
	</form>
	<?require_once($_SERVER["DOCUMENT_ROOT"]."/functions/plantilla_ckeditor/bloque_plantilla.php");?>
	<img id="imgCerrarVentanaCuerpo" src="/images/cerrar.png" onClick="cerrarVentanaCuerpo()" />
</div>

<?require_once($_SERVER["DOCUMENT_ROOT"]."/functions/plantilla_ckeditor/ventana_plantilla.php");?>

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

	mostrarEnPortadaClic(<?= ($isAlta)?"false":(($row["AI_MOSTRARENPORTADA"] == "S")?"true":"false")?>);
</script>