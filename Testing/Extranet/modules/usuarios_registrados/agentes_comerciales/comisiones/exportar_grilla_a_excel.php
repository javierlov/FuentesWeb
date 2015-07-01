<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/export_query.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion($_SESSION["comisiones"]);

set_time_limit(180);

$sql2 = "";

switch ($_REQUEST["s"]) {
	case "f":
		$nombreArchivo = "Fac_".$_SESSION["entidad"]."_";
		$sql = $_SESSION["sqlFacturas"];
		$sql2 = " AND identidad = ".$_SESSION["entidad"];
		break;
	case "l":
		$nombreArchivo = "Liq_Ent_".$_SESSION["entidad"]."_";
		$sql = $_SESSION["sqlLiquidaciones"];
		break;
	case "m":
		$nombreArchivo = "Mov_Ent_".$_SESSION["entidad"]."_";
		$sql = $_SESSION["sqlLiquidacionesMovimientos"];
		break;
	case "p":
		$nombreArchivo = "Liq_Pend_Ent_".$_SESSION["entidad"]."_";
		$sql = $_SESSION["sqlLiquidacionesPendientes"];
		$sql2 = " AND identidad = ".$_SESSION["entidad"];
		break;
	case "r":
		$nombreArchivo = "Ret_Ent_".$_SESSION["entidad"]."_";
		$sql = $_SESSION["sqlLiquidacionesRetenciones"];
		break;
		break;
	case "v":
		$nombreArchivo = "Ven_Ent_".$_SESSION["entidad"]."_";
		$sql = $_SESSION["sqlLiquidacionesVendedores"];
		break;
}

$sql = str_replace("ORDER BY", $sql2." ORDER BY", $sql);

$exportQuery = new ExportQuery($sql, $nombreArchivo.date("dmY"));
$exportQuery->setFieldAlignment($_SESSION["fieldsAlignment"]);
$exportQuery->export();
?>