<?
// INICIO - Validación de la sesión..
if (isset($_SESSION["isCliente"]))
	validarSesion(isset($_SESSION["isCliente"]));
else
	validarSesion((isset($_SESSION["EsAltaAdministrador"])) or (isset($_SESSION["UsuarioIdIngresoPrimeraVez"])));

$esAltaAdministrador = ((isset($_SESSION["EsAltaAdministrador"])) and ($_SESSION["EsAltaAdministrador"]));
$esIngresoPrimeraVezUsuarioRaso = isset($_SESSION["UsuarioIdIngresoPrimeraVez"]);

if ((!$esAltaAdministrador) and (!$esIngresoPrimeraVezUsuarioRaso))
	validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 69));
// FIN - Validación de la sesión..


if ($esAltaAdministrador) {
	$params = array(":cuit" => $_SESSION["AltaAdministradorCuit"]);
	$sql = "SELECT em_nombre FROM aem_empresa WHERE em_cuit = :cuit";
	$empresa = valorSql($sql, "", $params);

	$params = array();
	$sql =
		"SELECT NULL uc_cargo, NULL uc_email, SYSDATE uc_fechaalta, NULL uc_nombre, NULL uc_telefonos
			 FROM DUAL";
}
elseif ($esIngresoPrimeraVezUsuarioRaso) {
	$params = array(":id" => $_SESSION["UsuarioIdIngresoPrimeraVez"]);
	$sql =
		"SELECT em_nombre
			 FROM aem_empresa, aco_contrato, web.wcu_contratosxusuarios, web.wuc_usuariosclientes
			WHERE em_id = co_idempresa
				AND co_contrato = cu_contrato
				AND cu_idusuario = uc_id
				AND uc_idusuarioextranet = :id";
	$empresa = valorSql($sql, "", $params);

	$params = array(":id" => $_SESSION["UsuarioIdIngresoPrimeraVez"]);
	$sql =
		"SELECT uc_cargo, uc_email, uc_fechaalta, uc_nombre, uc_telefonos
			 FROM web.wuc_usuariosclientes
			WHERE uc_idusuarioextranet = :id";
}
else {
	$empresa = $_SESSION["empresa"];

	$params = array(":id" => $_SESSION["idUsuario"]);
	$sql =
		"SELECT uc_cargo, uc_email, uc_fechaalta, uc_nombre, uc_telefonos
			 FROM web.wuc_usuariosclientes
			WHERE uc_idusuarioextranet = :id";
}
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<script type="text/javascript">
function escribirClave(valor) {
	if (valor != '') {
		document.getElementById('trRepetirContrasena').style.display = 'block';
		document.getElementById('repetirContrasena').focus();
	}
}
</script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/mi_perfil/procesar_perfil.php" id="formPerfil" method="post" name="formPerfil" target="iframeProcesando">
	<input id="validarCondiciones" name="validarCondiciones" type="hidden" value="<?= ($esAltaAdministrador)?"t":"f"?>" />
	<div class="TituloSeccion" style="display:block; width:730px;">Mi Perfil</div>
	<div class="ContenidoSeccion" style="margin-top:20px;">
		<div style="margin-left:24px;">
			<label align="right">Nombre y Apellido</label>
			<input autofocus id="nombre" maxlength="80" name="nombre" style="width:360px;" type="text" value="<?= $row["UC_NOMBRE"]?>" />
		</div>
		<div style="margin-left:90px; margin-top:4px;">
			<label align="right">e-Mail</label>
			<input id="email" name="email" <?= ($esAltaAdministrador)?"":"readonly"?> style="<?= ($esAltaAdministrador)?"":"background-color:#ccc;"?> width:360px;" type="text" value="<?= $row["UC_EMAIL"]?>" /> (El e-mail es el usuario de login)
		</div>
		<div style="margin-left:62px; margin-top:4px;">
			<label align="right">Contraseña</label>
			<input id="contrasena" maxlength="20" name="contrasena" style="width:136px;" type="password" value="" onBlur="escribirClave(this.value)" />
		</div>
		<div id="trRepetirContrasena" style="display:none; margin-left:20px; margin-top:4px;">
			<label align="right">Repetir Contraseña</label>
			<input id="repetirContrasena" maxlength="20" name="repetirContrasena" style="width:136px;" type="password" value="" />
		</div>
		<div style="margin-left:75px; margin-top:4px;">
			<label align="right">Empresa</label>
			<input id="empresa" name="empresa" readonly style="background-color:#ccc; width:360px;" type="text" value="<?= $empresa?>" />
		</div>
		<div style="margin-left:91px; margin-top:4px;">
			<label align="right">Cargo</label>
			<input id="cargo" maxlength="60" name="cargo" style="width:360px;" type="text" value="<?= $row["UC_CARGO"]?>" />
		</div>
		<div style="margin-left:71px; margin-top:4px;">
			<label align="right">Teléfonos</label>
			<input id="telefono" maxlength="120" name="telefono" style="width:360px;" type="text" value="<?= $row["UC_TELEFONOS"]?>" />
		</div>
		<div style="margin-left:0px; margin-top:4px;">
			<label align="right">Fecha Alta/Habilitación</label>
			<input id="fechaAlta" name="fechaAlta" readonly style="background-color:#ccc; width:80px;" type="text" value="<?= $row["UC_FECHAALTA"]?>" />
		</div>
<?
if ($esAltaAdministrador) {
?>
		<div style="margin-top:8px;">
			<iframe id="iframeTerminos" name="iframeTerminos" src="/modules/usuarios_registrados/clientes/mi_perfil/terminos_y_condiciones.php" style="height:144px; width:688px;"></iframe>
		</div>
		<div style="margin-top:4px;">
			<input id="aceptoCondiciones" name="aceptoCondiciones" type="checkbox" />
			<label for="aceptoCondiciones">Acepto las Condiciones de Uso<label/>
		</div>
<?
}
?>
		<div align="right" style="margin-top:12px;">
			<input class="btnGrabar" type="submit" value="" />
		</div>	
		<p id="guardadoOk" style="background:#0f539c; color:#fff; display:none; margin-top:8px; padding:2px; width:192px;">&nbsp;Datos guardados exitosamente.</p>
		<div id="divErrores" style="display:none;">
			<table border="1" bordercolor="#ff0000" align="center" cellpadding="6" cellspacing="0">
				<tr>
					<td>
						<table cellpadding="4" cellspacing="0">
							<tr>
								<td><img border="0" src="/modules/usuarios_registrados/images/atencion.jpg" /></td>
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
	</div>
</form>
<div style="display:none">
	<form action="/modules/usuarios_registrados/clientes/validar_login.php" id="formAltaOk" method="post" name="formAltaOk" target="_self">
		<input id="ps" name="ps" type="hidden" value="" />
		<input id="sr" name="sr" type="hidden" value="" />
	</form>
</div>