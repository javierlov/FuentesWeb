<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/telefonos/funciones.php");


function formaJuridicaFija($cuit) {
	switch (substr($cuit, 0, 2)) {
		case 20:
		case 23:
		case 24:
		case 27:
			$result = true;
			break;
		default:
			$result = false;
	}
	return $result;
}

function formatPeriodo($periodo) {
	if ($periodo == "" )
		return "";
	else
		return substr($periodo, 0, 4)."/".substr($periodo, -2);
}

function getClausulaPenal($id) {
	if (($id == "C328723") or ($id == "C334890"))
		return "$2.000.- (pesos dos mil)";		// Harcodeado por ticket 41756..
	else
		return "$100.000.- (pesos cien mil)";
}


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["id"]);

$id = substr($_REQUEST["id"], 1);
$modulo = substr($_REQUEST["id"], 0, 1);

if ($modulo == "R") {		// Si es una revisión de precio..
	$params = array(":id" => $id);
	$sql =
		"SELECT TO_CHAR(sa_fechaafiliacion, 'yyyy') anosuscripcion,
						NVL(sa_totempleados, sr_canttrabajadores) cantidadtrabajadores,
						cac1.ac_codigo codigoactividad1,
						cac2.ac_codigo codigoactividad2,
						cac3.ac_codigo codigoactividad3,
						NULL codigoactividad4,
						NVL(sa_condicionanteafip, 'Empleador') condicionanteafip,
					  sr_costofijocotizado costofijocotizado,
						art.utiles.armar_cuit(sr_cuit) cuit,
						NULL cuotainicialrc,
						cac1.ac_descripcion descripcionactividad1,
						cac2.ac_descripcion descripcionactividad2,
						cac3.ac_descripcion descripcionactividad3,
					  DECODE(NVL(sa_totempleados, 0), 0, sr_canttrabajadores, sa_totempleados) * sr_costofinalcotizado cuotamensual, TO_CHAR(sa_fechaafiliacion, 'dd') diasuscripcion,
					  sa_mail_legal email,
						en_codbanco,
					  NVL(sa_establecimientos, sr_establecimientos) establecimientos,
					  sr_fechanotificacioncomercial + CASE WHEN ca_tipo = 'B' THEN 60 ELSE 30 END fechavencimiento,
						NVL(sa_idgrupoeconomico, em_idgrupoeconomico) grupoeconomico,
						sr_idcanal idcanal,
					  sr_identidad identidad,
						sr_idsucursal idsucursal,
						sr_idvendedor idvendedor,
					  TO_CHAR(NVL(sa_masatotal, sr_masasalarial), '$9,999,999,990.00') masasalarial,
					  TO_NUMBER(TO_CHAR(sa_fechaafiliacion, 'mm')) messuscripcion,
					  NVL(sa_nombre_vendedor, ve_nombre) nombrecomercializador,
						sr_nrosolicitud nrosolicitud,
					  NVL(sa_periodo, sr_periodo) periodo,
						sr_porcentajevariablecotizado porcentajevariablecotizado,
					  NVL(sa_nombre, em_nombre) razonsocial,
						NVL(sa_nombre, em_nombre) razonsocialsolicitud,
						rp_acidomuriatico,
						rp_amoniaco,
						rp_bencina,
						rp_descripcion,
						rp_desengrasante,
						rp_desinfectantes,
						rp_detergentes,
						rp_electrico,
						rp_escalerabaranda,
						rp_exterioraltura,
						rp_exterioraltura_cual,
						rp_extintor,
						rp_extintor_cual,
						rp_hipocloritodesodio,
						rp_incendio,
						rp_indumentaria,
						rp_indumentaria_cual,
						rp_insecticida,
						rp_insecticida_cual,
						rp_interioraltura,
						rp_interioraltura_cual,
						rp_otroriesgoquimico,
						rp_proteccionbalcones,
						rp_proteccionpersonal,
						rp_proteccionpersonal_cual,
						rp_raticida,
						rp_raticida_cual,
						rp_sodacaustica,
						sa_calle,
						sa_cargo,
						sa_cargo_titular,
					  sa_clausulasadicionales,
						sa_contacto,
						sa_cpostal,
						sa_departamento,
						sa_direlectronica_cont,
						sa_datosempleadormanual,
						sa_direlectronica_titular,
						sa_documento_titular,
						sa_fechaafiliacion,
					  TO_CHAR(sa_fechavigenciadesde, 'DD/MON/YYYY') sa_fechavigenciadesde,
					  TO_CHAR(sa_fechavigenciahasta, 'DD/MON/YYYY') sa_fechavigenciahasta,
						sa_feinicactiv,
						sa_formaj,
						sa_id,
						sa_localidad,
					  sa_lugarsuscripcion,
						sa_nivel,
						sa_numero,
						sa_observaciones,
						sa_piso,
						sa_presentorgrl,
						sa_provincia,
						sa_rgrlimpreso,
					  sa_sexo_cont,
						sa_sexo_titular,
						sa_telefonos_cont,
						sa_telefono_titular,
						sa_titular,
						sa_solicitud_pcp solopcp,
						sr_statussrt statussrt,
						su_codsucursal,
					  NULL sumaaseguradarc,
						NVL(sa_telefonos, sr_telefono) telefono,
						NULL valorrc,
						ve_vendedor,
						sa_nombre_vendedor vendedor
			 FROM asr_solicitudreafiliacion, asa_solicitudafiliacion, aem_empresa, cac_actividad cac1, cac_actividad cac2, cac_actividad cac3, xev_entidadvendedor, xen_entidad, xve_vendedor,
						asu_sucursal, aca_canal, afi.arp_riesgo_pcp
			WHERE sr_id = sa_idrevisionprecio(+)
				AND sr_cuit = em_cuit
				AND NVL(sa_idactividad, sr_idactividad1) = cac1.ac_id
				AND sr_idactividad2 = cac2.ac_id(+)
				AND sr_idactividad3 = cac3.ac_id(+)
				AND sa_identidadvendedor = ev_id(+)
				AND ev_identidad = en_id(+)
				AND ev_idvendedor = ve_id(+)
				AND sa_idsucursal = su_id(+)
				AND sr_idcanal = ca_id(+)
				AND sa_id = rp_idsolicitud(+)
				AND sr_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$alicuotaCuotalInicialResultante = $row["CUOTAMENSUAL"];
	$alicuotaFijo = $row["COSTOFIJOCOTIZADO"];
	$alicuotaPorcentaje = $row["PORCENTAJEVARIABLECOTIZADO"];
}
else {
	$params = array(":id" => $id);
	$sql =
		"SELECT TO_CHAR(sa_fechaafiliacion, 'yyyy') anosuscripcion,
						NVL(sa_totempleados, NVL(co_canttrabajador, sc_canttrabajador)) cantidadtrabajadores,
						cac1.ac_codigo codigoactividad1,
						cac2.ac_codigo codigoactividad2,
						cac3.ac_codigo codigoactividad3,
						NVL(sa_condicionanteafip, 'Empleador') condicionanteafip,
						art.utiles.armar_cuit(sc_cuit) cuit,
						TO_CHAR(sc_masasalarial * CASE WHEN sc_valor_rc < 0 THEN 0 ELSE sc_valor_rc END / 100, '$9,999,999,990.00') cuotainicialrc,
						cac1.ac_descripcion descripcionactividad1,
						cac2.ac_descripcion descripcionactividad2,
						cac3.ac_descripcion descripcionactividad3,
						TO_CHAR(sa_fechaafiliacion, 'dd') diasuscripcion,
						sa_mail_legal email,
						en_codbanco,
						NVL(sa_establecimientos, sc_establecimientos) establecimientos,
						sc_fechavigencia + CASE WHEN ca_tipo = 'B' THEN 60 ELSE 30 END fechavencimiento,
						NVL(sa_idgrupoeconomico, sc_idgrupoeconomico) grupoeconomico,
						sc_canal idcanal,
						sc_identidad identidad,
						sc_idsucursal idsucursal,
						sc_idvendedor idvendedor,
					  TO_CHAR(NVL(sa_masatotal, NVL(co_masasalarial, sc_masasalarial)), '$9,999,999,990.00') masasalarial,
					  TO_NUMBER(TO_CHAR(sa_fechaafiliacion, 'mm')) messuscripcion,
					  NVL(sa_nombre_vendedor, ve_nombre) nombrecomercializador,
						sc_nrosolicitud nrosolicitud,
						NVL(sa_periodo, sc_periodo) periodo,
					  sa_nombre razonsocial,
						NVL(co_razonsocial, sc_razonsocial) razonsocialsolicitud,
						rp_acidomuriatico,
						rp_amoniaco,
						rp_bencina,
						rp_descripcion,
						rp_desengrasante,
						rp_desinfectantes,
						rp_detergentes,
						rp_electrico,
						rp_escalerabaranda,
						rp_exterioraltura,
						rp_exterioraltura_cual,
						rp_extintor,
						rp_extintor_cual,
						rp_hipocloritodesodio,
						rp_incendio,
						rp_indumentaria,
						rp_indumentaria_cual,
						rp_insecticida,
						rp_insecticida_cual,
						rp_interioraltura,
						rp_interioraltura_cual,
						rp_otroriesgoquimico,
						rp_proteccionbalcones,
						rp_proteccionpersonal,
						rp_proteccionpersonal_cual,
						rp_raticida,
						rp_raticida_cual,
						rp_sodacaustica,
						sa_calle,
						sa_cargo,
						sa_cargo_titular,
					  sa_clausulasadicionales,
						sa_contacto,
						sa_cpostal,
						sa_datosempleadormanual,
						sa_departamento,
						sa_direlectronica_cont,
						sa_direlectronica_titular,
						sa_documento_titular,
						sa_fechaafiliacion,
						TO_CHAR(sa_fechavigenciadesde, 'DD/MON/YYYY') sa_fechavigenciadesde,
					  TO_CHAR(sa_fechavigenciahasta, 'DD/MON/YYYY') sa_fechavigenciahasta,
						sa_feinicactiv,
						sa_formaj,
						sa_id,
						sa_localidad,
					  sa_lugarsuscripcion,
						sa_nivel,
						sa_numero,
						sa_observaciones,
						sa_piso,
						sa_presentorgrl,
						sa_provincia,
						sa_rgrlimpreso,
					  sa_sexo_cont,
						sa_sexo_titular,
						sa_telefonos_cont,
						sa_telefono_titular,
						sa_titular,
						sc_statussrt statussrt,
						NVL(sa_solicitud_pcp, sc_cotizacion_pcp) solopcp,
						su_codsucursal,
					  sc_sumaasegurada_rc sumaaseguradarc,
						NVL(sa_telefonos, sc_telefono) telefono,
					  CASE WHEN sc_valor_rc < 0 THEN 0 ELSE sc_valor_rc END valorrc,
						ve_vendedor,
					  sa_nombre_vendedor vendedor
			 FROM asc_solicitudcotizacion, asa_solicitudafiliacion, aco_cotizacion, cac_actividad cac1, cac_actividad cac2, cac_actividad cac3, xev_entidadvendedor, xen_entidad, xve_vendedor,
						asu_sucursal, aca_canal, afi.arp_riesgo_pcp
			WHERE sc_id = sa_idsolicitudcotizacion(+)
				AND sc_idcotizacion = co_id(+)
				AND NVL(sa_idactividad, NVL(co_idactividad, sc_idactividad)) = cac1.ac_id
				AND sc_idactividad2 = cac2.ac_id(+)
				AND sc_idactividad3 = cac3.ac_id(+)
				AND sa_identidadvendedor = ev_id(+)
				AND ev_identidad = en_id(+)
				AND ev_idvendedor = ve_id(+)
				AND sa_idsucursal = su_id(+)
				AND sc_canal = ca_id(+)
				AND sa_id = rp_idsolicitud(+)
				AND sc_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	// INICIO - Fix para los casos de PCP, que en algunos casos viene el CIIU vacio..
	if ($row["CUIT"] == "") {
		$params2 = array(":id" => $id);
		$sql2 =
			"UPDATE asc_solicitudcotizacion
					SET sc_idactividad = (SELECT ac_id
																	FROM cac_actividad
																 WHERE ac_codigo = 950000)
				WHERE sc_id = :id";
		DBExecSql($conn, $sql2, $params2);

		$stmt = DBExecSql($conn, $sql, $params);
		$row = DBGetQuery($stmt);
	}
	// FIN - Fix para los casos de PCP, que en algunos casos viene el CIIU vacio..

	$curs = null;
	$params = array(":nrosolicitud" => $row["NROSOLICITUD"]);
	$sql = "BEGIN art.cotizacion.get_valor_carta(:nrosolicitud, :data); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params);
	$rowValorFinal = DBGetSP($curs);

	$alicuotaCuotalInicialResultante = $rowValorFinal["COSTOMENSUAL"];
	$alicuotaFijo = $rowValorFinal["SUMAFIJA"];
	$alicuotaPorcentaje = $rowValorFinal["PORCVARIABLE"];
}
$alta = ($row["SA_ID"] == "");

// Teléfonos Domicilio..
$dataTel = inicializarTelefonos(OCI_COMMIT_ON_SUCCESS, "ts_solicitud", $row["SA_ID"], "ts", "ats_telefonosolicitud", $_SESSION["usuario"]);
quitarTelefonosTemporales($dataTel);
copiarTelefonosATemp($dataTel, $_SESSION["usuario"]);

// Teléfonos Responsable ART..
$dataTel2 = inicializarTelefonos(OCI_COMMIT_ON_SUCCESS, "ts_solicitud", $row["SA_ID"], "ts", "ats_telefonosolicitud", $_SESSION["usuario"], "X");
quitarTelefonosTemporales($dataTel2);
copiarTelefonosATemp($dataTel2, $_SESSION["usuario"]);

// Traigo la razón social según el sitio de la SRT si existiere..
if ($row["RAZONSOCIAL"] == "") {
	$params = array(":cuit" => $row["CUIT"]);
	$sql =
		"SELECT em_nombre
			 FROM srt.sem_empresas
			WHERE em_fechabaja IS NULL
				AND em_cuit = :cuit
	 ORDER BY em_fechaalta DESC";
	$row["RAZONSOCIAL"] = valorSql($sql, $row["RAZONSOCIALSOLICITUD"], $params);
}

$params = array(":id" => $_SESSION["entidad"]);
$sql =
	"SELECT en_codbanco
		 FROM xen_entidad
		WHERE en_id = :id";
$entidad = valorSql($sql, "", $params);

$params = array(":id" => nullIsEmpty($_SESSION["sucursal"]));
$sql =
	"SELECT su_descripcion
		 FROM asu_sucursal
		WHERE su_id = :id";
$sucursal = valorSql($sql, "", $params);

$params = array(":id" => nullIsEmpty($_SESSION["vendedor"]));
$sql =
	"SELECT ve_vendedor
		 FROM xve_vendedor
		WHERE ve_id = :id";
$vendedor = valorSql($sql, "", $params);

require("afiliacion_combos.php");
?>
<style>
	input[type=checkbox] {vertical-align:-3px;}
	input[type=radio] {vertical-align:-3px;}
	.divSubSeccion {background-color:#000; color:#fff; font-size:8pt; font-weight:700; height:18px; margin-top:20px; padding-top:3px;}
	#divGridEspera {background-color:#0f539c; cursor:wait; display:none; filter:alpha(opacity = 20); height:2400px; left:0; opacity:.1; position:absolute; top:0; width:740px;}
	#divGridEsperaTexto {background-color:#fff; border:1px solid #808080; color:#000; cursor:wait; display:none; font-family:Trebuchet MS; left:228px; padding:5px; position:absolute; top:144px;}
</style>
<link href="/modules/solicitud_afiliacion/css/afiliacion.css" rel="stylesheet" type="text/css" />
<script src="/modules/solicitud_afiliacion/js/afiliacion.js?rnd=<?= date("Ymdhni")?>" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/solicitud_afiliacion/procesar_afiliacion.php" id="formSolicitudAfiliacion" method="post" name="formSolicitudAfiliacion" target="iframeProcesando" onSubmit="return validarSolicitud()" >
	<input id="formaJuridicaTmp" name="formaJuridicaTmp" type="hidden" value="<?= (formaJuridicaFija($row["CUIT"]))?"009":(($alta)?-1:$row["SA_FORMAJ"])?>" />
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<input id="idGrupoEconomico" name="idGrupoEconomico" type="hidden" value="<?= $row["GRUPOECONOMICO"]?>" />
	<input id="idSolicitudAfiliacion" name="idSolicitudAfiliacion" type="hidden" value="<?= ($alta)?0:$row["SA_ID"]?>" />
	<input id="nivel" name="nivel" type="hidden" value="<?= ($alta)?2:$row["SA_NIVEL"]?>" />
	<input id="soloPCP" name="soloPCP" type="hidden" value="<?= $row["SOLOPCP"]?>" />
	<div id="divPrincipalSolicitudAfiliacion" style="font-family:Trebuchet MS; width:736px;">
		<div align="center" class="TituloSeccion">Solicitud de Afiliación</div>
		<div align="center" class="divSubSeccion">1. 1. DATOS DE LA ASEGURADORA</div>
		<div align="left" style="color:#211e1e; font-size:7.5pt; margin-left:8px;">
			<div style="float:right; margin-top:16px;"><img border="0" src="/modules/solicitud_afiliacion/images/telefonos.jpg"></div>
			<div>Provincia A.R.T. S.A.</div>
			<div>Código de A.R.T.: 0005-1 - C.U.I.T. Nº: 30-68825409-0 </div>
			<div>Carlos Pellegrini 91 - (C1009ABA) - Ciudad Autónoma de Bs As</div>
			<div>Tel.: (011) 4819-2800 - Fax: (011) 4819-2888 </div>
			<div><a class="linkSubrayado" target="_blank" href="/">www.provinciart.com.ar</a> - <a class="linkSubrayado" href="mailto:info@provart.com.ar">info@provart.com.ar</a></div>
		</div>
		<div align="center" class="divSubSeccion">1. 1. 1. FECHA DE SUSCRIPCIÓN</div>
		<div align="left" style="color:#211e1e; font-size:8pt; margin-left:8px; margin-top:4px;">
			<label for="fechaSuscripcion">Fecha de Suscripción</label>
			<input autofocus class="input2" id="fechaSuscripcion" maxlength="10" name="fechaSuscripcion" style="width:68px;" type="text" value="<?= $row["SA_FECHAAFILIACION"]?>" onChange="copiarFechaSuscripcion('<?= $row["STATUSSRT"]?>', this.value)" />
			<input class="botonFecha" id="btnFechaSuscripcion" name="btnFechaSuscripcion" type="button" value="" />
			<i>(dd/mm/aaaa)</i>
		</div>
		<div align="center" class="divSubSeccion">1. 2. DATOS DEL EMPLEADOR</div>
		<div align="left" style="color:#211e1e; font-size:8pt; margin-left:8px; margin-top:4px;">
			<div style="margin-bottom:6px;">
				<label for="razonSocial">Nombre o razón social</label>
				<input class="input2" id="razonSocial" maxlength="60" name="razonSocial" readonly style="text-transform:uppercase; width:600px;" type="text" value="<?= $row["RAZONSOCIAL"]?>" onBlur="this.value = this.value.toUpperCase();" />
			</div>
			<div style="margin-bottom:6px;">
				<label for="cuit">C.U.I.T. Nº</label>
				<input class="input2" id="cuit" name="cuit" style="width:86px;" type="text" value="<?= $row["CUIT"]?>" readonly>
				<label for="formaJuridica" id="labelFormaJuridica" style="margin-left:4px;">Forma Jurídica</label>
				<?= $comboFormaJuridica->draw();?>
				<label for="condicionAnteAfip" id="labelCondicionAnteAfip" style="font-size:8pt; margin-left:4px;">Condición ante la A.F.I.P.</label>
				<input class="input2" id="condicionAnteAfip" maxlength="64" name="condicionAnteAfip" style="text-transform:uppercase; width:120px;" type="text" value="<?= $row["CONDICIONANTEAFIP"]?>" onBlur="this.value = this.value.toUpperCase();">
			</div>
			<div id="divActividadPrincipal" style="margin-bottom:16px;">
				<label for="actividadPrincipal" id="labelActividadPrincipal" style="font-size:8pt;">Actividad Principal</label>
				<input class="input2" id="actividadPrincipal" name="actividadPrincipal" style="width:348px;" type="text" value="<?= $row["DESCRIPCIONACTIVIDAD1"]?>" readonly>
				<label for="fechaInicioActividad" style="font-size:8pt; margin-left:4px;">Fecha de inicio de actividad</label>
				<input class="input2" id="fechaInicioActividad" maxlength="10" name="fechaInicioActividad" style="width:76px;" type="text" value="<?= $row["SA_FEINICACTIV"]?>">
				<input class="botonFecha" id="btnFechaInicioActividad" type="button" value="">
			</div>
			<div id="divFormulario883" style="margin-bottom:2px;">
				<span style="color:#211e1e; font-size:8pt; font-weight:700">Código de actividad según CLasificador de Actividades Económicas (CLAE) - Formulario Nº 883 (Resolución A.F.I.P. Nº 3537)</span>
			</div>
			<div style="margin-bottom:16px;">
				<label for="ciiu" style="font-size:8pt;">C.I.I.U. 1</label>
				<input class="input2" id="ciiu" maxlength="6" name="ciiu" style="width:60px;" type="text" value="<?= $row["CODIGOACTIVIDAD1"]?>" readonly>
				<label for="ciiuDescripcion" style="font-size:8pt; margin-left:4px;">Descripción</label>
				<input class="input2" id="ciiuDescripcion" name="ciiuDescripcion" style="width:524px;" type="text" value="<?= $row["DESCRIPCIONACTIVIDAD1"]?>" readonly>
			</div>
<?
if ($row["CODIGOACTIVIDAD2"] != "") {
?>
			<div style="margin-bottom:16px; margin-top:-12px;">
				<label for="ciiu2" style="font-size:8pt;">C.I.I.U. 2</label>
				<input class="input2" id="ciiu2" maxlength="6" name="ciiu2" style="width:60px;" type="text" value="<?= $row["CODIGOACTIVIDAD2"]?>" readonly>
				<label for="ciiuDescripcion2" style="font-size:8pt; margin-left:4px;">Descripción</label>
				<input class="input2" id="ciiuDescripcion2" name="ciiuDescripcion2" style="width:524px;" type="text" value="<?= $row["DESCRIPCIONACTIVIDAD2"]?>" readonly>
			</div>
<?
}
if ($row["CODIGOACTIVIDAD3"] != "") {
?>
			<div style="margin-bottom:16px; margin-top:-12px;">
				<label for="ciiu3" style="font-size:8pt;">C.I.I.U. 3</label>
				<input class="input2" id="ciiu3" maxlength="6" name="ciiu3" style="width:60px;" type="text" value="<?= $row["CODIGOACTIVIDAD3"]?>" readonly>
				<label for="ciiuDescripcion3" style="font-size:8pt; margin-left:4px;">Descripción</label>
				<input class="input2" id="ciiuDescripcion3" name="ciiuDescripcion3" style="width:524px;" type="text" value="<?= $row["DESCRIPCIONACTIVIDAD3"]?>" readonly>
			</div>
<?
}
?>
			<div style="margin-bottom:2px;">
				<span style="color:#211E1E; font-size:8pt; font-weight:700">Domicilio constituido</span>
			</div>
			<div style="margin-bottom:6px;">
				<label for="calle" style="font-size:8pt;">Calle</label>
				<input class="input2" id="calle" maxlength="60" name="calle" style="text-transform:uppercase; width:368px;" type="text" value="<?= $row["SA_CALLE"]?>" onBlur="this.value = this.value.toUpperCase();">
				<label for="numero" style="font-size:8pt; margin-left:8px;">Nº</label>
				<input class="input2" id="numero" maxlength="20" name="numero" style="text-transform:uppercase; width:70px;" type="text" value="<?= $row["SA_NUMERO"]?>" onBlur="this.value = this.value.toUpperCase();">
				<label for="piso" style="font-size:8pt; margin-left:8px;">Piso</label>
				<input class="input2" id="piso" maxlength="20" name="piso" style="text-transform:uppercase; width:56px;" type="text" value="<?= $row["SA_PISO"]?>" onBlur="this.value = this.value.toUpperCase();">
				<label for="oficina" style="font-size:8pt; margin-left:8px;">Oficina</label>
				<input class="input2" id="oficina" maxlength="20" name="oficina" style="text-transform:uppercase; width:54px;" type="text" value="<?= $row["SA_DEPARTAMENTO"]?>" onBlur="this.value = this.value.toUpperCase();">
			</div>
			<div style="margin-bottom:6px;">
				<label for="codigoPostal" style="font-size:8pt;">Código Postal</label>
				<input class="input2" id="codigoPostal" maxlength="5" name="codigoPostal" style="text-transform:uppercase; width:56px;" type="text" value="<?= $row["SA_CPOSTAL"]?>" onBlur="this.value = this.value.toUpperCase(); cargarComboLocalidad();">
				<label for="provincia" style="font-size:8pt; margin-left:4px;">Provincia</label>
				<?= $comboProvincia->draw();?>
				<label for="localidad" style="font-size:8pt; margin-left:4px;">Localidad</label>
				<?= $comboLocalidadCombo->draw();?>
				<input class="input2" id="localidad" maxlength="60" name="localidad" style="text-transform:uppercase; width:314px;" type="text" value="<?= $row["SA_LOCALIDAD"]?>" onBlur="this.value = this.value.toUpperCase();">
			</div>
			<div id="divEmail" style="margin-bottom:6px;">
				<label for="email" style="font-size:8pt;">e-Mail</label>
				<input class="input2" id="email" maxlength="200" name="email" style="text-transform:uppercase; width:680px;" type="text" value="<?= $row["EMAIL"]?>" onBlur="this.value = this.value.toUpperCase();" />
			</div>
			<div style="margin-left:0px; margin-top:8px;">
				<iframe frameborder="no" height="0" id="iframeTelefonos" name="iframeTelefonos" scrolling="no" src="/functions/telefonos/telefonos.php?s=isAgenteComercial&idModulo=<?= $_REQUEST["id"]?>&idTablaPadre=<?= $row["SA_ID"]?>&tablaTel=ats_telefonosolicitud&campoClave=ts_solicitud&prefijo=ts" width="716" onLoad="ajustarTamanoIframe(this, 192)"></iframe>
			</div>
			<div id="divCantidadEstablecimientos" style="margin-bottom:6px;">
				<label for="establecimientos" style="font-size: 8pt">Cantidad de establecimientos</label>
				<input class="input2" id="establecimientos" maxlength="3" name="establecimientos" style="width:48px;" type="text" value="<?= $row["ESTABLECIMIENTOS"]?>" />
				<span style="font-size: 9pt">(Completar los datos de los establecimientos en el Formulario de Ubicación de Riesgo)</span>
			</div>
			<div id="divNivel" style="margin-bottom:16px; margin-top:8px;">
				<label for="nivel" style="font-size:8pt; margin-right:8px;">Nivel de cumplimiento en Higiene y Seguridad</label>
				<span id="nivel1" name="nivel1" style="background-color:#fff; border:1px solid #808080; color:#808080; cursor:pointer; font-family:Trebuchet MS; font-size:8pt; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; text-align:center;" onClick="selectNivel(1, true)">I</span>
				<span id="nivel2" name="nivel2" style="background-color:#fff; border:1px solid #808080; color:#808080; cursor:pointer; font-family:Trebuchet MS; font-size:8pt; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; text-align:center;" onClick="selectNivel(2, true)">II</span>
				<span id="nivel3" name="nivel3" style="background-color:#fff; border:1px solid #808080; color:#808080; cursor:pointer; font-family:Trebuchet MS; font-size:8pt; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; text-align:center;" onClick="selectNivel(3, true)">III</span>
				<span id="nivel4" name="nivel4" style="background-color:#fff; border:1px solid #808080; color:#808080; cursor:pointer; font-family:Trebuchet MS; font-size:8pt; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; text-align:center;" onClick="selectNivel(4, true)">IV</span>
			</div>
			<div id="divResponsableArtTitulo" style="margin-bottom:2px;">
				<span style="color:#211E1E; font-size:8pt; font-weight:700">Responsable de ART</span>
			</div>
			<div id="divNombreApellidoResponsable" style="margin-bottom:6px;">
				<label for="nombreApellidoResponsable" style="font-size:8pt;">Nombre y apellido</label>
				<input class="input2" id="nombreApellidoResponsable" maxlength="100" name="nombreApellidoResponsable" style="text-transform:uppercase; width:624px;" type="text" value="<?= $row["SA_CONTACTO"]?>" onBlur="this.value = this.value.toUpperCase();">
			</div>
			<div id="divCargoSexoResponsable" style="margin-bottom:6px; margin-top:12px;">
				<label for="cargoResponsable" style="font-size:8pt;">Cargo</label>
				<?= $comboCargoResponsable->draw();?>
				<label for="sexoResponsable" style="font-size:8pt; margin-left:16px;">Sexo</label>
				<?= $comboSexoResponsable->draw();?>
			</div>
			<div id="divEmailResponsable" style="margin-bottom:6px;">
				<label for="emailResponsable" style="font-size:8pt;">e-Mail</label>
				<input class="input2" id="emailResponsable" maxlength="120" name="emailResponsable" style="text-transform:uppercase; width:680px;" type="text" value="<?= $row["SA_DIRELECTRONICA_CONT"]?>" onBlur="this.value = this.value.toUpperCase();">
			</div>
			<div id="divTelefonosResponsable" style="margin-left:0px; margin-top:8px;">
				<iframe frameborder="no" height="0" id="iframeTelefonos2" name="iframeTelefonos2" scrolling="no" src="/functions/telefonos/telefonos.php?s=isAgenteComercial&idModulo=<?= $_REQUEST["id"]?>&idTablaPadre=<?= $row["SA_ID"]?>&tablaTel=ats_telefonosolicitud&campoClave=ts_solicitud&prefijo=ts&tipo=X" width="716" onLoad="ajustarTamanoIframe(this, 192)"></iframe>
			</div>
		</div>
		<div align="center" class="divSubSeccion">2. VIGENCIA</div>
		<div align="left" style="color:#211e1e; font-size:8pt; margin-left:8px;">
			<div style="margin-top:8px;">
				<label for="fechaVigenciaDesde">Desde el</label>
				<input class="input2" id="fechaVigenciaDesde" maxlength="10" name="fechaVigenciaDesde" readonly style="width:76px;" type="text" value="<?= $row["SA_FECHAVIGENCIADESDE"]?>" />
				<label for="fechaVigenciaDesde" style="margin-left:8px;">Hasta el</label>
				<input class="input2" id="fechaVigenciaHasta" maxlength="10" name="fechaVigenciaHasta" readonly style="width:76px;" type="text" value="<?= $row["SA_FECHAVIGENCIAHASTA"]?>" />
			</div>
			<div style="border-left:1px solid; left:336px; padding-left:4px; padding-right:4px; position:relative; top:-24px; width:384px;">
				El campo de vigencia no puede quedar en blanco. Debe ser completado en forma obligatoria. La fecha de inicio de vigencia no debe ser anterior a la fecha de suscripción de la presente solicitud de afiliación.
				<span id="spanTextoVigencia">
					<br /><br />
					La vigencia de los contratos asegurados de oficio será a partir de las cero (0) horas del día inmediato posterior a la fecha en que la SRT ponga a disposición los datos de dicha asignación.
				</span>
			</div>
		</div>
		<div id="divEntregaRgrl" style="font-size:8pt; font-weight:700; margin-left:8px;">
			<span>&gt; Entrega relevamiento General de riesgos Laborales (RGRL). Resolución 463/09</span>
			<label for="entregaRgrl" style="margin-left:16px;">SI</label>
			<input disabled id="entregaRgrl" name="entregaRgrl" style="margin:0px; vertical-align:middle;" type="radio" value="T" <?= ($row["SA_PRESENTORGRL"] == "T")?"checked":"checked"?>>
			<label for="entregaRgrl" style="margin-left:16px;">NO</label>
			<input disabled id="entregaRgrl" name="entregaRgrl" style="margin:0px; vertical-align:middle;" type="radio" value="F" <?= ($row["SA_PRESENTORGRL"] == "F")?"checked":""?>>
		</div>
		<div id="divSuscribeClausulas" style="font-size:8pt; font-weight:700; margin-left:8px;">
			<span>&gt; Suscribe claúsulas adicionales (claúsulas novena y décima)</span>
			<label for="suscribeClausulas" style="margin-left:113px;">SI</label>
			<input disabled id="suscribeClausulas" name="suscribeClausulas" style="margin:0px; vertical-align:middle;" type="radio" value="S" <?= ($row["SA_CLAUSULASADICIONALES"] == "S")?"checked":"checked"?>>
			<label for="suscribeClausulas" style="margin-left:16px;">NO</label>
			<input disabled id="suscribeClausulas" name="suscribeClausulas" style="margin:0px; vertical-align:middle;" type="radio" value="N" <?= ($row["SA_CLAUSULASADICIONALES"] == "N")?"checked":""?>>
		</div>
		<div align="center" id="divAlicuotaTitulo" class="divSubSeccion">3. ALÍCUOTA</div>
		<div align="center" id="divAlicuotaDefault" style="display:<?= ($row["SOLOPCP"] == "S")?"none":"block"?>">
			<table border="0" cellpadding="0" width="736" cellspacing="1">
				<tr>
					<td valign="top">
						<table border="0" cellpadding="0" width="100%" cellspacing="1">
							<tr>
								<td colspan="3" align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" bgcolor="#000000">
									<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700" color="#FFFFFF">TRABAJADORES</font>
								</td>
							</tr>
							<tr>
								<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="30%">
									<p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">Cantidad</font></p>
									<p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">(a)</font>
								</td>
								<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="29%">
									<p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">Masa Salarial</font></p>
									<p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">(b)</font>
								</td>
								<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="20%">
									<p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">Mes/Año</font>
								</td>
							</tr>
							<tr>
								<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="30%">
									<input class="inputNumber2" id="trabajadoresCantidad" name="trabajadoresCantidad" style="width:72px;" type="text" value="<?= $row["CANTIDADTRABAJADORES"]?>" readonly>
								</td>
								<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="29%">
									<input class="inputNumber2" id="trabajadoresMasaSalarial" name="trabajadoresMasaSalarial" style="width:96px;" type="text" value="<?= trim($row["MASASALARIAL"])?>" readonly>
								</td>
								<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="20%">
									<input class="input2" id="trabajadoresPeriodo" name="trabajadoresPeriodo" style="text-align:center; width:56px;" type="text" value="<?= formatPeriodo($row["PERIODO"])?>" readonly>
								</td>
							</tr>
						</table>
					</td>
					<td valign="top">
						<table border="0" cellpadding="0" width="100%" cellspacing="1">
							<tr>
								<td colspan="4" align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" bgcolor="#000000">
									<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700" color="#FFFFFF">ALÍCUOTAS</font>
								</td>
							</tr>
							<tr>
								<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="29%">
									<p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">% sobre Masa Salarial (c)</font></p>
								</td>
								<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="6%">
									<p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">Fijo</font></p>
									<p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">(d)</font>
								</td>
								<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="9%">
									<p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">F.F.E.P.</font>
								</td>
								<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="43%">
									<p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">Cuota inicial resultante</font></p>
									<p style="margin-top: 0; margin-bottom: 0"><span style="font-weight: 700"><font face="Trebuchet MS" style="font-size: 8pt">(bxc) + (axd) + (axf.f.e.p.)</font></span>
								</td>
							</tr>
							<tr>
								<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="29%">
									<input class="inputNumber2" id="alicuotaPorcentaje" name="alicuotaPorcentaje" style="width:96px;" type="text" value="<?= $alicuotaPorcentaje?>" readonly>
								</td>
								<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="6%">
									<input class="inputNumber2" id="alicuotaFijo" name="alicuotaFijo" style="width:40px;" type="text" value="<?= trim($alicuotaFijo)?>" readonly>
								</td>
								<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="9%">
									<input class="inputNumber2" id="alicuotaFfep" name="alicuotaFfep" style="width:64px;" type="text" value="$ 0,60" readonly>
								</td>
								<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="43%">
									<input class="inputNumber2" id="alicuotaCuotalInicialResultante" name="alicuotaCuotalInicialResultante" style="width:160px;" type="text" value="<?= trim($alicuotaCuotalInicialResultante)?>" readonly>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<div style="font-size:8pt; margin-left:4px; margin-right:4px;">
				Fondo fiduciario de enfermedades profesionales Dto. 1278/2000: $0,60.- por cada trabajador. Las alícuotas están sujetas a la firma 
				del contrato de afiliación por parte de Provincia A.R.T. S.A. y podrán ser rectificadas a efectos de ajustarse a lo dispuesto por la 
				Resolución Nº 24.445 de la S.S.N. y de la S.R.T. Nota: La cotización de referencia tiene validez durante los próximos 30 días. La 
				presente Solicitud de Afiliación debe ser ingresada en Provincia ART antes del
				<input class="input2" id="fechaVencimiento" name="fechaVencimiento" readonly style="width:76px;" type="text" value="<?= $row["FECHAVENCIMIENTO"]?>" />
			</div>
		</div>
		<div align="center" id="divAlicuotaPCP">
			<div align="center" class="divPCPTabla">
				<div id="divPCPLeyenda">IMPORTANTE: Recuerde que el firmante del contrato de afiliación deberá coincidir con el Dador de Empleo registrado en AFIP.</div>
				<table id="tablePCPTabla">
					<tr>
						<th>HORAS TRABAJADAS SEMANALMENTE</th>
						<th>CANTIDAD DE TRABAJADORES</th>
						<th>ALÍCUOTA</th>
					</tr>
<?
$params = array(":idsolicitud" => $row["SA_ID"]);
$sql =
	"SELECT 1
		 FROM afi.aap_alicuotas_pcp
		WHERE ap_idsolicitud = :idsolicitud";
if (existeSql($sql, $params)) {		// Tomo los datos de la PCP de la solicitud de afiliacion..
	$params = array(":idsolicitud" => $row["SA_ID"]);
	$sql =
		"SELECT NVL((SELECT ap_alicuota
									 FROM afi.aap_alicuotas_pcp
									WHERE ap_idparametro_pcp = pp_id
										AND ap_fechabaja IS NULL
										AND ap_idsolicitud = :idsolicitud), 0) alicuota,
						TO_CHAR(NVL((SELECT ap_alicuota
													 FROM afi.aap_alicuotas_pcp
													WHERE ap_idparametro_pcp = pp_id
														AND ap_fechabaja IS NULL
														AND ap_idsolicitud = :idsolicitud), 0), '$99,999,990.00') alicuotaformateada,
						NVL((SELECT ap_canttrabajador
									 FROM afi.aap_alicuotas_pcp
									WHERE ap_idparametro_pcp = pp_id
										AND ap_fechabaja IS NULL
										AND ap_idsolicitud = :idsolicitud), 0) canttrabajador, pp_descripcion
			 FROM afi.app_parametro_pcp
			WHERE art.actualdate BETWEEN pp_fechadesde AND pp_fechahasta
				AND pp_fechabaja IS NULL
	 ORDER BY pp_renglon";
}
else {		// Tomo los datos de la PCP de la solicitud de cotización..
	$params = array(":idsolicitudcotizacion" => $id);
	$sql =
		"SELECT NVL((SELECT cp_alicuota
									 FROM afi.acp_cotizacion_pcp
									WHERE cp_idparametro_pcp = pp_id
										AND cp_idsolicitudcotizacion = :idsolicitudcotizacion), 0) alicuota,
						TO_CHAR(NVL((SELECT cp_alicuota
													 FROM afi.acp_cotizacion_pcp
													WHERE cp_idparametro_pcp = pp_id
														AND cp_idsolicitudcotizacion = :idsolicitudcotizacion), 0), '$99,999,990.00') alicuotaformateada,
						NVL((SELECT cp_canttrabajador
									 FROM afi.acp_cotizacion_pcp
									WHERE cp_idparametro_pcp = pp_id
										AND cp_idsolicitudcotizacion = :idsolicitudcotizacion), 0) canttrabajador, pp_descripcion
			 FROM afi.app_parametro_pcp
			WHERE art.actualdate BETWEEN pp_fechadesde AND pp_fechahasta
				AND pp_fechabaja IS NULL
	 ORDER BY pp_renglon";
}
$stmt = DBExecSql($conn, $sql, $params);
$totalAlicuota = 0;
$totalTrabajadores = 0;
while ($rowPCP = DBGetQuery($stmt)) {
	$totalAlicuota+= ($rowPCP["ALICUOTA"]);
	$totalTrabajadores+= $rowPCP["CANTTRABAJADOR"];
?>
	<tr>
		<td><?= $rowPCP["PP_DESCRIPCION"]?></td>
		<td class="pcpCampoBlanco"><?= $rowPCP["CANTTRABAJADOR"]?></td>
		<td class="pcpCampoBlanco"><?= ($rowPCP["CANTTRABAJADOR"] == 0)?"-":$rowPCP["ALICUOTAFORMATEADA"]?></td>
	</tr>
<?
}
?>
					<tr>
						<td>Cuota Inicial Resultante (*)</td>
						<td class="pcpCampoGris"><?= $totalTrabajadores?></td>
						<td class="pcpCampoGris"><?= ($totalAlicuota == 0)?"-":number_format($totalAlicuota, 2)?></td>
					</tr>
				</table>
			</div>
			<div class="divPCPTabla">(*) Incluye $0.60 por cada trabajador destinados al Fondo Fiduciario para Enfermedades Profesionales.</div>
		</div>

		<div align="center" class="divSubSeccion" id="divBonificacionesEspecialesTitulo">4. BONIFICACIONES ESPECIALES</div>
		<div align="center" id="divBonificacionesEspeciales" style="color:#211e1e; font-size:8pt; margin-left:8px;">
			<span style="text-decoration:underline;">Bonificaciones especiales:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; N/A%</span><br/>
			RÉGIMEN DE ALÍCUOTAS PARA SUPUESTOS ESPECIALES - Resolución 65/96 S.R.T. Y S.S.N. Nº 24.573/96: ART. 1°.- El empleador que 
			contare con más de un establecimiento celebrará un único contrato de afiliación. La alícuota se determinará de acuerdo a los 
			procedimientos estipulados por la normativa que regula el régimen en general, entendiéndose que el nivel de cumplimiento, a los fines 
			del encuadramiento en el régimen de alícuotas, será el que corresponda al establecimiento de menor nivel de cumplimiento; salvo que de 
			común acuerdo el empleador y la Aseguradora establezcan como más representativo del estado de riesgo de la empresa en su conjunto, 
			el nivel de cualquiera de los otros establecimientos.
		</div>
		<div align="center" class="divSubSeccion" id="divClausulaPenaltitulo">5. CLAÚSULA PENAL</div>
		<div align="center" style="color:#211e1e; font-size:8pt; margin-left:8px; text-decoration:underline;">Claúsula penal por incumplimientos de denuncias del empleador: <?= getClausulaPenal($_REQUEST["id"])?></div>
		<div align="center" class="divSubSeccion">6. LISTADO DE PRESTADORES</div>
		<div align="center" style="color:#211e1e; font-size:8pt; margin-left:8px;">
			El mismo esta a su disposición en <a class="linkSubrayado" target="_blank" href="/">www.provinciart.com.ar</a><br/>
			<span id="spanObservaciones">Observaciones: (máximo 250 caracteres)<br/></span>
			<textarea cols="85" id="observaciones" name="observaciones" rows="4" style="text-transform:uppercase;" onBlur="this.value = this.value.toUpperCase();"><?= $row["SA_OBSERVACIONES"]?></textarea>
		</div>

		<div align="center" class="divSubSeccion" id="divTareasRiesgosLaboralesTitulo">7. DESCRIPCIÓN DE TAREAS Y RIESGOS LABORALES (POSEE CARÁCTER DE DECLARACIÓN JURADA DEL EMPLEADOR)</div>
		<div id="divTareasRiesgosLaborales" style="color:#211e1e; font-size:8pt; margin-left:8px;">
			<label for="breveDescripcionTareas" id="labelBreveDescripcionTareas">Breve descripción de tareas</label>
			<textarea id="breveDescripcionTareas" name="breveDescripcionTareas" maxlength="250" onBlur="this.value = this.value.toUpperCase();"><?= $row["RP_DESCRIPCION"]?></textarea>
			<div style="margin-bottom:2px; margin-top:8px;">
				<br />
				<span style="color:#211e1e; font-size:8pt; font-weight:700;">Riesgo Eléctrico</span>
				<div>
					<label for="electrico">¿ Posee sistema de protección para las personas en la instalación eléctrica ?</label>
					<label for="electrico" id="labelElectrico" >SÍ</label>
					<input <?= ($row["RP_ELECTRICO"] == "S")?"checked":""?> id="electrico" name="electrico" type="radio" value="S" />
					<label for="electrico" id="labelElectrico">NO</label>
					<input <?= ($row["RP_ELECTRICO"] == "N")?"checked":""?> id="electrico" name="electrico" type="radio" value="N" />
				</div>
			</div>
			<div style="margin-bottom:2px; margin-top:8px;">
				<br />
				<span style="color:#211e1e; font-size:8pt; font-weight:700;">Riesgo de Incendio</span>
				<div>
					<label for="incendio">¿ Posee algún dispositivo de extinción de incendio ?</label>
					<label for="incendio" id="labelIncendio">SÍ</label>
					<input <?= ($row["RP_INCENDIO"] == "S")?"checked":""?> id="incendioS" name="incendio" type="radio" value="S" />
					<label for="incendio" id="labelIncendio">NO</label>
					<input <?= ($row["RP_INCENDIO"] == "N")?"checked":""?> id="incendioN" name="incendio" type="radio" value="N" />
				</div>
				<div>
					<span style="color:#211e1e; font-size:8pt;">Indique cual</span>
					<label for="extintor" id="labelExtintor">Extintor portátil CO2</label>
					<input <?= ($row["RP_EXTINTOR"] == "1")?"checked":""?> id="extintor1" name="extintor" type="radio" value="1" />
					<label for="extintor" id="labelExtintor">Extintor Triclase Polvo Químico</label>
					<input <?= ($row["RP_EXTINTOR"] == "2")?"checked":""?> id="extintor2" name="extintor" type="radio" value="2" />
					<label for="extintor" id="labelExtintor">Extintor de Agua</label>
					<input <?= ($row["RP_EXTINTOR"] == "3")?"checked":""?> id="extintor3" name="extintor" type="radio" value="3" />
					<label for="extintorCual" id="labelExtintor">Otros</label>
					<input class="input2" id="extintorCual" name="extintorCual" type="text" value="<?= $row["RP_EXTINTOR_CUAL"]?>" />
				</div>
			</div>
			<div style="margin-bottom:2px; margin-top:8px;">
				<br />
				<span style="color:#211e1e; font-size:8pt; font-weight:700;">Riesgo Químico</span>
				<div>
					<label>Indique que elementos químicos utiliza habitualmente:</label>
				</div>
				<div>
					<table width="720">
						<tr>
							<td width="20%">Insecticidas</td>
							<td align="left" width="10%">
								<label for="insecticida">SÍ</label>
								<input <?= ($row["RP_INSECTICIDA"] == "S")?"checked":""?> id="insecticidaS" name="insecticida" type="radio" value="S" />
							</td>
							<td align="left" width="10%">
								<label for="insecticida">NO</label>
								<input <?= ($row["RP_INSECTICIDA"] == "N")?"checked":""?> id="insecticidaN" name="insecticida" type="radio" value="N" />
							</td>
							<td align="left" width="60%">
								<label for="insecticidaCual">¿ Cuáles ?</label>
								<input class="input2" id="insecticidaCual" name="insecticidaCual" type="text" value="<?= $row["RP_INSECTICIDA_CUAL"]?>">
							</td>
						</tr>
						<tr>
							<td>Bencina</td>
							<td>
								<label for="bencina">SÍ</label>
								<input <?= ($row["RP_BENCINA"] == "S")?"checked":""?> id="bencina" name="bencina" type="radio" value="S" />
							</td>
							<td colspan="2">
								<label for="bencina">NO</label>
								<input <?= ($row["RP_BENCINA"] == "N")?"checked":""?> id="bencina" name="bencina" type="radio" value="N" />
							</td>
						</tr>
						<tr>
							<td>Raticidas</td>
							<td>
								<label for="raticida">SÍ</label>
								<input <?= ($row["RP_RATICIDA"] == "S")?"checked":""?> id="raticidaS" name="raticida" type="radio" value="S" />
							</td>
							<td>
								<label for="raticida">NO</label>
								<input <?= ($row["RP_RATICIDA"] == "N")?"checked":""?> id="raticidaN" name="raticida" type="radio" value="N" />
							</td>
							<td>
								<label for="raticidaCual">¿ Cuáles ?</label>
								<input class="input2" id="raticidaCual" name="raticidaCual" type="text" value="<?= $row["RP_RATICIDA_CUAL"]?>">
							</td>
						</tr>
						<tr>
							<td>Desinfectantes</td>
							<td>
								<label for="desinfectantes">SÍ</label>
								<input <?= ($row["RP_DESINFECTANTES"] == "S")?"checked":""?> id="desinfectantes" name="desinfectantes" type="radio" value="S" />
							</td>
							<td colspan="2">
								<label for="desinfectantes">NO</label>
								<input <?= ($row["RP_DESINFECTANTES"] == "N")?"checked":""?> id="desinfectantes" name="desinfectantes" type="radio" value="N" />
							</td>
						</tr>
						<tr>
							<td>Detergentes</td>
							<td>
								<label for="detergentes">SÍ</label>
								<input <?= ($row["RP_DETERGENTES"] == "S")?"checked":""?> id="detergentes" name="detergentes" type="radio" value="S" />
							</td>
							<td colspan="2">
								<label for="detergentes">NO</label>
								<input <?= ($row["RP_DETERGENTES"] == "N")?"checked":""?> id="detergentes" name="detergentes" type="radio" value="N" />
							</td>
						</tr>
						<tr>
							<td>Soda Caústica</td>
							<td>
								<label for="sodaCaustica">SÍ</label>
								<input <?= ($row["RP_SODACAUSTICA"] == "S")?"checked":""?> id="sodaCaustica" name="sodaCaustica" type="radio" value="S" />
							</td>
							<td colspan="2">
								<label for="sodaCaustica">NO</label>
								<input <?= ($row["RP_SODACAUSTICA"] == "N")?"checked":""?> id="sodaCaustica" name="sodaCaustica" type="radio" value="N" />
							</td>
						</tr>
						<tr>
							<td>Desengrasante</td>
							<td>
								<label for="desengrasante">SÍ</label>
								<input <?= ($row["RP_DESENGRASANTE"] == "S")?"checked":""?> id="desengrasante" name="desengrasante" type="radio" value="S" />
							</td>
							<td colspan="2">
								<label for="desengrasante">NO</label>
								<input <?= ($row["RP_DESENGRASANTE"] == "N")?"checked":""?> id="desengrasante" name="desengrasante" type="radio" value="N" />
							</td>
						</tr>
						<tr>
							<td>Hipoclorito de sodio (lavandina)</td>
							<td>
								<label for="hipocloritoDeSodio">SÍ</label>
								<input <?= ($row["RP_HIPOCLORITODESODIO"] == "S")?"checked":""?> id="hipocloritoDeSodio" name="hipocloritoDeSodio" type="radio" value="S" />
							</td>
							<td colspan="2">
								<label for="hipocloritoDeSodio">NO</label>
								<input <?= ($row["RP_HIPOCLORITODESODIO"] == "N")?"checked":""?> id="hipocloritoDeSodio" name="hipocloritoDeSodio" type="radio" value="N" />
							</td>
						</tr>
						<tr>
							<td>Amoníaco</td>
							<td>
								<label for="amoniaco">SÍ</label>
								<input <?= ($row["RP_AMONIACO"] == "S")?"checked":""?> id="amoniaco" name="amoniaco" type="radio" value="S" />
							</td>
							<td colspan="2">
								<label for="amoniaco">NO</label>
								<input <?= ($row["RP_AMONIACO"] == "N")?"checked":""?> id="amoniaco" name="amoniaco" type="radio" value="N" />
							</td>
						</tr>
						<tr>
							<td>Ácido clorhídrico muriático</td>
							<td>
								<label for="acidoMuriatico">SÍ</label>
								<input <?= ($row["RP_ACIDOMURIATICO"] == "S")?"checked":""?> id="acidoMuriatico" name="acidoMuriatico" type="radio" value="S" />
							</td>
							<td colspan="2">
								<label for="acidoMuriatico">NO</label>
								<input <?= ($row["RP_ACIDOMURIATICO"] == "N")?"checked":""?> id="acidoMuriatico" name="acidoMuriatico" type="radio" value="N" />
							</td>
						</tr>
					</table>
				</div>
				<div>
					<label for="otroRiesgoQuimico" id="labelOtroRiesgoQuimico">Otros</label>
					<textarea id="otroRiesgoQuimico" name="otroRiesgoQuimico" onBlur="this.value = this.value.toUpperCase();"><?= $row["RP_OTRORIESGOQUIMICO"]?></textarea>
				</div>
			</div>
			<div style="margin-bottom:2px; margin-top:8px;">
				<br />
				<span style="color:#211e1e; font-size:8pt; font-weight:700;">Instalaciones Edilicias</span>
				<div>
					<label>Indique cuales de las siguientes situaciones posee en su vivienda:</label>
				</div>
				<div>
					<table width="720">
						<tr>
							<td>Protecciones en borde de losas y balcones (barandas)</td>
							<td>
								<label for="proteccionBalcones">SÍ</label>
								<input <?= ($row["RP_PROTECCIONBALCONES"] == "S")?"checked":""?> id="proteccionBalcones" name="proteccionBalcones" type="radio" value="S" />
							</td>
							<td colspan="2">
								<label for="proteccionBalcones">NO</label>
								<input <?= ($row["RP_PROTECCIONBALCONES"] == "N")?"checked":""?> id="proteccionBalcones" name="proteccionBalcones" type="radio" value="N" />
							</td>
						</tr>
						<tr>
							<td width="40%">Realizan tareas interiores en altura, a mas de 2.00 mts.</td>
							<td align="left" width="6%">
								<label for="interiorAltura">SÍ</label>
								<input <?= ($row["RP_INTERIORALTURA"] == "S")?"checked":""?> id="interiorAlturaS" name="interiorAltura" type="radio" value="S" />
							</td>
							<td align="left" width="6%">
								<label for="interiorAltura">NO</label>
								<input <?= ($row["RP_INTERIORALTURA"] == "N")?"checked":""?> id="interiorAlturaN" name="interiorAltura" type="radio" value="N" />
							</td>
							<td align="left" width="48%">
								<label for="interiorAlturaCual">¿ Cuáles ?</label>
								<input class="input2" id="interiorAlturaCual" name="interiorAlturaCual" type="text" value="<?= $row["RP_INTERIORALTURA_CUAL"]?>">
							</td>
						</tr>
						<tr>
							<td>Realizan tareas exteriores en altura, a mas de 2.00 mts.<br />(fachadas y frentes/contrafrentes)</td>
							<td>
								<label for="exteriorAltura">SÍ</label>
								<input <?= ($row["RP_EXTERIORALTURA"] == "S")?"checked":""?> id="exteriorAlturaS" name="exteriorAltura" type="radio" value="S" />
							</td>
							<td>
								<label for="exteriorAltura">NO</label>
								<input <?= ($row["RP_EXTERIORALTURA"] == "N")?"checked":""?> id="exteriorAlturaN" name="exteriorAltura" type="radio" value="N" />
							</td>
							<td>
								<label for="exteriorAlturaCual">¿ Cuáles ?</label>
								<input class="input2" id="exteriorAlturaCual" name="exteriorAlturaCual" type="text" value="<?= $row["RP_EXTERIORALTURA_CUAL"]?>">
							</td>
						</tr>
						<tr>
							<td>Escaleras con barandas</td>
							<td>
								<label for="escaleraBaranda">SÍ</label>
								<input <?= ($row["RP_ESCALERABARANDA"] == "S")?"checked":""?> id="escaleraBaranda" name="escaleraBaranda" type="radio" value="S" />
							</td>
							<td colspan="2">
								<label for="escaleraBaranda">NO</label>
								<input <?= ($row["RP_ESCALERABARANDA"] == "N")?"checked":""?> id="escaleraBaranda" name="escaleraBaranda" type="radio" value="N" />
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div style="margin-bottom:2px; margin-top:8px;">
				<br />
				<span style="color:#211e1e; font-size:8pt; font-weight:700;">Ropa y elementos de trabajo</span>
				<div>
					<table width="720">
						<tr>
							<td width="42%">Entrega indumentaria de trabajo<br />(Ejemplo: calzado, delantal, pantalón, camisa, vestido, etc.)</td>
							<td align="left" width="6%">
								<label for="indumentaria">SÍ</label>
								<input <?= ($row["RP_INDUMENTARIA"] == "S")?"checked":""?> id="indumentariaS" name="indumentaria" type="radio" value="S" />
							</td>
							<td align="left" width="6%">
								<label for="indumentaria">NO</label>
								<input <?= ($row["RP_INDUMENTARIA"] == "N")?"checked":""?> id="indumentariaN" name="indumentaria" type="radio" value="N" />
							</td>
							<td align="left" width="46%">
								<label for="indumentariaCual">¿ Cuáles ?</label>
								<input class="input2" id="indumentariaCual" name="indumentariaCual" type="text" value="<?= $row["RP_INDUMENTARIA_CUAL"]?>">
							</td>
						</tr>
						<tr>
							<td>Entrega de Elementos de protección personal<br />(Ejemplo: Guantes, etc.)</td>
							<td>
								<label for="proteccionPersonal">SÍ</label>
								<input <?= ($row["RP_PROTECCIONPERSONAL"] == "S")?"checked":""?> id="proteccionPersonalS" name="proteccionPersonal" type="radio" value="S" />
							</td>
							<td>
								<label for="proteccionPersonal">NO</label>
								<input <?= ($row["RP_PROTECCIONPERSONAL"] == "N")?"checked":""?> id="proteccionPersonalN" name="proteccionPersonal" type="radio" value="N" />
							</td>
							<td>
								<label for="proteccionPersonalCual">¿ Cuáles ?</label>
								<input class="input2" id="proteccionPersonalCual" name="proteccionPersonalCual" type="text" value="<?= $row["RP_PROTECCIONPERSONAL_CUAL"]?>">
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

		<div align="center" class="divSubSeccion" id="divLugarFechaSuscripcion">7. LUGAR Y FECHA DE SUSCRIPCIÓN</div>
		<div align="left" style="color:#211e1e; font-size:8pt; margin-left:8px;">
			<div>
				<label for="lugarSuscripcion">En</label>
				<input class="input2" id="lugarSuscripcion" maxlength="64" name="lugarSuscripcion" style="text-transform:uppercase; width:280px;" type="text" value="<?= $row["SA_LUGARSUSCRIPCION"]?>" onBlur="this.value = this.value.toUpperCase();">
				<label for="diaSuscripcion">a los</label>
				<input class="input2" id="diaSuscripcion" name="diaSuscripcion" style="width:40px;" type="text" value="<?= $row["DIASUSCRIPCION"]?>" readonly>
				<label for="mesSuscripcion">días del mes de</label>
				<input class="input2" id="mesSuscripcion" name="mesSuscripcion" style="width:160px;" type="text" value="<?= GetMonthName($row["MESSUSCRIPCION"])?>" readonly>
				<label for="anoSuscripcion">de</label>
				<input class="input2" id="anoSuscripcion" name="anoSuscripcion" style="width:64px;" type="text" value="<?= $row["ANOSUSCRIPCION"]?>" readonly>
			</div>
			<div style="font-weight:700; margin-bottom:2px; margin-top:16px;">&gt; Datos del comercializador</div>
			<div style="margin-bottom:6px;">
				<label for="nombreComercializador">Nombre y Apellido</label>
				<input class="input2" id="nombreComercializador" maxlength="100" name="nombreComercializador" style="text-transform:uppercase; width:624px;" type="text" value="<?= $row["NOMBRECOMERCIALIZADOR"]?>" onBlur="this.value = this.value.toUpperCase();">
			</div>
			<div style="margin-bottom:6px;">
				<label for="entidad">Entidad</label>
				<input class="input2" id="entidad" name="entidad" style="width:216px;" type="text" value="<?= ($alta)?$entidad:(($row["EN_CODBANCO"] == "")?$entidad:$row["EN_CODBANCO"])?>" readonly>
				<label for="sucursal">Sucursal</label>
				<input class="input2" id="sucursal" name="sucursal" style="width:200px;" type="text" value="<?= ($alta)?$sucursal:(($row["SU_CODSUCURSAL"] == "")?$sucursal:$row["SU_CODSUCURSAL"])?>" readonly>
				<label for="vendedor">Código Vendedor</label>
				<input class="input2" id="vendedor" maxlength="10" name="vendedor" style="text-transform:uppercase; width:98px;" type="text" value="<?= ($alta)?$vendedor:$row["VE_VENDEDOR"]?>" onBlur="this.value = this.value.toUpperCase();">
			</div>
			<div style="font-weight:700; margin-bottom:2px; margin-top:16px;">&gt; Datos del empleador</div>
<?
if ($_SESSION["canal"] == 322) {
?>
			<div style="font-size:8pt; font-weight:700; margin-bottom:6px; margin-left:8px;">
				<span style="margin-left:-8px;">¿ Los datos del empleador serán cargados manualmente al momento de firmar la solicitud de afiliación ?</span>
				<label for="datosEmpleadorManual" style="margin-left:16px;">SI</label>
				<input id="datosEmpleadorManual" name="datosEmpleadorManual" style="margin:0px; vertical-align:middle;" type="radio" value="S" <?= ($row["SA_DATOSEMPLEADORMANUAL"] == "S")?"checked":""?>>
				<label for="datosEmpleadorManual" style="margin-left:16px;">NO</label>
				<input id="datosEmpleadorManual" name="datosEmpleadorManual" style="margin:0px; vertical-align:middle;" type="radio" value="N" <?= ($row["SA_DATOSEMPLEADORMANUAL"] != "S")?"checked":""?>>
			</div>
<?
}
?>
			<div style="margin-bottom:6px;">
				<label for="nombreEmpleador">Nombre y Apellido</label>
				<input class="input2" id="nombreEmpleador" maxlength="100" name="nombreEmpleador" style="text-transform:uppercase; width:624px;" type="text" value="<?= $row["SA_TITULAR"]?>" onBlur="this.value = this.value.toUpperCase();">
			</div>
			<div>
				<label for="cargoEmpleador">Cargo/Personería</label>
				<?= $comboCargoEmpleador->draw();?>
				<label for="sexoEmpleador" style="font-size:8pt; margin-left:16px;">Sexo</label>
				<?= $comboSexoEmpleador->draw();?>
				<label for="dniTitular" style="margin-left:16px;">D.N.I.</label>
				<input class="input2" id="dniTitular" maxlength="8" name="dniTitular" style="text-transform:uppercase; width:80px;" type="text" value="<?= $row["SA_DOCUMENTO_TITULAR"]?>" onBlur="this.value = this.value.toUpperCase();">
			</div>
			<div>
				<label for="telefonoEmpleador">Teléfono</label>
				<input class="input2" id="telefonoEmpleador" maxlength="60" name="telefonoEmpleador" style="text-transform:uppercase; width:296px;" type="text" value="<?= $row["SA_TELEFONO_TITULAR"]?>" onBlur="this.value = this.value.toUpperCase();">
				<label for="emailTitular" style="margin-left:16px;">e-Mail</label>
				<input class="input2" id="emailTitular" maxlength="60" name="emailTitular" style="text-transform:uppercase; width:310px;" type="text" value="<?= $row["SA_DIRELECTRONICA_TITULAR"]?>" onBlur="this.value = this.value.toUpperCase();">
			</div>
		</div>
		<div align="center" class="divSubSeccion" id="divEstablecimientosTitulo">8. ESTABLECIMIENTOS</div>
		<div align="left" id="divEstablecimientos">
			<div id="divEstablecimientosRgrlImpreso" style="font-size:8pt; font-weight:700; margin-bottom:6px; margin-left:8px;">
				<span>¿ Tiene completo e impreso el relevamiento general de riesgo laboral (RGRL) por cada establecimiento ?</span>
				<label for="rgrlImpreso" style="margin-left:16px;">SI</label>
				<input id="rgrlImpreso" name="rgrlImpreso" style="margin:0px; vertical-align:middle;" type="radio" value="S" <?= ($row["SA_RGRLIMPRESO"] == "S")?"checked":""?>>
				<label for="rgrlImpreso" style="margin-left:16px;">NO</label>
				<input id="rgrlImpreso" name="rgrlImpreso" style="margin:0px; vertical-align:middle;" type="radio" value="N" <?= ($row["SA_RGRLIMPRESO"] == "N")?"checked":""?>>
			</div>
<?
// Borro los establecimientos temporales que pudieran existir para el usuario actual..
if ($row["SOLOPCP"] == "S") {
	$params = array(":usualta" => "W_".substr($_SESSION["usuario"], 0, 18));
	$sql =
		"DELETE FROM afi.alt_lugartrabajo_pcp
					 WHERE lt_idsolicitud = -1
						 AND lt_usualta = :usualta
						 AND lt_usuarioweb = 'T'";
	DBExecSql($conn, $sql, $params);
}
else {
	// Uso el 9 como si fuera el -1 porque hay una constraint en la ase_solicitudestablecimiento que no me deja poner -1..
	$params = array(":usualta" => "W_".substr($_SESSION["usuario"], 0, 18));
	$sql =
		"DELETE FROM hys.hra_respuestaadicional
					 WHERE ra_idsolicitudestablecimiento IN (SELECT se_id
																										 FROM ase_solicitudestablecimiento
																										WHERE se_idsolicitud = 9
																											AND se_usualta = :usualta
																											AND se_usuarioweb = 'T')";
	DBExecSql($conn, $sql, $params);

	$params = array(":usualta" => "W_".substr($_SESSION["usuario"], 0, 18));
	$sql =
		"DELETE FROM hys.hsp_solicitudplanillafgrl
					 WHERE sp_idsolicitudfgrl IN (SELECT sf_id
																					FROM hys.hsf_solicitudfgrl
																				 WHERE sf_idsolicitudestablecimiento IN (SELECT se_id
																																									 FROM ase_solicitudestablecimiento
																																									WHERE se_idsolicitud = 9
																																										AND se_usualta = :usualta
																																										AND se_usuarioweb = 'T'))";
	DBExecSql($conn, $sql, $params);

	$params = array(":usualta" => "W_".substr($_SESSION["usuario"], 0, 18));
	$sql =
		"DELETE FROM hys.hst_solicituditemsfgrl
					 WHERE st_idsolicitudfgrl IN (SELECT sf_id
																					FROM hys.hsf_solicitudfgrl
																				 WHERE sf_idsolicitudestablecimiento IN (SELECT se_id
																																									 FROM ase_solicitudestablecimiento
																																									WHERE se_idsolicitud = 9
																																										AND se_usualta = :usualta
																																										AND se_usuarioweb = 'T'))";
	DBExecSql($conn, $sql, $params);

	$params = array(":usualta" => "W_".substr($_SESSION["usuario"], 0, 18));
	$sql =
		"DELETE FROM hys.hsf_solicitudfgrl
					 WHERE sf_idsolicitudestablecimiento IN (SELECT se_id
																										 FROM ase_solicitudestablecimiento
																										WHERE se_idsolicitud = 9
																											AND se_usualta = :usualta
																											AND se_usuarioweb = 'T')";
	DBExecSql($conn, $sql, $params);

	$params = array(":usualta" => "W_".substr($_SESSION["usuario"], 0, 18));
	$sql =
		"DELETE FROM ase_solicitudestablecimiento
					 WHERE se_idsolicitud = 9
						 AND se_usualta = :usualta
						 AND se_usuarioweb = 'T'";
	DBExecSql($conn, $sql, $params);
}
?>
			<iframe frameborder="no" height="0" id="iframeEstablecimientos" name="iframeEstablecimientos" scrolling="no" src="/modules/solicitud_afiliacion/establecimientos<?= ($row["SOLOPCP"] == "S")?"_pcp":""?>.php?rnd=<?= date("Ymd")?>&idModulo=<?= $_REQUEST["id"]?>&idSolicitud=<?= (!$alta)?$row["SA_ID"]:(($row["SOLOPCP"] == "S")?-1:9)?>" width="736" onLoad="ajustarTamanoIframe(this, 192);"></iframe>
		</div>
<?
$params = array(":id" => $id);

if ($modulo == "C")
	$params = array(":id" => $id);
else		// Si es una revisión de precio le meto -2 para que no traiga nada y deje todo en blanco..
	$params = array(":id" => -2);
$sql =
	"SELECT pr_cbu, pr_iibb, pr_iva, pr_mail, pr_medio_pago, pr_origenpago, pr_poliza, pr_sumaasegurada
		 FROM art.apr_polizarc, asa_solicitudafiliacion, asc_solicitudcotizacion
		WHERE pr_idsolicitudafi = sa_id
			AND sa_idsolicitudcotizacion = sc_id
			AND sc_id = :id";
$stmt2 = DBExecSql($conn, $sql, $params);
$rowRC = DBGetQuery($stmt2);
require("afiliacion_combos.php");

if ($alta)
	$suscribePoliza = "S";
else
	$suscribePoliza = "N";
if (isset($rowRC["PR_POLIZA"]))
	$suscribePoliza = $rowRC["PR_POLIZA"];

if (($_SESSION["entidad"] != 400) and ($_SESSION["entidad"] != 10891) and ($modulo == "C") and ($row["SOLOPCP"] != "S")) {
// Si (no es "Banco Nación") y (no es del "CPCECABA") y (viene de una "solicitud de cotización") y (no es "Solo PCP")..
?>
		<div class="divSubSeccion" style="background-color:#00539b; height:36px; padding-left:12px; ">
			<span style="font-size:12px; font-weight:700; vertical-align:-16px;">RESPONSABILIDAD CIVIL PATRONAL</span>
			<img src="/modules/solicitud_cotizacion/images/provincia_seguros.gif" style="float:right; height:34; width:85px;" />
		</div>
		<div align="left" style="color:#211e1e; font-size:8pt; margin-left:8px; margin-top:8px; position:relative;">
			<div style="font-size:8pt; font-weight:700; margin-bottom:6px;">
				<span>¿ Suscribe Póliza de Responsabilidad Civil Patronal ?</span>
				<label for="suscribePolizaRC" style="margin-left:16px;">SI</label>
				<input id="suscribePolizaRC" name="suscribePolizaRC" style="margin:0px; vertical-align:middle;" type="radio" value="S" <?= ($suscribePoliza == "S")?"checked":""?>>
				<label for="suscribePolizaRC" style="margin-left:16px;">NO</label>
				<input id="suscribePolizaRC" name="suscribePolizaRC" style="margin:0px; vertical-align:middle;" type="radio" value="N" <?= ($suscribePoliza == "N")?"checked":""?>>
			</div>
			<div style="border:1px solid; float:left; padding:2px; position:relative;">
				<div align="center" style="border-bottom:1px solid;">Suma Asegurada</div>
				<input <?= ($row["SUMAASEGURADARC"] == "250000")?"checked":""?> id="sumaAseguradaRC" name="sumaAseguradaRC" type="radio" value="250000" />
				<label style="vertical-align:3px;">Hasta $250.000</label>
				<br />
				<input <?= ($row["SUMAASEGURADARC"] == "500000")?"checked":""?> id="sumaAseguradaRC" name="sumaAseguradaRC" type="radio" value="500000" />
				<label style="vertical-align:3px;">Hasta $500.000</label>
				<br />
				<input <?= ($row["SUMAASEGURADARC"] == "1000000")?"checked":""?> id="sumaAseguradaRC" name="sumaAseguradaRC" type="radio" value="1000000" />
				<label style="vertical-align:3px;">Hasta $1.000.000</label>
			</div>
			<div style="border:1px solid; float:left; margin-left:6px; padding:2px; position:relative;">
				<div align="center" style="border-bottom:1px solid;">Forma de Pago</div>
				<div style="border-right:0px solid; float:left; margin-top:4px; padding-right:4px;">
					<input <?= ($rowRC["PR_MEDIO_PAGO"] == "B")?"checked":""?> id="formaPago" name="formaPago" type="radio" value="B" onClick="cambiaFormaPago('B')" />
					<label style="vertical-align:3px;">Boleta</label>
					<br />
					<input <?= ($rowRC["PR_MEDIO_PAGO"] == "TC")?"checked":""?> id="formaPago" name="formaPago" type="radio" value="TC" onClick="cambiaFormaPago('TC')" />
					<label style="vertical-align:3px;">Tarjeta Crédito</label>
					<?= $comboTarjetaCredito->draw();?>
					<?= $comboTarjetaCreditoFalso->draw();?>
					<br />
					<input <?= ($rowRC["PR_MEDIO_PAGO"] == "DA")?"checked":""?> id="formaPago" name="formaPago" style="margin-top:4px;" type="radio" value="DA" onClick="cambiaFormaPago('DA')" />
					<label style="vertical-align:3px;">Débito Automático</label>
					<br />
					<label id="labelCbu" style="margin-left:4px;">C.B.U.</label>
					<input class="input2" id="cbu" maxlength="22" name="cbu" readonly style="text-transform:uppercase; width:160px;" type="text" value="<?= $rowRC["PR_CBU"]?>" />
				</div>
			</div>
			<div style="border:1px solid; float:left; margin-left:6px; padding:2px; position:relative;">
				<div align="center" style="border-bottom:1px solid;">I.V.A.</div>
				<input <?= ($rowRC["PR_IVA"] == "CF")?"checked":""?> id="iva" name="iva" type="radio" value="CF" />
				<label style="vertical-align:3px;">Consumidor Final</label>
				<br />
				<input <?= ($rowRC["PR_IVA"] == "MT")?"checked":""?> id="iva" name="iva" type="radio" value="MT" />
				<label style="vertical-align:3px;">Monotributo</label>
				<br />
				<input <?= ($rowRC["PR_IVA"] == "NI")?"checked":""?> id="iva" name="iva" type="radio" value="NI" />
				<label style="vertical-align:3px;">No Inscripto</label>
				<br />
				<input <?= ($rowRC["PR_IVA"] == "RI")?"checked":""?> id="iva" name="iva" type="radio" value="RI" />
				<label style="vertical-align:3px;">Resp. Inscripto</label>
				<br />
				<input <?= ($rowRC["PR_IVA"] == "EX")?"checked":""?> id="iva" name="iva" type="radio" value="EX" />
				<label style="vertical-align:3px;">Exento</label>
			</div>
			<div style="border:1px solid; float:left; margin-left:6px; padding:2px; position:relative;">
				<div align="center" style="border-bottom:1px solid;">I.I.B.B.</div>
				<input <?= ($rowRC["PR_IIBB"] == "AP")?"checked":""?> id="iibb" name="iibb" type="radio" value="AP" />
				<label style="vertical-align:3px;">Agente de Percepción</label>
				<br />
				<input <?= ($rowRC["PR_IIBB"] == "CL")?"checked":""?> id="iibb" name="iibb" type="radio" value="CL" />
				<label style="vertical-align:3px;">Contribuyente Local</label>
				<br />
				<input <?= ($rowRC["PR_IIBB"] == "CM")?"checked":""?> id="iibb" name="iibb" type="radio" value="CM" />
				<label style="vertical-align:3px;">Convenio Multilateral</label>
				<br />
				<input <?= ($rowRC["PR_IIBB"] == "EX")?"checked":""?> id="iibb" name="iibb" type="radio" value="EX" />
				<label style="vertical-align:3px;">Exento</label>
				<br />
				<input <?= ($rowRC["PR_IIBB"] == "SI")?"checked":""?> id="iibb" name="iibb" type="radio" value="SI" />
				<label style="vertical-align:3px;">SICOM</label>
				<br />
				<input <?= ($rowRC["PR_IIBB"] == "ZZ")?"checked":""?> id="iibb" name="iibb" type="radio" value="ZZ" />
				<label style="vertical-align:3px;">No corresponde</label>
				<br />
				<input <?= ($rowRC["PR_IIBB"] == "RS")?"checked":""?> id="iibb" name="iibb" type="radio" value="RS" />
				<label style="vertical-align:3px;">Régimen Simplificado</label>
			</div>
			<div style="clear:left; position:relative; top:-8px;">
				<input class="btnRecalcularPolizaRC" type="button" value="" onClick="recalcularRC('<?= $_REQUEST["id"]?>')" />
			</div>
			<div class="ContenidoSeccion" style="left:-13px; position:relative; width:432px;">
				<div align="center" style="background-color:#00539b; color:#fff; font-weight:700; padding-bottom:8px; padding-top:8px;">VALOR COTIZADO DE RESPONSABILIDAD CIVIL PATRONAL</div>
				<div align="center" style="color:#676767;">
					<div style="background-color:#fff; border-left:1px solid #676767; float:left; padding-bottom:2px; padding-top:2px; width:142px;">Alícuota variable</div>
					<div style="background-color:#fff; border-left:1px solid #676767; border-right:1px solid #676767; float:left; padding-bottom:2px; padding-top:2px; width:143px;">Masa salarial</div>
					<div style="background-color:#fff; border-right:1px solid #676767; float:left; padding-bottom:2px; padding-top:2px; width:143px;">Cuota inicial resultante</div>
				</div>
				<div align="center" style="position:relative;">
					<div style="background-color:#fff; border-bottom:1px solid #676767; border-left:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:142px;"><input class="inputNumber2" id="polizaRC" name="polizaRC" style="width:120px;" type="text" value="<?= $row["VALORRC"]?>" readonly /></div>
					<div style="background-color:#fff; border-bottom:1px solid #676767; border-left:1px solid #676767; border-right:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:143px;"><input class="inputNumber2" style="width:120px;" type="text" value="<?= trim($row["MASASALARIAL"])?>" readonly /></div>
					<div style="background-color:#fff; border-bottom:1px solid #676767; border-right:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:143px;"><input class="inputNumber2" id="cuotaInicialRC" name="cuotaInicialRC" style="width:120px;" type="text" value="<?= $row["CUOTAINICIALRC"]?>" readonly /></div>
				</div>
			</div>
			<div style="clear:left; position:relative;">
				<label>Recepción de Póliza vía e-mail a</label>
				<input class="input2" id="emailPolizaRC" maxlength="200" name="emailPolizaRC" style="text-transform:lowercase; width:552px;" type="text" value="<?= $rowRC["PR_MAIL"]?>" />
			</div>
		</div>
<?
}
?>
		<div style="padding-bottom:24px; margin-top:16px;">
			<div style="float:left; margin-left:8px;">
				<input class="btnGrabar" id="btnGrabar" name="btnGrabar" type="submit" value="" />
				<img id="imgGuardando" src="/images/loading.gif" style="display:none; vertical-align:-4px;" />
			</div>
			<div style="float:right; margin-right:8px;">
				<input class="btnVolver" style="margin-left:24px;" type="button" value="" onClick="window.location.href= '<?= $_SESSION["paginaAnterior"]?>'" />
<?
if (!$alta) {
?>
				<input class="btnImprimir" type="button" value="" onClick="imprimir('<?= $_REQUEST["id"]?>', <?= $row["SA_ID"]?>)" />
<?
}
?>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
	Calendar.setup ({
		inputField: "fechaSuscripcion",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaSuscripcion"
	});
	Calendar.setup ({
		inputField: "fechaInicioActividad",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaInicioActividad"
	});

	selectNivel('<?= ($alta)?2:$row["SA_NIVEL"]?>', false);
	cambiaFormaPago('<?= $rowRC["PR_MEDIO_PAGO"]?>');
	mostrarSoloPCP(<?= ($row["SOLOPCP"] == "S")?"true":"false"?>);
	setLocalidad('<?= $row["SA_LOCALIDAD"]?>');
	if (document.getElementById('cbu') != null)
		document.getElementById('cbu').value = '<?= $rowRC["PR_CBU"]?>';
		
	Inicializar();
	buscarEstablecimientos('<?= $row["CUIT"]?>', '<?= (!$alta)?$row["SA_ID"]:(($row["SOLOPCP"] == "S")?-1:9)?>');
</script>
<?
if ((isset($_REQUEST["i"])) and ($_REQUEST["i"] == "ok")) {
?>
	<div id="msgOk" name="msgOk">
		<span style="background-color:#000; border: 3px solid #000;cursor:pointer; color:#fff; position:relative; right:-188px;" onClick="document.getElementById('msgOk').style.display = 'none'"><b>&nbsp;X&nbsp;</b></span>
		<p align="center" style="cursor:default; margin-bottom:32px; margin-top:24px;"><b>Los datos se guardaron correctamente.</b></p>
	</div>
	<script type="text/javascript">
		setTimeout("document.getElementById('msgOk').style.display = 'none';", 2000);
	</script>
<?
}
?>
<div id="divGridEspera">&nbsp;</div>
<div id="divGridEsperaTexto">Generando PDF, aguarde un instante por favor...</div>