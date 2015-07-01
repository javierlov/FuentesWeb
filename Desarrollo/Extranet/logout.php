<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");

// Solo mantengo la sesión del chat..
if (isset($_SESSION["chatIdSession"]))
	$idSesionChat = $_SESSION["chatIdSession"];

session_unset();
session_destroy();

if (isset($idSesionChat)) {
	session_start();
	$_SESSION["chatIdSession"] = $idSesionChat;
}

header("Location: /");
?>