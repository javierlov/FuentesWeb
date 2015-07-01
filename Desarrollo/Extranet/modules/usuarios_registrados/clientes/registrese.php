<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");


function validar(&$msgErrores) {
	$errores = false;

	if ($_POST["cuit"] == "") {
		$msgErrores.= "- El campo C.U.I.T. es obligatorio.<br />";
		$errores = true;
	}
	else {
		$params = array(":cuit" => $_POST["cuit"]);
		$sql = "SELECT art.utiles.is_cuitvalido(:cuit) FROM DUAL";
		if (valorSql($sql, "", $params) != "S") {
			$msgErrores.= "- La C.U.I.T. indicada no pudo ser validada en este momento. Por favor verifique que la haya cargado correctamente o bien contáctese con nuestro centro de atención al cliente al 0-800-333-1278.<br />";
			$errores = true;
		}
		else {
			$params = array(":cuit" => $_POST["cuit"]);
			$sql =
				"SELECT 1
					 FROM aem_empresa, aco_contrato
					WHERE em_id = co_idempresa
						AND art.afiliacion.check_cobertura(co_contrato, SYSDATE) = 1
						AND em_cuit = :cuit";
			if (!existeSql($sql, $params)) {
				$msgErrores.= "- La C.U.I.T. es inválida.<br />";
				$errores = true;
			}
		}
	}

	if ($_POST["nombre"] == "") {
		$msgErrores.= "- El campo Nombre y Apellido es obligatorio.<br />";
		$errores = true;
	}

	if ($_POST["email"] == "") {
		$msgErrores.= "- El campo e-Mail es obligatorio.<br />";
		$errores = true;
	}
	else {
		$params = array(":email" => $_POST["email"]);
		$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
		if (valorSql($sql, "", $params) != "S") {
			$msgErrores.= "- El e-Mail es inválido.<br />";
			$errores = true;
		}

		$sql = "SELECT 1 FROM web.wue_usuariosextranet WHERE ue_idmodulo = 49 AND UPPER(ue_usuario) = UPPER(:email)";
		$params = array(":email" => strtolower($_POST["email"]));
		if (valorSql($sql, "", $params) == 1) {
			$msgErrores.= "- El e-Mail ya existe en la base de datos.<br />";
			$errores = true;
		}
	}

	if ($_POST["telefono"] == "") {
		$msgErrores.= "- El campo Teléfonos es obligatorio.<br />";
		$errores = true;
	}

	if (!isset($_POST["aceptoCondiciones"])) {
		$msgErrores.= "- Si no acepta las Condiciones de Uso no se puede continuar.<br />";
		$errores = true;
	}

	return !$errores;
}


$errores = false;

if ((isset($_POST["guardar"])) and ($_POST["guardar"] == "t")) {
	try {
		$msgErrores = "";
		$errores = (!validar($msgErrores));
		if (!$errores) {
			$params = array(":cuit" => $_POST["cuit"]);
			$sql = "SELECT art.webart.get_cuit_encriptado(:cuit) FROM DUAL";
			$pass = valorSql($sql, "", $params);

			$curs = null;
			$params = array(":cestado" => "P",
											":scargo" => $_POST["cargo"],
											":sclave" => $pass,
											":scuit" => $_POST["cuit"],
											":semail" => strtolower($_POST["email"]),
											":snombre" => $_POST["nombre"],
											":stelefonos" => $_POST["telefono"]);
			$sql = "BEGIN webart.set_alta_cliente_administrador(:data, :cestado, :scargo, :sclave, :scuit, :semail, :snombre, :stelefonos); END;";
			DBExecSP($conn, $curs, $sql, $params);

			$body = "<html><body>";
			$body.= "C.U.I.T.: ". $_POST["cuit"]."<br />";
			$body.= "Nombre y Apellido: ". $_POST["nombre"]."<br />";
			$body.= "e-Mail: ". $_POST["email"]."<br />";
			$body.= "Cargo: ". $_POST["cargo"]."<br />";
			$body.= "Teléfonos: ". $_POST["telefono"]."<br />";
			$body.= "</body></html>";
			$subject = "Nuevo usuario registrado en el Sitio Web de Provincia ART";
			sendEmail($body, "Provincia ART", $subject, array("comercial@provart.com.ar"), array(), array(), 'H');
		}
	}
	catch (Exception $e) {
		echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
		exit;
	}
}
else {
	$_POST["cargo"] = "";
	$_POST["cuit"] = "";
	$_POST["email"] = "";
	$_POST["nombre"] = "";
	$_POST["telefono"] = "";
}
?>
<div class="TituloSeccion" style="display:block; width:730px;">Regístrese</div>
<?
if ((!isset($_POST["guardar"])) or ((isset($_POST["guardar"])) and ($_POST["guardar"] == "t") and ($errores))) {
?>
<form action="<?= $_SERVER["REQUEST_URI"]?>" id="formRegistrese" method="post" name="formRegistrese">
	<input id="guardar" name="guardar" type="hidden" value="t" />
	<div class="ContenidoSeccion" style="margin-top:20px;">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td class="ContenidoSeccion" valign="top">
					<table cellpadding="0" cellspacing="5">
						<tr>
							<td align="right">C.U.I.T. (*)</td>
							<td><input id="cuit" maxlength="11" name="cuit" style="width:80px;" type="text" value="<?= $_POST["cuit"]?>"></td>
						</tr>
						<tr>
							<td align="right">Nombre y Apellido (*)</td>
							<td><input id="nombre" maxlength="80" name="nombre" style="width:336px;" type="text" value="<?= $_POST["nombre"]?>"></td>
						</tr>
						<tr>
							<td align="right">e-Mail (*)</td>
							<td><input id="email" name="email" style="width:336px;" type="text" value="<?= $_POST["email"]?>"> (El e-mail es el usuario de logueo)</td>
						</tr>
						<tr>
							<td align="right"><span style="margin-right:21px;">Cargo</span></td>
							<td><input id="cargo" maxlength="60" name="cargo" style="width:336px;" type="text" value="<?= $_POST["cargo"]?>"></td>
						</tr>
						<tr>
							<td align="right">Teléfonos (*)</td>
							<td><input id="telefono" maxlength="120" name="telefono" style="width:336px;" type="text" value="<?= $_POST["telefono"]?>"></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="ContenidoSeccion">&nbsp;</td>
			</tr>
			<tr>
				<td><iframe id="iframeTerminos" name="iframeTerminos" src="/modules/usuarios_registrados/clientes/mi_perfil/terminos_y_condiciones.php" style="height:188px; margin-left:12px; width:688px;"></iframe></td>
			</tr>
			<tr>
				<td class="ContenidoSeccion">&nbsp;</td>
			</tr>
			<tr>
				<td class="ContenidoSeccion">
					<input id="aceptoCondiciones" name="aceptoCondiciones" type="checkbox" />
					<label for="aceptoCondiciones">Acepto las Condiciones de Uso<label/>
				</td>
			</tr>
			<tr>
				<td class="ContenidoSeccion">&nbsp;</td>
			</tr>
			<tr>
				<td class="ContenidoSeccion"><input class="btnGrabar" type="submit" value="" /></td>
			</tr>	
		</table>
<?
	if ($errores) {
?>
		<p id="guardadoOk" style="background:#0f539c; color:#fff; display:none; margin-top:8px; padding:2px; width:192px;">&nbsp;Datos guardados exitosamente.</p>
		<div id="divErrores" style="display:inline;">
			<table border="1" bordercolor="#ff0000" align="center" cellpadding="6" cellspacing="0">
				<tr>
					<td>
						<table cellpadding="4" cellspacing="0">
							<tr>
								<td><img border="0" src="/modules/usuarios_registrados/images/atencion.jpg" /></td>
								<td>
									<font color="#000000">
										No es posible continuar mientras no se corrijan los siguientes errores:<br /><br />
										<span id="errores"><?= $msgErrores?></span>
									 </font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<input id="foco" name="foco" readonly style="height:1px; width:1px;" type="checkbox" />
		</div>
<?
	}
?>
		<table width="96%">
			<tr>
				<td><input class="btnVolver" type="button" value="" onClick="history.back(-1);" /></td>
			</tr>
		</table>
	</div>
</form>
<script type="text/javascript">
	document.getElementById('cuit').focus();

<?
	if ($errores) {
		echo "document.getElementById('foco').style.display = 'block';";
		echo "document.getElementById('foco').focus();";
		echo "document.getElementById('foco').style.display = 'none';";
	}
?>
</script>
<?
}
else {
?>
<div class="ContenidoSeccion" style="margin-top:20px;">Su pedido a sido registrado, proximamente recibirá un e-mail con los datos para poder ingresar.</div>
<?
}
?>