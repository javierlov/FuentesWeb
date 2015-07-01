<?
$alta = !isset($_REQUEST["id"]);
if (!$alta) {
	$sql = 
		"SELECT bc_idempresa, bc_idestado, bc_nombrearchivo, bc_puesto
			FROM rrhh.rbc_busquedascorporativas
		 WHERE bc_id = :id";
	$params = array(":id" => $_REQUEST["id"]);
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	if ($row["BC_NOMBREARCHIVO"] != "") {
		$partesFile = pathinfo($row["BC_NOMBREARCHIVO"]);
		$file = base64_encode(DATA_BUSQUEDAS_CORPORATIVAS_PATH.$_REQUEST["id"].".".$partesFile["extension"]);
	}
}
?>
<iframe id="iframeBusquedaCorporativa" name="iframeBusquedaCorporativa" src="" style="display:none;"></iframe>
<form action="/modules/abm_busquedas_corporativas/procesar_busqueda_corporativa.php" enctype="multipart/form-data" id="formBusquedaCorporativa" method="post" name="formBusquedaCorporativa" target="iframeBusquedaCorporativa" onSubmit="return ValidarForm(formBusquedaCorporativa)">
	<input id="id" name="id" type="hidden" value="<?= ($alta)?"":$_REQUEST["id"]?>">
	<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="20000000">
	<input id="tipoOp" name="tipoOp" type="hidden" value="<?= ($alta)?"A":"M"?>">
	<div align="left">
		<p style="margin-bottom:2px; margin-left:92px;">
			<label class="FormLabelAzul" for="numero">Número</label>
			<input class="FormInputText" id="numero" name="numero" size="4" type="text" value="<?= ($alta)?"":$_REQUEST["id"]?>" readonly>
		</p>
		<p style="margin-bottom:2px; margin-left:97px;">
			<label class="FormLabelAzul" for="puesto">Puesto</label>
			<input class="FormInputText" id="puesto" maxlength="128" name="puesto" size="100" title="Puesto" type="text" validar="true" value="<?= ($alta)?"":$row["BC_PUESTO"]?>">
		</p>
		<p style="margin-bottom:2px; margin-left:88px;">
			<label class="FormLabelAzul" for="estado">Empresa</label>
			<select class="Combo" id="empresa" name="empresa" title="Empresa" validar="true"></select>
		</p>
		<p style="margin-bottom:2px; margin-left:98px;">
			<label class="FormLabelAzul" for="estado">Estado</label>
			<select class="Combo" id="estado" name="estado" title="Estado" validar="true"></select>
		</p>
		<p style="margin-bottom:2px; margin-left:96px;">
			<label class="FormLabelAzul" for="archivo">Archivo</label>
			<input class="FormInputText" ext="doc,docx,pdf" id="archivo" name="archivo" size="40" type="file" validarArchivo="true">
<?
if ((!$alta) and ($row["BC_NOMBREARCHIVO"] != "")) {
?>
			<a class="FormLabelAzul" href="#" style="cursor:hand; margin-left:16px; text-decoration:none;" onClick="window.open('<?= "/functions/get_file.php?fl=".$file?>', 'intranetWindow');">Ver archivo "<?= $row["BC_NOMBREARCHIVO"]?>"</a>
			
<?
}
?>
		</p>
		<p style="margin-bottom:8px;">
			<hr color="#c0c0c0" size="1" style="border-bottom-style:dotted; border-bottom-width: 1px; border-left-width:1px; border-right-width:1px; border-top-width:1px;">
		</p>
		<div align="right" style="margin-right:8px;">
			<input class="BotonBlanco" name="btnGuardar" type="submit" value="Guardar">
			<input class="BotonBlanco" name="btnCancelar" style="margin-left:8px;" type="button" value="Cancelar" onClick="history.go(-1);">
<?
if (!$alta) {
?>
			<input class="BotonBlanco" name="btnDarBaja" style="margin-left:8px;" type="button" value="Dar de Baja" onClick="darBaja()">
<?
}
?>
		</div>
	</tr>
</table>
</form>
<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "empresa";
$RCparams = array();
$RCquery =
	"SELECT em_id ID, em_nombre detalle
		FROM aem_empresa
	 WHERE em_idgrupoeconomico = 88
UNION ALL
	 SELECT -2, 'PROVINCIA A.R.T.'
		FROM DUAL
UNION ALL
	 SELECT -3, 'INVIERTA BUENOS AIRES'
		FROM DUAL
ORDER BY 2";
$RCselectedItem = ($alta)?-1:$row["BC_IDEMPRESA"];
FillCombo();

$RCfield = "estado";
$RCparams = array();
$RCquery =
	"SELECT ec_id ID, ec_detalle detalle
		FROM rrhh.rec_estadosbusquedacorporativa
	 WHERE ec_fechabaja IS NULL
ORDER BY 2";
$RCselectedItem = ($alta)?-1:$row["BC_IDESTADO"];
FillCombo();
?>
	document.getElementById('puesto').focus();
</script>