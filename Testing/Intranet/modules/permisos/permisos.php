<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$modulePath = "/Modules/Permisos/";

$params = array(":usuario" => GetWindowsLoginName());
$sql =
	"SELECT 1
		 FROM use_usuarios
		WHERE se_usuario IN ('ALAPACO', 'FPEREZ')
			AND UPPER(se_usuario) = UPPER(:usuario)";
if (!ExisteSql($sql, $params)) {
	ShowError('Permisos', "Usted no tiene permiso para ingresar a esta página.");
	exit;
}

$params = array(":id" => $_REQUEST["pageid"]);
$sql = 
	"SELECT pi_nombre
		 FROM web.wpi_paginasintranet
		WHERE pi_id = :id";
$pageName = str_replace('%s', ValorSql($sql, "", $params), GetPageTitle(3));
?>
<html>
<head>
<?= GetHead($pageName, array("style.css"))?>
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<meta http-equiv="Pragma" content="no-cache" />
	<script language="JavaScript" src="<?= $modulePath?>js/permisos.js"></script>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" id="tableMain" width="100%">
	<tr>
		<td align="center" class="TituloHeaderNegro"><?= substr($pageName, 0, strpos($pageName, ':') - 1)?></td>
	</tr>
</table>
<iframe id="iframePermisos" name="iframePermisos" src="" style="display:none;"></iframe>
<form action="procesar_permisos.php" id="formPermisos" method="post" name="formPermisos" target="iframePermisos">
<input id="PageId" name="PageId" type="hidden" value="">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="8"></td>
		<td class="FormLabelAzul" width="160">Usuarios SIN permiso</td>
		<td width="16"></td>
		<td width="64"></td>
		<td width="16"></td>
		<td class="FormLabelAzul" width="160">Usuarios CON permiso</td>
		<td width="400"></td>
	</tr>
	<tr>
		<td></td>
		<td><select class="Combo" id="UsuariosSinPermiso" name="UsuariosSinPermiso" size="10" style="width:160" multiple></select></td>
		<td></td>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="center"><input alt="sss" class="BotonBlanco" id="btnAgregarTodos" name="btnAgregarTodos" style="width:40" type="button" value=">>" onClick="PreAgregarTodos('UsuariosSinPermiso', 'UsuariosConPermiso[]')"></td>
				</tr>
				<tr height="8">
					<td></td>
				</tr>
				<tr>
					<td align="center"><input class="BotonBlanco" id="btnAgregar" name="btnAgregar" style="width:40" type="button" value=">" onClick="PreAgregarUsuarios('UsuariosSinPermiso', 'UsuariosConPermiso[]')"></td>
				</tr>
				<tr height="8">
					<td></td>
				</tr>
				<tr>
					<td align="center"><input class="BotonBlanco" id="btnQuitar" name="btnQuitar" style="width:40" type="button" value="<" onClick="PreAgregarUsuarios('UsuariosConPermiso[]', 'UsuariosSinPermiso')"></td>
				</tr>
				<tr height="8">
					<td></td>
				</tr>
				<tr>
					<td align="center"><input class="BotonBlanco" id="btnQuitarTodos" name="btnQuitarTodos" style="width:40" type="button" value="<<" onClick="PreAgregarTodos('UsuariosConPermiso[]', 'UsuariosSinPermiso')"></td>
				</tr>
			</table>
		</td>
		<td></td>
		<td><select class="Combo" id="UsuariosConPermiso[]" name="UsuariosConPermiso[]" size="10" style="width:160" multiple></select></td>
		<td></td>
	</tr>
	<tr height="16">
		<td colspan="7"></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td align="right" colspan="4"><input class="BotonBlanco" id="btnGuardar" name="btnGuardar" type="button" value="Guardar" onClick="Guardar()">&nbsp;<input class="BotonBlanco" id="btnCancelar" name="btnCancelar" type="button" value="Cancelar" onClick="window.close()"></td>
		<td></td>
	</tr>
	<tr id="trProcesando" name="trProcesando" style="display:none">
		<td></td>
		<td class="FormLabelRojo" colspan="6">Procesando, aguarde por favor...</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td colspan="5"><span class="Mensaje" id="spanMensaje" name="spanMensaje" style="display:none" onMouseMove="OcultarMensajeOk()">Los datos se guardaron correctamente.</span></td>
	</tr>
</table>
</form>
<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "UsuariosSinPermiso";
$RCparams = array(":idpagina" => $_REQUEST["pageid"]);
$RCquery = 
	"SELECT se_id ID, se_usuario detalle
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_id NOT IN(SELECT pe_idusuario
												 FROM web.wpe_permisosintranet
												WHERE pe_idpagina = :idpagina)
			AND se_usuariogenerico = 'N'
 ORDER BY 2";
$RCselectedItem = -1;
FillCombo(false);

$RCfield = "UsuariosConPermiso[]";
$RCparams = array(":idpagina" => $_REQUEST["pageid"]);
$RCquery = 
	"SELECT se_id ID, se_usuario detalle
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_id IN(SELECT pe_idusuario
										 FROM web.wpe_permisosintranet
										WHERE pe_idpagina = :idpagina)
			AND se_usuariogenerico = 'N'
 ORDER BY 2";
$RCselectedItem = -1;
FillCombo(false);
?>
document.getElementById('PageId').value = <?= $_REQUEST["pageid"]?>;
document.getElementById('UsuariosSinPermiso').focus();
</script>
</body>
</html>