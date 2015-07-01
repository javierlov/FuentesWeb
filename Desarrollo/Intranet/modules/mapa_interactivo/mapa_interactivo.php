<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


logUrlIn($_SERVER["REQUEST_URI"]);

$params = array(":usuario" => getWindowsLoginName(true));
$sql =
	"SELECT 'http://www.google.com/maps/place/' || INITCAP(art.utiles.reemplazar_acentos(el_nombre)) || ', ' || INITCAP(art.utiles.reemplazar_acentos(pv_descripcion)) || ', Argentina'
		 FROM use_usuarios, del_delegacion, cpv_provincias
		WHERE se_delegacion = el_id
			AND el_provincia = pv_codigo
			AND el_fechabaja IS NULL
			AND se_usuario = :usuario";
header("Location: ".valorSql($sql, "", $params));
?>