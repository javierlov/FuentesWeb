<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


validarParametro(isset($_REQUEST["id"]));

$_REQUEST["id"] = intval($_REQUEST["id"]);

validarSesion(isset($_SESSION["isAgenteComercial"]));
//validarSesion(validarContrato($_REQUEST["id"]));
if (!validarContrato($_REQUEST["id"])) {
	echo "<span id=\"sesionInvalidMsg\">ESTA ENTIDAD YA NO TIENE INGERENCIA EN EL CONTRATO QUE SE QUIERE VISUALIZAR.</span>";
	exit;
}

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "3";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$params = array(":contrato" => $_REQUEST["id"]);
$sql =
	"SELECT ac_codigo || ' - ' || ac_descripcion actividad,
					NVL(as_nombre, '-') asesoremision,
					dc_calle calle,
					(SELECT art.legales.get_cantjuiciosempresa(em_cuit, SYSDATE - 360, SYSDATE)
						 FROM DUAL) cantdemjudicinic,
					(SELECT COUNT(*)
						 FROM art.sex_expedientes
						WHERE NVL(ex_causafin, ' ') NOT IN('99', '95')
							AND ex_contrato = co_contrato
							AND ex_fechaaccidente >= SYSDATE - 360) cantidadsiniestrosdenunciados,
					(SELECT COUNT(*)
						 FROM art.sex_expedientes
						WHERE NVL(ex_causafin, ' ') IN('02')
							AND ex_contrato = co_contrato
							AND ex_recaida = 0
							AND ex_fechaaccidente >= SYSDATE - 360) cantidadsiniestrosrechazados,
					co_contrato contrato,
					NVL(dc_cpostala, dc_cpostal) codigopostal,
					em_cuit cuit,
					NVL(dc_departamento, '-') departamento,
					TO_CHAR(art.deuda.get_deudatotalconsolidada(co_contrato), '$9,999,999,990.00') deudatotal,
					webart.get_resumen_cobranza('S', 1, co_contrato) devengados,
					co_direlectronica email,
					as_mail emailasesoremision,
					use1.se_mail emailejecutivocomercial,
					gc_direlectronica emailgestor,
					it_email emailpreventor,
					NVL(aec1.ec_nombre, '-') ejecutivocomercial,
					webart.get_resumen_cobranza('S', 2, co_contrato) empleados,
					NVL((SELECT TO_CHAR(MAX(TRUNC(ae_fechahorainicio)))
								 FROM agenda.aae_agendaevento
								WHERE ae_idtipoevento = 193
									AND ae_idmotivoingreso = 5
									AND ae_contrato = co_contrato
									AND ae_idusuario = use1.se_id), '-') fechaultimavisita,
					NVL(gc_nombre, '-') gestor,
					NVL(ge_descripcion, '-') holding,
					webart.get_resumen_cobranza('S', 6, co_contrato) importereclamoafip,
					dc_localidad localidad,
					webart.get_resumen_cobranza('S', 3, co_contrato) masasalarial,
					NVL(dc_numero, 'S/N') numero,
					webart.get_resumen_cobranza('S', 4, co_contrato) pagos,
					art.utiles.armar_periodo(webart.get_resumen_cobranza('S', 5, co_contrato)) periodo,
					NVL(dc_piso, '-') piso,
					TO_CHAR(tc_porcmasa, '990.000') || '%' porcvariable,
					NVL(it_nombre, '-') preventor,
					pv_descripcion provincia,
					em_nombre razonsocial,
					(SELECT COUNT(*)
						 FROM art.sex_expedientes
						WHERE NVL(ex_causafin, ' ') NOT IN('99', '95')
							AND ex_posiblerecupero = 'S'
							AND ex_contrato = co_contrato
							AND ex_fechaaccidente >= SYSDATE - 360) recuperosiniestros,
					TO_CHAR(tc_alicuotapesos, '$9,999,999,990.00') sumafija,
					afi.get_telefonos('ATO_TELEFONOCONTRATO', co_contrato, 'L') telefonos,
					co_vigenciadesde vigenciadesde,
					co_vigenciahasta vigenciahasta,
					tc_vigenciatarifa vigenciatarifa
		 FROM aco_contrato, aem_empresa, cac_actividad, avc_vendedorcontrato, xev_entidadvendedor, xen_entidad, asu_sucursal, atc_tarifariocontrato, aec_ejecutivocuenta aec1, agc_gestorcuenta,
					adc_domiciliocontrato, cpv_provincias, age_grupoeconomico, use_usuarios use1, ias_asesoremision, pit_firmantes
		WHERE co_idempresa = em_id
			AND co_idactividad = ac_id
			AND co_contrato = vc_contrato
			AND vc_identidadvend = ev_id
			AND ev_identidad = en_id
			AND vc_idsucursal = su_id(+)
			AND co_contrato = tc_contrato
			AND co_idejecutivo = aec1.ec_id(+)
			AND co_idgestor = gc_id(+)
			AND art.afiliacion.check_cobertura(co_contrato, SYSDATE) = 1
			AND su_fechabaja IS NULL
			AND en_fechabaja IS NULL
			AND vc_fechabaja IS NULL
			AND co_contrato = dc_contrato
			AND dc_tipo = 'L'
			AND dc_provincia = pv_codigo(+)
			AND em_idgrupoeconomico = ge_id(+)
			AND ec_usuario = use1.se_usuario(+)
			AND co_idasesoremision = as_id(+)
			AND hys.get_preventor_referente(em_cuit, SYSDATE) = it_codigo(+)
			AND co_contrato = :contrato";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<script src="/modules/usuarios_registrados/agentes_comerciales/contratos_activos/js/contratos_activos.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<div class="TituloSeccion" style="display:block; width:730px;">
	<span style="margin-right:32px;">Contrato Activo</span>
	<a href="/modules/usuarios_registrados/agentes_comerciales/contratos_activos/exportar_contrato_a_excel.php?c=<?= $_REQUEST["id"]?>">
		<img border="0" src="/modules/usuarios_registrados/images/icoEXCEL.gif" style="vertical-align:-2px;" title="Exportar a Excel" />
	</a>
</div>
<div class="ContenidoSeccion" style="padding:4px; margin-left:4px; margin-top:4px; width:720px;">
	<div>
		<label style="margin-left:37px;">Contrato</label>
		<span><b><?= $row["CONTRATO"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:15px;">Razón Social</label>
		<span><b><?= $row["RAZONSOCIAL"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label>Vigencia Desde</label>
		<span><b><?= $row["VIGENCIADESDE"]?></b></span>
		<label style="margin-left:8px;">Vigencia Hasta</label>
		<span><b><?= $row["VIGENCIAHASTA"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:33px;">Actividad</label>
		<span><b><?= substr($row["ACTIVIDAD"], 0, 88)?></b></span>
	</div>
	<div>
		<span style="margin-left:92px;"><b><?= substr($row["ACTIVIDAD"], 88)?></b></span>
	</div>
</div>

<div class="TituloSeccion" style="display:block; font-size:9pt; margin-top:8px; width:730px;">Domicilio Constituido</div>
<div class="ContenidoSeccion" style="padding:4px; margin-left:4px; margin-top:4px; width:720px;">
	<div>
		<label style="margin-left:72px;">Calle</label>
		<span><b><?= $row["CALLE"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:55px;">Número</label>
		<span><b><?= $row["NUMERO"]?></b></span>
		<label style="margin-left:8px;">Piso</label>
		<span><b><?= $row["PISO"]?></b></span>
		<label style="margin-left:8px;">Departamento</label>
		<span><b><?= $row["DEPARTAMENTO"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:47px;">Localidad</label>
		<span><b><?= $row["LOCALIDAD"]?></b></span>
		<label style="margin-left:8px;">Código Postal</label>
		<span><b><?= $row["CODIGOPOSTAL"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:49px;">Provincia</label>
		<span><b><?= $row["PROVINCIA"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label>e-Mail Corporativo</label>
		<span><b><?= $row["EMAIL"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:47px;">Teléfonos</label>
		<span><b><?= $row["TELEFONOS"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<span style="margin-left:103px;">Si los datos han cambiado, usted puede imprimir el <a href="<?= getFile("D:/Storage_Extranet/descargables_web/Provincia_ART_Rect_datos.pdf")?>" target="_blank">formulario de validación de datos</a>.</span>
		<a href="<?= getFile("F:/Storage_Extranet/descargables_web/Provincia_ART_Rect_datos.pdf")?>" target="_blank"><img border="0" src="/images/link.png" style="vertical-align:-4px;" title="Formulario de Validación de Datos" /></a>
	</div>
</div>

<div class="TituloSeccion" style="display:block; font-size:9pt; margin-top:8px; width:730px;">Datos de la Cuenta</div>
<div class="ContenidoSeccion" style="padding:4px; margin-left:4px; margin-top:4px; width:720px;">
	<div>
		<label style="margin-left:14px;">Período Mes/Año</label>
		<span><b><?= $row["PERIODO"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label>Cant. Trabajadores</label>
		<span><b><?= $row["EMPLEADOS"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:33px;">Masa Salarial</label>
		<span><b><?= $row["MASASALARIAL"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:23px;">Vigencia Tarifa</label>
		<span><b><?= $row["VIGENCIATARIFA"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:50px;">Suma Fija</label>
		<span><b><?= $row["SUMAFIJA"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:29px;">Porc. Variable</label>
		<span><b><?= $row["PORCVARIABLE"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:20px;">Devengado Mes</label>
		<span><b><?= $row["DEVENGADOS"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:41px;">Pagado Mes</label>
		<span><b><?= $row["PAGOS"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:40px;">Deuda Total</label>
		<span><b><?= $row["DEUDATOTAL"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:64px;">Holding</label>
		<span><b><?= $row["HOLDING"]?></b></span>
	</div>
</div>

<div class="TituloSeccion" style="display:block; font-size:9pt; margin-top:8px; width:730px;">Documentación Faltante</div>
<div class="ContenidoSeccion" style="padding:4px; margin-left:4px; margin-top:4px; width:720px;">
	<div>
		<label style="margin-left:15px;">En construcción.</label>
	</div>
</div>

<div class="TituloSeccion" style="display:block; font-size:9pt; margin-top:8px; width:730px;">Datos del Intermediario y Ejecutivos</div>
<div class="ContenidoSeccion" style="padding:4px; margin-left:4px; margin-top:4px; width:720px;">
	<div>
		<label style="margin-left:23px;">Ejecutivo Comercial de la Empresa</label>
		<span><b><?= $row["EJECUTIVOCOMERCIAL"]?></b><?= ($row["EMAILEJECUTIVOCOMERCIAL"]!="")?" (<a href=\"mailto:".$row["EMAILEJECUTIVOCOMERCIAL"]."\">".$row["EMAILEJECUTIVOCOMERCIAL"]."</a>)":""?></span>
	</div>
	<div style="margin-top:2px;">
		<label>Fecha última visita Ejecutivo Comercial</label>
		<span><b><?= $row["FECHAULTIMAVISITA"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:98px;">Gestor de Cobranzas</label>
		<span><b><?= $row["GESTOR"]?></b><?= ($row["EMAILGESTOR"]!="")?" (<a href=\"mailto:".$row["EMAILGESTOR"]."\">".$row["EMAILGESTOR"]."</a>)":""?></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:158px;">Preventor</label>
		<span><b><?= $row["PREVENTOR"]?></b><?= ($row["EMAILPREVENTOR"]!="")?" (<a href=\"mailto:".$row["EMAILPREVENTOR"]."\">".$row["EMAILPREVENTOR"]."</a>)":""?></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:111px;">Asesor de Emisión</label>
		<span><b><?= $row["ASESOREMISION"]?></b><?= ($row["EMAILASESOREMISION"]!="")?" (<a href=\"mailto:".$row["EMAILASESOREMISION"]."\">".$row["EMAILASESOREMISION"]."</a>)":""?></span>
	</div>
</div>

<div class="TituloSeccion" style="display:block; font-size:9pt; margin-top:8px; width:730px;">Prevención y Establecimientos</div>
<div class="ContenidoSeccion" style="padding:4px; margin-left:4px; margin-top:4px; width:720px;">
	<div id="divContentGrid" name="divContentGrid" style="margin-top:8px;">
<?
$params = array(":contrato" => $_REQUEST["id"]);

//	"SELECT es_nroestableci ¿id?,
//					es_nroestableci ¿id2?,
$sql =
	"SELECT ¿es_nombre?,
					art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, es_localidad) ¿domicilio?,
					pv_descripcion ¿provincia?,
					art.hys.get_nombre_preventor_estab(em_cuit, es_nroestableci) ¿preventor?,
					webart.get_fecha_ult_vis_establec(em_cuit, es_nroestableci) ¿fechaultvisita?
		 FROM art.cpv_provincias, afi.aem_empresa, afi.aco_contrato, afi.aes_establecimiento
		WHERE co_contrato = es_contrato
			AND em_id = co_idempresa
			AND pv_codigo = es_provincia
			AND es_contrato = :contrato
			AND es_fechabaja IS NULL
 ORDER BY 1";
$grilla = new Grid();
//$grilla->addColumn(new Column("Editar RGRL", -1, true, false, -1, "btnEditar", "/modules/usuarios_registrados/agentes_comerciales/contratos_activos/abrir_ventana_rgrl.php?c=".$_REQUEST["id"]));
//$grilla->addColumn(new Column("Imprimir RGRL", -1, true, false, -1, "btnImprimirChico", "/modules/usuarios_registrados/agentes_comerciales/contratos_activos/imprimir_rgrl.php?c=".$_REQUEST["id"]));
$grilla->addColumn(new Column("Nombre"));
$grilla->addColumn(new Column("Domicilio"));
$grilla->addColumn(new Column("Provincia"));
$grilla->addColumn(new Column("Preventor"));
$grilla->addColumn(new Column("F. Última Visita"));
$grilla->setColsSeparator(true);
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRefreshIntoWindow(true);
$grilla->setRowsSeparator(true);
$grilla->setSql($sql);
$grilla->setUseTmpIframe(true);
$grilla->Draw();
?>
	</div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
</div>

<div class="TituloSeccion" style="display:block; font-size:9pt; width:730px;">Datos Siniestrales (Último Año)</div>
<div class="ContenidoSeccion" style="padding:4px; margin-left:4px; margin-top:4px; width:720px;">
	<div>
		<label style="margin-left:38px;">Cantidad de Siniestros Denunciados</label>
		<span><b><?= $row["CANTIDADSINIESTROSDENUNCIADOS"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:44px;">Cantidad de Siniestros Rechazados</label>
		<span><b><?= $row["CANTIDADSINIESTROSRECHAZADOS"]?></b></span>
	</div>
<?
/*
	<div style="margin-top:2px;">
		<label style="margin-left:189px;">Recupero de Siniestros</label>
		<span><b><?= $row["RECUPEROSINIESTROS"]?></b></span>
	</div>
*/
?>
	<div style="margin-top:2px;">
		<label>Cantidad de Demandas Judiciales Iniciadas</label>
		<span><b><?= $row["CANTDEMJUDICINIC"]?></b></span>
	</div>
	<div style="margin-top:2px;">
		<a href="/consulta-siniestros/<?= $_REQUEST["id"]?>" style="margin-left:157px;">Ver Siniestros</a>
		<a href="/consulta-siniestros/<?= $_REQUEST["id"]?>"><img border="0" src="/images/link.png" style="vertical-align:-4px;" title="Ver Siniestros" /></a>
	</div>
</div>

<?
$params = array(":contrato" => $_REQUEST["id"]);
$sql =
	"SELECT 1
		 FROM aen_endoso
		WHERE en_motivo = '506'
			AND en_contrato = :contrato";
if (existeSql($sql, $params))
	$docFal = "En verificación";
else {
	// Se corre primero este SP que recupera la documentación faltante..
	$curs = null;
	$params = array(":contrato" => $_REQUEST["id"], ":usuario" => $_SESSION["usuario"]);
	$sql = "BEGIN art.afiliacion.do_documentacion_faltante(:contrato, :usuario); END;";
	DBExecSP($conn, $curs, $sql, $params, false);

	$params = array(":usuario" => $_SESSION["usuario"]);
	$sql =
		"SELECT df_contrato, tb_descripcion AS forma_juridica, df_contacto, df_firmante AS caracter_firmante, df_documentofalta AS documento
			 FROM tmp.tdf_documentacion_faltante
	LEFT JOIN art.ctb_tablas ON tb_clave = 'FJURI' AND tb_codigo = df_formajuridica
			WHERE df_usuario = :usuario
	 ORDER BY df_firmante, df_documentofalta";
	$stmt = DBExecSql($conn, $sql, $params);
	$hayDocFal = (DBGetRecordCount($stmt) > 1);
	$docFal = ($hayDocFal)?"<span style=\"cursor:hand;\" onClick=\"verDocumentacionFaltante();\" title=\"Ver Documentaciónn Faltante\">SÍ</span>":"NO";
}
?>
<!--
<div class="TituloSeccion" style="display:block; font-size:9pt; margin-top:8px; width:730px;">Varios</div>
<div class="ContenidoSeccion" style="padding:4px; margin-left:4px; margin-top:4px; width:720px;">
	<div>
		<label>Documentación Faltante Contrato</label>
		<span><b><?= $docFal?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:108px;">DDJJ Faltantes</label>
		<span><b><?= "???"?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:128px;">Novedades</label>
		<span><b><?= "???"?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:105px;">Capacitaciones</label>
		<span><b><?= "???"?></b></span>
	</div>
	<div style="margin-top:2px;">
		<label style="margin-left:83px;">Datos del Siniestro</label>
		<span><b><?= "???"?></b></span>
	</div>
</div>
-->
<div class="TituloSeccion" style="display:block; font-size:9pt; margin-top:8px; width:730px;">Certificados de Cobertura</div>
<div class="ContenidoSeccion" style="padding:4px; margin-left:4px; margin-top:4px; width:720px;">
	<div>
		<a href="/certificados-cobertura/<?= $_REQUEST["id"]?>">Emitir Certificado de Cobertura</a>
		<a href="/certificados-cobertura/<?= $_REQUEST["id"]?>"><img border="0" src="/images/link.png" style="margin-left:10px; vertical-align:-4px;" title="Emitir Certificado de Cobertura" /></a>
	</div>
</div>

<?
if ($_SESSION["entidad"] != 400) {
?>
<div class="TituloSeccion" style="display:block; font-size:9pt; margin-top:8px; width:730px;">Responsabilidad Civil</div>
<div class="ContenidoSeccion" style="padding:4px; margin-left:4px; margin-top:4px; width:720px;">
	<div>
		<a href="/responsabilidad-civil/<?= $_REQUEST["id"]?>">Emitir Póliza de RC</a>
		<a href="/responsabilidad-civil/<?= $_REQUEST["id"]?>"><img border="0" src="/images/link.png" style="margin-left:79px; vertical-align:-4px;" title="Emitir Póliza de RC" /></a>
	</div>
</div>
<?
}
?>
<div class="TituloSeccion" style="display:block; font-size:9pt; margin-top:8px; width:730px;">Denuncia de Siniestros</div>
<div class="ContenidoSeccion" style="padding:4px; margin-left:4px; margin-top:4px; width:720px;">
	<div>
		<a href="/denuncia-siniestros/<?= $_REQUEST["id"]?>">Carga de Denuncia de Siniestros</a>
		<a href="/denuncia-siniestros/<?= $_REQUEST["id"]?>"><img border="0" src="/images/link.png" style="vertical-align:-4px;" title="Carga de Denuncia de Siniestros" /></a>
	</div>
</div>

<div class="TituloSeccion" style="display:block; font-size:9pt; margin-top:8px; width:730px;">Estado de Cuenta</div>
<div class="ContenidoSeccion" style="padding:4px; margin-left:4px; margin-top:4px; width:720px;">
	<div>
		<a href="/estado-cuenta/ec/<?= $_REQUEST["id"]?>">Estado de Cuenta</a>
		<a href="/estado-cuenta/ec/<?= $_REQUEST["id"]?>"><img border="0" src="/images/link.png" style="margin-right:32px; vertical-align:-4px;" title="Ver PDF" /></a>
		<a href="/estado-cuenta/f817/<?= $_REQUEST["id"]?>">F817</a>
		<a href="/estado-cuenta/f817/<?= $_REQUEST["id"]?>"><img border="0" src="/images/link.png" style="margin-right:32px; vertical-align:-4px;" title="Ver PDF" /></a>
		<a href="/estado-cuenta/f801c/<?= $_REQUEST["id"]?>">F801C</a>
		<a href="/estado-cuenta/f801c/<?= $_REQUEST["id"]?>"><img border="0" src="/images/link.png" style="vertical-align:-4px;" title="Ver PDF" /></a>
	</div>
</div>

<div class="TituloSeccion" style="display:block; font-size:9pt; margin-top:8px; width:730px;">Legales</div>
<div class="ContenidoSeccion" style="padding:4px; margin-left:4px; margin-top:4px; width:720px;">
	<div>
		<a href="/legales/<?= $_REQUEST["id"]?>">Ver Datos Legales</a>
		<a href="/legales/<?= $_REQUEST["id"]?>"><img border="0" src="/images/link.png" style="vertical-align:-4px;" title="Ver Formularios" /></a>
	</div>
</div>

<div class="TituloSeccion" style="display:block; font-size:9pt; margin-top:8px; width:730px;">Formularios</div>
<div class="ContenidoSeccion" style="padding:4px; margin-left:4px; margin-top:4px; width:720px;">
	<div>
		<a href="/descarga-formularios">Ver Formularios</a>
		<a href="/descarga-formularios"><img border="0" src="/images/link.png" style="vertical-align:-4px;" title="Ver Formularios" /></a>
	</div>
</div>
<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />