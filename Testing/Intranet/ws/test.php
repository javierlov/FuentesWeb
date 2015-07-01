<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/nusoap/lib/nusoap.php");

$auth = new nusoap_client("https://testws.srt.gob.ar/Autenticacion/Autenticacion.asmx");

$err = $auth->getError();
if ($err)
	echo "<h2>Constructor error</h2><pre>".$err."</pre>";

$xxx = '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';
$params = array("texto" => $xxx);
$result = $auth->call("RequestTicket", $params);

/*
<?xml version="1.0"?>
<RequestTicket>
<id src="E=mail@org.gov.ar, CN=CertORG, OU=USI, O=Organismo, L=Buenos Aires, S=CABA, C=AR" dst="CN=AuthServer, O=SRT, C=AR, SERIALNUMBER=CUIT 33123456789" unique_id="6154654"/>
<operation type="login">
<login entity="CUIT de Entidad Solicitante" system=" PruebaWS" username="CUIT de Entidad Solicitante" authmethod="ticket"></login>
</operation>
</RequestTicket>

String XmlRequest = "";
XmlRequest += "<?xml version=\"1.0\"?>";
XmlRequest += "<RequestTicket>";
XmlRequest += "<id src=\"E=mail@org.gov.ar, CN=CertORG, OU=ART, O=Organismo, L=Buenos Aires, S=CABA, C=AR\" dst=\"CN=AuthServer, O=SRT, C=AR, SERIALNUMBER=CUIT 33686471689\" unique_id=\"123456\"/>";
XmlRequest += "<operation type=\"login\">";
XmlRequest += "<login entity=\"CUIT\" system=\"NombreWS\" username=\"CUIT\" authmethod=\"ticket\"></login>";
XmlRequest += "</operation>";
XmlRequest += "</RequestTicket>";

byte[] msgBytes = Encoding.UTF8.GetBytes(XmlRequest);
// Firmo el message y se pasa a Base64
string cmsFirmadoBase64;
byte[] encodedSignedCms = CertificadosX509Lib.FirmaBytesMensaje(msgBytes, certFirmante);
cmsFirmadoBase64 = Convert.ToBase64String(encodedSignedCms);

try
{
Autenticacion auth = new Autenticacion();
String ticket = auth.RequestTicket(cmsFirmadoBase64);
//Ticket generado
Console.WriteLine(ticket);
}
catch (SoapException soapEx) {
}

*/








if ($auth->fault) {
	echo "<h2>Fault</h2><pre>";
	print_r($result);
	echo "</pre>";
}
else {
	$err = $auth->getError();
	if ($err)
		echo "<h2>Error</h2><pre>".$err."</pre>";
	else {
//		echo "<h2>Result</h2><pre>";
		print_r($result);
//    echo "</pre>";
//		echo base64_decode($result);
	}
}

// Display the request and response
//echo "<h2>Request</h2>";
//echo "<pre>".htmlspecialchars($auth->request, ENT_QUOTES)."</pre>";
//echo "<h2>Response</h2>";
//echo "<pre>".htmlspecialchars($auth->response, ENT_QUOTES)."</pre>";

//Display the debug messages
//echo "<h2>Debug</h2>";
//echo "<pre>".htmlspecialchars($auth->debug_str, ENT_QUOTES)."</pre>";
?>