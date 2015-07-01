<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");


function agregarItems($idPadre, $selectedValue, $profundidad) {
	global $conn;

	$result = "";
	if ($idPadre == -1)
		$result = "<option value=\"-1\">* ITEM RAÍZ *</option>";

	$params = array(":idpadre" => $idPadre);
	$sql =
		"SELECT de_id, de_idpadre, de_nombre
			 FROM rrhh.rde_descargables
			WHERE de_idpadre = :idpadre
				AND de_nombrearchivo IS NULL
				AND de_fechabaja IS NULL
	 ORDER BY de_orden";
	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt))
		$result.= "<option ".(($selectedValue == $row["DE_ID"])?"selected":"")." value=\"".$row["DE_ID"]."\">".str_repeat(".", ($profundidad * 3)).$row["DE_NOMBRE"]."</option>".agregarItems($row["DE_ID"], $selectedValue, ($profundidad + 1));

	return $result;
}


if (!hasPermiso(100)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}


$isAlta = ($_REQUEST["id"] == 0);

if (!$isAlta) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT *
			 FROM rrhh.rde_descargables
			WHERE de_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	if ($row["DE_NOMBREARCHIVO"] != "")
		$file = base64_encode(DATA_DESCARGABLES_PATH.armPathFromNumber($_REQUEST["id"]).$row["DE_NOMBREARCHIVO"]);
}

require_once("item_combos.php");
?>
<link href="/modules/mantenimiento/css/descargables.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/descargables.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/descargables/guardar_item.php" enctype="multipart/form-data" id="formAbmItem" method="post" name="formAbmItem" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="20000000">
	<div>
		<div class="fila">
			<label for="itemPadre">Item Padre</label>
			<?= $comboItemPadre->draw();?>
		</div>
		<div class="fila">
			<label for="texto">Nombre</label>
			<input id="nombre" maxlength="255" name="nombre" type="text" value="<?= ($isAlta)?"":$row["DE_NOMBRE"]?>" />
		</div>
		<div class="fila" id="divArchivo">
			<label for="texto">Archivo</label>
			<input id="archivo" name="archivo" type="file" value="" />
<?
if ((!$isAlta) and ($row["DE_NOMBREARCHIVO"] != "")) {
?>
			<a href="<?= "/archivo/".$file?>" target="_blank">Ver archivo "<?= $row["DE_NOMBREARCHIVO"]?>"</a>
			
<?
}
?>
		</div>
		<div class="fila">
			<label for="orden">Orden</label>
			<input id="orden" maxlength="3" name="orden" type="text" value="<?= ($isAlta)?"":$row["DE_ORDEN"]?>" />
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
		<input id="btnGuardar" name="btnGuardar" type="button" onClick="guardarItem()" />
		<img id="imgProcesando" src="/images/loading.gif" title="Procesando, aguarde unos segundos por favor..." />
		<input id="btnCancelar" name="btnCancelar" type="button" onClick="cancelarItem()" />
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
<script>
	document.getElementById('itemPadre').innerHTML = '<?= agregarItems(-1, (($isAlta)?-1:$row["DE_IDPADRE"]), 0);?>';
	cambiarItemPadre(document.getElementById('itemPadre').value);
</script>