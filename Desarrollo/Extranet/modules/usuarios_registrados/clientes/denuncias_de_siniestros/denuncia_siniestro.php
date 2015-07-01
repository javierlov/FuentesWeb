<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");


function fillComboHora() {
	$result =
'<option value="-1">-</option><option value="00">00</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option>
<option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option>
<option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option>
<option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option>
<option value="23">23</option>';

	return $result;
}

function fillComboMinuto() {
	$result =
'<option value="-1">-</option><option value="00">00</option><option value="05">05</option><option value="10">10</option><option value="15">15</option><option value="20">20</option>
<option value="25">25</option><option value="30">30</option><option value="35">35</option><option value="40">40</option><option value="45">45</option><option value="50">50</option>
<option value="55">55</option>';

	return $result;
}

function getTelefono($contrato) {
	global $conn;

	$result = "";

	$params = array(":contrato" => $contrato);
	$sql =
		"SELECT hd_codareafax, hd_codareatelefonos, hd_fax, hd_telefonos
			 FROM ahd_historicodomicilio
			WHERE hd_contrato = :contrato
	 ORDER BY hd_id DESC";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	if ($row["HD_CODAREATELEFONOS"] != "")
		$result.= "(".$row["HD_CODAREATELEFONOS"].")";
	$result.= " ".$row["HD_TELEFONOS"];

	if ($row["HD_FAX"] != "") {
		$result.= " / ";
		if ($row["HD_CODAREAFAX"] != "")
			$result.= "(".$row["HD_CODAREAFAX"].")";
		$result.= " ".$row["HD_FAX"];
	}

	return $result;
}


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 61));


$params = array(":contrato" => $_SESSION["contrato"]);
$sql =
	"SELECT 1
		 FROM SIN.see_empresaestableci3ro
		WHERE ee_fechabaja IS NULL
			AND ee_contrato = :contrato";
$tieneEstablecimientosDeTercero = existeSql($sql, $params);

$isAlta = ((isset($_REQUEST["cem"])) or (!isset($_REQUEST["id"])));
$cargarCampos = false;
if (!$isAlta) {
	$curs = null;
	$params = array(":idtransaccion" => $_REQUEST["id"]);
	$sql = "BEGIN webart.get_denuncia_siniestro(:data, :idtransaccion); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params);
	$row = DBGetSP($curs);

	if (!$row)
		echo '<p style="color:red">ERROR: Este trabajador no está asociado a la empresa '.$_SESSION["empresa"].'.</p>';
	else
		$cargarCampos = true;
}
elseif (isset($_REQUEST["cem"])) {
	$curs = null;
	$params = array(":cuit" => $_SESSION["cuit"], ":idcem" => $_REQUEST["id"]);
	$sql = "BEGIN webart.get_denuncia_cem(:data, :cuit, :idcem); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params);
	$row = DBGetSP($curs);

	if ($row)
		$cargarCampos = true;
}

require_once("denuncia_siniestro_combos.php");
?>
<style>
	#agenteMaterial {width:506px;}
	#establecimientoPropio {width:556px;}
	#establecimientoTercero {width:408px;}
	#formaAccidente {width:506px;}
	#localidadAccidente {width:600px;}
</style>
<script src="/modules/usuarios_registrados/clientes/js/denuncia_siniestros.js?rnd=<?= date("YmdHni")?>" type="text/javascript"></script>
<script type="text/javascript">
	function enviar() {
		with (document) {
			getElementById('btnEnviar').style.display = 'none';
			getElementById('imgProcesando').style.display = 'inline';
			getElementById('formDenunciaSiniestro').submit();
		}
	}
</script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/denuncias_de_siniestros/procesar_denuncia.php" id="formDenunciaSiniestro" method="post" name="formDenunciaSiniestro" target="iframeProcesando">
	<input id="idPrestador" name="idPrestador" type="hidden" value="<?= ($cargarCampos)?$row["EW_PRESTADORID"]:-1?>" />
	<input id="idProvincia" name="idProvincia" type="hidden" value="<?= ($cargarCampos)?$row["JW_PROVINCIA"]:-1?>" />
	<input id="idProvinciaAccidente" name="idProvinciaAccidente" type="hidden" value="<?= ($cargarCampos)?$row["EW_LUGARPROVINCIA"]:-1?>" />
	<input id="idTrabajador" name="idTrabajador" type="hidden" value="<?= ($cargarCampos)?$row["JW_IDTRABAJADOR"]:-1?>">
	<input id="numeroCecap" name="numeroCecap" type="hidden" value="<?= ($cargarCampos)?$row["NROCECAP"]:-1?>">
	<div class="TituloSeccion" id="titulo" name="titulo" style="display:block; width:730px;">Paso 1: Datos del Trabajador</div>
	<div align="right" class="ContenidoSeccion" style="margin-top:5px;">
		<a href="#" onClick="paso = 1; cambiarPaso(1);"><span id="numeroPaso1">1</span></a> |
		<a href="#" onClick="paso = 2; cambiarPaso(2);"><span id="numeroPaso2">2</span></a> |
		<a href="#" onClick="paso = 3; cambiarPaso(3);"><span id="numeroPaso3">3</span></a> |
		<a href="#" onClick="paso = 4; cambiarPaso(4);"><span id="numeroPaso4">4</span></a> |
		<a href="#" onClick="paso = 5; cambiarPaso(5);"><span id="numeroPaso5">5</span></a>
		<div align="left" style="margin-bottom:16px; margin-top:12px;">
			<table style="border-color:#808080; border-style:double; width:720px;">
				<tr>
					<td colspan="4"><div class="TituloTablaCeleste">EMPRESA</div></td>
				</tr>
				<tr>
					<td style="vertical-align:top; width:112px;"><b>Razón Social</b></td>
					<td><?= $_SESSION["empresa"]?></td>
					<td style="margin-left:16px; vertical-align:top; width:40px;"><b>C.U.I.T.</b></td>
					<td style="vertical-align:top;"><?= ponerGuiones($_SESSION["cuit"])?></td>
				</tr>
				<tr>
					<td><b>Contrato</b></td>
					<td><?= $_SESSION["contrato"]?></td>
					<td><b>Tel/Fax</b></td>
					<td style="width:120px;"><?= getTelefono($_SESSION["contrato"])?></td>
				</tr>
				<tr>
					<td><b>Fecha Denuncia</b></td>
					<td><?= date("d/m/Y")?></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</div>
	</div>


<div class="ContenidoSeccion" id="divPaso1" style="display:none">
<table cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="1" bordercolor="#808080">
				<tr>
					<td>
						<table cellpadding="5" cellspacing="0" width="710px">
							<tr>
								<td colspan="4" class="TituloTablaCeleste">Datos del trabajador accidentado</td>
							</tr>
<?
if (!isset($_SESSION["isAgenteComercial"])) {
?>
							<tr>
								<td colspan="4">Si el trabajador no se encuentra dado de alta en la nómina de la empresa, haga <a class="linkSubrayado" href="/nomina-trabajadores/alta-trabajador">clic aquí</a> para darlo de alta.</td>
							</tr>
<?
}
?>
							<tr>
								<td colspan="4" height="15"></td>
							</tr>
							<tr>
								<td align="right" width="120">Apellido y Nombre *</td>
								<td><span id="apellidoNombre"><?= ($cargarCampos)?$row["JW_NOMBRE"]:""?></span></td>
								<td><img border="0" id="btnBuscarTrabajador" src="/modules/usuarios_registrados/images/buscar_trabajador.jpg" style="cursor:pointer;" onClick="buscarTrabajador()" /></td>
								<td></td>
							</tr>
							<tr>
								<td align="right" style="padding-right:16px;">C.U.I.L.</td>	
								<td><span id="cuil"><?= ($cargarCampos)?$row["JW_DOCUMENTO"]:""?></span></td>
								<td align="right"></td>
								<td></td>
							</tr>
							<tr>
								<td align="right" style="padding-right:16px;">Sexo</td>
								<td><?= $comboSexo->draw();?></td>
								<td align="right">Estado Civil</td>
								<td><?= $comboEstadoCivil->draw();?></td>
							</tr>
							<tr>
								<td align="right" style="padding-right:16px;">Nacionalidad</td>
								<td><?= $comboNacionalidad->draw();?></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td align="right">Fecha Nacimiento *</td>	
								<td>
									<input id="fechaNacimiento" maxlength="10" name="fechaNacimiento" style="width:76px;" type="text" value="<?= ($cargarCampos)?$row["JW_FEC_NACIMIENTO"]:""?>" onBlur="getElementById('spanFechaNacimiento').innerHTML = this.value;">
									<input class="botonFecha" id="btnFechaNacimiento" name="btnFechaNacimiento" style="vertical-align:-5px;" type="button" onBlur="getElementById('spanFechaNacimiento').innerHTML = getElementById('fechaNacimiento').value;">
								</td>
								<td align="right">Fecha Ingreso a la Empresa</td>
								<td>
									<input id="fechaIngreso" maxlength="10" name="fechaIngreso" style="width:76px;" type="text" value="<?= ($cargarCampos)?$row["JW_FEC_INGRESO"]:""?>"onBlur="getElementById('spanFechaIngreso').innerHTML = this.value;">
									<input class="botonFecha" id="btnFechaIngreso" name="btnFechaIngreso" style="vertical-align:-5px;" type="button" onBlur="getElementById('spanFechaIngreso').innerHTML = getElementById('fechaIngreso').value;">
								</td>
							</tr>
							<tr>
								<td colspan="4" height="20"></td>	
							</tr>
							<tr>
								<td colspan="4" class="TituloTablaCeleste">Domicilio del Trabajador</td>	
							</tr>
							<tr>
								<td colspan="4">
									<div>
<?
$hayDatos = (($cargarCampos) and ($row["JW_CALLE"] != ""));
if (!$hayDatos) {
?>
									<p id="pSinDatosconocidos" style="margin-left:132px;">
										<span>- Sin Datos Conocidos -</span>
									</p>
<?
}
?>
									<div id="divDatosDomicilio" style="display:<?= ($hayDatos)?"block":"none"?>">
										<p style="margin-left:100px;">
											<label for="calle">Calle</label>
											<input id="calle" maxlength="60" name="calle" readonly style="background-color:#ccc; width:500px;" type="text" value="<?= ($cargarCampos)?$row["JW_CALLE"]:""?>">
										</p>
										<p style="margin-left:84px;">
											<label for="numero">Número</label>
											<input id="numero" maxlength="6" name="numero" style="width:76px;" type="text" value="<?= ($cargarCampos)?$row["JW_NUMERO"]:""?>" onBlur="getElementById('spanNumero').innerHTML = this.value;">
											<label for="piso" style="margin-left:16px;">Piso</label>
											<input id="piso" maxlength="6" name="piso" style="width:76px;" type="text" value="<?= ($cargarCampos)?$row["JW_PISO"]:""?>" onBlur="getElementById('spanPiso').innerHTML = this.value;">
											<label for="departamento" style="margin-left:16px;">Departamento</label>
											<input id="departamento" maxlength="6" name="departamento" style="width:76px;" type="text" value="<?= ($cargarCampos)?$row["JW_DEPARTAMENTO"]:""?>" onBlur="getElementById('spanDepartamento').innerHTML = this.value;">
										</p>
										<p style="margin-left:76px;">
											<label for="localidad">Localidad</label>
											<input id="localidad" maxlength="85" name="localidad" readonly style="background-color:#ccc; width:320px;" type="text" value="<?= ($cargarCampos)?$row["JW_LOCALIDAD"]:""?>">
											<label for="codigoPostal" style="margin-left:16px;">Código Postal</label>
											<input id="codigoPostal" maxlength="5" name="codigoPostal" readonly style="background-color:#ccc; width:67px;" type="text" value="<?= ($cargarCampos)?$row["JW_CODPOSTAL"]:""?>">
										</p>
										<p style="margin-left:76px;">
											<label for="provincia">Provincia</label>
											<input id="provincia" name="provincia" readonly style="background-color:#ccc; width:320px;" type="text" value="<?= ($cargarCampos)?$row["PROVINCIATRABAJADOR"]:""?>">
										</p>
									</div>
									<p style="margin-left:132px; margin-top:16px;">
										<img border="0" id="btnBuscarDomicilio" src="/modules/usuarios_registrados/images/boton_buscar_domicilio.jpg" style="cursor:pointer;" onClick="buscarDomicilio(true, 'pSinDatosConocidos', 'divDatosDomicilio', 'idProvincia', 'provincia', 'localidad', '', 'codigoPostal', 'calle', 'numero', 'piso', 'departamento', '', 0, 680, 0, 0);">
									</p>
									</div>
									<p style="margin-left:68px; margin-top:16px;">
										<label for="telefono">Teléfono *</label>
										<input id="telefono" maxlength="30" name="telefono" style="width:160px;" type="text" value="<?= ($cargarCampos)?$row["JW_TELEFONO"]:""?>" onBlur="getElementById('spanTelefono').innerHTML = this.value;">
									</p>
								</td>
							</tr>
							<tr>
								<td colspan="4"><hr style="border-bottom:1px solid;" /></td>	
							</tr>
							<tr>
								<td align="right" width="104">Puesto *</td>	
								<td colspan="3"><input id="puesto" name="puesto" style="width:480px;" type="text" value="<?= ($cargarCampos)?$row["JW_PUESTO"]:""?>" onBlur="getElementById('spanPuesto').innerHTML = this.value;"></td>
							</tr>
							<tr>
								<td colspan="4">
									<span style="margin-left:70px;">Horario habitual de Trabajo *</span>
									<span style="margin-left:12px; margin-right:12px;">de</span>
									<select id="horaDesde" name="horaDesde" onChange="copiarValor(this, getElementById('horaJornadaLaboralDesde'))" onBlur="getElementById('spanHorarioTrabajoDesde').innerHTML = getElementById('horaDesde').value + ':' + getElementById('minutoDesde').value;"><?= fillComboHora()?></select>
									<select id="minutoDesde" name="minutoDesde" onChange="copiarValor(this, getElementById('minutoJornadaLaboralDesde'))" onBlur="getElementById('spanHorarioTrabajoDesde').innerHTML = getElementById('horaDesde').value + ':' + getElementById('minutoDesde').value;"><?= fillComboMinuto()?></select>
									<span style="margin-left:12px; margin-right:12px;">a</span>
									<select id="horaHasta" name="horaHasta" onChange="copiarValor(this, getElementById('horaJornadaLaboralHasta'))" onBlur="getElementById('spanHorarioTrabajoHasta').innerHTML = getElementById('horaHasta').value + ':' + getElementById('minutoHasta').value;"><?= fillComboHora()?></select>
									<select id="minutoHasta" name="minutoHasta" onChange="copiarValor(this, getElementById('minutoJornadaLaboralHasta'))" onBlur="getElementById('spanHorarioTrabajoHasta').innerHTML = getElementById('horaHasta').value + ':' + getElementById('minutoHasta').value;"><?= fillComboMinuto()?></select>
								</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>	
							</tr>
						</table>
					</td>
				</tr>
			</table>		
		</td>
	</tr>
</table>
</div>


<div class="ContenidoSeccion" id="divPaso2" style="display:none">
<table cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="1" bordercolor="#808080">
				<tr>
					<td>
						<table cellpadding="5" cellspacing="0" width="710px">
							<tr>
								<td colspan="4" class="TituloTablaCeleste">Datos del siniestro</td>	
							</tr>
							<tr>
								<td colspan="4" height="15"></td>	
							</tr>
							<tr>
								<td align="right">Tipo de Siniestro *</td>	
								<td><?= $comboTipoSiniestro->draw();?></td>
								<td align="right" width="112">Siniestro Múltiple</td>	
								<td width="224">
									<select id="siniestroMultiple" name="siniestroMultiple" onBlur="getElementById('spanSiniestroMultiple').innerHTML = this.options[this.selectedIndex].text;">
										<option value="1" <?= (($cargarCampos) and ($row["EW_MULTIPLE"] == "1"))?"selected":""?>>Si</option>
										<option value="0" <?= (($cargarCampos) and ($row["EW_MULTIPLE"] == "0"))?"selected":"selected"?>>No</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right">Fecha Siniestro *</td>	
								<td>
									<input id="fechaSiniestro" maxlength="10" name="fechaSiniestro" style="width:76px;" type="text" value="<?= ($cargarCampos)?$row["EW_FECHASIN"]:""?>" onBlur="getElementById('spanFechaSiniestro').innerHTML = this.value;">
									<input class="botonFecha" id="btnFechaSiniestro" name="btnFechaSiniestro" style="vertical-align:-5px;" type="button" onBlur="getElementById('spanFechaSiniestro').innerHTML = getElementById('fechaSiniestro').value;">
								</td>
								<td align="right">Fecha Recaida</td>
								<td>
									<input id="fechaRecaida" maxlength="10" name="fechaRecaida" style="width:76px;" type="text" value="<?= ($cargarCampos)?$row["EW_EPMANIFESTACION"]:""?>" onBlur="getElementById('spanFechaCaida').innerHTML = this.value;">
									<input class="botonFecha" id="btnFechaRecaida" name="btnFechaRecaida" style="vertical-align:-5px;" type="button" onBlur="getElementById('spanFechaCaida').innerHTML = getElementById('fechaRecaida').value;">
								</td>
							</tr>
							<tr>
								<td align="right" style="padding-right:16px;">Hora Accidente </td>	
								<td>
									<select id="horaAccidente" name="horaAccidente" onBlur="getElementById('spanHoraAccidente').innerHTML = getElementById('horaAccidente').value + ':' + getElementById('minutoAccidente').value;"><?= fillComboHora()?></select>
									<select id="minutoAccidente" name="minutoAccidente" onBlur="getElementById('spanHoraAccidente').innerHTML = getElementById('horaAccidente').value + ':' + getElementById('minutoAccidente').value;"><?= fillComboMinuto()?></select>
								</td>
								<td align="right">H. Jorn. Laboral</td>
								<td style="width:248px;">
									de
									<select id="horaJornadaLaboralDesde" name="horaJornadaLaboralDesde" onBlur="getElementById('spanHorarioJornadaLaboral').innerHTML = getElementById('horaJornadaLaboralDesde').value + ':' + getElementById('minutoJornadaLaboralDesde').value + ' a ' + getElementById('horaJornadaLaboralHasta').value + ':' + getElementById('minutoJornadaLaboralHasta').value;"><?= fillComboHora()?></select>
									<select id="minutoJornadaLaboralDesde" name="minutoJornadaLaboralDesde" onBlur="getElementById('spanHorarioJornadaLaboral').innerHTML = getElementById('horaJornadaLaboralDesde').value + ':' + getElementById('minutoJornadaLaboralDesde').value + ' a ' + getElementById('horaJornadaLaboralHasta').value + ':' + getElementById('minutoJornadaLaboralHasta').value;"><?= fillComboMinuto()?></select>
									a
									<select id="horaJornadaLaboralHasta" name="horaJornadaLaboralHasta" onBlur="getElementById('spanHorarioJornadaLaboral').innerHTML = getElementById('horaJornadaLaboralDesde').value + ':' + getElementById('minutoJornadaLaboralDesde').value + ' a ' + getElementById('horaJornadaLaboralHasta').value + ':' + getElementById('minutoJornadaLaboralHasta').value;"><?= fillComboHora()?></select>
									<select id="minutoJornadaLaboralHasta" name="minutoJornadaLaboralHasta" onBlur="getElementById('spanHorarioJornadaLaboral').innerHTML = getElementById('horaJornadaLaboralDesde').value + ':' + getElementById('minutoJornadaLaboralDesde').value + ' a ' + getElementById('horaJornadaLaboralHasta').value + ':' + getElementById('minutoJornadaLaboralHasta').value;"><?= fillComboMinuto()?></select>
								</td>
							</tr>
							<tr>
								<td align="right" style="padding-right:15px;">Lugar de Ocurrencia</td>
								<td><?= $comboLugarOcurrencia->draw();?></td>
								<td align="right">&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr id="trLugarOcurrenciaOtros" style="visibility:hidden">
								<td align="right" style="padding-right:16px;">Otros detallar</td>
								<td colspan="3"><input id="lugarOcurrenciaOtros" maxlength="100" name="lugarOcurrenciaOtros" style="width:542px;" type="text" value="<?= ($cargarCampos)?$row["EW_OTROLUGAR"]:""?>" onBlur="copiarLugarOcurrencia()"></td>
							</tr>
							<tr id="trEstablecimientoTercero" style="visibility:hidden;">
								<td align="right">Establecimiento de 3ro.</td>	
								<td colspan="3">
									<?= $comboEstablecimientoTercero->draw();?>
									<input class="btnAdminEst3ro" style="margin-left:16px;" type="button" value="" onClick="administrarEstablecimientos()" />
								</td>
							</tr>
							<tr id="trEstablecimiento" style="visibility:hidden">
								<td align="right">Establecimiento Prop*</td>
								<td colspan="3"><?= $comboEstablecimientoPropio->draw();?></td>
							</tr>
							<tr>
								<td colspan="4" height="20"></td>	
							</tr>
							<tr>
								<td colspan="4" class="TituloTablaCeleste">Domicilio de ocurrencia del accidente</td>	
							</tr>
							<tr>
								<td colspan="4">
									<div>
										<div id="divDatosDomicilioAccidente" style="display:<?= (true/*$hayDatos*/)?"block":"none"?>">
											<p style="margin-left:45px;">
												<label for="calle">Calle</label>
												<input id="calleAccidente" maxlength="60" name="calleAccidente" style="width:500px;" type="text" value="<?= ($cargarCampos)?$row["EW_LUGARCALLE"]:""?>" />
											</p>
											<p style="margin-left:29px;">
												<label for="numero">Número</label>
												<input id="numeroAccidente" maxlength="6" name="numeroAccidente" style="width:76px;" type="text" value="<?= ($cargarCampos)?$row["EW_LUGARNRO"]:""?>" onBlur="getElementById('spanNumeroAccidente').innerHTML = this.value;" />
												<label for="piso" style="margin-left:43px;">Piso</label>
												<input id="pisoAccidente" maxlength="6" name="pisoAccidente" style="width:76px;" type="text" value="" onBlur="getElementById('spanPisoAccidente').innerHTML = this.value;" />
												<label for="departamento" style="margin-left:16px;">Departamento</label>
												<input id="departamentoAccidente" maxlength="6" name="departamentoAccidente" style="width:76px;" type="text" value="" onBlur="getElementById('spanDepartamentoAccidente').innerHTML = this.value;" />
											</p>
											<div>
												<label for="codigoPostalAccidente">Código Postal</label>
												<input id="codigoPostalAccidente" maxlength="5" name="codigoPostalAccidente" readonly style="background-color:#ccc; text-transform:uppercase; width:76px;" type="text" value="<?= ($cargarCampos)?$row["EW_LUGARCPOSTAL"]:""?>" onBlur="this.value = this.value.toUpperCase(); cargarComboLocalidadAccidente();" />
												<label for="provinciaAccidente" style="margin-left:16px;">Provincia</label>
												<input id="provinciaAccidente" name="provinciaAccidente" readonly style="background-color:#ccc; text-transform:uppercase;" type="text" value="<?= ($cargarCampos)?$row["EW_LUGARPROVINCIA"]:""?>" />
												<img id="btnBuscarLocalidad" src="/modules/usuarios_registrados/images/boton_buscar_codigo_postal.jpg" style="cursor:pointer; margin-left:16px; vertical-align:-3px;" onClick="buscarCodigoPostal()" />
											</div>
											<div style="margin-top:8px;">
												<label for="localidadAccidente" style="margin-left:21px;">Localidad</label>
												<input id="localidadAccidente" maxlength="85" name="localidadAccidente" readonly style="background-color:#ccc; text-transform:uppercase;" type="text" value="<?= ($cargarCampos)?$row["EW_LUGARLOCALIDAD"]:""?>" />
											</div>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td align="right">Establecimiento</td>
								<td><?= $comboEstablecimientoAccidente->draw();?></td>
								<td><p align="right">CUIT Contratista *</td>
								<td><input id="cuitContratista" maxlength="13" name="cuitContratista" type="text" value="<?= ($cargarCampos)?$row["EW_CUITCONTRATISTA"]:""?>"></td>
							</tr>
							<tr>
								<td colspan="4"><hr color="#c0c0c0"></td>	
							</tr>
							<tr>
								<td colspan="4">Descripción de tareas al momento del accidente (<i>máximo 100 caracteres</i>)</td>	
							</tr>
							<tr>
								<td colspan="4"><textarea id="tareasAccidente" maxlength="100" name="tareasAccidente" style="height:72px; width:680px;" onBlur="getElementById('spanTareasAccidente').innerHTML = this.value;"><?= ($cargarCampos)?$row["EW_TAREAACCIDENTE"]:""?></textarea></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>		
		</td>
	</tr>
</table>
</div>


<div class="ContenidoSeccion" id="divPaso3" style="display:none">
<table cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="1" bordercolor="#808080">
				<tr>
					<td>
						<table cellpadding="5" cellspacing="0" width="710px">
							<tr>
								<td colspan="2" class="TituloTablaCeleste">Descripción y códigos</td>
							</tr>
							<tr>
								<td colspan="2" height="8"></td>
							</tr>
							<tr>
								<td align="right" valign="top" width="176">
									Descripción del Hecho *<br />
									(<i>máximo 250 caracteres</i>)
								</td>
								<td height="15"><textarea id="descripcionHecho" maxlength="250" name="descripcionHecho" rows="4" style="width:496px;" onBlur="getElementById('spanDescripcionHecho').innerHTML = this.value;"><?= ($cargarCampos)?$row["EW_DESCRIPCION"]:""?></textarea></td>
							</tr>
							<tr>
								<td align="right">Forma del Accidente *</td>
								<td><?= $comboFormaAccidente->draw();?></td>
							</tr>
							<tr>
								<td align="right">Agente Material *</td>
								<td><?= $comboAgenteMaterial->draw();?></td>
							</tr>
							<tr>
								<td align="right">Parte del Cuerpo Lesionada *</td>
								<td><?= $comboParteCuerpoLesionada->draw();?></td>
							</tr>
							<tr>
								<td align="right">Naturaleza de la Lesión *</td>
								<td><?= $comboNaturalezaLesion->draw();?></td>
							</tr>
							<tr>
								<td align="right">Gravedad Presunta *</td>
								<td><?= $comboGravedadPresunta->draw();?></td>
							</tr>
							<tr>
								<td align="right" style="padding-right:16px;">Mano Hábil</td>
								<td><?= $comboManoHabil->draw();?></td>
								</tr>
							<tr>
								<td align="right" style="padding-right:16px;">Accidente de Tránsito</td>
								<td><?= $comboAccidenteTransito->draw();?></td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>		
		</td>
	</tr>
</table>
</div>


<div class="ContenidoSeccion" id="divPaso4" style="display:none">
<table cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="1" bordercolor="#808080">
				<tr>
					<td>
						<table cellpadding="5" cellspacing="0" width="710px">
							<tr>
								<td colspan="3" class="TituloTablaCeleste">Prestaciones Médicas</td>	
							</tr>
							<tr>
								<td colspan="3">Utilice el buscador de prestadores para localizar donde fue atendido el trabajador accidentado.<br>Si no lo encuentra, detalle la razón social, el domicilio y teléfono del prestador.</td>	
							</tr>
							<tr>
								<td colspan="3" height="15"></td>	
							</tr>
							<tr>
								<td width="196" align="right"></td>
								<td colspan="2" width="227">
									<img border="0" id="btnBuscarPrestador" src="/modules/usuarios_registrados/images/buscar_prestador.jpg" style="cursor:pointer;" onClick="buscarPrestador()">
									<img border="0" id="btnLimpiarSeleccion" src="/modules/usuarios_registrados/images/cruz.gif" style="cursor:pointer; margin-left:16px; vertical-align:-6px;" title="Limpiar selección" onClick="quitarPrestador()">
								</td>
							</tr>
							<tr>
								<td colspan="3"><hr color="#C0C0C0"></td>
							</tr>
							<tr>
								<td align="right">Razón Social</td>
								<td colspan="2"><input id="razonSocialPrestador" maxlength="100" name="razonSocialPrestador" style="width:480px;" type="text" value="<?= ($cargarCampos)?$row["EW_PRESTADORNOMBRE"]:""?>" onBlur="getElementById('spanRazonSocialPrestador').innerHTML = this.value;"></td>
							</tr>
							<tr>
								<td align="right">Teléfono</td>
								<td colspan="2"><input id="telefonoPrestador" maxlength="50" name="telefonoPrestador" style="width:480px;" type="text" value="<?= ($cargarCampos)?$row["EW_PRESTADORTELEFONO"]:""?>" onBlur="getElementById('spanTelefonoPrestador').innerHTML = this.value;"></td>
							</tr>
							<tr>
								<td align="right" valign="top">Domicilio</td>
								<td colspan="2"><textarea id="domicilioPrestador" maxlength="100" name="domicilioPrestador" style="height:44px; width:480px;" onBlur="getElementById('spanDomicilioPrestador').innerHTML = this.value;"><?= ($cargarCampos)?$row["EW_PRESTADORDOMICILIO"]:""?></textarea></td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>		
		</td>
	</tr>
</table>
</div>


<div class="ContenidoSeccion" id="divPaso5" style="display:none">
<table cellpadding="0" cellspacing="0">
	<tr>
		<td class="SubtituloSeccionAzul">Verifique su denuncia</td>
	</tr>
	<tr>
		<td class="ContenidoSeccion">
			Puede utilizar los botones del panel superior para recorrer los pasos del formulario.<br />
			Si es correcto, presione Enviar.
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>

	<tr>
		<td>
			<div style="border-color:#808080; border-style:double; width:712px;">
				<div class="TituloTablaCeleste">Datos del trabajador accidentado</div>
				<p style="margin-left:8px;">
					<label><b>Apellido y Nombre</b></label>
					<span id="spanApellidoNombre"><?= ($cargarCampos)?$row["JW_NOMBRE"]:""?></span>
					<label style="margin-left:16px;"><b>C.U.I.L.</b></label>
					<span id="spanCuil"><?= ($cargarCampos)?$row["JW_DOCUMENTO"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Sexo</b></label>
					<span id="spanSexo"><?= ($cargarCampos)?$row["SEXO"]:""?></span>
					<label style="margin-left:16px;"><b>Estado Civil</b></label>
					<span id="spanEstadoCivil"><?= ($cargarCampos)?$row["ESTADOCIVIL"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Fecha de Nacimiento</b></label>
					<span id="spanFechaNacimiento"><?= ($cargarCampos)?$row["JW_FEC_NACIMIENTO"]:""?></span>
					<label style="margin-left:16px;"><b>Nacionalidad</b></label>
					<span id="spanNacionalidad"><?= ($cargarCampos)?$row["NACIONALIDAD"]:""?></span>
				</p>
				<div class="TituloTablaCeleste">Domicilio</div>
				<p style="margin-left:8px;">
					<label><b>Calle</b></label>
					<span id="spanCalle"><?= ($cargarCampos)?$row["JW_CALLE"]:""?></span>
					<label style="margin-left:16px;"><b>Nro</b></label>
					<span id="spanNumero"><?= ($cargarCampos)?$row["JW_NUMERO"]:""?></span>
					<label style="margin-left:16px;"><b>Piso</b></label>
					<span id="spanPiso"><?= ($cargarCampos)?$row["JW_PISO"]:""?></span>
					<label style="margin-left:16px;"><b>Dpto</b></label>
					<span id="spanDepartamento"><?= ($cargarCampos)?$row["JW_DEPARTAMENTO"]:""?></span>
					<label style="margin-left:16px;"><b>Código Postal</b></label>
					<span id="spanCodigoPostal"><?= ($cargarCampos)?$row["JW_CODPOSTAL"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Localidad</b></label>
					<span id="spanLocalidad"><?= ($cargarCampos)?$row["JW_LOCALIDAD"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Provincia</b></label>
					<span id="spanProvincia"><?= ($cargarCampos)?$row["PROVINCIATRABAJADOR"]:""?></span>
					<label style="margin-left:16px;"><b>País</b></label>
					<span id="spanPais">Argentina</span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Teléfono</b></label>
					<span id="spanTelefono"><?= ($cargarCampos)?$row["JW_TELEFONO"]:""?></span>
					<label style="margin-left:16px;"><b>Puesto</b></label>
					<span id="spanPuesto"><?= ($cargarCampos)?$row["JW_PUESTO"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Horario habitual de trabajo</b></label>
					<span id="spanHorarioTrabajoDesde"><?= ($cargarCampos)?$row["JW_HORARIOINICIO"]:""?></span>&nbsp;<b>a</b>&nbsp;<span id="spanHorarioTrabajoHasta"><?= ($cargarCampos)?$row["JW_HORARIOFIN"]:""?></span>
				</p>
			</div>		
		</td>
	</tr>

	<tr>
		<td>&nbsp;</td>
	</tr>

	<tr>
		<td>
			<div style="border-color:#808080; border-style:double; width:712px;">
				<div class="TituloTablaCeleste">Datos del siniestro</div>
				<p style="margin-left:8px;">
					<label><b>Tipo de Siniestro</b></label>
					<span id="spanTipoSiniestro"><?= ($cargarCampos)?$row["TIPOSINIESTRO"]:""?></span>
					<label style="margin-left:16px;"><b>Siniestro Múltiple</b></label>
					<span id="spanSiniestroMultiple"><?= ($cargarCampos)?(($row["EW_MULTIPLE"] == "1")?"Si":"No"):"No"?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Fecha Siniestro</b></label>
					<span id="spanFechaSiniestro"><?= ($cargarCampos)?$row["EW_FECHASIN"]:""?></span>
					<label style="margin-left:16px;"><b>Fecha 1º manifest. o caida</b></label>
					<span id="spanFechaCaida"><?= ($cargarCampos)?$row["EW_EPMANIFESTACION"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Hora Accidente</b></label>
					<span id="spanHoraAccidente"><?= ($cargarCampos)?$row["EW_HORASIN"]:""?></span>
					<label style="margin-left:16px;"><b>Horario Jornada Laboral</b></label>
					<span id="spanHorarioJornadaLaboral"><?= ($cargarCampos)?$row["EW_HORJORNADADESDE"]." a ".$row["EW_HORJORNADAHASTA"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Sector/Lugar de ocurrencia</b></label>
					<span id="spanLugarOcurrencia"></span>
				</p>
				<div class="TituloTablaCeleste">Domicilio de ocurrencia del accidente</div>
				<p style="margin-left:8px;">
					<label><b>Calle</b></label>
					<span id="spanCalleAccidente"><?= ($cargarCampos)?$row["EW_LUGARCALLE"]:""?></span>
					<label style="margin-left:16px;"><b>Nro</b></label>
					<span id="spanNumeroAccidente"><?= ($cargarCampos)?$row["EW_LUGARNRO"]:""?></span>
					<label style="margin-left:16px;"><b>Piso</b></label>
					<span id="spanPisoAccidente"></span>
					<label style="margin-left:16px;"><b>Dpto</b></label>
					<span id="spanDepartamentoAccidente"></span>
					<label style="margin-left:16px;"><b>Código Postal</b></label>
					<span id="spanCodigoPostalAccidente"><?= ($cargarCampos)?$row["EW_LUGARCPOSTAL"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Localidad</b></label>
					<span id="spanLocalidadAccidente"><?= ($cargarCampos)?$row["EW_LUGARLOCALIDAD"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Provincia</b></label>
					<span id="spanProvinciaAccidente"><?= ($cargarCampos)?$row["PROVINCIAACCIDENTE"]:""?></span>
					<label style="margin-left:16px;"><b>País</b></label>
					<span id="spanPaisAccidente">Argentina</span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Tipo Establecimiento</b></label>
					<span id="spanEstablecimientoPropio"><?= ($cargarCampos)?$row["ESTABLECIMIENTOPROPIO"]:"Propio"?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Descripción de tareas al momento del accidente</b></label>
					<span id="spanTareasAccidente"><?= ($cargarCampos)?$row["EW_TAREAACCIDENTE"]:""?></span>
				</p>
			</div>
		</td>
	</tr>

	<tr>
		<td>&nbsp;</td>
	</tr>

	<tr>
		<td>
			<div style="border-color:#808080; border-style:double; width:712px;">
				<div class="TituloTablaCeleste">Descripción y Códigos</div>
				<p style="margin-left:8px;">
					<label><b>Descripción del Hecho</b></label>
					<span id="spanDescripcionHecho"><?= ($cargarCampos)?$row["EW_DESCRIPCION"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Forma del Accidente</b></label>
					<span id="spanFormaAccidente"><?= ($cargarCampos)?$row["FORMAACCIDENTE"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Agente Causante</b></label>
					<span id="spanAgenteCausante"><?= ($cargarCampos)?$row["AGENTEMATERIAL"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Parte del Cuerpo Lesionada</b></label>
					<span id="spanParteCuerpoLesionada"><?= ($cargarCampos)?$row["ZONA"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Naturaleza de la Lesión</b></label>
					<span id="spanNaturalezaLesion"><?= ($cargarCampos)?$row["NATURALEZALESION"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Gravedad Presunta</b></label>
					<span id="spanGravedadPresunta"><?= ($cargarCampos)?$row["GRAVEDADPRESUNTA"]:""?></span>
					<label style="margin-left:16px;"><b>Mano Hábil</b></label>
					<span id="spanManoHabil"><?= ($cargarCampos)?(($row["EW_MANOHABIL"] == "I")?"Izquierda":(($row["EW_MANOHABIL"] == "D")?"Derecha":"Ambas")):"Ambas"?></span>
					<label style="margin-left:16px;"><b>Accidente de Tránsito</b></label>
					<span id="spanAccidenteTransito"><?= ($cargarCampos)?(($row["EW_TRANSITO"] == 1)?"Si":"No"):"No"?></span>
				</p>
			</div>
		</td>
	</tr>

	<tr>
		<td>&nbsp;</td>
	</tr>

	<tr>
		<td>
			<div style="border-color:#808080; border-style:double; width:712px;">
				<div class="TituloTablaCeleste">Prestaciones Médicas</div>
				<p style="margin-left:8px;">
					<label><b>Prestador</b></label>
					<span id="spanPrestador"><?= ($cargarCampos)?$row["EW_PRESTADORNOMBRE"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Razón Social</b></label>
					<span id="spanRazonSocialPrestador"><?= ($cargarCampos)?$row["EW_PRESTADORNOMBRE"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Teléfono</b></label>
					<span id="spanTelefonoPrestador"><?= ($cargarCampos)?$row["EW_PRESTADORTELEFONO"]:""?></span>
				</p>
				<p style="margin-left:8px;">
					<label><b>Domicilio</b></label>
					<span id="spanDomicilioPrestador"><?= ($cargarCampos)?$row["EW_PRESTADORDOMICILIO"]:""?></span>
				</p>
			</div>
		</td>
	</tr>

	<tr>
		<td>&nbsp;</td>
	</tr>

	<tr>
		<td>
			<div style="border-color:#808080; border-style:double; width:712px;">
				<div class="TituloTablaCeleste">Datos Finales</div>
				<p style="margin-left:52px;">
					<label><b>Lugar</b></label>
					<input id="lugar" maxlength="255" name="lugar" style="width:600px;" type="text" value="" />
				</p>
				<p style="margin-left:8px;">
					<label><b>Denunciante</b></label>
					<input id="denunciante" maxlength="255" name="denunciante" style="width:600px;" type="text" value="" />
				</p>
				<p style="margin-left:53px;">
					<label><b>D.N.I.</b></label>
					<input id="dni" maxlength="8" name="dni" style="width:64px;" type="text" value="" />
				</p>
			</div>
		</td>
	</tr>

</table>
</div>


<div class="ContenidoSeccion" style="margin-top:20px;">
	<p>
		<span><i>Los campos marcados con asterisco * son obligatorios.</i></span>
	</p>
	<p>
		<img border="0" id="btnAnterior" src="/modules/usuarios_registrados/images/anterior.jpg" style="cursor:pointer;" onClick="paso--; cambiarPaso(paso);">
		<img border="0" id="btnSiguiente" src="/modules/usuarios_registrados/images/siguiente.jpg" style="cursor:pointer; margin-left:8px;" onClick="paso++; cambiarPaso(paso);">
		<input class="btnEnviar" id="btnEnviar" style="margin-left:8px; vertical-align:1px;" type="button" value="" onClick="enviar()" />
		<img border="0" id="imgProcesando" src="/images/loading.gif" style="display:none;" title="Procesando, aguarde unos segundos por favor..." />
<?
if (!$isAlta) {
?>
	<a href="/denuncia-siniestros/formulario/<?= $_REQUEST["id"]?>">
		<img border="0" id="btnGenerarPdf" src="/modules/usuarios_registrados/images/ver_pdf.jpg">
	</a>
<?
}
?>
		<a href="/denuncia-siniestros"><img border="0" id="btnSalir" src="/modules/usuarios_registrados/images/salir.jpg" style="cursor:pointer; margin-left:8px;" /></a>
	</p>
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
	<a href="/denuncia-siniestros"><input class="btnVolver" type="button" value="" /></a>
</div>
</form>
<script type="text/javascript">
	Calendar.setup ({
		inputField: "fechaNacimiento",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaNacimiento"
	});
	Calendar.setup ({
		inputField: "fechaIngreso",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaIngreso"
	});
	Calendar.setup ({
		inputField: "fechaSiniestro",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaSiniestro"
	});
	Calendar.setup ({
		inputField: "fechaRecaida",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaRecaida"
	});

	with (document) {
		getElementById('horaAccidente').value							= '<?= ($cargarCampos)?$row["HORAACCIDENTE"]:-1?>';
		getElementById('horaDesde').value									= '<?= ($cargarCampos)?$row["HORADESDE"]:-1?>';
		getElementById('horaHasta').value									= '<?= ($cargarCampos)?$row["HORAHASTA"]:-1?>';
		getElementById('horaJornadaLaboralDesde').value		= '<?= ($cargarCampos)?$row["HORAJORNADALABORALDESDE"]:-1?>';
		getElementById('horaJornadaLaboralHasta').value		= '<?= ($cargarCampos)?$row["HORAJORNADALABORALHASTA"]:-1?>';
		getElementById('minutoAccidente').value						= '<?= ($cargarCampos)?$row["MINUTOACCIDENTE"]:-1?>';
		getElementById('minutoDesde').value								= '<?= ($cargarCampos)?$row["MINUTODESDE"]:-1?>';
		getElementById('minutoHasta').value								= '<?= ($cargarCampos)?$row["MINUTOHASTA"]:-1?>';
		getElementById('minutoJornadaLaboralDesde').value	= '<?= ($cargarCampos)?$row["MINUTOJORNADALABORALDESDE"]:-1?>';
		getElementById('minutoJornadaLaboralHasta').value	= '<?= ($cargarCampos)?$row["MINUTOJORNADALABORALHASTA"]:-1?>';
	}

	var todoDeshabilitado = false;
	if (<?= ($isAlta)?"false":"true"?>) {
		todoDeshabilitado = true;
		deshabilitarTodo();
	}

	cambiaLugarOcurrencia(<?= ($tieneEstablecimientosDeTercero)?"true":"false"?>, document.getElementById('lugarOcurrencia').value)
	copiarLugarOcurrencia();
	cambiarPaso(1);

<?
if (isset($_REQUEST["cem"])) {
?>
	with (document) {
		getElementById('fechaSiniestro').readOnly = true;
		getElementById('btnFechaSiniestro').style.display = 'none';
		getElementById('fechaRecaida').readOnly = true;
		getElementById('btnFechaRecaida').style.display = 'none';
		getElementById('btnBuscarTrabajador').style.display = 'none';
<?
	if (($row["EW_PRESTADORNOMBRE"] != "") or ($row["EW_PRESTADORTELEFONO"] != "") or ($row["EW_PRESTADORDOMICILIO"] != "")) {
?>
		getElementById('btnBuscarPrestador').style.display = 'none';
		getElementById('btnLimpiarSeleccion').style.display = 'none';
		getElementById('razonSocialPrestador').readOnly = true;
		getElementById('telefonoPrestador').readOnly = true;
		getElementById('domicilioPrestador').readOnly = true;
<?
	}
?>
	}
<?
}
?>
</script>