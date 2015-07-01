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

header("Location: http://ntfinancialapp.artprov.com.ar:8000/OA_HTML/AppsLocalLogin.jsp?requestUrl=APPSHOMEPAGE&cancelUrl=http%3A%2F%2Fntfinancialapp.artprov.com.ar%3A8000%2Foa_servlets%2Foracle.apps.fnd.sso.AppsLogin&s2=B002A6650092CA271D979C04698DF2603A6D5BE1B876782C80B31FC88A7CF010");
?>