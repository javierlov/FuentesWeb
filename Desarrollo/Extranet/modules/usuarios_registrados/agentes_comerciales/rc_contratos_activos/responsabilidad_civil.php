<?
validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion(($_SESSION["entidad"] != 400));

SetDateFormatOracle("DD/MM/YYYY");


$params = array(":contrato" => $_REQUEST["id"]);
$sql =
	"SELECT zg_id
		 FROM (SELECT zg_id
						 FROM ahd_historicodomicilio, afi.azg_zonasgeograficas
						WHERE hd_provincia = zg_idprovincia
							AND hd_tipo = 'L'
							AND hd_contrato = :contrato
				 ORDER BY hd_id DESC)
		WHERE ROWNUM = 1";
$idZonaGeografica = valorSql($sql, 0, $params);

$params = array(":contrato" => $_REQUEST["id"]);
$sql =
	"SELECT DISTINCT 'S'
		 FROM aco_contrato JOIN art.apr_polizarc ON pr_idformulario = co_idformulario
		 JOIN art.ado_documentacion ON do_idformulario = co_idformulario
		WHERE 1 = 1
			AND do_iddocumentoafi = 21
			AND do_presente = 'S'
			AND co_contrato = :contrato";
$existe2 = (valorSql($sql, "N", $params) == "S");

$params = array(":contrato" => $_REQUEST["id"]);
$sql =
	"SELECT TO_CHAR(art.cotizacion.get_valor_rc(em_cuit, NVL(co_totempleadosactual, co_totempleados), DECODE(NVL(co_masatotalactual, NVL(co_masatotal, 0)), 0, 1, NVL(co_masatotalactual, co_masatotal)), tc_porcmasa, tc_sumafija, zg_id, NULL, 250000, 0), '9,999,999,990.00') || '%' alicuota250,
				  TO_CHAR(art.cotizacion.get_valor_rc(em_cuit, NVL(co_totempleadosactual, co_totempleados), DECODE(NVL(co_masatotalactual, NVL(co_masatotal, 0)), 0, 1, NVL(co_masatotalactual, co_masatotal)), tc_porcmasa, tc_sumafija, zg_id, NULL, 500000, 0), '9,999,999,990.00') || '%' alicuota500,
				  TO_CHAR(art.cotizacion.get_valor_rc(em_cuit, NVL(co_totempleadosactual, co_totempleados), DECODE(NVL(co_masatotalactual, NVL(co_masatotal, 0)), 0, 1, NVL(co_masatotalactual, co_masatotal)), tc_porcmasa, tc_sumafija, zg_id, NULL, 1000000, 0), '9,999,999,990.00') || '%' alicuota1000,
				  co_contrato, co_vigenciadesde, co_vigenciahasta, art.utiles.armar_cuit(em_cuit) cuit,
				  TO_CHAR(NVL(co_masatotalactual, co_masatotal) * art.cotizacion.get_valor_rc(em_cuit, NVL(co_totempleadosactual, co_totempleados), DECODE(NVL(co_masatotalactual, NVL(co_masatotal, 0)), 0, 1, NVL(co_masatotalactual, co_masatotal)), tc_porcmasa, tc_sumafija, zg_id, NULL, 250000, 0) / 100, '$9,999,999,990.00') cuotainicialrc250,
				  TO_CHAR(NVL(co_masatotalactual, co_masatotal) * art.cotizacion.get_valor_rc(em_cuit, NVL(co_totempleadosactual, co_totempleados), DECODE(NVL(co_masatotalactual, NVL(co_masatotal, 0)), 0, 1, NVL(co_masatotalactual, co_masatotal)), tc_porcmasa, tc_sumafija, zg_id, NULL, 500000, 0) / 100, '$9,999,999,990.00') cuotainicialrc500,
				  TO_CHAR(NVL(co_masatotalactual, co_masatotal) * art.cotizacion.get_valor_rc(em_cuit, NVL(co_totempleadosactual, co_totempleados), DECODE(NVL(co_masatotalactual, NVL(co_masatotal, 0)), 0, 1, NVL(co_masatotalactual, co_masatotal)), tc_porcmasa, tc_sumafija, zg_id, NULL, 1000000, 0) / 100, '$9,999,999,990.00') cuotainicialrc1000,
				  ev_identidad, em_nombre, TO_CHAR(NVL(co_masatotalactual, co_masatotal), '$9,999,999,990.00') masasalarial,
				  art.utiles.armar_periodo(co_ultimoperiodocobranza) periodo, NVL(co_totempleadosactual, co_totempleados) totempleados
		 FROM aco_contrato, aem_empresa, atc_tarifariocontrato, adc_domiciliocontrato, afi.azg_zonasgeograficas, avc_vendedorcontrato, xev_entidadvendedor
		WHERE co_idempresa = em_id
			AND co_contrato = tc_contrato
			AND co_contrato = dc_contrato
			AND dc_tipo = 'L'
			AND dc_provincia = zg_idprovincia
			AND co_contrato = vc_contrato(+)
			AND vc_identidadvend = ev_id(+)
			AND co_contrato = :contrato";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

validarSesion((validarContrato($_REQUEST["id"])) or (($_SESSION["canal"] == 321) and ($row["EV_IDENTIDAD"] == 400)));

if ((trim($row["MASASALARIAL"]) == "$0.00") or ($row["TOTEMPLEADOS"] == 0)) {
?>
<script type="text/javascript">
	alert('La cantidad de trabajadores o la masa salarial están en cero.');
	history.back();
</script>
<?
	exit;
}


$params = array(":contrato" => $_REQUEST["id"]);
$sql =
	"SELECT pr_apellido_nomre, pr_cbu, pr_fechaimpresion, pr_franquicia, pr_idcaracterfirma, pr_iibb, pr_iva, pr_mail, pr_medio_pago, pr_nrodocumento, pr_origenpago, pr_poliza, pr_sexo, pr_sumaasegurada
		 FROM art.apr_polizarc, aen_endoso
		WHERE pr_idendoso = en_id
			AND en_contrato = :contrato";
$stmt = DBExecSql($conn, $sql, $params);
$rowPoliza = DBGetQuery($stmt);

$existe = (($existe2) or ($rowPoliza["PR_FECHAIMPRESION"] != ""));

require_once("responsabilidad_civil_combos.php");
?>
<style>
	#tarjetaCredito {display:none; vertical-align:4px; width:208px;}
	#tarjetaCreditoFalso {vertical-align:4px; width:208px;}
</style>
<script src="/modules/usuarios_registrados/agentes_comerciales/js/clientes.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/agentes_comerciales/rc_contratos_activos/procesar_rc.php" id="formRC" method="post" name="formRC" target="iframeProcesando">
	<input id="contrato" name="contrato" type="hidden" value="<?= $_REQUEST["id"]?>">
	<input id="entidadContrato" name="entidadContrato" type="hidden" value="<?= $row["EV_IDENTIDAD"]?>">
	<div class="TituloSeccion" style="display:block; width:730px;">Responsabilidad Civil</div>
	<div class="ContenidoSeccion" style="border:1px solid #ccc; padding:4px; margin-left:8px; margin-top:12px; width:720px;">
		<div>
			<label>Contrato</label>
			<span><b><?= $row["CO_CONTRATO"]?></b></span>
			<label style="margin-left:16px;">C.U.I.T.</label>
			<span><b><?= $row["CUIT"]?></b></span>
		</div>
		<div style="margin-top:4px;">
			<label>Razón Social</label>
			<span><b><?= $row["EM_NOMBRE"]?></b></span>
		</div>
		<div style="margin-top:4px;">
			<label>Vigencia Desde</label>
			<span><b><?= $row["CO_VIGENCIADESDE"]?></b></span>
			<label style="margin-left:16px;">Vigencia Hasta</label>
			<span><b><?= $row["CO_VIGENCIAHASTA"]?></b></span>
		</div>
		<div style="margin-top:4px;">
			<label>Cantidad de Trabajadores</label>
			<span><b><?= $row["TOTEMPLEADOS"]?></b></span>
			<label style="margin-left:16px;">Masa Salarial</label>
			<span><b><?= $row["MASASALARIAL"]?></b></span>
			<label style="margin-left:16px;">Mes / Año</label>
			<span><b><?= $row["PERIODO"]?></b></span>
		</div>
	</div>
	<div class="ContenidoSeccion" style="margin-top:12px;">
		<div align="left" style="color:#211e1e; font-size:8pt;">
			<div style="font-size:8pt; font-weight:700; margin-bottom:6px;">
				<span>¿ Suscribe Póliza de Responsabilidad Civil ?</span>
				<label for="suscribePolizaRC" style="margin-left:16px;">SI</label>
				<input <?= ($rowPoliza["PR_POLIZA"] != "N")?"checked":""?> <?= ($existe)?"disabled":""?> id="suscribePolizaRC" name="suscribePolizaRC" style="margin:0px; vertical-align:middle;" type="radio" value="S">
				<label for="suscribePolizaRC" style="margin-left:16px;">NO</label>
				<input <?= ($rowPoliza["PR_POLIZA"] == "N")?"checked":""?> <?= ($existe)?"disabled":""?> id="suscribePolizaRC" name="suscribePolizaRC" style="margin:0px; vertical-align:middle;" type="radio" value="N">
				<div style="font-weight:normal;">Selección Suma Asegurada</div>
			</div>
			<div class="ContenidoSeccion" style="margin-left:32px; margin-top:16px; width:626px;">
				<div align="center" style="background-color:#00539b; color:#fff; font-weight:700; padding-bottom:8px; margin-left:19px; padding-top:8px; width:605px;">VALOR COTIZADO DE RESPONSABILIDAD CIVIL</div>
				<div align="center" style="background-color:#fff; color:#676767; margin-left:19px; ">
					<div style="border-left:1px solid #676767; float:left; font-weight:700; padding-bottom:2px; padding-top:2px; width:150px;">Suma Asegurada</div>
					<div style="border-left:1px solid #676767; float:left; padding-bottom:2px; padding-top:2px; width:150px;">Alícuota variable</div>
					<div style="border-left:1px solid #676767; float:left; padding-bottom:2px; padding-top:2px; width:150px;">Masa salarial</div>
					<div style="border-left:1px solid #676767; border-right:1px solid #676767; float:left; padding-bottom:2px; padding-top:2px; width:150px;">Cuota inicial resultante</div>
				</div>
				<div align="center" style="background-color:#fff; float:left; margin-left:8px;">
					<input <?= ($rowPoliza["PR_SUMAASEGURADA"] == "250000")?"checked":""?> <?= ($existe)?"disabled":""?> id="sumaAseguradaRC" name="sumaAseguradaRC" style="float:left; margin-top:10px;" type="radio" value="250000" />
					<div id="divSumaAsegurada250" style="border-left:1px solid #676767; border-top:1px solid #676767; cursor:default; float:left; font-weight:700; margin-left:-2px; padding-bottom:2px; padding-top:10px; width:150px;">$250.000</div>
					<div style="border-left:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:150px;"><input class="input2 inputNumber" id="alicuota250" name="alicuota250" style="width:88px;" type="text" value="<?= $row["ALICUOTA250"]?>" readonly /></div>
					<div style="border-left:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:150px;"><input class="input2 inputNumber" style="width:88px;" type="text" value="<?= trim($row["MASASALARIAL"])?>" readonly /></div>
					<div style="border-left:1px solid #676767; border-right:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:150px;"><input class="input2 inputNumber" style="width:88px;" type="text" value="<?= $row["CUOTAINICIALRC250"]?>" readonly /></div>
				</div>
				<div align="center" style="background-color:#fff; float:left; margin-left:8px;">
					<input <?= ($rowPoliza["PR_SUMAASEGURADA"] == "500000")?"checked":""?> <?= ($existe)?"disabled":""?> id="sumaAseguradaRC" name="sumaAseguradaRC" style="float:left; margin-top:10px;" type="radio" value="500000" />
					<div id="divSumaAsegurada500" style="border-left:1px solid #676767; border-top:1px solid #676767; cursor:default; float:left; font-weight:700; margin-left:-2px; padding-bottom:2px; padding-top:10px; width:150px;">$500.000</div>
					<div style="border-left:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:150px;"><input class="input2 inputNumber" id="alicuota500" name="alicuota500" style="width:88px;" type="text" value="<?= trim($row["ALICUOTA500"])?>" readonly /></div>
					<div style="border-left:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:150px;"><input class="input2 inputNumber" style="width:88px;" type="text" value="<?= trim($row["MASASALARIAL"])?>" readonly /></div>
					<div style="border-left:1px solid #676767; border-right:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:150px;"><input class="input2 inputNumber" style="width:88px;" type="text" value="<?= $row["CUOTAINICIALRC500"]?>" readonly /></div>
				</div>
				<div align="center" style="background-color:#fff; float:left; margin-left:8px;">
					<input <?= ($rowPoliza["PR_SUMAASEGURADA"] == "1000000")?"checked":""?> <?= ($existe)?"disabled":""?> id="sumaAseguradaRC" name="sumaAseguradaRC" style="float:left; margin-top:10px;" type="radio" value="1000000" />
					<div id="divSumaAsegurada1000" style="border-bottom:1px solid #676767; border-left:1px solid #676767; cursor:default; border-top:1px solid #676767; float:left; font-weight:700; margin-left:-2px; padding-bottom:2px; padding-top:10px; width:150px;">$1.000.000</div>
					<div style="border-bottom:1px solid #676767; border-left:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:150px;"><input class="input2 inputNumber" id="alicuota1000" name="alicuota1000" style="width:88px;" type="text" value="<?= trim($row["ALICUOTA1000"])?>" readonly /></div>
					<div style="border-bottom:1px solid #676767; border-left:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:150px;"><input class="input2 inputNumber" style="width:88px;" type="text" value="<?= trim($row["MASASALARIAL"])?>" readonly /></div>
					<div style="border-bottom:1px solid #676767; border-left:1px solid #676767; border-right:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:150px;"><input class="input2 inputNumber" style="width:88px;" type="text" value="<?= $row["CUOTAINICIALRC1000"]?>" readonly /></div>
				</div>
			</div>
<?
if (!$existe) {
?>
			<div align="center" id="divBotonesSuperiores" style="clear:left; margin-top:64px;">
				<input class="btnCarta" style="margin-top:16px;" type="button" value="" onClick="mostrarCarta(<?= $_REQUEST["id"]?>, '<?= $row["EV_IDENTIDAD"]?>')" />
				<input class="btnPoliza" style="margin-left:16px;" type="button" value="" onClick="document.getElementById('divPoliza').style.display = 'block'; document.getElementById('divBotonesSuperiores').style.display = 'none';" />
				<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
			</div>
<?
}
?>
			<div id="divPoliza" style="clear:left; display:<?= ($existe)?"block":"none"?>;">
				<div style="border:1px solid; float:left; margin-left:8px; margin-top:16px; padding:2px;">
					<div align="center" id="divFormaPago" style="border-bottom:1px solid; cursor:default;">Forma de Pago</div>
					<div style="border-right:0px solid; float:left; margin-top:4px; padding-right:4px;">
						<input <?= ($rowPoliza["PR_MEDIO_PAGO"] == "B")?"checked":""?> <?= ($existe)?"disabled":""?> id="formaPago" name="formaPago" type="radio" value="B" onClick="cambiaFormaPago('B')" />
						<label style="vertical-align:3px;">Boleta</label>
						<br />
						<input <?= ($rowPoliza["PR_MEDIO_PAGO"] == "TC")?"checked":""?> <?= ($existe)?"disabled":""?> id="formaPago" name="formaPago" type="radio" value="TC" onClick="cambiaFormaPago('TC')" />
						<label style="vertical-align:3px;">Tarjeta Crédito</label>
						<?= $comboTarjetaCredito->draw();?>
						<?= $comboTarjetaCreditoFalso->draw();?>
						<br />
						<input <?= ($rowPoliza["PR_MEDIO_PAGO"] == "DA")?"checked":""?> <?= ($existe)?"disabled":""?> id="formaPago" name="formaPago" type="radio" value="DA" onClick="cambiaFormaPago('DA')" />
						<label style="vertical-align:3px;">Débito Automático</label>
						<br />
						<label id="labelCbu" style="margin-left:4px;">C.B.U.</label>
						<input class="input2" <?= ($existe)?"disabled":""?> id="cbu" maxlength="22" name="cbu" readonly style="background-color:#ccc; text-transform:uppercase; width:160px;" type="text" value="<?= $rowPoliza["PR_CBU"]?>" />
					</div>
				</div>
				<div style="border:1px solid; float:left; margin-left:26px; margin-top:16px; padding:2px;">
					<div align="center" id="divIva" style="border-bottom:1px solid; cursor:default;">I.V.A.</div>
					<input <?= ($rowPoliza["PR_IVA"] == "CF")?"checked":""?> <?= ($existe)?"disabled":""?> id="iva" name="iva" type="radio" value="CF" />
					<label style="vertical-align:3px;">Consumidor Final</label>
					<br />
					<input <?= ($rowPoliza["PR_IVA"] == "MT")?"checked":""?> <?= ($existe)?"disabled":""?> id="iva" name="iva" type="radio" value="MT" />
					<label style="vertical-align:3px;">Monotributo</label>
					<br />
					<input <?= ($rowPoliza["PR_IVA"] == "NI")?"checked":""?> <?= ($existe)?"disabled":""?> id="iva" name="iva" type="radio" value="NI" />
					<label style="vertical-align:3px;">No Inscripto</label>
					<br />
					<input <?= ($rowPoliza["PR_IVA"] == "RI")?"checked":""?> <?= ($existe)?"disabled":""?> id="iva" name="iva" type="radio" value="RI" />
					<label style="vertical-align:3px;">Resp. Inscripto</label>
					<br />
					<input <?= ($rowPoliza["PR_IVA"] == "EX")?"checked":""?> <?= ($existe)?"disabled":""?> id="iva" name="iva" type="radio" value="EX" />
					<label style="vertical-align:3px;">Exento</label>
				</div>
				<div style="border:1px solid; float:left; margin-left:26px; margin-top:16px; padding:2px;">
					<div align="center" id="divIibb" style="border-bottom:1px solid; cursor:default;">I.I.B.B.</div>
					<input <?= ($rowPoliza["PR_IIBB"] == "AP")?"checked":""?> <?= ($existe)?"disabled":""?> id="iibb" name="iibb" type="radio" value="AP" />
					<label style="vertical-align:3px;">Agente de Percepción</label>
					<br />
					<input <?= ($rowPoliza["PR_IIBB"] == "CL")?"checked":""?> <?= ($existe)?"disabled":""?> id="iibb" name="iibb" type="radio" value="CL" />
					<label style="vertical-align:3px;">Contribuyente Local</label>
					<br />
					<input <?= ($rowPoliza["PR_IIBB"] == "CM")?"checked":""?> <?= ($existe)?"disabled":""?> id="iibb" name="iibb" type="radio" value="CM" />
					<label style="vertical-align:3px;">Convenio Multilateral</label>
					<br />
					<input <?= ($rowPoliza["PR_IIBB"] == "EX")?"checked":""?> <?= ($existe)?"disabled":""?> id="iibb" name="iibb" type="radio" value="EX" />
					<label style="vertical-align:3px;">Exento</label>
					<br />
					<input <?= ($rowPoliza["PR_IIBB"] == "SI")?"checked":""?> <?= ($existe)?"disabled":""?> id="iibb" name="iibb" type="radio" value="SI" />
					<label style="vertical-align:3px;">SICOM</label>
					<br />
					<input <?= ($rowPoliza["PR_IIBB"] == "ZZ")?"checked":""?> <?= ($existe)?"disabled":""?> id="iibb" name="iibb" type="radio" value="ZZ" />
					<label style="vertical-align:3px;">No corresponde</label>
					<br />
					<input <?= ($rowPoliza["PR_IIBB"] == "RS")?"checked":""?> <?= ($existe)?"disabled":""?> id="iibb" name="iibb" type="radio" value="RS" />
					<label style="vertical-align:3px;">Régimen Simplificado</label>
				</div>
				<div style="clear:left; margin-left:8px;">
					<label>Recepción de Póliza vía e-mail a</label>
					<input class="input2" <?= ($existe)?"disabled":""?> id="email" maxlength="200" name="email" style="text-transform:lowercase; width:428px;" title="Recepción de Póliza vía e-mail" type="text" value="<?= $rowPoliza["PR_MAIL"]?>" />
				</div>
				<div style="margin-left:8px; margin-top:16px;">
					<div style="background-color:#00539b; color:#fff; font-weight:700; padding:2px; width:664px;">DATOS DEL FIRMANTE</div>
					<div style="margin-left:8px; margin-top:4px;">
						<label for="nombre">Nombre y Apellido</label>
						<input class="input2" <?= ($existe)?"disabled":""?> id="nombre" maxlength="255" name="nombre" style="text-transform:lowercase; width:360px;" type="text" value="<?= $rowPoliza["PR_APELLIDO_NOMRE"]?>" />
						<label for="sexo" style="margin-left:16px;">Sexo</label>
						<?= $comboSexo->draw();?>
					</div>
					<div style="margin-left:8px; margin-top:4px;">
						<label for="cargo" style="margin-left:4px;">Cargo/Personería</label>
						<?= $comboCargo->draw();?>
						<label for="dni" style="margin-left:16px;">D.N.I.</label>
						<input class="input2" <?= ($existe)?"disabled":""?> id="dni" maxlength="8" name="dni" style="text-transform:lowercase; width:120px;" type="text" value="<?= $rowPoliza["PR_NRODOCUMENTO"]?>" />
					</div>
				</div>
			<div align="center" id="divBotonesInferiores" style="margin-top:16px;">
<?
if ($existe) {
?>
				<input class="btnCarta" style="margin-top:16px;" type="button" value="" onClick="mostrarCarta(<?= $_REQUEST["id"]?>, '<?= $row["EV_IDENTIDAD"]?>')" />
<?
}
if ($rowPoliza["PR_FECHAIMPRESION"] == "") {
	if ($rowPoliza["PR_SUMAASEGURADA"] != "") {
?>
				<input class="btnPoliza" style="margin-left:16px;" type="button" value="" onClick="window.open('/modules/solicitud_afiliacion/reporte_responsabilidad_civil.php?c=<?= $_REQUEST["id"]?>', 'extranetWindow', 'location=0');" />
<?
	}
}
else {
?>
				<input class="btnReImpresion" style="margin-left:16px;" type="button" value="" onClick="window.open('/modules/solicitud_afiliacion/reporte_responsabilidad_civil.php?c=<?= $_REQUEST["id"]?>', 'extranetWindow', 'location=0');" />
<?
}
?>
				<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
			</div>
<?
if ($rowPoliza["PR_MAIL"] == "") {
?>
				<p style="margin-left:8px;  margin-top:16px;">
					<input class="btnGrabar" type="submit" value="" />
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
				</p>
<?
}
?>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
	cambiaFormaPago('<?= $rowPoliza["PR_MEDIO_PAGO"]?>');
	document.getElementById('cbu').value = '<?= $rowPoliza["PR_CBU"]?>';
</script>