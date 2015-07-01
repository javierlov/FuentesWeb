<?
function busquedaEmpleadosAgregarCodigo($empleado, $cssDivLista = "", $mostrarTelefono = false, $foco = true, $urlOnClick = "") {
?>
	<iframe id="iframeBusquedaEmpleados" name="iframeBusquedaEmpleados" src="" style="display:none;"></iframe>
	<link href="/functions/busqueda_empleados/css/buscar_empleado.css" rel="stylesheet" type="text/css" />
	<script src="/functions/busqueda_empleados/js/busqueda_empleados.js" type="text/javascript"></script>
	<input id="mostrarTelefono" name="mostrarTelefono" type="hidden" value="<?= ($mostrarTelefono)?"t":"f"?>" />
	<input id="urlOnClick" name="urlOnClick" type="hidden" value="<?= $urlOnClick?>" />
	<input autocomplete="off" <?= ($foco)?"autofocus":""?> id="empleadoLista" name="empleadoLista" type="text" value="" onBlur="blurInput()" onFocus="focus()" onKeyPress="keyUp()" />
	<div id="divBusquedaListaEmpleados" style="<?= $cssDivLista?>"></div>
<?
}
?>