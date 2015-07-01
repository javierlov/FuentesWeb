<?
$alta = !isset($_REQUEST["id"]);
if (!$alta) {
	$sql = 
		"SELECT li_autor, li_dias, li_ibsn, li_estado, li_resumen, li_tema, li_titulo
			FROM rrhh.bli_libro
		  WHERE li_id = :id";
	$params = array(":id" => $_REQUEST["id"]);
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
}
?>
<script>
	function darBaja() {
		if (confirm('¿ Realmente desea dar de baja este libro ?'))
			with (document) {
				getElementById('tipoOp').value = 'B';
				getElementById('formLibro').submit();
			}
	}

	showTitle(true, 'Agregar Libro');
</script>
<div>
	<iframe id="iframeLibro" name="iframeLibro" src="" style="display:none;"></iframe>
	<form action="/modules/biblioteca/procesar_libro.php" id="formLibro" method="post" name="formLibro" target="iframeLibro" onSubmit="return ValidarForm(formLibro)">
		<input id="id" name="id" type="hidden" value="<?= ($alta)?"":$_REQUEST["id"]?>">
		<input id="tipoOp" name="tipoOp" type="hidden" value="<?= ($alta)?"A":"M"?>">
		<p style="left:20px; margin-bottom:4px; position:relative;">
			<label class="FormLabelAzul" for="titulo">Titulo</label>
			<input class="FormInputText" id="titulo" maxlength="250" name="titulo" size="80" title="Título" type="text" validar="true" value="<?= ($alta)?"":$row["LI_TITULO"]?>" />
		</p>
		<p style="margin-bottom:4px;">
			<label class="FormLabelAzul" for="resumen" style="position:relative; top:-64px;">Resumen</label>
			<textarea class="FormInputText" cols="79" id="resumen" maxlength="2000" name="resumen" rows="5" onKeyUp="return checkMaxLength(this)"><?= ($alta)?"":$row["LI_RESUMEN"]?></textarea>
		</p>
		<p style="left:20px; margin-bottom:4px; position:relative;">
			<label class="FormLabelAzul" for="tema" style="position:relative; top:-64px;">Tema</label>
			<textarea class="FormInputText" cols="79" id="tema" maxlength="2000" name="tema" rows="5" onKeyUp="return checkMaxLength(this)"><?= ($alta)?"":$row["LI_TEMA"]?></textarea>
		</p>
		<p style="left:15px; margin-bottom:4px; position:relative;">
			<label class="FormLabelAzul" for="isbn">I.S.B.N.</label>
			<input class="FormInputText" id="isbn" maxlength="50" name="isbn" size="80" type="text" value="<?= ($alta)?"":$row["LI_IBSN"]?>" />
		</p>
		<p style="left:23px; margin-bottom:4px; position:relative;">
			<label class="FormLabelAzul" for="autor">Autor</label>
			<input class="FormInputText" id="autor" maxlength="250" name="autor" size="80" type="text" value="<?= ($alta)?"":$row["LI_AUTOR"]?>" />
		</p>
		<p style="left:30px; margin-bottom:4px; position:relative;">
			<label class="FormLabelAzul" for="dias">Días</label>
			<input class="FormInputText" id="dias" maxlength="3" name="dias" size="2" title="Días" type="text" validarEntero="true" value="<?= ($alta)?"":$row["LI_DIAS"]?>" />
		</p>
		<p style="left:15px; margin-bottom:4px; position:relative;">
			<label class="FormLabelAzul" for="estado">Estado</label>
			<input class="FormInputText" id="estado" maxlength="20" name="estado" size="40" type="text" value="<?= ($alta)?"":$row["LI_ESTADO"]?>" />
		</p>
		<p style="left:56px; position:relative;">
			<input class="BotonBlanco" style="margin-right:8px;" type="submit" value="Guardar">
<?
if (!$alta) {
?>
			<input class="BotonBlanco" style="margin-right:8px;" type="button" value="Dar de Baja" onClick="darBaja()">
<?
}
?>
			<input class="BotonBlanco" type="button" value="Cancelar" onClick="history.back();">
		</p>
	</form>
</div>
<script>
	document.getElementById('titulo').focus();
</script>