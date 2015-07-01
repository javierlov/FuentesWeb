<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
	</head>	
<body>	
<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/FirePHPCore/lib/FirePHPCore/FirePHP.class.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/Functions/CrearLog.php");

ob_start();
$firephp = FirePHP::getInstance(true);

echo 'hola que tal'; 


EscribirLogWARN('Mensaje warn');
EscribirLogUSERMENSAJE('username', 'Mensaje enviado a consola');
EscribirLogWARN('Mensaje Error');



$firephp->log('Mensaje enviado a consola', 'Mensaje');

$firephp->log('Un mensaje plano');
$firephp->info('Un mensaje de información');
$firephp->warn('Una alerta');
$firephp->error('Enviar un mensaje de error');
$firephp->trace('alert');
$firephp->info('Un mensaje de información');
$firephp->warn('Una alerta');
$firephp->error('Enviar un mensaje de error');
		
?>
</body>
</html>