<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once("index_combos.php");


function getLink() {
	if (isset($_SESSION["isAgenteComercial"]))
		return "/buscar-contrato-2/nt";
	else
		return "/nomina-trabajadores";
}


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 70));

if (isset($_SESSION["isAgenteComercial"])) {
	if (isset($_REQUEST["id"])) {
		validarSesion(validarContrato($_REQUEST["id"]));
		validarSesion(validarEntero($_REQUEST["id"]));
		$params = array(":contrato" => $_REQUEST["id"]);
		$sql = 
			"SELECT em_cuit, NVL(em_nombre, '-') empresa, NVL(co_idempresa, -1) idempresa
				 FROM aco_contrato, aem_empresa
				WHERE co_idempresa = em_id
					AND art.afiliacion.check_cobertura(co_contrato, SYSDATE) = 1
					AND co_contrato = :contrato";
		$stmt = DBExecSql($conn, $sql, $params);
		$row = DBGetQuery($stmt);

		$_SESSION["contrato"] = $_REQUEST["id"];
		$_SESSION["cuit"] = $row["EM_CUIT"];
		$_SESSION["empresa"] = $row["EMPRESA"];
		$_SESSION["idEmpresa"] = $row["IDEMPRESA"];
	}
	else {
		$_SESSION["contrato"] = 0;
		$_SESSION["cuit"] = "";
		$_SESSION["empresa"] = "";
		$_SESSION["idEmpresa"] = 0;
	}
}

$_SESSION["certificadoCobertura"]["trabajadores"] = array();

$params = array(":contrato" => $_SESSION["contrato"]);
$sql =
	"SELECT webart.get_validar_certificado(:contrato)
		 FROM DUAL";
$puedeImprimir = (valorSql($sql, 0, $params) == 0);
?>
<style>
	#tableCoberturaExterior tr {height:24px;}
	#tableDatosComitentes tr {height:24px;}
</style>
<script src="/modules/usuarios_registrados/clientes/js/certificados.js?rnd=<?= date("Ymdhni")?>" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/certificados-cobertura/seleccion" id="formTipoCertificado" method="post" name="formTipoCertificado" target="_self">
	<input id="tieneDeuda" name="tieneDeuda" type="hidden" value="f" />
	<div class="TituloSeccion" style="display:block; width:730px;">Certificados de Cobertura</div>
	<div class="ContenidoSeccion" align=right style="margin-top:5px;"><i>>> <a href="/certificados-cobertura/terminos-y-condiciones">Términos y Condiciones de uso</a></i></div>
	<div class="ContenidoSeccion" style="margin-top:20px;">
<?
if ($puedeImprimir) {
?>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td>
					Emita en línea los certificados de cobertura de su empresa.<br>
					Seleccione el tipo de certificado (común o con cláusula de no repetición) y el tipo de nómina (total/parcial, simple/completa) que necesita.<br>
					También puede agrupar la nómina por establecimiento.<br>
					Si su nómina está desactualizada, recuerde que puede ponerla al día mediante la aplicación <a class="linkSubrayado" href="<?= getLink()?>">Nómina de Trabajadores</a>.
				</td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td class="SubtituloSeccionAzul">SELECCIÓN DE CERTIFICADOS</td>
			</tr>
			<tr>
				<td class="ContenidoSeccion">
					<table cellpadding="0" cellspacing="3">
						<tr>
							<td><input checked id="tipoCertificado" name="tipoCertificado" type="radio" value="ccc" onClick="seleccionarCertificado('trCcc')" /></td>
							<td>Certificado de Cobertura Común</td>
						</tr>
						<tr>
							<td><input id="tipoCertificado" name="tipoCertificado" type="radio" value="cccr" onClick="seleccionarCertificado('trCccr')" /></td>
							<td>Certificado de Cobertura con cláusula de no repetición</td>
						</tr>
						<tr>
							<td><input id="tipoCertificado" name="tipoCertificado" type="radio" value="cce" onClick="seleccionarCertificado('trCce')" /></td>
							<td>Certificado de Cobertura al exterior</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td height="15"></td>
			</tr>	
			<tr id="trDeuda" style="display:none;">
				<td class="ContenidoSeccion" style="padding:5px;">
					<div style="border:#f00 1px solid; color:#f00; padding:4px;">
						<img border="0" src="/modules/usuarios_registrados/images/alerta.png" style="float:left; vertical-align:12px;" />
						<span>En este momento no es posible emitir, por esta vía, el certificado que usted solicita. Por favor, escriba a <a class="linkSubrayado" href="mailto:certificados@provart.com.ar?subject=Solicitud de Certificado de Cobertura con cláusula de no repetición" style="color:#f00;">certificados@provart.com.ar</a> indicando nombre completo de la empresa, CUIT y datos del/los trabajador/es.</span>
					</div>
				</td>
			</tr>
			<tr id="trCccr" style="display:none;">
				<td class="ContenidoSeccion">
					<table border="1" bordercolor="#808080" width="400">
						<tr>
							<td>
								<table cellpadding="5" cellspacing="0" id="tableDatosComitentes" width="100%">
									<tr>
										<td colspan="2" class="TituloTablaCeleste">Ingrese los datos de la empresa comitente</td>	
									</tr>
									<tr>
										<td width="104">Razón Social (*)</td>	
										<td>
											<input id="razonSocial" maxlength="500" name="razonSocial" style="width:232px;" type="text" value="" />
											<img border="0" src="/images/lupa.jpg" style="cursor:pointer; vertical-align:-4px;" title="Empresas Agendadas" onClick="cargarDatosEmpresaComitente()" />
										</td>
									</tr>
									<tr>
										<td>Calle <span id="asteriscoCalle"></span></td>
										<td><input id="calle" maxlength="60" name="calle" style="width:232px;" type="text" value="" onKeyUp="colocarValidacion()"></td>
									</tr>
									<tr>
										<td>Número <span id="asteriscoNumero"></span></td>
										<td><input id="numero" maxlength="20" name="numero" style="width:32px;" type="text" value="" onKeyUp="colocarValidacion()"></td>
									</tr>
									<tr>
										<td>Piso</td>
										<td><input id="piso" maxlength="20" name="piso" style="width:32px;" type="text" value=""></td>
									</tr>
									<tr>
										<td>Departamento</td>
										<td><input id="departamento" maxlength="20" name="departamento" style="width:32px;" type="text" value=""></td>
									</tr>
									<tr>
										<td>Código Postal</td>
										<td><input id="codigoPostal" maxlength="5" name="codigoPostal" style="width:48px;" type="text" value=""></td>
									</tr>	
									<tr>
										<td>Localidad <span id="asteriscoLocalidad"></span></td>
										<td><input id="localidad" maxlength="60" name="localidad" style="width:232px;" type="text" value="" onKeyUp="colocarValidacion()"></td>
									</tr>
									<tr>
										<td>Provincia</td>
										<td><?= $comboIdprovincia->draw();?></select></td>
									</tr>
									<tr>
										<td></td>
										<td><input id="agendar" name="agendar" style="margin-left:-1px;" type="checkbox" value="ON">Agendar los datos al continuar</td>
									</tr>
									<tr>
										<td></td>
										<td><img border="0" src="/modules/usuarios_registrados/images/limpiar.jpg" style="cursor:pointer;" onClick="limpiarForm('cccr')"></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr id="trCce" style="display:none;">
				<td class="ContenidoSeccion">
					<table border="1" bordercolor="#808080">
						<tr>
							<td>
								<table cellpadding="5" cellspacing="0" id="tableCoberturaExterior" width="400">
									<tr>
										<td colspan="2" class="TituloTablaCeleste">Ingrese los datos de cobertura al exterior</td>	
									</tr>
									<tr>
										<td>Fecha de Salida (*)</td>	
										<td>
											<input id="fechaSalida" maxlength="10" name="fechaSalida" style="width:64px;" type="text" value="" />
											<input class="botonFecha" id="btnFechaSalida" name="btnFechaSalida" style="vertical-align:-5px;" type="button" value="" />
										</td>
									</tr>
									<tr>
										<td>Fecha de Regreso (*)</td>	
										<td>
											<input id="fechaRegreso" maxlength="10" name="fechaRegreso" style="width:64px;" type="text" value="" />
											<input class="botonFecha" id="btnFechaRegreso" name="btnFechaRegreso" style="vertical-align:-5px;" type="button" value="" />
										</td>
									</tr>
									<tr>
										<td>País (*)</td>	
										<td><?= $comboPais->draw();?></td>
									</tr>
									<tr>
										<td>Destino (*)</td>	
										<td><input id="destino" maxlength="255" name="destino" style="width:228px;" type="text" value="" /></td>
									</tr>
									<tr>
										<td>Asistencia al Viajero (*)</td>	
										<td><input id="asistenciaViajero" maxlength="255" name="asistenciaViajero" style="width:228px;" type="text" value="" /></td>
									</tr>
									<tr>
										<td>Forma de Viaje (*)</td>	
										<td>
											<select id="formaViaje" name="formaViaje" style="margin-left:-1px;">
												<option value="-1">- Seleccionar -</option>
												<option value="A">Aéreo</option>
												<option value="M">Marítimo</option>
												<option value="T">Terrestre</option>
											</select>
										</td>
									</tr>
									<tr>
										<td valign="top">
											Observaciones<br /><br />
											<span style="font-size:9px;">(máximo 255 caracteres</span><br />
											<span style="font-size:9px;">restan <span id="caracteresRestantes">255</span> caracteres)</span>
										</td>
										<td><textarea id="observaciones" maxlength="255" name="observaciones" rows="3" style="width:228px;" onKeyUp="contarCaracteresObservaciones()"></textarea></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td height="15"></td>
			</tr>
			<tr id="trCcc">
				<td class="ContenidoSeccion" valign="top">
					<table cellpadding="0" cellspacing="5">
						<tr>
							<td>Selección de Nómina</td>
							<td>
								<select id="seleccionNomina" name="seleccionNomina" onChange="cambiarSeleccionNomina(this.value)">
									<option value="sn">Sin Nómina</option>
									<option value="p">Parcial (selección de CUILES)</option>
									<option value="t">Total (todos los CUILES)</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Tipo de Nómina</td>
							<td>
								<select id="tipoNomina" name="tipoNomina" disabled>
									<option value="s">Simple (CUIL, Nombre, Tarea)</option>
									<option value="c">Completa (todos los datos)</option>
								</select>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td height="15"></td>
			</tr>	
			<tr>
				<td>
					<img border="0" src="/modules/usuarios_registrados/images/continuar.jpg" style="cursor:pointer;" onClick="validarPrimerPaso()" />
					<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
				</td>
			</tr>	
			<tr>
				<td height="15"></td>
			</tr>	
			<tr id="trDeuda2" style="display:none;">
				<td class="ContenidoSeccion" style="padding:6px;">
					<div style="border:#f00 1px solid; color:#f00; padding:4px;">
						<img border="0" src="/modules/usuarios_registrados/images/alerta.png" style="float:left; vertical-align:12px;" />
						<span>En este momento no es posible emitir, por esta vía, el certificado que usted solicita. Por favor, escriba a <a class="linkSubrayado" href="mailto:certificados@provart.com.ar?subject=Solicitud de Certificado de Cobertura con cláusula de no repetición" style="color:#f00;">certificados@provart.com.ar</a> indicando nombre completo de la empresa, CUIT y datos del/los trabajador/es.</span>
					</div>
				</td>
			</tr>
			<tr>
				<td height="15"></td>
			</tr>	
		</table>
<?
}
else {
?>
	<p>Estimado Cliente, le informamos que no tenemos registro de trabajadores activos vinculados a su contrato, dado que la última DDJJ registrada es sin personal, por este motivo momentáneamente no es posible emitir el certificado de cobertura.</p>
	<p>Estamos a su disposición para asesorarlo y aclarar sus dudas y consultas, para ello puede enviarnos un e-mail a <a class="linkSubrayado" href="mailto:certificados@provart.com.ar">certificados@provart.com.ar</a>.</p>
<?
}
?>
	</div>
</form>
<?
if ($puedeImprimir) {
?>
<script type="text/javascript">
	Calendar.setup ({
		inputField: "fechaSalida",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaSalida"
	});

	Calendar.setup ({
		inputField: "fechaRegreso",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaRegreso"
	});
<?
if (isset($_SESSION["certificadoCobertura"]["campoError"])) {
?>
	with (document) {
		if (!getElementById('formTipoCertificado').tipoCertificado[0].checked) {
			getElementById('<?= $_SESSION["certificadoCobertura"]["campoError"]?>').style.backgroundColor = '#f00';
			getElementById('<?= $_SESSION["certificadoCobertura"]["campoError"]?>').style.color = '#fff';
			getElementById('<?= $_SESSION["certificadoCobertura"]["campoError"]?>').focus();

			alert(unescape('<?= rawurlencode($_SESSION["certificadoCobertura"]["msgError"])?>'));
			setTimeout("document.getElementById('<?= $_SESSION["certificadoCobertura"]["campoError"]?>').style.backgroundColor = ''; document.getElementById('<?= $_SESSION["certificadoCobertura"]["campoError"]?>').style.color = '';", 2000);
		}
	}
<?
}
?>
	colocarValidacion();
</script>
<?
}
?>