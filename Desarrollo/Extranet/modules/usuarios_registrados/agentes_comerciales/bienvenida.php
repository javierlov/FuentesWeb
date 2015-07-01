<?
validarSesion(isset($_SESSION["isAgenteComercial"]));

require_once("bienvenida_combos.php");

if (isset($_REQUEST["vs"])) {
?>
	<div class="ContenidoSeccion" style="margin-top:10px; position:absolute;">
		<p align="center" style="color:#f00; font-size:18pt; font-weight:bold; margin-bottom:40px;">ATENCIÓN</p>
		<p style="color:#000; font-size:10pt; font-weight:bold; margin:12px;">Se creó una única opción para el manejo de "CONTRATOS ACTIVOS", desde donde usted podrá consultar su cartera y desde ahí realizar Certificados de Cobertura, Administración de Nómina de Trabajadores, Estados de Cuenta, Póliza de Responsabilidad Civil y Denuncia de Siniestros.</p>
		<p style="margin-left:12px; margin-top:80px;"><a href="/bienvenida/2"><img border="0" src="/modules/usuarios_registrados/images/continuar.jpg" /></a></p>
	</div>
<?
}
elseif (($_SESSION["entidad"] == 10891) and (isset($_REQUEST["mnsj"]))) {		// Consejo Profesional de Ciencias Económicas..
?>
	<div class="TituloSeccion" style="display:block; width:730px;">Bienvenido!</div>
	<div class="SubtituloSeccion" style="margin-top:8px;">Estimado Profesional:</div>
	<div class="ContenidoSeccion" style="margin-top:10px;">
		Desde aquí, usted podrá gestionar ágilmente su relación comercial con Provincia ART, durante las 24 hs., los 365 días del año.
		<p>A través del menú, usted ingresar en la opción <a class="linkSubrayado" href="/buscar-cotizacion">SOLICITUD DE COTIZACIONES</a>, para solicitar cotizaciones, consultar el estado de las mismas, imprimir la carta de cotización y generar las solicitudes de afiliación con sus formularios anexos. También podrá consultar el <a class="linkSubrayado" href="/estado-cuenta">ESTADO DE CUENTA</a> (alícuota, pagos, saldo) de su cartera.</p>
		<p>&gt; Para obtener más información, haga clic aquí para descargar la <a class="linkSubrayado" target="_blank" href="<?= getFile(STORAGE_EXTRANET."descargables_web/guia_web_CPCE.pdf")?>">Guía de Uso de esta plataforma</a>, modelo de <!--<a class="linkSubrayado" target="_blank" href="<?= getFile(STORAGE_EXTRANET."descargables_web/Oferta_de_servicios.pdf")?>">-->Oferta de Servicios<!--</a>--> y la comunicación sobre <a class="linkSubrayado" target="_blank" href="<?= getFile(STORAGE_EXTRANET."descargables_web/lavado_de_activos.pdf")?>">Prevención de Lavado de Activos.</a></p>
		<p>&gt; Le informamos los requisitos para formalizar la propuesta:</p>
		<ul>
			<li>Oferta de Servicios, con firma en original e inicializado en todas sus hojas.</li>
			<li>Comunicación sobre Prevención Lavado de Activos, firmado en original e inicializado en todas sus hojas .</li>
			<li>Fotocopia de DNI.</li>
			<li>Constancia de Inscripción en AFIP (Actualizada)</li>
			<li>Constancia de Inscripción en IIBB/Convenio Multilateral, etc.</li>
			<li>Constancia de CBU, en caso de acordar la percepción de honorarios mediante Pago Electrónico. Tener presente que la firma debe estar certificada por banco en el formulario de Solicitud de Pago por Transferencia Electrónica.</li>
		</ul>
		<p><i>En caso de dudas o consulta, comuníquese con su <a class="linkSubrayado" href="mailto:consejos@provart.com.ar">Ejecutivo de Cuenta</a> o diríjase al espacio para consultas ubicado en el hall central del Consejo Profesional de Ciencias Económicas de la Ciudad Autónoma de Buenos Aires (Viamonte 1549).</i></p>
		<p><a href="/bienvenida"><img border="0" src="/modules/usuarios_registrados/images/continuar.jpg" /></a></p>
	</div>
	<div style="color:#676767; font-size:8pt; margin-top:5px; padding-left:12px; padding-right:12px; width:700px;">
		<table cellpadding="0" cellspacing="0" style="height:58px;">
			<tr>
				<td style="padding-left: 15px; padding-right:3px; background-image:url('images/fnd.bmp'); width:350px;">
					<font color="00539B"><b>REFORMA DE LA LRT - LEY 26773</b></font><br><b>Principales características</b> <a class="linkSubrayado" target="_blank" href="download/P_ART_Actualizacion_LRT_v3_ej.pdf" style="color:#00539b;">Ver [+]</a>
				</td>
				<td style="padding-left: 15px; padding-right:3px; background-image:url('images/fnd.bmp'); width:350px;">
					<font color="00539B"><b>CALCULO DE LA BASE IMPONIBLE</b></font><br><b>Ley 26.773 – Artículo 10</b> <a class="linkSubrayado" href="/acceso-exclusivo-agentes-comerciales/noticia/noticia1.html" style="color:#00539b;">Ver [+]</a>
				</td>
			</tr>
		</table>
	</div>	
<?
}
elseif ($_SESSION["nivel"] == 99) {		// Si tiene permiso para cambiar su canal - entidad - sucursal - vendedor..
?>
	<style>
		#canal {position:relative; top:0;}
		#entidad {position:relative;}
		#sucursal {position:relative;}
		#vendedor {position:relative;}
	</style>
	<div class="TituloSeccion" style="display:block; width:730px;">Bienvenido agente comercial</div>
	<div class="SubtituloSeccion" style="margin-top:8px;">Acceso exclusivo agentes comerciales</div>
	<div class="ContenidoSeccion" style="margin-top:10px;">Gestione ágilmente su relación comercial con Provincia ART y aproveche los beneficios de los nuevos aplicativos incorporados. En forma gratuita, las 24 horas, usted podrá realizar cotizaciones, gestionar solicitudes de afiliación, consultar el estado de sus clientes y verificar su estado de cuentas.</div>
	<script src="/modules/usuarios_registrados/agentes_comerciales/js/clientes.js" type="text/javascript"></script>
	<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
	<form action="/modules/usuarios_registrados/agentes_comerciales/guardar_cambio_datos.php" id="formCambiarDatos" method="post" name="formCambiarDatos" target="iframeProcesando" onSubmit="return ValidarForm(formCambiarDatos)">
		<input id="guardar" name="guardar" type="hidden" value="t" />
		<div align="left" style="border:1px solid #00539B; font-family:Trebuchet MS; margin-left:12px; position:relative; top:20px; width:488px;">
			<div style="margin-bottom:6px; margin-top:4px; position:relative;">
				<label for="canal" style="font-size:8pt; margin-left:25px;">Canal</label>
				<?= $comboCanal->draw();?>
			</div>
			<div style="margin-bottom:6px; position:relative;">
				<label for="entidad" style="font-size:8pt; margin-left:14px;">Entidad</label>
				<?= $comboEntidad->draw();?>
				<input class="btnLupa" style="vertical-align:-8px;" title="Buscar Entidad" type="button" onClick="abrirVentanaBusquedaEntidad(document.getElementById('canal').value);" />
			</div>
			<div style="margin-bottom:6px; position:relative;">
				<label for="sucursal" style="font-size:8pt; margin-left:11px;">Sucursal</label>
				<?= $comboSucursal->draw();?>
			</div>
			<div style="margin-bottom:16px; position:relative;">
				<label for="vendedor" style="font-size:8pt; margin-left:4px;">Vendedor</label>
				<?= $comboVendedor->draw();?>
			</div>
			<div align="right" style="margin-bottom:6px; position:relative;">
				<input class="btnCambiar" style="margin-right:16px; position:relative;" type="submit" value="" />
			</div>
			<div id="divDatosCambiados" style="background-color:#0f539c; color:#fff; cursor:default; display:none; font-size:10pt; height:20px; left:20px; overflow:hidden; padding-left:4px; padding-right:4px; position:absolute; top:120px;">Datos cambiados correctamente.</div>
		</div>
	</form>
	<div style="padding-left:12px; padding-right:12px; font-size:8pt; color:#676767; margin-top:25px; width:700px;">
		<table cellpadding="0" cellspacing="0" style="height:58px;">
			<tr>
				<td style="padding-left: 15px; padding-right:3px; background-image:url('images/fnd.bmp'); width:350px;">
					<font color="00539B"><b>REFORMA DE LA LRT - LEY 26773</b></font><br><b>Principales características</b> <a class="linkSubrayado" target="_blank" href="/download/P_ART_Actualizacion_LRT_v3_ej.pdf" style="color:#00539b;">Ver [+]</a>
				</td>
				<td style="padding-left: 15px; padding-right:3px; background-image:url('images/fnd.bmp'); width:350px;">
					<font color="00539B"><b>CALCULO DE LA BASE IMPONIBLE</b></font><br><b>Ley 26.773 – Artículo 10</b> <a class="linkSubrayado" href="/acceso-exclusivo-agentes-comerciales/noticia/noticia1.html" style="color:#00539b;">Ver [+]</a>
				</td>
			</tr>
		</table>
	</div>		
<?
}
else {
	$params = array(":identidad" => $_SESSION["entidad"], ":idusuarioweb" => $_SESSION["idUsuario"]);
	$sql = "SELECT art.cotizacion.get_mailavisoweb(:idusuarioweb, :identidad) FROM DUAL";
	$emailEjecutivoCuentas = valorSql($sql, "", $params);
?>
	<div class="TituloSeccion" style="display:block; width:730px;">Bienvenido agente comercial</div>
	<div class="SubtituloSeccion" style="margin-top:8px;">Acceso exclusivo agentes comerciales</div>
	<div class="ContenidoSeccion" style="margin-top:10px;">Gestione ágilmente su relación comercial con Provincia ART y aproveche los beneficios de los nuevos aplicativos incorporados. En forma gratuita, las 24 horas, usted podrá realizar cotizaciones, gestionar solicitudes de afiliación, consultar el estado de sus clientes y verificar su estado de cuentas.</div>
	<div style="padding-left:12px; padding-right:12px; font-size:8pt; color:#676767; margin-top:0px; width:700px;">
		<table cellpadding="0" cellspacing="0" style="height:58px;">
			<tr>
				<td style="padding-left: 15px; padding-right:3px; background-image:url('images/fnd.bmp'); width:350px;">
					<font color="00539B"><b>REFORMA DE LA LRT - LEY 26773</b></font><br><b>Principales características</b> <a class="linkSubrayado" target="_blank" href="/download/P_ART_Actualizacion_LRT_v3_ej.pdf" style="color:#00539b;">Ver [+]</a>
				</td>
				<td style="padding-left: 15px; padding-right:3px; background-image:url('images/fnd.bmp'); width:350px;">
					<font color="00539B"><b>CALCULO DE LA BASE IMPONIBLE</b></font><br><b>Ley 26.773 – Artículo 10</b> <a class="linkSubrayado" href="/acceso-exclusivo-agentes-comerciales/noticia/noticia1.html" style="color:#00539b;">Ver [+]</a>
				</td>
			</tr>
		</table>
	</div>
	<div class="ContenidoSeccion" style="margin-bottom:12px; margin-top:10px;">
		<hr />
		<b>Resolución UIF 52/2012</b><br />
		<b>DDJJ de personas expuestas políticamente</b>
		<div style="margin-top:6px;">Con la sanción de la Resolución 52/2012, la Unidad de Información Financiera (UIF) exige la presentación de una declaración jurada informando el carácter de Persona Expuesta Políticamente (PEP) a todos los clientes, requirentes, donantes y aportantes de sujetos obligados por las normas de prevención del lavado de activos (bancos, aseguradoras, etc).</div>
		<div style="margin-top:6px;">En consecuencia, el proceso de afiliación de clientes mantiene el requisito de presentación del Formulario de Declaración Jurada de Personas Expuestas Políticamente, el cual se generará junto a cada solicitud de afiliación. Luego remite el original a Provincia ART junto con la restante documentación de la afiliación (Carlos Pellegrini 91 – 4º piso Afiliaciones – C1009ABA – Ciudad de Buenos Aires). <a class="linkSubrayado" target="_blank" href="/modules/varios/descargables/Guia_DDJJ_PEP2012.pdf">Clic aquí para más información</a></div>
		<div style="margin-top:6px;">Esta información permitirá determinar las acciones de monitoreo sobre las operaciones de los clientes PEP, dar cumplimiento a la normativa vigente.</div>
		<div style="margin-top:6px;">En caso de dudas o consultas sobre este tema, contáctese con su <a class="linkSubrayado" href="mailto:<?= $emailEjecutivoCuentas?>">Ejecutivo de Cuentas</a>.</div>
		<div style="margin-top:6px;"><b>></b> <a class="linkSubrayado" target="_blank" href="http://infoleg.gov.ar/infolegInternet/anexos/195000-199999/195785/norma.htm">Ver Resolución UIF 52/2012, publicada en el Boletín Oficial del 3 de abril de 2012</a>.</div>
	</div>
<?
}
?>