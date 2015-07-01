<?php

function Envio_SMS($array_envio)
{
	// Set the POST data
	$postdata = http_build_query($array_envio);

	// Set the POST options
	$opts = array('http' =>
		array (
			'method' => 'POST',
			'header' => 'Content-type: application/x-www-form-urlencoded',
			'content' => $postdata
		)
	);

	// Create the POST context
	$context  = stream_context_create($opts);

	// POST the data to an api
	$url = 'http://10.1.11.1:8088/smstart/api_send_http2.php';
	$result = file_get_contents($url, false, $context);
	print $result;

	/* $result devuelve el resultado de la operación según la siguiente codificación:
	0 = transferencia de datos ok;
	1 = error en el texto del mensaje;
	2 = se superan la longitud de 160 caracteres;
	3 = los dos errores anteriores juntos;
	4 = error número de celular;
	5 = error transferencia de datos;
	6 = error validación LOGIN;
	*/

}

////////////////////// Ejemplo básico envío SMS ///////////////////////////////////////////
// Las variables ##NOMBRE## y ##DATO## pueden insertarse en el mensaje para personalizarlo.

/// En la matriz $array_envio se agregarán todos los elementos a enviar ///
/// El elemento 0 del array es el usuario y contraseña ///

  $usuario = "";
  if (isset($_POST["usuario"]))
    $usuario = $_POST["usuario"];
  else
    $usuario = 'Provincia';

  $clave = "";
  if (isset($_POST["clave"]))
    $clave = $_POST["clave"];
  else
    $clave = 'art';

  $array_envio[0] = array('usuario' => $usuario, 'clave' => $clave);

  $telefono = "";
  if (isset($_POST["telefono"]))
    $telefono = $_POST["telefono"];

  $nombre = "";
  if (isset($_POST["nombre"]))
    $nombre = $_POST["nombre"];

  $dato = "";
  if (isset($_POST["dato"]))
    $dato = $_POST["dato"];

  $texto = "";
  if (isset($_POST["texto"]))
    $texto = $_POST["texto"];

  $array_envio[1] = array('cel' => $telefono,
                          'nom' => $nombre,
                          'dato' => $dato,
                          'men' => $texto
		);

/// Enviamos la matriz a la función de envío ///
Envio_SMS($array_envio);

?>