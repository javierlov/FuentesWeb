<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/administracion_usuarios/get_grid.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 66));
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
	</head>
	<body style="margin:0;">
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<div align="center" id="divContent" name="divContent">
<?
echo getGrid($_REQUEST["idcliente"]);
?>
		</div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	</body>
</html>