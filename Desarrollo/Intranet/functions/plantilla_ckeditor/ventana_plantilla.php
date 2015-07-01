<div id="divNombrePlantilla">
	<form action="/functions/plantilla_ckeditor/guardar_plantilla.php" enctype="multipart/form-data" id="formGuardarPlantilla" method="post" name="formGuardarPlantilla" target="iframeProcesando">
		<input id="idPlantilla" name="idPlantilla" type="hidden" value="-1" />
		<input id="modulo" name="modulo" type="hidden" value="<?= $moduloPlantilla?>" />
		<label id="labelNombrePlantilla">Nombre</label>
		<input id="nombrePlantilla" maxlength="100" name="nombrePlantilla" type="text" value="" />
		<input id="btnGuardar" name="btnGuardar" type="button" onClick="guardarRealmentePlantilla()" />
		<input id="btnCancelar" name="btnCancelar" type="button" onClick="cancelarPlantilla()" />
		<textarea id="cuerpoPlantilla" name="cuerpoPlantilla"></textarea>
	</form>
</div>