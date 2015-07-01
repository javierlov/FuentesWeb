<?
validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 66));

$_SESSION["establecimientosUsuario"] = ",";

$contratos = "-1";
$isAlta = (!isset($_REQUEST["id"]));

if (!$isAlta) {
	// Tragio los contratos a los que está asociado el usuario..
	$params = array(":idusuarioextranet" => $_REQUEST["id"]);
	$sql =
		"SELECT cu_contrato
			 FROM web.wcu_contratosxusuarios, web.wuc_usuariosclientes
			WHERE cu_idusuario = uc_id
				AND uc_idusuarioextranet = :idusuarioextranet";
	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt))
		$contratos.= ",".$row["CU_CONTRATO"];

	// Si no es admintotal que solo pueda modificar sus propios datos..
	if (!$_SESSION["isAdminTotal"]) {
		$params = array(":contrato" => $_SESSION["contrato"], ":idusuarioextranet" => $_REQUEST["id"]);
		$sql =
			"SELECT 1
				 FROM web.wcu_contratosxusuarios, web.wuc_usuariosclientes
				WHERE uc_id = cu_idusuario
					AND cu_contrato = :contrato
					AND uc_idusuarioextranet = :idusuarioextranet";
		validarSesion(ExisteSql($sql, $params));
	}

	// Traigo los datos del usuario..
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT *
			 FROM web.wuc_usuariosclientes
			WHERE uc_idusuarioextranet = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$habilitarEstablecimientos = "";
	if ($row["UC_VERTODOSESTABLECIMIENTOS"] == "S")
		$habilitarEstablecimientos = "checked";

	// Traigo los establecimientos ahora..
	$params = array(":idcliente" => $_REQUEST["id"]);
	$sql =
		"SELECT el_idestablecimiento
			 FROM web.wel_establecimientoscliente, web.wuc_usuariosclientes
			WHERE el_idcliente = uc_id
				AND uc_idusuarioextranet = :idcliente";
	$stmt = DBExecSql($conn, $sql, $params);
	while ($row2 = DBGetQuery($stmt))
		$_SESSION["establecimientosUsuario"].= $row2["EL_IDESTABLECIMIENTO"].",";
}
?>
<script language="JavaScript" src="/modules/usuarios_registrados/clientes/js/administracion_usuarios.js"></script>
<iframe id="iframeProcesando2" name="iframeProcesando2" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/administracion_usuarios/procesar_usuario.php" id="formUsuario" method="post" name="formUsuario" target="iframeProcesando2">
	<input id="contratos" name="contratos" type="hidden" value="<?= $contratos?>" />
	<input id="id" name="id" type="hidden" value="<?= (!$isAlta)?$_REQUEST["id"]:""?>">
	<div class="TituloSeccion" style="display:block; width:730px;"><?= ($isAlta)?"Alta":"Edición"?> de Usuario</div>
	<div class="ContenidoSeccion" style="margin-top:20px;">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td class="ContenidoSeccion" valign="top">
					<table cellpadding="0" cellspacing="5">
						<tr>
							<td align="right" width="112">e-Mail</td>
							<td><input <?= (!$isAlta)?"readonly style='background-color:#ccc'":""?> id="email" maxlength="255" name="email" style="width:352px;" type="text" value="<?= (!$isAlta)?$row["UC_EMAIL"]:""?>"> (El e-mail es el usuario de logueo)</td>
						</tr>
						<tr>
							<td align="right">Repetir e-Mail</td>
							<td><input <?= (!$isAlta)?"readonly style='background-color:#ccc'":""?> id="email2" maxlength="255" name="email2" style="width:352px;" type="text" value="<?= (!$isAlta)?$row["UC_EMAIL"]:""?>"></td>
						</tr>
						<tr>
							<td align="right">Forzar Clave</td>
							<td><input <?= ($isAlta)?"checked disabled":""?> id="forzarClave" name="forzarClave" type="checkbox" value="ON"></td>
						</tr>
						<tr>
							<td align="right">Nombre y Apellido</td>
							<td><input id="nombre" maxlength="80" name="nombre" style="width:352px;" type="text" value="<?= (!$isAlta)?$row["UC_NOMBRE"]:""?>"></td>
						</tr>
						<tr>
							<td align="right">Cargo</td>
							<td><input id="cargo" maxlength="60" name="cargo" style="width:352px;" type="text" value="<?= (!$isAlta)?$row["UC_CARGO"]:""?>"></td>
						</tr>
						<tr>
							<td align="right">Teléfonos</td>
							<td><input id="telefono" maxlength="120" name="telefono" style="width:352px;" type="text" value="<?= (!$isAlta)?$row["UC_TELEFONOS"]:""?>"></td>
						</tr>
						<tr>
							<td align="right">Habilitar Establec.</td>
							<td>
								<input <?= (!$isAlta)?$habilitarEstablecimientos:""?> id="habilitarEstablecimientos" name="habilitarEstablecimientos" type="checkbox" value="ON">
								<span style="vertical-align:2px;">(Si tilda esta opción indica que el usuario va a tener habilitados TODOS los establecimientos)</span>
							</td>
						</tr>
						<tr>
							<td align="right" valign="top"><br/><br/>Establecimientos</td>
							<td><iframe frameborder="no" height="0" id="iframeEstablecimientos" name="iframeEstablecimientos" scrolling="no" src="/modules/usuarios_registrados/clientes/administracion_usuarios/establecimientos.php?idcliente=<?= (!$isAlta)?$_REQUEST["id"]:-1?>" width="564" onLoad="ajustarTamanoIframe(this)"></iframe></td>
						</tr>
						<tr>
							<td></td>
							<td><img border="0" src="/modules/usuarios_registrados/images/alta_multiples_empresas.jpg" style="cursor:hand;" onClick="buscarEmpresa()" /></td>
						</tr>
						<tr>
							<td></td>
							<td><iframe frameborder="no" height="0" id="iframeEmpresas" name="iframeEmpresas" scrolling="no" src="/modules/usuarios_registrados/clientes/administracion_responsables_contrato/empresas.php?c=<?= $contratos?>" width="520" onLoad="ajustarTamanoIframe(this, 64)"></iframe></td>
						</tr>
						<tr>
							<td align="right">Funcionalidades</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td align="right"><input <?= ((!$isAlta) and ($row["UC_AVISOOBRA"]) == 'S')?"checked":""?> id="avisoObra" name="avisoObra" type="checkbox" value="ON"></td>
							<td>Aviso de Obra</td>
						</tr>
						<tr>
							<td align="right"><input <?= ((!$isAlta) and ($row["UC_CARTILLA"]) == 'S')?"checked":""?> id="cartilla" name="cartilla" type="checkbox" value="ON"></td>
							<td>Cartilla</td>
						</tr>
						<tr>
							<td align="right"><input <?= ((!$isAlta) and ($row["UC_CERTIFICADOCOBERTURA"]) == 'S')?"checked":""?> id="certificadoCobertura" name="certificadoCobertura" type="checkbox" value="ON"></td>
							<td>Certificado de Cobertura</td>
						</tr>
						<tr>
							<td align="right"><input <?= ((!$isAlta) and ($row["UC_DENUNCIASINIESTROS"]) == 'S')?"checked":""?> id="denunciaSiniestros" name="denunciaSiniestros" type="checkbox" value="ON"></td>
							<td>Denuncia de Siniestros</td>
						</tr>
						<tr>
							<td align="right"><input <?= ((!$isAlta) and ($row["UC_CONSULTASINIESTROS"]) == 'S')?"checked":""?> id="consultaSiniestros" name="consultaSiniestros" type="checkbox" value="ON"></td>
							<td>Consulta de Siniestros</td>
						</tr>
						<tr>
							<td align="right"><input <?= ((!$isAlta) and ($row["UC_ESTADOSITUACIONPAGOS"]) == 'S')?"checked":""?> id="estadoSituacionPagos" name="estadoSituacionPagos" type="checkbox" value="ON"></td>
							<td>Estado de Situación de Pagos</td>
						</tr>
						<tr>
							<td align="right"><input <?= ((!$isAlta) and ($row["UC_LEGALES"]) == 'S')?"checked":""?> id="legales" name="legales" type="checkbox" value="ON"></td>
							<td>Legales</td>
						</tr>
						<tr>
							<td align="right"><input <?= ((!$isAlta) and ($row["UC_NOMINATRABAJADORES"]) == 'S')?"checked":""?> id="nominaTrabajadores" name="nominaTrabajadores" type="checkbox" value="ON"></td>
							<td>Nómina de Trabajadores</td>
						</tr>
						<tr>
							<td align="right"><input <?= ((!$isAlta) and ($row["UC_PREVENCION"]) == 'S')?"checked":""?> id="prevencion" name="prevencion" type="checkbox" value="ON"></td>
							<td>Prevención</td>
						</tr>
						<tr>
							<td align="right"><input <?= ((!$isAlta) and ($row["UC_RGRL"]) == 'S')?"checked":""?> id="rgrl" name="rgrl" type="checkbox" value="ON"></td>
							<td>RGRL (Res. 463/09)</td>
						</tr>
						<tr>
							<td align="right"><input <?= ((!$isAlta) and ($row["UC_RAR"]) == 'S')?"checked":""?> id="crar" name="crar" type="checkbox" value="ON"></td>
							<td>RAR (Nómina de personal expuesto)</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="ContenidoSeccion">&nbsp;</td>
			</tr>
			<tr>
				<td class="ContenidoSeccion">
					<input class="btnGrabar" style="margin-left:16px;" type="submit" value="" />
<?
if (!$isAlta) {
?>
					<img border="0" src="/modules/usuarios_registrados/images/eliminar.jpg" style="cursor:pointer; margin-left:16px;" onClick="eliminarUsuario(<?= $_REQUEST["id"]?>)" />
<?
}
?>
				</td>
			</tr>	
		</table>
		<p id="guardadoOk" style="background:#0f539c; color:#fff; display:none; margin-top:8px; padding:2px; width:192px;">&nbsp;Datos guardados exitosamente.</p>
		<p id="borradoOk" style="background:#0f539c; color:#fff; display:none; margin-top:8px; padding:2px; width:256px;">&nbsp;El usuario fue eliminado exitosamente.</p>
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
		<table width="96%">
			<tr>
				<td><input class="btnVolver" type="button" value="" onClick="history.back(-1);" /></td>
			</tr>
		</table>
	</div>
</form>
<script type="text/javascript">
	document.getElementById('email').focus();
</script>