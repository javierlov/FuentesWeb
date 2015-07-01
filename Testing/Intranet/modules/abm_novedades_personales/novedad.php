<?
$alta = !isset($_REQUEST["id"]);
if (!$alta) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql = 
		"SELECT np_texto, np_tiponovedad, np_titulo
			 FROM rrhh.rnp_novedadespersonales
			WHERE np_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
}
?>
<iframe id="iframeNovedad" name="iframeNovedad" src="" style="display:none;"></iframe>
<div align="left" style="margin-left:40px;">
	<form action="/modules/abm_novedades_personales/procesar_novedad.php" enctype="multipart/form-data" id="formNovedad" method="post" name="formNovedad" target="iframeNovedad">
		<input id="id" name="id" type="hidden" value="<?= ($alta)?"":$_REQUEST["id"]?>">
		<input id="tipoOp" name="tipoOp" type="hidden" value="<?= ($alta)?"A":"M"?>">
		<p>
			<label class="FormLabelAzul">Tipo de Novedad</label>
			<select class="Combo" id="tipoNovedad" name="tipoNovedad"></select>
		</p>
		<p style="margin-left:62px; margin-top:8px;">
			<label class="FormLabelAzul" style="vertical-align:top;">Título</label>
			<input class="FormTextArea" id="titulo" maxlength="255" name="titulo" style="width:417px;" type="text" value="<?= ($alta)?"":$row["NP_TITULO"]?>">
		</p>
		<p style="margin-left:62px; margin-top:8px;">
			<label class="FormLabelAzul" style="vertical-align:top;">Texto</label>
			<textarea class="FormTextArea" cols="80" id="texto" name="texto" rows="8"><?= ($alta)?"":$row["NP_TEXTO"]?></textarea>
		</p>
		<p style="margin-left:53px; margin-top:8px;">
			<label class="FormLabelAzul">Imagen</label>
			<input class="InputText" id="imagen" name="imagen" style="width:418px;" type="file" />
<?
if (!$alta) {
	if (file_exists(DATA_CELEBRACIONES_PATH.$_REQUEST["id"].".gif"))
		$img = base64_encode(DATA_CELEBRACIONES_PATH.$_REQUEST["id"].".gif");
	elseif (file_exists(DATA_CELEBRACIONES_PATH.$_REQUEST["id"].".jpeg"))
		$img = base64_encode(DATA_CELEBRACIONES_PATH.$_REQUEST["id"].".jpeg");
	elseif (file_exists(DATA_CELEBRACIONES_PATH.$_REQUEST["id"].".jpg"))
		$img = base64_encode(DATA_CELEBRACIONES_PATH.$_REQUEST["id"].".jpg");
	elseif (file_exists(DATA_CELEBRACIONES_PATH.$_REQUEST["id"].".png"))
		$img = base64_encode(DATA_CELEBRACIONES_PATH.$_REQUEST["id"].".png");
?>
			<a href="/functions/get_image.php?file=<?= $img?>" target="_blank"><img border="0" src="/functions/get_image.php?file=<?= $img ?>" style="cursor:hand; height:20px; margin-left:16px; vertical-align:-6px;" title="Clic aquí para ver la imagen" /></a>
<?
}
?>
		</p>
		<p style="margin-bottom:8px; margin-left:99px; margin-top:16px;">
			<input class="BotonBlanco" id="btnGuardar" name="btnGuardar" type="submit" value="Guardar">
			<input class="BotonBlanco" id="btnCancelar" name="btnCancelar" style="margin-left:16px; margin-right:16px;" type="button" value="Cancelar" onClick="history.go(-1);">
			<input class="BotonBlanco" id="btnDarBaja" name="btnDarBaja" type="button" value="Dar de Baja" <?= ($alta)?"DISABLED":""?> onClick="darBaja()">
			<input class="BotonBlanco" id="btnComentarios" name="btnComentarios" style="margin-left:118px;" type="button" value="Ver Comentarios" onClick="verComentarios(32, <?= $_REQUEST["id"]?>)">
		</p>
	</form>
	<p id="guardadoOk" style="background:#00a3e4; color:#fff; display:none; margin-left:120px; padding:4px; width:252px;">Los datos fueron guardados exitosamente.</p>
	<div id="divErrores" style="display:none; margin-left:98px; margin-top:8px;">
		<table border="1" bordercolor="#ff0000" cellpadding="6" cellspacing="0">
			<tr>
				<td>
					<table cellpadding="4" cellspacing="0">
						<tr>
							<td><img border="0" src="/images/atencion.jpg"></td>
							<td class="ContenidoSeccion">
								<font color="#000000">
									No es posible continuar mientras no se corrijan los siguientes errores:
									<br />
									<br />
									<span id="errores"></span>
								</font>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<input id="foco" name="foco" readonly style="height:1px; width:1px;" type="checkbox" />
	</div>
</div>
<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "tipoNovedad";
$RCparams = array();
$RCquery =
	"SELECT   'C' ID, 'Casamiento' detalle
   		 FROM DUAL
	UNION ALL
	 SELECT   'G' ID, 'Graduación' detalle
   		 FROM DUAL
	UNION ALL
	 SELECT   'N' ID, 'Nacimiento' detalle
   		 FROM DUAL
	 ORDER BY 2";
$RCselectedItem = ($alta)?-1:$row["NP_TIPONOVEDAD"];
FillCombo();
?>
	document.getElementById('tipoNovedad').focus();
</script>