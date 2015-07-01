<?
$alta = !isset($_REQUEST["id"]);
$cssImagen = "";
$dadaDeBaja = false;
$rutaImagen = "";
$tipoAlmacenamiento = "T";
if ($alta) {
	$cssImagen = "visibility:hidden;";
}
else {
	$params = array(":id" => $_REQUEST["id"]);
	$sql = 
		"SELECT en_activa, en_detalle, en_fechabaja, en_imagencabecera, en_mostrarimagencabecera, en_permitemodificaciones, en_tipoalmacenamiento, en_titulo, TO_CHAR(en_fechaalta, 'dd/mm/yyyy') fechaalta
			 FROM rrhh.ren_encuestas
			WHERE en_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	if ($row["EN_IMAGENCABECERA"] == "")
		$cssImagen = "visibility:hidden;";
	$dadaDeBaja = ($row["EN_FECHABAJA"] != "");
	$rutaImagen = "/functions/get_image.php?file=".base64_encode(IMAGES_ENCUESTAS_CABECERA_PATH.$row["EN_IMAGENCABECERA"]);
	$tipoAlmacenamiento = $row["EN_TIPOALMACENAMIENTO"];
}
?>
<link href="/modules/abm_encuestas/css/style_encuestas.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="js/encuesta.js"></script>
<iframe id="iframeEncuesta" name="iframeEncuesta" src="" style="display:none;"></iframe>
<div>
	<form action="/modules/abm_encuestas/procesar_encuesta.php" enctype="multipart/form-data" id="formEncuesta" method="post" name="formEncuesta" target="iframeEncuesta" onSubmit="return validarEnvio(formEncuesta)">
		<input id="id" name="id" type="hidden" value="<?= ($alta)?"":$_REQUEST["id"]?>">
		<input id="tipoOp" name="tipoOp" type="hidden" value="<?= ($alta)?"A":"M"?>">
		<input id="activaAnterior" name="activaAnterior" type="hidden" value="<?= ($alta)?"F":$row["EN_ACTIVA"]?>">
		<input id="max_file_size" name="max_file_size" type="hidden" value="200000">
<?
if (!$alta) {
?>
		<p id="separadores">
			<label for="fechaAlta">Fecha de Alta</label>
			<input class="FormInputText" id="fechaAlta" name="fechaAlta" type="text" value="<?= ($alta)?"":$row["FECHAALTA"]?>" readonly />
		</p>
<?
}
?>
		<p id="separadores">
			<label for="titulo">Título</label>
			<input class="FormInputText" id="titulo" maxlength="128" name="titulo" type="text" value="<?= ($alta)?"":$row["EN_TITULO"]?>" />
		</p>
		<p id="separadores">
			<label for="detalle">Detalle</label>
			<input class="FormInputText" id="detalle" maxlength="256" name="detalle" type="text" validar="true" title="Detalle" value="<?= ($alta)?"":$row["EN_DETALLE"]?>" />
		</p>
		<p id="separadores">
			<label for="activa">Activa</label>
			<input id="activa" name="activa" type="checkbox" value="T" <?= ($alta)?"":($row["EN_ACTIVA"] == "T")?"checked":""?> />
		</p>
		<p id="separadores">
			<label for="permiteModificaciones">Permite modificaciones</label>
			<input id="permiteModificaciones" name="permiteModificaciones" type="checkbox" <?= ($alta)?"":($row["EN_PERMITEMODIFICACIONES"] == "T")?"checked":""?> />
		</p>
		<p id="separadores">
			Guardar en base de datos
			<input id="tipoAlmacenamiento" name="tipoAlmacenamiento" type="radio" value="T" <?= ($tipoAlmacenamiento == "T")?"CHECKED":""?> />
			<label for="tipoAlmacenamiento">Al finalizar</label>
			<input id="tipoAlmacenamiento" name="tipoAlmacenamiento" type="radio" value="P" <?= ($tipoAlmacenamiento == "P")?"CHECKED":""?> />
			<label for="tipoAlmacenamiento">Por pregunta</label>
		</p>
		<p id="separadores">
			<label for="mostrarImagen">Mostrar imagen en la cabecera de la página</label>
			<input id="mostrarImagen" name="mostrarImagen" type="checkbox" <?= ($alta)?"":($row["EN_MOSTRARIMAGENCABECERA"] == "T")?"checked":""?> />
		</p>
		<p id="separadores">
			<label for="imagen">Imagen</label>
			<input class="FormInputText" id="imagen" name="imagen" type="file" validarImagen="true" title="Imagen" />
			<img alt="Ver imagen" border="0" height="16" id="imagenCabecera" src="<?= $rutaImagen?>" width="200" style="<?= $cssImagen?>" onClick="verImagen('C', '<?= ($alta)?"":$row["EN_IMAGENCABECERA"]?>')" />
		</p>
		<div id="usuarios">
			<p id="separadores">
				<span id="usuariosTitulo">Usuarios Autorizados</span>
			</p>
			<p id="separadores">
				<hr id="linea">
			</p>
			<select class="Combo" id="usuarios[]" name="usuarios[]" size="8" multiple></select>
			<label for="tiposUsuario" id="labelTiposUsuario">Tipos de usuario</label>
			<select class="Combo" id="tiposUsuario" name="tiposUsuario" onChange="seleccionarUsuarios(this.value)">
				<option value="t">Todos</option>
				<option value="e">Empleados</option>
				<option value="j">Jefes</option>
				<option value="jyg">Jefes y Gerentes</option>
				<option value="g">Gerentes</option>
				<option value="d">Directores</option>
			</select>
		</div>
		<div id="preguntas">
			<p id="separadores">
				<span id="preguntasTitulo">Preguntas</span>
				<img alt="Agregar pregunta" border="0" src="/images/add16.png" style="cursor:pointer; vertical-align:text-bottom;" onClick="agregarPregunta(-1, -1, '', false, false, true)" />
			</p>
			<p id="separadores">
				<hr id="linea">
			</p>
		</div>
		<p id="separadores">
			<span>
				<input class="BotonBlanco" name="btnVistaPrevia" type="button" value="Vista Previa" onClick="window.open('/index.php?pageid=50&vp=T&encuestaid='+document.getElementById('id').value)">
				<input class="BotonBlanco" name="btnEstadisticas" type="button" value="Estadísticas" onClick="window.location.href='/index.php?pageid=49&encuestaid='+document.getElementById('id').value;">
<?
$css = "";
if (($alta) or ($dadaDeBaja))
	$css = "visibility:hidden;";
?>
				<input class="BotonBlanco" name="btnDarBaja" type="button" value="Dar de Baja" style="<?= $css?>" onClick="darBaja()">
			</span>
			<span style="margin-left:248px;">
				<input class="BotonBlanco" name="btnGuardar" type="submit" value="Guardar" style="<?= ($dadaDeBaja)?"visibility:hidden;":""?>">
				<input class="BotonBlanco" name="btnCancelar" type="button" value="Cancelar" onClick="window.location.href='/index.php?pageid=48&buscar=yes';">
			</span>
		</p>
	</form>
</div>
<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "usuarios[]";
$RCparams = array();
$RCquery = 
	"SELECT se_id id, se_nombre detalle
     FROM use_usuarios
    WHERE se_fechabaja IS NULL
      AND se_usuariogenerico = 'N'
      AND se_idsector IS NOT NULL
 ORDER BY 2";
$RCselectedItem = -1;
FillCombo(false);

if (!$alta) {
	$params = array(":idencuesta" => $_REQUEST["id"]);
	$sql = 
		"SELECT pe_id, pe_multiopcion, pe_pregunta, pe_respuestalibre, pe_validarcheck
			 FROM rrhh.rpe_preguntasencuesta
			WHERE pe_idencuesta = :idencuesta
				AND pe_fechabaja IS NULL
	 ORDER BY pe_id";
	$stmt = DBExecSql($conn, $sql, $params);
	$num = 0;
	while ($row = DBGetQuery($stmt)) {
		$num++;
		$multiopcion = (($row["PE_MULTIOPCION"] == "T")?"true":"false");
		$respuestaLibre = (($row["PE_RESPUESTALIBRE"] == "T")?"true":"false");
		$validarCheck = (($row["PE_VALIDARCHECK"] == "T")?"true":"false");
		echo "agregarPregunta(".$num.", ".$row["PE_ID"].", '".$row["PE_PREGUNTA"]."', ".$multiopcion.", ".$respuestaLibre.", ".$validarCheck.", false);";

		$params = array(":idpregunta" => $row["PE_ID"]);
		$sql = 
			"SELECT op_id, op_idpreguntasiguiente, op_imagen, op_opcion, op_permiteobservacion
				 FROM rrhh.rop_opcionespreguntas
				WHERE op_idpregunta = :idpregunta
					AND op_fechabaja IS NULL
		 ORDER BY op_id";
		$stmt2 = DBExecSql($conn, $sql, $params);
		$num2 = 0;
		while ($row2 = DBGetQuery($stmt2)) {
			$permiteObservacion = (($row2["OP_PERMITEOBSERVACION"] == "T")?"true":"false");
			$params = array(":id" => intval("0".$row2["OP_IDPREGUNTASIGUIENTE"]));
			$sql =
				"SELECT NVL(numero, -1)
  				 FROM (SELECT ROWNUM numero, pe_id
            			 FROM rrhh.rpe_preguntasencuesta
           				WHERE pe_idencuesta = ".$_REQUEST["id"]."
             				AND pe_fechabaja IS NULL
        			 ORDER BY pe_id)
 				  WHERE pe_id = :id";

			$num2++;
			echo "agregarOpcion(".$num.", ".$num2.", ".$row2["OP_ID"].", '".$row2["OP_OPCION"]."', ".$permiteObservacion.", '".ValorSql($sql, -1, $params)."', ".$respuestaLibre.", '".$row2["OP_IMAGEN"]."', false);";
		}
	}
}
?>
	seleccionarUsuarios('s');

	document.getElementById('titulo').focus();
</script>