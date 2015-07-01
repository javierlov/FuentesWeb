<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["idmodulo"]);

$params = array(":id" => $_REQUEST["id"]);
$sql = "SELECT ir_idestablecimiento, ir_idtipopdf FROM web.wir_impresionesrgrl WHERE ir_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$file = "/modules/solicitud_afiliacion/";
switch ($row["IR_IDTIPOPDF"]) {
	case 1:
		$id = substr($_REQUEST["idmodulo"], 1);
		$params = array(":id" => $id);
		$sql =
			"SELECT sc_cotizacion_pcp
				 FROM asc_solicitudcotizacion
				WHERE sc_id = :id";
		if ((valorSql($sql, 0, $params) == "S") and (substr($_REQUEST["idmodulo"], 0, 1) == "C"))
			$file.= "reporte_solicitud_afiliacion_pcp";
		else
			$file.= "reporte_solicitud_afiliacion";
		break;
	case 2:
		$file.= "reporte_ubicacion_riesgo";
		break;
	case 3:
		$file.= "reporte_rgrl";
		break;
	case 4:
		$file.= "reporte_addenda";
		break;
	case 5:
		$file.= "reporte_responsabilidad_civil";
		break;
	case 6:
		$file.= "reporte_peps";
		break;
	case 7:
		$file.= "reporte_exposicion_riesgos_quimicos";
		break;
	case 8:
		$file.= "reporte_ventanilla_electronica";
		break;
	case 9:
		$file.= "reporte_nomina_personal_expuesto";
		break;
}
$file.= ".php?idmodulo=".$_REQUEST["idmodulo"]."&idestablecimiento=".$row["IR_IDESTABLECIMIENTO"];
?>
<script type="text/javascript">
	window.open('<?= $file?>', 'extranetWindow', 'location=0');
</script>