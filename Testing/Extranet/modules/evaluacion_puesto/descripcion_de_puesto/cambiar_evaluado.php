<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");


$_SESSION["idEvaluado"] = $_REQUEST["vld"];
header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."descripcion_de_puesto/index.php");
?>