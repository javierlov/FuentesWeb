<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/export_query.php");


if ((isset($_REQUEST["d"])) and ($_REQUEST["d"] == "s")) {
	validarSesion(isset($_SESSION["isAgenteComercial"]));


	set_time_limit(10800);

	$sql = $_SESSION["sqlContratos"];

	$sql2 = " AND (en_idcanal = ".$_SESSION["canal"]." AND en_id = ".$_SESSION["entidad"];
	if ($_SESSION["entidad"] != 9003) {
		if ($_SESSION["sucursal"] != "")
			$sql2.= " AND vc_idsucursal = ".$_SESSION["sucursal"];

		if ($_SESSION["vendedor"] != "")
			$sql2.= " AND ev_idvendedor = ".$_SESSION["vendedor"];
	}
	$sql2.= ")";

	$sql = str_replace("ORDER BY", $sql2." ORDER BY", $sql);

	$exportQuery = new ExportQuery($sql, "Contratos_Activos_".date("dmY"));
	$exportQuery->setHeader($_SESSION["contratosActivosHeader"]);
	$exportQuery->setFieldNamesStyle("background-color:#00a4e4; border-color:#b1b1b1; border-width:1px; border-style:solid; color:white; font-family:Verdana; font-size:12px; height:20px;");
	$exportQuery->setFieldValuesStyle("border-color:#b1b1b1; border-width:1px; border-style:solid; font-family:Verdana; font-size:12px; height:20px;");
	$exportQuery->export();
}
else {
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Description" content="Provincia ART es una de las empresas aseguradoras del Grupo Banco Provincia, especializada en la prestación del seguro de cobertura de riesgos del trabajo." />
		<meta name="Language" content="Spanish" />
		<title>.:: Provincia ART - Generación de Reportes ::.</title>
	</head>
	<body style="background-color:#0f539c; font-weight:bold;">
		Generando reporte, aguarde por favor...
		<script type="text/javascript">
			window.location.href = '<?= $_SERVER["PHP_SELF"]."?d=s"?>';
		</script>
	</body>
</html>
<?
}
?>