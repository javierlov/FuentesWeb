<?
function agregarItems($idPadre, $profundidad) {
	global $conn;

	$result = "";

	$params = array(":idpadre" => $idPadre);
	$sql =
		"SELECT de_id, de_idpadre, de_nombre, de_orden
			 FROM rrhh.rde_descargables
			WHERE de_idpadre = :idpadre
				AND de_fechabaja IS NULL
	 ORDER BY de_orden";
	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt)) {
?>
		<input id="padreItem_<?= $row["DE_ID"]?>" name="padreItem_<?= $row["DE_ID"]?>" type="hidden" value="<?= $row["DE_IDPADRE"]?>" />
		<input id="posicionItem_<?= $row["DE_ID"]?>" name="posicionItem_<?= $row["DE_ID"]?>" type="hidden" value="<?= $row["DE_ORDEN"]?>" />
		<div class="divMantenimientoItem" draggable="true" id="item_<?= $row["DE_ID"]?>" style="margin-left:<?= $profundidad * 12?>px;" onDblClick="editarItem(<?= $row["DE_ID"]?>)"><?= $row["DE_NOMBRE"]?></div>
		<div id="divNada"></div>
<?
		agregarItems($row["DE_ID"], ($profundidad + 1));
	}
}


if (!hasPermiso(100)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}
?>
<link href="/modules/mantenimiento/css/descargables.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/descargables.js" type="text/javascript"></script>
<iframe id="iframeDescargablePadre" name="iframeDescargablePadre" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/descargables/guardar_padre.php" id="formDescargablePadre" method="post" name="formDescargablePadre" target="iframeDescargablePadre">
<div id="divMantenimientoPadre">
	<div id="divMantenimientoItems">
<?
$sql =
	"SELECT de_id, de_nombre, de_orden
		 FROM rrhh.rde_descargables
		WHERE de_idpadre = -1
			AND de_fechabaja IS NULL
 ORDER BY de_orden";
$stmt = DBExecSql($conn, $sql);
while ($rowP = DBGetQuery($stmt)) {
?>
	<br />
	<input id="posicionPadre_<?= $rowP["DE_ID"]?>" name="posicionPadre_<?= $rowP["DE_ID"]?>" type="hidden" value="<?= $rowP["DE_ORDEN"]?>" />
	<div class="divMantenimientoPadre" id="padre_<?= $rowP["DE_ID"]?>">
		<div class="divMantenimientoPadreTexto" draggable="true" id="padreTexto_<?= $rowP["DE_ID"]?>" onDblClick="editarItem(<?= $rowP["DE_ID"]?>)"><?= $rowP["DE_NOMBRE"]?></div>
		<div id="divNada"></div>
<?
	agregarItems($rowP["DE_ID"], 1);
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