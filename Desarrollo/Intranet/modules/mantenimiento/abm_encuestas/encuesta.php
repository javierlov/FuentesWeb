<?
if (!hasPermiso(25)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}


$isAlta = ($_REQUEST["id"] == 0);
$imgCabecera = "/modules/mantenimiento/images/agregar_grande.png";
$tipoAlmacenamiento = "T";

if (!$isAlta) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT *
			 FROM rrhh.ren_encuestas
			WHERE en_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$imgCabecera = IMAGES_ENCUESTAS_CABECERA_PATH.$row["EN_IMAGENCABECERA"];
	$imgCabecera = "/functions/get_image.php?file=".base64_encode($imgCabecera);

	$tipoAlmacenamiento = $row["EN_TIPOALMACENAMIENTO"];
}

require_once("encuesta_combos.php");
?>
<link href="/modules/mantenimiento/css/abm_encuestas.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_encuestas.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_encuestas/guardar_encuesta.php" enctype="multipart/form-data"  id="formAbmEncuesta" method="post" name="formAbmEncuesta" target="iframeProcesando">
	<input id="activaAnterior" name="activaAnterior" type="hidden" value="<?= ($isAlta)?"F":$row["EN_ACTIVA"]?>">
	<input id="baja" name="baja" type="hidden" value="<?= ($isAlta)?"f":($row["EN_FECHABAJA"]=="")?"f":"t"?>" />
	<input id="fileImgCabecera" name="fileImgCabecera" type="hidden" value="" />
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div>
		<div class="fila">
			<label for="titulo">Título</label>
			<input autofocus id="titulo" maxlength="128" name="titulo" type="text" value="<?= ($isAlta)?"":$row["EN_TITULO"]?>" />
		</div>
		<div class="fila">
			<label for="detalle">Detalle</label>
			<input id="detalle" maxlength="256" name="detalle" type="text" value="<?= ($isAlta)?"":$row["EN_DETALLE"]?>" />
		</div>
		<div class="fila">
			<label for="activa">Encuesta Activa</label>
			<input <?= ($isAlta)?"":(($row["EN_ACTIVA"] == "T")?"checked":"")?> id="activa" name="activa" type="checkbox" value="ok" />
		</div>
		<div class="fila">
			<label for="permiteModificaciones">Permite Modificaciones</label>
			<input <?= ($isAlta)?"":(($row["EN_PERMITEMODIFICACIONES"] == "T")?"checked":"")?> id="permiteModificaciones" name="permiteModificaciones" type="checkbox" value="ok" />
		</div>
		<div class="fila">
			<label for="tipoAlmacenamiento">Guardar en Base de Datos</label>
			<input id="tipoAlmacenamiento" name="tipoAlmacenamiento" type="radio" value="T" <?= ($tipoAlmacenamiento == "T")?"checked":""?> />
			<label for="tipoAlmacenamiento" id="labelTipoAlmacenamiento">Al finalizar</label>
			<input id="tipoAlmacenamiento" name="tipoAlmacenamiento" type="radio" value="P" <?= ($tipoAlmacenamiento == "P")?"checked":""?> />
			<label for="tipoAlmacenamiento" id="labelTipoAlmacenamiento">Por pregunta</label>
		</div>
		<div class="fila">
			<label for="mostrarResultados">Mostrar Resultados</label>
			<input <?= ($isAlta)?"":(($row["EN_MOSTRARRESULTADOS"] == "T")?"checked":"")?> id="mostrarResultados" name="mostrarResultados" type="checkbox" value="ok" />
		</div>
		<div class="fila">
			<label for="mostrarImagen">Mostrar Imagen en la Cabecera de la Página</label>
			<input <?= ($isAlta)?"":(($row["EN_MOSTRARIMAGENCABECERA"] == "T")?"checked":"")?> id="mostrarImagen" name="mostrarImagen" type="checkbox" value="ok" />
		</div>
		<div class="fila">
			<label for="imgCabecera">Imagen Cabecera</label>
			<a href="/functions/edit_image.php?finalFunction=setImagenCabecera&minWidth=120&minHeight=120" target="_blank">
				<img id="imgCabecera" name="imgCabecera" src="<?= $imgCabecera?>" title="Clic aquí para cambiar la imagen de la cabecera" />
			</a>
		</div>
		<div class="fila">
			<label for="vigenciaDesde">Vigencia Desde</label>
			<input class="fecha" id="vigenciaDesde" maxlength="10" name="vigenciaDesde" type="text" value="<?= ($isAlta)?"":$row["EN_FECHAVIGENCIADESDE"]?>" />
			<input class="botonFecha" id="btnVigenciaDesde" name="btnVigenciaDesde" type="button" value="" />
			<label for="vigenciaHasta" id="labelVigenciaHasta">Vigencia Hasta</label>
			<input class="fecha" id="vigenciaHasta" maxlength="10" name="vigenciaHasta" type="text" value="<?= ($isAlta)?"":$row["EN_FECHAVIGENCIAHASTA"]?>" />
			<input class="botonFecha" id="btnVigenciaHasta" name="btnVigenciaHasta" type="button" value="" />
		</div>

		<div id="divUsuarios">
			<div id="separadores">
				<span id="usuariosTitulo">Usuarios Autorizados</span>
			</div>
			<div id="separadores">
				<hr id="linea">
			</div>
		</div>
		<div id="divUsuariosCombo">
			<?= $comboUsuarios->draw();?>
			<label for="tiposUsuario" id="labelTiposUsuario">Tipos de usuario</label>
			<?= $comboTiposUsuario->draw();?>
		</div>

		<div id="preguntas">
			<p id="separadores">
				<span id="preguntasTitulo">Preguntas</span>
				<img id="imgAgregarPregunta" src="/modules/mantenimiento/images/agregar_grande.png" title="Agregar pregunta" onClick="agregarPregunta(-1, -1, '', false, false, true, true)" />
			</p>
			<p id="separadores">
				<hr id="linea">
			</p>
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
		<input id="btnVistaPrevia" name="btnVistaPrevia" type="button" onClick="vistaPrevia(<?= $_REQUEST["id"]?>)" />
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
<?
if (!$isAlta) {
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
									 FROM (SELECT pe_id
													 FROM rrhh.rpe_preguntasencuesta
													WHERE pe_idencuesta = ".$_REQUEST["id"]."
														AND pe_fechabaja IS NULL
											 ORDER BY pe_id))
					WHERE pe_id = :id";

			$num2++;
			echo "agregarOpcion(".$num.", ".$num2.", ".$row2["OP_ID"].", '".$row2["OP_OPCION"]."', ".$permiteObservacion.", '".ValorSql($sql, -1, $params)."', ".$respuestaLibre.", '".$row2["OP_IMAGEN"]."', false);";
		}
	}
}
?>
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

	setTimeout(function() {seleccionarUsuarios('<?= ($isAlta)?"t":"s"?>');}, 500);
</script>