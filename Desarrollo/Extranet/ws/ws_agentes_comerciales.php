<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/nusoap/lib/nusoap.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/login_agente_comercial.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/solicitud_cotizacion.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/tablas_referencia.php");


function cambiarClave($usuario, $claveVieja, $claveNueva) {
	$login = new LoginAgenteComercial();

	$result = $login->getLoginResult($usuario, $claveVieja, true);

	if ($result == "")
		return $login->setClave($usuario, $claveNueva);
	else
		return $result;
}

function cargarSolicitudCotizacion($datosSolicitudCotizacion) {
	$login = new LoginAgenteComercial();
	$solicitudCotizacion = new SolicitudCotizacion();

	$result = $login->validarToken($datosSolicitudCotizacion["token"]);
	if ($result != "")
		return $result;

	$result = $solicitudCotizacion->validateSolicitud($datosSolicitudCotizacion);
	if ($result != "")
		return $result;

	return $solicitudCotizacion->saveSolicitud($datosSolicitudCotizacion);
}

function login($usuario, $clave) {
	$login = new LoginAgenteComercial();
	$result = $login->getLoginResult($usuario, $clave);

	if ($result == "")
		return $login->getToken($usuario);
	else
		return $result;
}

function verNombresTablasReferencia($token) {
	$login = new LoginAgenteComercial();
	$tablasReferencia = new TablasReferencia();

	$result = $login->validarToken($token);
	if ($result != "")
		return $result;

	return $tablasReferencia->getNombresTablasReferencia();
}

function verTablasReferencia($token, $tabla, $detalle) {
	$login = new LoginAgenteComercial();
	$tablasReferencia = new TablasReferencia();

	$result = $login->validarToken($token);
	if ($result != "")
		return $result;

	return $tablasReferencia->getTablasReferencia($tabla, $detalle);
}


error_reporting(E_ALL ^ E_WARNING);
SetDateFormatOracle("DD/MM/YYYY HH24:MI:SS");

$url = '/ws/ws_agentes_comerciales.php';
$server = new soap_server();
$server->configureWSDL('WS Provincia A.R.T.', $url);
$server->wsdl->schemaTargetNamespace = $url;

$server->wsdl->addComplexType("EstablecimientoSolicitudCotizacion",
															"complexType",
															"struct",
															"all",
															"",
															array("idProvincia" => array("name" => "provincia", "type" => "xsd:int"),
																		"idLocalidad" => array("name" => "localidad", "type" => "xsd:int"),
																		"idActividad" => array("name" => "actividad", "type" => "xsd:int"),
																		"idCiiu" => array("name" => "ciiu", "type" => "xsd:int"),
																		"cantidadTrabajadores" => array("name" => "cantidadTrabajadores", "type" => "xsd:int")));

$server->wsdl->addComplexType("EstablecimientosSolicitudCotizacion",
															"complexType",
															"array",
															"",
															"SOAP-ENC:Array",
															array(),
															array(array("ref" => "SOAP-ENC:arrayType", "wsdl:arrayType" => "tns:EstablecimientoSolicitudCotizacion[]")),
															"tns:EstablecimientoSolicitudCotizacion");

$server->wsdl->addComplexType("SolicitudCotizacion",
															"complexType",
															"struct",
															"all",
															"",
															array("token" => array("name" => "token", "type" => "xsd:string"),
																		"cuit" => array("name" => "cuit", "type" => "xsd:string"),
																		"razonSocial" => array("name" => "razonSocial", "type" => "xsd:string"),
																		"contacto" => array("name" => "contacto", "type" => "xsd:string"),
																		"telefono" => array("name" => "telefono", "type" => "xsd:string"),
																		"email" => array("name" => "email", "type" => "xsd:string"),
																		"holding" => array("name" => "holding", "type" => "xsd:int"),
																		"ciiu1" => array("name" => "ciiu1", "type" => "xsd:int"),
																		"totalTrabajadores1" => array("name" => "totalTrabajadores1", "type" => "xsd:int"),
																		"masaSalarial1" => array("name" => "masaSalarial1", "type" => "xsd:float"),
																		"ciiu2" => array("name" => "ciiu2", "type" => "xsd:int"),
																		"totalTrabajadores2" => array("name" => "totalTrabajadores2", "type" => "xsd:int"),
																		"masaSalarial2" => array("name" => "masaSalarial2", "type" => "xsd:float"),
																		"ciiu3" => array("name" => "ciiu3", "type" => "xsd:int"),
																		"totalTrabajadores3" => array("name" => "totalTrabajadores3", "type" => "xsd:int"),
																		"masaSalarial3" => array("name" => "masaSalarial3", "type" => "xsd:float"),
																		"periodo" => array("name" => "periodo", "type" => "xsd:string"),
																		"actividadReal" => array("name" => "actividadReal", "type" => "xsd:string"),
																		"statusSrt" => array("name" => "statusSrt", "type" => "xsd:int"),
																		"artAnterior" => array("name" => "artAnterior", "type" => "xsd:int"),
																		"statusBcra" => array("name" => "statusBcra", "type" => "xsd:int"),
																		"datosCompetencia" => array("name" => "datosCompetencia", "type" => "xsd:string"),
																		"soloPagoTotalMensual" => array("name" => "soloPagoTotalMensual", "type" => "xsd:float"),
																		"formulario931CostoFijo" => array("name" => "formulario931CostoFijo", "type" => "xsd:float"),
																		"formulario931CostoVariable" => array("name" => "formulario931CostoVariable", "type" => "xsd:float"),
																		"alicuotaCompetenciaSumaFija" => array("name" => "alicuotaCompetenciaSumaFija", "type" => "xsd:float"),
																		"alicuotaCompetenciaVariable" => array("name" => "alicuotaCompetenciaVariable", "type" => "xsd:float"),
																		"edadPromedio" => array("name" => "edadPromedio", "type" => "xsd:int"),
																		"sector" => array("name" => "sector", "type" => "xsd:int"),
																		"cantidadEstablecimientos" => array("name" => "cantidadEstablecimientos", "type" => "xsd:int"),
																		"zonaGeografica" => array("name" => "zonaGeografica", "type" => "xsd:int"),
																		"prestacionesEspeciales" => array("name" => "prestacionesEspeciales", "type" => "xsd:string"),
																		"codigoVendedor" => array("name" => "codigoVendedor", "type" => "xsd:string"),
																		"observaciones" => array("name" => "observaciones", "type" => "xsd:string"),
																		"establecimientos" => array("name" => "establecimientos", "type" => "tns:EstablecimientosSolicitudCotizacion"),
																		"suscribePolizaRC" => array("name" => "suscribePolizaRC", "type" => "xsd:string"),
																		"sumaAseguradaRC" => array("name" => "sumaAseguradaRC", "type" => "xsd:int")));

$server->register("cambiarClave",		// Nombre de la función..
									array("usuario" => "xsd:string", "claveVieja" => "xsd:string", "claveNueva" => "xsd:string"),		// Parametros de entrada..
									array("return" => "xsd:string"),		// Parametros de salida..
									$url);
$server->register("cargarSolicitudCotizacion",
									array("datosSolicitudCotizacion" => "tns:SolicitudCotizacion"),
									array("return" => "xsd:string"),
									$url);
$server->register("login",
									array("usuario" => "xsd:string", "clave" => "xsd:string"),
									array("return" => "xsd:string"),
									$url);
$server->register("verNombresTablasReferencia",
									array("token" => "xsd:string"),
									array("return" => "xsd:string"),
									$url);
$server->register("verTablasReferencia",
									array("token" => "xsd:string", "tabla" => "xsd:string", "detalle" => "xsd:string"),
									array("return" => "xsd:string"),
									$url);

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA)?$HTTP_RAW_POST_DATA:"";
$server->service($HTTP_RAW_POST_DATA);
?>