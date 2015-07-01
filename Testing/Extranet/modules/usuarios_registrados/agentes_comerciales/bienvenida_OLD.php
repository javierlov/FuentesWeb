<?
validarSesion(isset($_SESSION["isAgenteComercial"]));


if (isset($_REQUEST["vs"])) {
?>
	<div class="ContenidoSeccion" style="margin-top:10px; position:absolute;">		
		<p align="center" style="color:#000; font-size:10pt; font-weight:bold; margin:10px;"><span style="color:#FF0000">IMPORTANTE</span> Nueva Ley de Riesgos del Trabajo</p>
		<p style="color:#000; font-size:8pt; margin:8px;">Detallamos a continuaci�n los aspectos m�s destacados acerca de la sanci�n de la nueva Ley N� 26773 (de reforma de la Ley N� 24.557 sobre los Riesgos del Trabajo), con entrada en vigencia el 01/11/2012:</p>
		<p style="color:#000; font-size:8pt; margin:12px;"><span style="color:#00A3E4"><b>></b></span> Esta reforma apunta a la suficiencia, accesibilidad y automaticidad de las prestaciones dinerarias y en especie que recibir�n los trabajadores como consecuencia de un accidente de trabajo o enfermedad profesional y a la previsibilidad de los empleadores.</p>
		<p style="color:#000; font-size:8pt; margin:12px;"><span style="color:#00A3E4"><b>></b></span> Se incrementan las prestaciones dinerarias a la vez que se establece como opci�n excluyente, cobrar dichas prestaciones o reclamar con fundamento en otros sistemas de responsabilidad. Las prestaciones establecidas en esta ley o las determinadas en otros sistemas de responsabilidad, no son acumulables.</p>
		<p style="color:#000; font-size:8pt; margin:12px;"><span style="color:#00A3E4"><b>></b></span> Tambi�n se han incrementado los m�nimos y las sumas fijas que percibir�n los trabajadores en caso de incapacidades permanentes, gran invalidez o muerte, que se ajustar�n por el �ndice RIPTE en los plazos previstos en el sistema previsional.</p>
		<p style="color:#000; font-size:8pt; margin:8px;">Para mantener el nivel de calidad de nuestras prestaciones y cumplir con las mayores exigencias establecidas por esta reforma, a partir del 01/11/2012 incrementaremos las tarifas de la siguiente manera:</p>
		<p style="color:#000; font-size:8pt; margin:12px;"><span style="color:#00A3E4"><b>::</b></span> Empresas menores a 25 c�pitas: Aumento del 10% con vigencia 01/11/2012 + aumento del 9.70% con vigencia 01/01/2013.</p>
		<p style="color:#000; font-size:8pt; margin:12px;"><span style="color:#00A3E4"><b>::</b></span> Empresas de 25 c�pitas o m�s: Aumento del 19.70% con vigencia 01/11/2012</p>
		<p style="color:#000; font-size:8pt; margin:12px;"><span style="color:#00A3E4"><b>::</b></span> El aporte al FFEP se mantiene en los valores actuales de $0,60 por trabajador. </p>
		<p style="color:#000; font-size:8pt; margin:8px; font-weight:bold;"><i>Sobre este asunto, nos encontramos enviando las cartas documento correspondientes a toda nuestra cartera. Cabe destacar que todas las aseguradoras de riesgos del trabajo se encuentran accionando de la misma manera.</i></p>  
		<p style="color:#00A3E4; font-size:9pt; margin:8px;">Para mayor informaci�n, comun�quese con su <a href="mailto:<?= $_SESSION["emailAvisoArt"]?>">Ejecutivo de Cuenta</a> o con nuestro Centro de Atenci�n al Cliente a trav�s de la l�nea gratuita 0800-333-1278.</p>
		<p style="margin-left:12px; margin-top:15px;"><a href="/index.php?pageid=26&mnsj"><img border="0" src="modules/usuarios_registrados/images/continuar.jpg"></a></p>
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
		<p>&gt; Para obtener m�s informaci�n, haga click aqu� para descargar la <a target="_blank" href="<?= "/functions/get_file.php?fl=".base64_encode(STORAGE_EXTRANET."descargables_web/guia_web_CPCE.pdf")?>">Gu�a de Uso  de esta plataforma</a>, modelo de <!--<a target="_blank" href="<?= "/functions/get_file.php?fl=".base64_encode(STORAGE_EXTRANET."descargables_web/Oferta_de_servicios.pdf")?>">-->Oferta de Servicios<!--</a>--> y la comunicaci�n sobre <a target="_blank" href="<?= "/functions/get_file.php?fl=".base64_encode(STORAGE_EXTRANET."descargables_web/lavado_de_activos.pdf")?>">Prevenci�n de Lavado de Activos.</a></p>
		<p>&gt; Le informamos los requisitos para formalizar la propuesta:</p>
		<ul>
			<li>Oferta de Servicios, con firma en original e inicializado en todas sus hojas.</li>
			<li>Comunicaci�n sobre Prevenci�n Lavado de Activos, firmado en original e inicializado en todas sus hojas.</li>
			<li>Fotocopia de DNI.</li>
			<li>Constancia de Inscripci�n en AFIP (Actualizada)</li>
			<li>Constancia de Inscripci�n en IIBB/Convenio Multilateral, etc.</li>
			<li>Constancia de CBU, en caso de acordar la percepci�n de honorarios mediante Pago Electr�nico. Tener presente que la firma debe estar certificada por banco en el formulario de Solicitud de Pago por Transferencia Electr�nica.</li>
		</ul>
		<p><i>En caso de dudas o consulta, comun�quese con su <a href="mailto:consejos@provart.com.ar">Ejecutivo de Cuenta</a> o dir�jase al espacio para consultas ubicado en el hall central del Consejo Profesional de Ciencias Econ�micas de la Ciudad Aut�noma de Buenos Aires (Viamonte 1549).</i></p> 
		<p><a href="/index.php?pageid=26"><img border="0" src="modules/usuarios_registrados/images/continuar.jpg"></a></p>
	</div>
	<div style="padding-left:12px; padding-right:12px; font-size:8pt; color:#676767; margin-top:5px; width:700px;">
		<table cellpadding="0" cellspacing="0" style="height:58px;">
			<tr>
				<td style="padding-left: 15px; padding-right:3px; background-image:url('images/fnd.bmp'); width:350px;">
					<font color="00A4E4"><b>REFORMA DE LA LRT - LEY 26773</b></font><br><b>Principales caracter�sticas</b> <a target="_blank" href="download/P_ART_Actualizacion_LRT_v3_ej.pdf" style="text-decoration:none">Ver [+]</a>
				</td>
				<td style="padding-left: 15px; padding-right:3px; background-image:url('images/fnd.bmp'); width:350px;">
					<font color="00A4E4"><b>CALCULO DE LA BASE IMPONIBLE</b></font><br><b>Ley 26.773 � Art�culo 10</b> <a href="/index.php?pageid=93&amp;pg=noticia1.html" style="text-decoration:none">Ver [+]</a>
				</td>
			</tr>
		</table>
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
	<div style="padding-left:12px; padding-right:12px; font-size:8pt; color:#676767; margin-top:25px; width:700px;">
		<table cellpadding="0" cellspacing="0" style="height:58px;">
			<tr>
				<td style="padding-left: 15px; padding-right:3px; background-image:url('images/fnd.bmp'); width:350px;">
					<font color="00A4E4"><b>REFORMA DE LA LRT - LEY 26773</b></font><br><b>Principales caracter�sticas</b> <a target="_blank" href="/download/P_ART_Actualizacion_LRT_v3_ej.pdf" style="text-decoration:none">Ver [+]</a>
				</td>
				<td style="padding-left: 15px; padding-right:3px; background-image:url('images/fnd.bmp'); width:350px;">
					<font color="00A4E4"><b>CALCULO DE LA BASE IMPONIBLE</b></font><br><b>Ley 26.773 � Art�culo 10</b> <a href="/index.php?pageid=93&amp;pg=noticia1.html" style="text-decoration:none">Ver [+]</a>
				</td>
			</tr>
		</table>
	</div>			

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

	<div style="padding-left:12px; padding-right:12px; font-size:8pt; color:#676767; margin-top:0px; width:700px;">
		<table cellpadding="0" cellspacing="0" style="height:58px;">
			<tr>
				<td style="padding-left: 15px; padding-right:3px; background-image:url('images/fnd.bmp'); width:350px;">
					<font color="00A4E4"><b>REFORMA DE LA LRT - LEY 26773</b></font><br><b>Principales caracter�sticas</b> <a target="_blank" href="/download/P_ART_Actualizacion_LRT_v3_ej.pdf" style="text-decoration:none">Ver [+]</a>
				</td>
				<td style="padding-left: 15px; padding-right:3px; background-image:url('images/fnd.bmp'); width:350px;">
					<font color="00A4E4"><b>CALCULO DE LA BASE IMPONIBLE</b></font><br><b>Ley 26.773 � Art�culo 10</b> <a href="/index.php?pageid=93&amp;pg=noticia1.html" style="text-decoration:none">Ver [+]</a>
				</td>
			</tr>
		</table>
	</div>	
	<div class="ContenidoSeccion" style="margin-bottom:12px; margin-top:10px;">
		<hr>
		<b>Resoluci�n UIF 52/2012</b><br/>
		<b>DDJJ de personas expuestas pol�ticamente</b>
		<div style="margin-top:6px;">Con la sanci�n de la Resoluci�n 52/2012, la Unidad de Informaci�n Financiera (UIF) exige la presentaci�n de una declaraci�n jurada informando el car�cter de Persona Expuesta Pol�ticamente (PEP) a todos los clientes, requirentes, donantes y aportantes de sujetos obligados por las normas de prevenci�n del lavado de activos (bancos, aseguradoras, etc).</div>
		<div style="margin-top:6px;">En consecuencia, el proceso de afiliaci�n de clientes mantiene el requisito de presentaci�n del Formulario de Declaraci�n Jurada de Personas Expuestas Pol�ticamente, el cual se generar� junto a cada solicitud de afiliaci�n. Luego remite el original a Provincia ART junto con la restante documentaci�n de la afiliaci�n (Carlos Pellegrini 91 � 4� piso Afiliaciones � C1009ABA � Ciudad de Buenos Aires). <a target="_blank" href="modules/varios/descargables/Guia_DDJJ_PEP2012.pdf">Click aqu� para m�s informaci�n</a></div>
		<div style="margin-top:6px;">Esta informaci�n permitir� determinar las acciones de monitoreo sobre las operaciones de los clientes PEP, dar cumplimiento a la normativa vigente.</div>
		<div style="margin-top:6px;">En caso de dudas o consultas sobre este tema, cont�ctese con su <a href="mailto:<?= $emailEjecutivoCuentas?>">Ejecutivo de Cuentas</a>.</div>
		<div style="margin-top:6px;"><b>></b> <a target="_blank" href="http://infoleg.gov.ar/infolegInternet/anexos/195000-199999/195785/norma.htm">Ver Resoluci�n UIF 52/2012, publicada en el Bolet�n Oficial del 3 de abril de 2012</a>.</div>
	</div>
<?
}
?>
<!--<object classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" id="banner1HomePage" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" border="0" width="240" height="110" style="left:0px; position:absolute; top:320px;">
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
</object>-->