<?
validarSesion(isset($_SESSION["isAgenteComercial"]));


if (isset($_REQUEST["vs"])) {
?>
	<div class="ContenidoSeccion" style="margin-top:10px; position:absolute;">
		<p align="center" style="color:#f00; font-size:18pt; font-weight:bold; margin-bottom:40px;">ATENCI�N</p>
		<p style="color:#000; font-size:10pt; font-weight:bold; margin:12px;">Se cre� una �nica opci�n para el manejo de "CONTRATOS ACTIVOS", desde donde usted podr� consultar su cartera y desde ah� realizar Certificados de Cobertura, Administraci�n de N�mina de Trabajadores, Estados de Cuenta, P�liza de Responsabilidad Civil y Denuncia de Siniestros.</p>
		<p style="margin-left:12px; margin-top:80px;"><a href="/index.php?pageid=26&mnsj"><img border="0" src="modules/usuarios_registrados/images/continuar.jpg"></a></p>
	</div>
<?
}
elseif (($_SESSION["entidad"] == 10891) and (isset($_REQUEST["mnsj"]))) {		// Consejo Profesional de Ciencias Econ�micas..
?>
	<div class="TituloSeccion" style="display:block; width:730px;">Bienvenido!</div>
	<div class="SubtituloSeccion" style="margin-top:8px;">Estimado Profesional:</div>
	<div class="ContenidoSeccion" style="margin-top:10px;">
		Desde aqu�, usted podr� gestionar �gilmente su relaci�n comercial con Provincia ART, durante las 24 hs., los 365 d�as del a�o.
		<p>A trav�s del men�, usted ingresar en la opci�n <a href="/index.php?pageid=27">SOLICITUD DE COTIZACIONES</a>, para solicitar cotizaciones, consultar el estado de las mismas, imprimir la carta de cotizaci�n y generar las solicitudes de afiliaci�n con sus formularios anexos. Tambi�n podr� consultar el <a href="/index.php?pageid=41">ESTADO DE CUENTA</a> (al�cuota, pagos, saldo) de su cartera.</p>
		<p>&gt; Para obtener m�s informaci�n, haga click aqu� para descargar la <a target="_blank" href="<?= "/functions/get_file.php?fl=".base64_encode(STORAGE_EXTRANET."descargables_web/guia_web_CPCE.pdf")?>">Gu�a de Uso  de esta plataforma</a>, modelo de <a target="_blank" href="<?= "/functions/get_file.php?fl=".base64_encode(STORAGE_EXTRANET."descargables_web/Oferta_de_servicios.pdf")?>">Oferta de Servicios</a> y la comunicaci�n sobre <a target="_blank" href="<?= "/functions/get_file.php?fl=".base64_encode(STORAGE_EXTRANET."descargables_web/lavado_de_activos.pdf")?>">Prevenci�n de Lavado de Activos.</a></p>
		<p>&gt; Le informamos los requisitos para formalizar la propuesta:</p>
		<ul>
			<li>Oferta de Servicios, con firma en original e inicializado en todas sus hojas.</li>
			<li>Comunicaci�n sobre Prevenci�n Lavado de Activos, firmado en original e inicializado en todas sus hojas .</li>
			<li>Fotocopia de DNI.</li>
			<li>Constancia de Inscripci�n en AFIP (Actualizada)</li>
			<li>Constancia de Inscripci�n en IIBB/Convenio Multilateral, etc.</li>
			<li>Constancia de CBU, en caso de acordar la percepci�n de honorarios mediante Pago Electr�nico. Tener presente que la firma debe estar certificada por banco en el formulario de Solicitud de Pago por Transferencia Electr�nica.</li>
		</ul>
		<p><i>En caso de dudas o consulta, comun�quese con su <a href="mailto:consejos@provart.com.ar">Ejecutivo de Cuenta</a> o dir�jase al espacio para consultas ubicado en el hall central del Consejo Profesional de Ciencias Econ�micas de la Ciudad Aut�noma de Buenos Aires (Viamonte 1549).</i></p> 
		<p><a href="/index.php?pageid=26"><img border="0" src="modules/usuarios_registrados/images/continuar.jpg"></a></p>
	</div>
<?
}
elseif ($_SESSION["nivel"] == 99) {		// Si tiene permiso para cambiar su canal - entidad - sucursal - vendedor..
?>
	<div class="TituloSeccion" style="display:block; width:730px;">Bienvenido agente comercial</div>
	<div class="SubtituloSeccion" style="margin-top:8px;">Acceso exclusivo agentes comerciales</div>
	<div class="ContenidoSeccion" style="margin-top:10px;">Gestione �gilmente su relaci�n comercial con Provincia ART y aproveche los beneficios de los nuevos aplicativos incorporados. En forma gratuita, las 24 horas, usted podr� realizar cotizaciones, gestionar solicitudes de afiliaci�n, consultar el estado de sus clientes y verificar su estado de cuentas.</div>
	<script src="/modules/usuarios_registrados/agentes_comerciales/js/clientes.js" type="text/javascript"></script>
	<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
	<form action="/modules/usuarios_registrados/agentes_comerciales/guardar_cambio_datos.php" id="formCambiarDatos" method="post" name="formCambiarDatos" target="iframeProcesando" onSubmit="return ValidarForm(formCambiarDatos)">
		<input id="guardar" name="guardar" type="hidden" value="t" />
		<div align="left" style="border:1px solid #0087c4; font-family:Trebuchet MS; margin-left:12px; position:relative; top:20px; width:440px;">
			<div style="margin-bottom:6px; margin-top:4px; position:relative;">
				<label for="canal" style="font-size:8pt; margin-left:25px;">Canal</label>
				<select id="canal" name="canal" style="border:1px solid #808080; color:#808080; font-family:Trebuchet MS; font-size:8pt; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; position:relative; top:0;" title="Canal" validar="true" onChange="cambiaCanal(this.value)"></select>
			</div>
			<div style="margin-bottom:6px; position:relative;">
				<label for="entidad" style="font-size:8pt; margin-left:14px;">Entidad</label>
				<select id="entidad" name="entidad" style="border:1px solid #808080; color:#808080; font-family:Trebuchet MS; font-size:8pt; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; position:relative;" title="Entidad" validar="true" onChange="cambiaEntidad(this.value)"></select>
			</div>
			<div style="margin-bottom:6px; position:relative;">
				<label for="sucursal" style="font-size:8pt; margin-left:11px;">Sucursal</label>
				<select id="sucursal" name="sucursal" style="border:1px solid #808080; color:#808080; font-family:Trebuchet MS; font-size:8pt; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; position:relative;"></select>
			</div>
			<div style="margin-bottom:16px; position:relative;">
				<label for="vendedor" style="font-size:8pt; margin-left:4px;">Vendedor</label>
				<select id="vendedor" name="vendedor" style="border:1px solid #808080; color:#808080; font-family:Trebuchet MS; font-size:8pt; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; position:relative;"></select>
			</div>
			<div style="margin-bottom:6px; position:relative;">
				<input class="botonGris" style="left:336px; position:relative;" type="submit" value="CAMBIAR">
			</div>
			<div id="divDatosCambiados" style="background-color:#5ec5ee; cursor:default; display:none; font-size:10pt; height:20px; left:20px; overflow:hidden; padding-left:4px; padding-right:4px; position:absolute; top:128px;">Datos cambiados correctamente.</div>
		</div>
	</form>
	<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "canal";
$RCparams = array();
$RCquery =
	"SELECT DISTINCT ca_id ID, ca_codigo || ' - ' || ca_descripcion detalle
						 FROM aca_canal
					  WHERE ca_fechabaja IS NULL
				  ORDER BY 2";
$RCselectedItem = $_SESSION["canal"];
FillCombo();

$RCfield = "entidad";
$RCparams = array(":idcanal" => $_SESSION["canal"]);
$RCquery =
	"SELECT DISTINCT en_id ID, en_codbanco || ' - ' || en_nombre detalle
						 FROM xen_entidad
					   WHERE en_idcanal = :idcanal
							AND en_fechabaja IS NULL
				  ORDER BY 2";
$RCselectedItem = $_SESSION["entidad"];
FillCombo();

$RCfield = "sucursal";
$RCparams = array(":identidad" => $_SESSION["entidad"]);
$RCquery =
	"SELECT su_id ID, su_codsucursal || ' - ' || su_descripcion detalle
		FROM asu_sucursal
	 WHERE su_fechabaja IS NULL
		  AND su_identidad = :identidad
ORDER BY 2";
$RCselectedItem = $_SESSION["sucursal"];
FillCombo(true, 1000);

$RCfield = "vendedor";
$RCparams = array(":identidad" => $_SESSION["entidad"]);
$RCquery =
	"SELECT ve_id ID, ve_vendedor || ' - ' || ve_nombre detalle
		FROM xve_vendedor, xev_entidadvendedor
	 WHERE ev_idvendedor = ve_id
		  AND ve_fechabaja IS NULL
		  AND ev_fechabaja IS NULL
		  AND ev_identidad = :identidad 
 ORDER BY 2";
$RCselectedItem = $_SESSION["vendedor"];
FillCombo(true, 1000);
?>
document.getElementById('canal').focus();
	</script>
<?
}
else {
	$params = array(":identidad" => $_SESSION["entidad"], ":idusuarioweb" => $_SESSION["idUsuario"]);
	$sql = "SELECT art.cotizacion.get_mailavisoweb(:idusuarioweb, :identidad) FROM DUAL";
	$emailEjecutivoCuentas = ValorSql($sql, "", $params);
?>
	<div class="TituloSeccion" style="display:block; width:730px;">Bienvenido agente comercial</div>
	<div class="SubtituloSeccion" style="margin-top:8px;">Acceso exclusivo agentes comerciales</div>
	<div class="ContenidoSeccion" style="margin-top:10px;">Gestione �gilmente su relaci�n comercial con Provincia ART y aproveche los beneficios de los nuevos aplicativos incorporados. En forma gratuita, las 24 horas, usted podr� realizar cotizaciones, gestionar solicitudes de afiliaci�n, consultar el estado de sus clientes y verificar su estado de cuentas.</div>
	<div class="ContenidoSeccion" style="margin-bottom:12px; margin-top:10px;">
		<hr></hr>
		<b>Resoluci�n UIF 11/2011</b><br />
		<b>DDJJ de personas expuestas pol�ticamente</b>
		<div style="margin-top:6px;">Con la sanci�n de la Resoluci�n 11/2011, la Unidad de Informaci�n Financiera (UIF) exige la presentaci�n de una declaraci�n jurada informando el car�cter de Persona Expuesta Pol�ticamente (PEP) a todos los clientes, requirentes, donantes y aportantes de sujetos obligados por las normas de prevenci�n del lavado de activos (bancos, aseguradoras, etc).</div>
		<div style="margin-top:6px;">En consecuencia, el proceso de afiliaci�n de clientes incorpora un nuevo requisito: la presentaci�n del Formulario de Declaraci�n Jurada de Personas Expuestas Pol�ticamente, el cual se generar� junto a cada solicitud de afiliaci�n. Luego remite el original a Provincia ART junto con la restante documentaci�n de la afiliaci�n (Carlos Pellegrini 91 � 4� piso Afiliaciones � C1009ABA � Ciudad de Buenos Aires). <a href="modules/varios/descargables/Guia_DDJJ_PEP.pdf">Click aqu� para m�s informaci�n</a></div>
		<div style="margin-top:6px;">Esta informaci�n permitir� determinar las acciones de monitoreo sobre las operaciones de los clientes PEP, para evitar multas de los organismos de control.</div>
		<div style="margin-top:6px;">En caso de dudas o consultas sobre este tema, cont�ctese con su <a href="mailto:<?= $emailEjecutivoCuentas?>">Ejecutivo de Cuentas</a>.</div>
		<div style="margin-top:6px;"><b>></b> <a target="_blank" href="modules/varios/descargables/Resolucion_UIF_11_2011.pdf">Ver Resoluci�n UIF 11/2011, publicada en el Bolet�n Oficial del 14 de enero de 2011</a>.</div>
	</div>
<?
}
?>
<object classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" id="banner1HomePage" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" border="0" width="240" height="110" style="left:0px; position:absolute; top:320px;">
	<param name="movie" value="/images/banner1.swf">
	<param name="quality" value="High">
	<embed src="/images/banner1.swf" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" name="obj3" width="240" height="110" quality="High">
</object>
<object classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" id="banner2HomePage" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" border="0" width="240" height="110" style="left:246px; position:absolute; top:320px;">
	<param name="movie" value="/images/banner2.swf">
	<param name="quality" value="High">
	<embed src="/images/banner2.swf" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" name="obj1" width="240" height="110" quality="High">
</object>
<object classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" id="banner3HomePage" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" border="0" width="240" height="110" style="left:512px; position:absolute; top:320px;">
	<param name="movie" value="/images/banner3.swf">
	<param name="quality" value="High">
	<embed src="/images/banner3.swf" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" name="obj2" width="240" height="110" quality="High">
</object>