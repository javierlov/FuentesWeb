<?
validarSesion(isset($_SESSION["isCliente"]));
validarSesion($_SESSION["isAdminTotal"]);

SetDateFormatOracle("DD/MM/YYYY");

$contratos = "-1";
$isAlta = (!isset($_REQUEST["id"]));
$tieneClave = false;

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

	// Traigo los datos del usuario..
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT uc_avisoobra, uc_cargo, uc_cartilla, uc_certificadocobertura, uc_consultasiniestros, uc_denunciasiniestros, uc_email, uc_esadminempresa, uc_esadmintotal,
						uc_estadosituacionpagos, uc_legales, uc_nombre, uc_nominatrabajadores, uc_prevencion, uc_rgrl, UC_RAR, uc_telefonos, ue_estado
			 FROM web.wue_usuariosextranet, web.wuc_usuariosclientes
			WHERE ue_id = uc_idusuarioextranet
				AND ue_idmodulo = 49
				AND uc_idusuarioextranet = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT 1
			 FROM web.wue_usuariosextranet
			WHERE ue_clave IS NOT NULL
				AND ue_id = :id";
	$tieneClave = ExisteSql($sql, $params);
}

require_once("usuario_combos.php");
?>
<script type="text/javascript">
	function grabar() {
		if ((document.getElementById('contrasena').value != '') || (<?= ($tieneClave)?"false":"true"?>))
			if (confirm('¿ Desea enviar por e-mail al usuarios los datos para poder loguearse ?'))
				document.getElementById('enviarDatos').value = 'S';
		formUsuario.submit();
	}
</script>
<script language="JavaScript" src="/modules/usuarios_registrados/clientes/js/administracion_responsables_contrato.js"></script>
<iframe id="iframeProcesando2" name="iframeProcesando2" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/administracion_responsables_contrato/procesar_usuario.php" id="formUsuario" method="post" name="formUsuario" target="iframeProcesando2">
	<input id="contratos" name="contratos" type="hidden" value="<?= $contratos?>" />
	<input id="enviarDatos" name="enviarDatos" type="hidden" value="N" />
	<input id="estadoAnterior" name="estadoAnterior" type="hidden" value="<?= (!$isAlta)?$row["UE_ESTADO"]:-1?>">
	<input id="id" name="id" type="hidden" value="<?= (!$isAlta)?$_REQUEST["id"]:""?>">
	<div class="TituloSeccion" style="display:block; width:730px;"><?= ($isAlta)?"Alta":"Edición"?> de Usuario</div>
	<div class="ContenidoSeccion" style="margin-top:20px;">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td class="ContenidoSeccion" valign="top">
					<table cellpadding="0" cellspacing="5">
						<tr>
							<td align="right">e-Mail</td>
							<td><input autofocus <?= (!$isAlta)?"readonly":""?> id="email" maxlength="255" name="email" style="<?= (!$isAlta)?"background-color:#ccc; ":""?>width:320px;" type="text" value="<?= (!$isAlta)?$row["UC_EMAIL"]:""?>"> (El e-mail es el usuario de login)</td>
						</tr>
						<tr>
							<td align="right">Repetir e-Mail</td>
							<td><input <?= (!$isAlta)?"readonly":""?> id="email2" maxlength="255" name="email2" style="<?= (!$isAlta)?"background-color:#ccc;":""?>width:320px;" type="text" value="<?= (!$isAlta)?$row["UC_EMAIL"]:""?>"></td>
						</tr>
						<tr>
							<td align="right">Contraseña</td>
							<td><input id="contrasena" maxlength="20" name="contrasena" style="width:136px;" type="password" value="" onBlur="escribirClave(this.value)"></td>
						</tr>
						<tr id="trRepetirContrasena" style="visibility:hidden;">
							<td align="right">Repetir Contraseña</td>
							<td><input id="repetirContrasena" maxlength="20" name="repetirContrasena" style="width:136px;" type="password" value=""></td>
						</tr>
						<tr>
							<td align="right">Nombre y Apellido</td>
							<td><input id="nombre" maxlength="80" name="nombre" style="width:320px;" type="text" value="<?= (!$isAlta)?$row["UC_NOMBRE"]:""?>"></td>
						</tr>
						<tr>
							<td align="right">Cargo</td>
							<td><input id="cargo" maxlength="60" name="cargo" style="width:320px;" type="text" value="<?= (!$isAlta)?$row["UC_CARGO"]:""?>"></td>
						</tr>
						<tr>
							<td align="right">Teléfonos</td>
							<td><input id="telefono" maxlength="120" name="telefono" style="width:320px;" type="text" value="<?= (!$isAlta)?$row["UC_TELEFONOS"]:""?>"></td>
						</tr>
						<tr>
							<td align="right">Administrador</td>
							<td><input <?= (($isAlta) or ($row["UC_ESADMINEMPRESA"] != "S"))?"":"checked"?> id="administrador" name="administrador" type="checkbox" value="check"></td>
						</tr>
						<tr>
							<td align="right">Administrador ART</td>
							<td><input <?= (($isAlta) or ($row["UC_ESADMINTOTAL"] != "S"))?"":"checked"?> id="administradorArt" name="administradorArt" type="checkbox" value="check" onClick="advertirAdministradorArt(this)"></td>
						</tr>
						<tr>
							<td align="right">Estado</td>
							<td><?= $comboEstado->draw();?></select></td>
						</tr>
						<tr>
							<td></td>
							<td>&nbsp;</td>
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
					<input class="btnGrabar" style="margin-left:16px;" type="button" value="" onClick="grabar();">
<?
if (!$isAlta) {
?>
					<img border="0" src="/modules/usuarios_registrados/images/eliminar.jpg" style="cursor:pointer; margin-left:16px;" onClick="eliminarUsuario(<?= $_REQUEST["id"]?>)">
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
								<td><img border="0" src="/modules/usuarios_registrados/images/atencion.jpg"></td>
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