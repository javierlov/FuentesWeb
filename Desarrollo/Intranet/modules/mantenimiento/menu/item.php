<?
if (!hasPermiso(87)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}


$isAlta = ($_REQUEST["id"] == 0);

if (!$isAlta) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT *
			 FROM web.wmi_menuintranet
			WHERE mi_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
}

$dir = SITE_PATH."/images/encabezado_secciones/";
$encabezados = array();

if (is_dir($dir))
	if ($gd = opendir($dir)) {
		while (($file = readdir($gd)) !== false)
			if (($file != ".") and ($file != ".."))
				$encabezados[] = substr($file, 0, strlen($file) - 4);
		closedir($gd);
	}

require_once("item_combos.php");
?>
<link href="/modules/mantenimiento/css/menu.css" rel="stylesheet" type="text/css" />
<script src="/js/jscolor/jscolor.js" type="text/javascript"></script>
<script src="/modules/mantenimiento/js/menu.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/menu/guardar_item.php" id="formAbmItem" method="post" name="formAbmItem" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div>
		<div class="fila">
			<label for="menuPadre">Menú Padre</label>
			<?= $comboMenuPadre->draw();?>
		</div>
		<div class="fila">
			<label for="texto">Texto</label>
			<input id="texto" maxlength="70" name="texto" type="text" value="<?= ($isAlta)?"":$row["MI_TEXTO"]?>" />
		</div>
		<div class="fila">
			<label for="color">Color</label>
			<input class="color" id="color" maxlength="10" name="color" title="Clic aquí para seleccionar el color del cuadro del item" type="text" value="<?= ($isAlta)?"":$row["MI_COLOR"]?>" />
		</div>
		<div class="fila">
			<label for="orden">Orden</label>
			<input id="orden" maxlength="3" name="orden" type="text" value="<?= ($isAlta)?"":$row["MI_ORDEN"]?>" />
		</div>
		<div class="fila">
			<label for="encabezado">Encabezado</label>
			<select id="encabezado" name="encabezado" onChange="cambiaImagenEncabezado()">
<?
foreach ($encabezados as $key => $value) {
?>
	<option value="<?= $value?>" <?= ($row["MI_IMAGENCABECERA"] == $value)?"selected":""?>><?= $value?></option>
<?
}
?>
			</select>
			<img id="imgEncabezado" src="/images/encabezado_secciones/default.jpg" onMouseOut="achicarEncabezado()" onMouseOver="agrandarEncabezado()" />
		</div>
		<div class="fila">
			<label for="link">Link</label>
			<input id="link" maxlength="255" name="link" type="text" value="<?= ($isAlta)?"":$row["MI_URL"]?>" />
		</div>
		<div class="fila">
			<label for="destino">Destino</label>
			<?= $comboDestino->draw();?>
		</div>
		<div class="fila">
			<label for="activo">Activo</label>
			<input <?= ($isAlta)?"":(($row["MI_ACTIVO"] == "S")?"checked":"")?> id="activo" name="activo" type="checkbox" value="ok" />
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
	cambiaImagenEncabezado();
</script>