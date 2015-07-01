<?
if (!hasPermiso(75)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}
?>
<link href="/modules/mantenimiento/css/menu.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/menu.js" type="text/javascript"></script>
<iframe id="iframeMenu" name="iframeMenu" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/menu/guardar_menu.php" id="formMenu" method="post" name="formMenu" target="iframeMenu">
<div id="divMantenimientoMenu">
	<div id="divMantenimientoItems">
<?
$sql =
	"SELECT mi_activo, mi_id, mi_orden, mi_texto
		 FROM web.wmi_menuintranet
		WHERE mi_idpadre = -1
			AND mi_fechabaja IS NULL
 ORDER BY mi_orden";
$stmt = DBExecSql($conn, $sql);
while ($rowP = DBGetQuery($stmt)) {
?>
	<br />
	<input id="posicionPadre_<?= $rowP["MI_ID"]?>" name="posicionPadre_<?= $rowP["MI_ID"]?>" type="hidden" value="<?= $rowP["MI_ORDEN"]?>" />
	<div class="divMantenimientoPadre" id="padre_<?= $rowP["MI_ID"]?>">
		<div class="divMantenimientoPadreTexto <?= ($rowP["MI_ACTIVO"] == "S")?"":"itemInactivo"?>" draggable="true" id="padreTexto_<?= $rowP["MI_ID"]?>" onDblClick="editarItem(<?= $rowP["MI_ID"]?>)"><?= $rowP["MI_TEXTO"]?></div>
		<div id="divNada"></div>
<?
	$params = array(":idpadre" => $rowP["MI_ID"]);
	$sql =
		"SELECT mi_activo, mi_id, mi_idpadre, mi_orden, mi_texto
			 FROM web.wmi_menuintranet
			WHERE mi_idpadre = :idpadre
				AND mi_fechabaja IS NULL
	 ORDER BY mi_orden";
	$stmt2 = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt2)) {
?>
		<input id="padreItem_<?= $row["MI_ID"]?>" name="padreItem_<?= $row["MI_ID"]?>" type="hidden" value="<?= $row["MI_IDPADRE"]?>" />
		<input id="posicionItem_<?= $row["MI_ID"]?>" name="posicionItem_<?= $row["MI_ID"]?>" type="hidden" value="<?= $row["MI_ORDEN"]?>" />
		<div class="divMantenimientoItem <?= ($row["MI_ACTIVO"] == "S")?"":"itemInactivo"?>" draggable="true" id="item_<?= $row["MI_ID"]?>" onDblClick="editarItem(<?= $row["MI_ID"]?>)"><?= $row["MI_TEXTO"]?></div>
		<div id="divNada"></div>
<?
	}
?>
	</div>
<?
}
?>
	</div>
</div>
<div id="divBotones">
	<input id="btnAgregar" name="btnAgregar" type="button" onClick="agregarItem()" />
	<input id="btnGuardar" name="btnGuardar" type="button" onClick="guardar()" />
	<img id="imgProcesando" src="/images/loading.gif" title="Procesando, aguarde unos segundos por favor..." />
	<input id="btnCancelar" name="btnCancelar" type="button" onClick="cancelar()" />
</div>
</form>
<script type="text/javascript">
var dragSrcEl = null;
addEvents();
</script>