<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/nusoap/lib/nusoap.php");

$client = new nusoap_client("http://".$_SERVER["HTTP_HOST"]."/ws/ws_lotes.php?wsdl", true);

$err = $client->getError();
if ($err)
	echo "<h2>Constructor error</h2><pre>".$err."</pre>";

$result = $client->call("obtenerLote", array("lote" => 1));

if ($client->fault) {
	echo "<h2>Fault</h2><pre>";
	print_r($result);
	echo "</pre>";
}
else {
	$err = $client->getError();
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
//echo "<pre>".htmlspecialchars($client->request, ENT_QUOTES)."</pre>";
//echo "<h2>Response</h2>";
//echo "<pre>".htmlspecialchars($client->response, ENT_QUOTES)."</pre>";

//Display the debug messages
//echo "<h2>Debug</h2>";
//echo "<pre>".htmlspecialchars($client->debug_str, ENT_QUOTES)."</pre>";
?>