<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$params = array(":usuario" => getWindowsLoginName());
$sql =
	"SELECT 1
		 FROM use_usuarios
		WHERE se_usuario IN ('ALAPACO', 'FPEREZ')
			AND UPPER(se_usuario) = UPPER(:usuario)";
if (!existeSql($sql, $params)) {
	showError('Permisos', "Usted no tiene permiso para ingresar a esta página.");
	exit;
}

if (isPublicPage($_REQUEST["pageid"])) {
	showError('Permisos', "Esta página es pública por lo tanto no se le puede configurar permisos.");
	exit;
}


$params = array(":id" => $_REQUEST["pageid"]);
$sql = 
	"SELECT pi_nombre
		 FROM web.wpi_paginasintranet
		WHERE pi_id = :id";
$pageName = str_replace("%s", valorSql($sql, "", $params), "Permisos sobre la página -%s-");

require_once("permisos_combos.php");
?>
<html>
	<head>
		<title>Permisos</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Language" content="Spanish" />
		<link href="/css/style.css" rel="stylesheet" type="text/css" />
		<link href="/modules/permisos/css/permisos.css" rel="stylesheet" type="text/css" />
		<script language="JavaScript" src="/js/functions.js"></script>
		<script language="JavaScript" src="/modules/permisos/js/permisos.js"></script>
	</head>

	<body>
		<iframe id="iframePermisos" name="iframePermisos" src="" style="display:none;"></iframe>

		<h2><?= substr($pageName, 0, strpos($pageName, ':') - 1)?></h2>
		<form action="/modules/permisos/procesar_permisos.php" id="formPermisos" method="post" name="formPermisos" target="iframePermisos">
			<input id="pageId" name="pageId" type="hidden" value="<?= $_REQUEST["pageid"]?>" />
			<div id="divForm">
				<div id="divUsuariosSinPermiso">
					<label class="labelCampos">Usuarios SIN permiso</label>
					<br />
					<?= $comboUsuariosSinPermiso->draw();?>
				</div>
				<div id="divBotones">
					<input id="btnAgregarTodos" name="btnAgregarTodos" title="Agregar a Todos" type="button" value=">>" onClick="preAgregarTodos('usuariosSinPermiso', 'usuariosConPermiso[]')" />
					<br /><br />
					<input id="btnAgregar" name="btnAgregar" title="Agregar" type="button" value=">" onClick="preAgregarUsuarios('usuariosSinPermiso', 'usuariosConPermiso[]')" />
					<br /><br />
					<input id="btnQuitar" name="btnQuitar" title="Quitar" type="button" value="<" onClick="preAgregarUsuarios('usuariosConPermiso[]', 'usuariosSinPermiso')" />
					<br /><br />
					<input id="btnQuitarTodos" name="btnQuitarTodos" title="Quitar a Todos" type="button" value="<<" onClick="preAgregarTodos('usuariosConPermiso[]', 'usuariosSinPermiso')" />
				</div>
				<div id="divUsuariosConPermiso">
					<label class="labelCampos">Usuarios CON permiso</label>
					<br />
					<?= $comboUsuariosConPermiso->draw();?>
				</div>
				<div id="divNada"></div>
			</div>
			<div id="divBotonesBottom">
				<input id="btnGuardar" name="btnGuardar" type="button" value="Guardar" onClick="guardar()" />
				<input id="btnCancelar" name="btnCancelar" type="button" value="Cancelar" onClick="window.close();" />
			</div>
			<div id="divMsgProcesando">Procesando, aguarde por favor...</div>
			<div id="divMsgOk" onMouseMove="ocultarMensajeOk()">Los datos se guardaron correctamente.</div>
		</form>

		<hr />

		<h2>COPIAR PERFIL</h2>
		<h4>(copia el perfil de todas las páginas, no solo de la página actual)</h4>
		<form action="/modules/permisos/copiar_perfil.php" id="formPerfiles" method="post" name="formPerfiles" target="iframePermisos">
			<input id="pageId" name="pageId" type="hidden" value="<?= $_REQUEST["pageid"]?>" />
			<div id="divForm">
				<div id="divUsuariosSinPermiso">
					<label class="labelCampos">Usuario Origen</label>
					<br />
					<?= $comboUsuarioOrigen->draw();?>
				</div>
				<div id="divBotonesCopiar">
					<input id="btnCopiar" name="btnCopiar" title="Copiar" type="button" value=">" onClick="copiar()" />
				</div>
				<div id="divUsuariosConPermiso">
					<label class="labelCampos">Usuarios Destino</label>
					<br />
					<?= $comboUsuariosDestino->draw();?>
				</div>
				<div id="divNada"></div>
			</div>
			<div id="divMsgOk2" onMouseMove="ocultarMensajeOk()">Los datos se guardaron correctamente.</div>
		</form>
	</body>
</html>