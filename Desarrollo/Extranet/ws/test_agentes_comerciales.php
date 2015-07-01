<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/nusoap/lib/nusoap.php");


set_time_limit(180);


//$client = new nusoap_client("http://".$_SERVER["HTTP_HOST"]."/ws/ws_agentes_comerciales.php?wsdl", true);
$client = new nusoap_client("http://".$_SERVER["HTTP_HOST"]."/ws/ws_agentes_comerciales.php?wsdl", true, false, false, false, false, 0, 300);

$err = $client->getError();
if ($err)
	echo "<h2>Constructor error</h2><pre>".$err."</pre>";

//$result = $client->call("login", array("usuario" => "SUPERNACION", "clave" => md5("SUPER")));

//$result = $client->call("cambiarClave", array("usuario" => "SUPERNACION", "claveVieja" => md5("SUPER"), "claveNueva" => md5("SUPER")));

//$result = $client->call("verNombresTablasReferencia", array("token" => "21dc19a1c745a3c0005348fcaf2f5b55"));

//$result = $client->call("verTablasReferencia", array("token" => "21dc19a1c745a3c0005348fcaf2f5b55", "tabla" => "ZONA_GEOGRAFICA", "detalle" => ""));

$datosSolicitudCotizacion = array("token" => "e1c950d2564f4d616f1d2252417bf297", "cuit" => "30688084519", "razonSocial" => "LAS JOTAS S.A.", "contacto" => "NAHUEL RUBIO",
																	"telefono" => "52398840", "email" => "CRUBIO@GRUPOMADERO.COM", "holding" => NULL, "ciiu1" => 813, "totalTrabajadores1" => 16, "masaSalarial1" => 56363.78,
																	"ciiu2" => NULL, "totalTrabajadores2" => 0, "masaSalarial2" => 0, "ciiu3" => NULL, "totalTrabajadores3" => 0, "masaSalarial3" => 0, "periodo" => "2013/08",
																	"actividadReal" => "PRODUCCIÓN DE LECHE DE GANADO BOVINO", "statusSrt" => 2, "artAnterior" => 27, "statusBcra" => 1, "datosCompetencia" => "N",
																	"soloPagoTotalMensual" => 0, "formulario931CostoFijo" => 0, "formulario931CostoVariable" => 0, "alicuotaCompetenciaSumaFija" => 0,
																	"alicuotaCompetenciaVariable" => 15.43, "edadPromedio" => 35, "sector" => 4, "cantidadEstablecimientos" => 1, "zonaGeografica" => 1,
																	"prestacionesEspeciales" => "N", "codigoVendedor" => NULL, "observaciones" => "", 
																	"establecimientos" => array(array("idProvincia" => 1, "idLocalidad" => 1407, "idActividad" => 5, "idCiiu" => 813, "cantidadTrabajadores" => 16)),
																	"suscribePolizaRC" => "N", "sumaAseguradaRC" => NULL);
$result = $client->call("cargarSolicitudCotizacion", array("datosSolicitudCotizacion" => $datosSolicitudCotizacion));


if ($client->fault) {
	echo "<h2>Fault</h2><pre>";
	print_r($result);
	echo "</pre>";
}
else {
	$err = $client->getError();
	if ($err)
		echo "<h2>Error</h2><pre>".$err."</pre><br/><br/>".$client->debug_str;
	else {
		echo "<h2>Result</h2><pre>";
		print_r($result);
    echo "</pre>";
//		echo base64_decode($result);
	}
}

// Display the request and response
//echo "<h2>Request</h2>";
//echo "<pre>".htmlspecialchars($client->request, ENT_QUOTES)."</pre>";
//echo "<h2>Response</h2>";
//echo "<pre>".htmlspecialchars($client->response, ENT_QUOTES)."</pre>";

//Display the debug messages
//echo "<h2>Debug</h2>";
//echo "<pre>".htmlspecialchars($client->debug_str, ENT_QUOTES)."</pre>";
?>