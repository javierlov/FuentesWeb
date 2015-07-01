<?
$alta = !isset($_REQUEST["id"]);
if (!$alta) {
	$sql = 
		"SELECT hn_idsectordesde, hn_idsectorhasta, hn_idusuario, hn_tipomovimiento
			 FROM rrhh.rhn_novedades
			WHERE hn_id = :id";
	$params = array(":id" => $_REQUEST["id"]);
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
}
?>
<iframe id="iframeNovedad" name="iframeNovedad" src="" style="display:none;"></iframe>
<form action="/modules/abm_novedades/procesar_novedad.php" id="formNovedad" method="post" name="formNovedad" target="iframeNovedad">
	<input id="id" name="id" type="hidden" value="<?= ($alta)?"":$_REQUEST["id"]?>" />
	<input id="TipoOp" name="TipoOp" type="hidden" value="<?= ($alta)?"A":"M"?>" />
	<div align="left">
		<div class="FormLabelBlanco" style="background-color:#c0c0c0;">
			<label style="margin-left:4px; margin-right:8px;">Seleccionar Usuario</label>
			<select class="Combo" id="Usuario" name="Usuario" validar="true" title="Usuario" <?= (!$alta)?"DISABLED":""?>></select>
		</div>
		<div style="margin-left:58px; margin-top:8px;">
			<label class="FormLabelAzul">Tipo Movimiento</label>
			<select class="Combo" id="TipoMovimiento" name="TipoMovimiento" validar="true" title="Tipo de Movimiento" onChange="cambiaTipoMovimiento()"></select>
		</div>
		<div style="margin-left:108px; margin-top:8px;">
			<label class="FormLabelAzul">Pasa de</label>
			<select class="Combo" id="SectorDesde" name="SectorDesde" style="width:292px;"></select>
			<label class="FormLabelAzul">a</label>
			<select class="Combo" id="SectorHasta" name="SectorHasta" style="width:292px;"></select>
		</div>
		<div style="margin-left:156px; margin-top:8px;">
			<input class="BotonBlanco" name="btnGuardar" type="button" value="GUARDAR" onClick="guardarNovedad()">
			<input class="BotonBlanco" name="btnCancelar" style="margin-left:16px; margin-right:16px;" type="button" value="CANCELAR" onClick="history.go(-1);">
			<input class="BotonBlanco" name="btnDarBaja" type="button" value="DAR DE BAJA" <?= ($alta)?"DISABLED":""?> onClick="darBaja()">
		</div>
	</div>
</form>
<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "Usuario";
$RCparams = array();
$RCquery =
	"SELECT se_id ID, se_nombre detalle
		 FROM use_usuarios
		WHERE (se_fechabaja IS NULL OR se_fechabaja >=(SYSDATE - 90))
			AND se_usuariogenerico = 'N'
 ORDER BY se_buscanombre";
$RCselectedItem = ($alta)?$_REQUEST["usuario"]:$row["HN_IDUSUARIO"];
FillCombo();

$RCfield = "TipoMovimiento";
$RCparams = array();
$RCquery =
	"SELECT 'A' ID, 'Ingreso' detalle
		FROM DUAL
UNION ALL
	 SELECT 'B' ID, 'Egreso' detalle
		FROM DUAL
UNION ALL
	 SELECT 'M' ID, 'Pase de Sector' detalle
		FROM DUAL
 ORDER BY 2";
$RCselectedItem = ($alta)?-1:$row["HN_TIPOMOVIMIENTO"];
FillCombo();

$RCfield = "SectorDesde";
$RCparams = array();
$RCquery =
	"SELECT se1.se_id ID,
					se1.se_descripcion || (SELECT DECODE(se1.se_descripcion, se2.se_descripcion, NULL, ' (' || se2.se_descripcion
														 || (SELECT DECODE(se2.se_descripcion, se3.se_descripcion, NULL, ' - ' || se3.se_descripcion)
																	 FROM computos.cse_sector se3
																	WHERE se3.se_nivel = 2
																		AND se3.se_id = se2.se_idsectorpadre) || ')')
																	 FROM computos.cse_sector se2
																	WHERE se2.se_nivel = 3
																		AND se2.se_fechabaja IS NULL
																		AND se2.se_id = se1.se_idsectorpadre) detalle
		 FROM computos.cse_sector se1
		WHERE se1.se_nivel = 4
 ORDER BY 2";
$RCselectedItem = ($alta)?-1:$row["HN_IDSECTORDESDE"];
FillCombo();

$RCfield = "SectorHasta";
$RCparams = array();
$RCquery =
	"SELECT se1.se_id ID,
					se1.se_descripcion || (SELECT DECODE(se1.se_descripcion, se2.se_descripcion, NULL, ' (' || se2.se_descripcion
														 || (SELECT DECODE(se2.se_descripcion, se3.se_descripcion, NULL, ' - ' || se3.se_descripcion)
																	 FROM computos.cse_sector se3
																	WHERE se3.se_nivel = 2
																		AND se3.se_id = se2.se_idsectorpadre) || ')')
																	 FROM computos.cse_sector se2
																	WHERE se2.se_nivel = 3
																		AND se2.se_fechabaja IS NULL
																		AND se2.se_id = se1.se_idsectorpadre) detalle
		 FROM computos.cse_sector se1
		WHERE se1.se_nivel = 4
 ORDER BY 2";
$RCselectedItem = ($alta)?-1:$row["HN_IDSECTORHASTA"];
FillCombo();
?>
cambiaTipoMovimiento();
<?
  if ($alta) {
?>
  document.getElementById('Usuario').focus();
<?
}
else {
?>
	document.getElementById('TipoMovimiento').focus();
<?
}
?>
</script>