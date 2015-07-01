<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/ellipse.php");
session_start();


if ((!isset($_SESSION["pcpId"])) or ($_SESSION["paso"] < 4)) {
	echo "<script>alert('La sesión ha caducado. Por favor, vuelva a loguearse.'); window.parent.location.href = '/pcp';</script>";
	die();
}

if ($_REQUEST["tipo"] == "contrato")
	require_once("reporte_contrato.php");
if ($_REQUEST["tipo"] == "pep")
	require_once("reporte_pep.php");
?>