<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/control_gestion/tablero_de_control/ver_permisos.php");
require_once("permisos_combos.php");


if (!verPermisos()) {
	echo "Usted no tiene permiso para entrar a este módulo";
	exit;
}

if ($_REQUEST["o"] == "t") {		// Entró desde el tablero de control..
	$urlVolver = "/tablero-control";
	$textoLink = " al Tablero de Control";
}
if ($_REQUEST["o"] == "i") {		// Entró desde informes de gestión..
	$urlVolver = "/index.php?pageid=34";
	$textoLink = " a Informes de Gestión";
}

$_SESSION["permisosControlGestion"] = array();
$sql =
	"SELECT pt_ejecutivo, pt_gestion, pt_informesgestion, pt_nivelejecutivo, pt_operativo, pt_usuario
		 FROM web.wpt_permisostablerocontrol
		WHERE pt_fechabaja IS NULL";
$stmt = DBExecSql($conn, $sql);
$primerUsuario = "";

while ($row = DBGetQuery($stmt)) {
	if ($primerUsuario == "")
		$primerUsuario = $row["PT_USUARIO"];
	$_SESSION["permisosControlGestion"][$row["PT_USUARIO"]] = array($row["PT_EJECUTIVO"], $row["PT_NIVELEJECUTIVO"], $row["PT_GESTION"], $row["PT_OPERATIVO"], $row["PT_INFORMESGESTION"]);
}
?>
<html>
	<head>
		<title>Tablero de Control</title>
		<link href="/modules/control_gestion/permisos/css/permisos.css" rel="stylesheet" type="text/css" />
		<script language="JavaScript" src="/js/functions.js"></script>
		<script language="JavaScript" src="/modules/control_gestion/permisos/js/permisos.js"></script>
	</head>
	<body>
		<iframe id="iframePermiso" name="iframePermiso" src="" style="display:none;"></iframe>
		<form action="/modules/control_gestion/permisos/procesar_permiso.php" id="formPermisos" method="post" name="formPermisos" target="iframePermiso">
			<div align="center">
				<img src="/modules/control_gestion/tablero_de_control/images/titulo_permisos.png">
				<div id="divControles">
					<div id="divUsuariosSinPermiso">
						<div id="divUsuariosSinPermisoTitulo">Usuarios <b>SIN</b> Permiso</div>
						<?= $comboUsuariosSinPermiso->draw();?>
					</div>
					<div id="divControles2">
						<div><input id="btnAgregarTodos" name="btnAgregarTodos" title="Agregar Todos" type="button" value="&gt;&gt;" onClick="preAgregarTodos('usuariosSinPermiso', 'usuariosConPermiso[]')" /></div>
						<div><input id="btnAgregar" name="btnAgregar" title="Agregar" type="button" value="&gt;" onClick="preAgregarUsuarios('usuariosSinPermiso', 'usuariosConPermiso[]')" /></div>
						<div><input id="btnQuitar" name="btnQuitar" title="Quitar" type="button" value="&lt;" onClick="preAgregarUsuarios('usuariosConPermiso[]', 'usuariosSinPermiso')" /></div>
						<div><input id="btnQuitarTodos" name="btnQuitarTodos" title="Quitar Todos" type="button" value="&lt;&lt;" onClick="preAgregarTodos('usuariosConPermiso[]', 'usuariosSinPermiso')" /></div>
					</div>
					<div id="divUsuariosConPermiso">
						<div id="divUsuariosConPermisoTitulo">Usuarios <b>CON</b> Permiso</div>
						<?= $comboUsuariosConPermiso->draw();?>
					</div>
				</div>
				<div class="formLabelRojo" id="trProcesando">Procesando, aguarde por favor...</div>
				<hr />
				<div id="divChecks">
					<p>
						<label class="formLabel" for="ejecutiva" id="labelEjecutiva">Sistema de Información Ejecutiva</label>
						<input id="ejecutiva" name="ejecutiva" type="checkbox" onClick="clicCheck(this)" />
						<label class="formLabel" for="nivel" id="labelNivel">Nivel</label>
						<input class="formInputText" id="nivel" maxlength="1" name="nivel" type="text" onBlur="exitNivel(this.value)" />
					</p>
					<p>
						<label class="formLabel" for="gestion">Sistema de Información de Gestión</label>
						<input id="gestion" name="gestion" type="checkbox" onClick="clicCheck(this)" />
					</p>
					<p>
						<label class="formLabel" for="operativa">Sistema de Información Operativa</label>
						<input id="operativa" name="operativa" type="checkbox" onClick="clicCheck(this)" />
					</p>
					<p>
						<label class="formLabel" for="informesGestion">Módulo de Informes de Gestión</label>
						<input id="informesGestion" name="informesGestion" type="checkbox" onClick="clicCheck(this)" />
					</p>
					<p>
						<input class="boton" name="btnGuardar" type="button" value="GUARDAR" onClick="guardar()">
						<span class="formLabel" id="spanVolver" onClick="window.location.href='<?= $urlVolver?>'">Volver<?= $textoLink?></span>
					</p>
					<p id="guardadoOk">&nbsp;Datos guardados exitosamente.</p>
				</div>
			</div>
		</form>
		<div id="divErrores">
			<table bordercolor="#ff0000" align="center" cellpadding="6" cellspacing="0">
				<tr>
					<td>
						<table cellpadding="4" cellspacing="0">
							<tr>
								<td><img src="/images/atencion.png"></td>
								<td>
									<font color="#000000">
										No es posible continuar mientras no se corrijan los siguientes errores:<br /><br />
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
		<script>
			cambiarUsuario('<?= $primerUsuario?>');
		</script>
	</body>
</html>