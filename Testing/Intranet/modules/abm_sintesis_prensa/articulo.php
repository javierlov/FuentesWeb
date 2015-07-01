<?
$alta = !isset($_REQUEST["id"]);
if (!$alta) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql = 
		"SELECT ap_contenido, ap_fuente, ap_notaprincipal, ap_titulo, TO_CHAR(ap_fecha, 'dd/mm/yyyy') fecha
			 FROM rrhh.rap_articulosprensa
			WHERE ap_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
}
?>
<iframe id="iframeArticulo" name="iframeArticulo" src="" style="display:none;"></iframe>
<form action="/modules/abm_sintesis_prensa/procesar_articulo.php" id="formArticulo" method="post" name="formArticulo" target="iframeArticulo" onSubmit="return ValidarForm(formArticulo)">
	<input id="id" name="id" type="hidden" value="<?= ($alta)?"":$_REQUEST["id"]?>" />
	<input id="TipoOp" name="TipoOp" type="hidden" value="<?= ($alta)?"A":"M"?>" />
	<div align="left" style="margin-left:80px;">
		<p style="margin-bottom:8px; margin-left:45px;">
			<label class="FormLabelAzul" style="margin-right:8px;">Fecha</label>
			<input class="FormInputText" id="Fecha" maxlength="10" name="Fecha" style="width:80px;" type="text" title="Fecha" validar="true" validarFecha="true" value="<?= ($alta)?"":$row["FECHA"]?>" />
			<input class="BotonFecha" id="btnFecha" name="btnFecha" type="button" value="" style="vertical-align:-4px;">
		</p>
		<p style="margin-bottom:8px; margin-left:39px;">
			<label class="FormLabelAzul" style="margin-right:8px;">Fuente</label>
			<input class="FormInputText" id="Fuente" maxlength="128" name="Fuente" style="width:392px;" type="text" validar="true" title="Fuente" value="<?= ($alta)?"":$row["AP_FUENTE"]?>" />
		</p>
		<p style="margin-bottom:8px; margin-left:46px;">
			<label class="FormLabelAzul" style="margin-right:8px;">Título</label>
			<input class="FormInputText" id="Titulo" name="Titulo" style="width:392px;" title="Título" type="text" validar="true" value="<?= ($alta)?"":$row["AP_TITULO"]?>" />
		</p>
		<p style="margin-bottom:8px; margin-left:22px;">
			<label class="FormLabelAzul" style="margin-right:8px; vertical-align:top;">Contenido</label>
			<textarea class="FormTextArea" id="Contenido" name="Contenido" rows="8" style="width:400px;" title="Contenido" validar="true"><?= ($alta)?"":$row["AP_CONTENIDO"]->load()?></textarea>
		</p>
		<p style="margin-bottom:8px;">
			<label class="FormLabelAzul" style="margin-right:8px; vertical-align:top;">Nota Principal</label>
			<input <?= ($alta)?"checked":($row["AP_NOTAPRINCIPAL"] == "S")?"checked":""?> id="notaPrincipal" name="notaPrincipal" style="vertical-align:-4px;" type="checkbox" />
		</p>
		<p style="margin-left:90px;">
			<input class="BotonBlanco" id="btnGuardar" name="btnGuardar" type="submit" value="Guardar" />
			<input class="BotonBlanco" id="btnCancelar" name="btnCancelar" style="margin-left:16px; margin-right:16px;" type="button" value="Cancelar" onClick="history.go(-1);" />
			<input class="BotonBlanco" id="btnDarBaja" name="btnDarBaja" type="button" value="Dar de Baja" <?= ($alta)?"disabled":""?> onClick="darBaja()" />
		</p>
	</div>
</form>
<script>
	try {
		Calendar.setup (
			{
				inputField: "Fecha",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFecha"
			}
  	);
  }
	catch(err) {
		window.parent.setCalendar();
	}
	document.getElementById('Fecha').focus();
</script>