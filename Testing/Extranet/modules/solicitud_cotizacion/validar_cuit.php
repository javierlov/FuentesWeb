<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function msgBox($msg, $controlFocus = "") {
	echo "<script type='text/javascript'>";
	echo "window.parent.document.getElementById('imgCuitLoading').style.visibility = 'hidden';";
	echo "alert('".$msg."');";

	if ($controlFocus != "")
		echo "window.parent.document.getElementById('".$controlFocus."').focus();";

	echo "</script>";
	exit;
}

validarSesion(isset($_SESSION["isAgenteComercial"]));
SetNumberFormatOracle();

if ($_REQUEST["cuit"] == "") {
	echo "<script type='text/javascript'>window.parent.document.getElementById('imgCuitLoading').style.visibility = 'hidden';</script>";
	exit;
}
		
// Validación 1..
// EJV (01/02/2010) = Control de CUIT reservado.
$params = array(":cuit" => $_REQUEST["cuit"]);
$sql =
	"SELECT ac_descripcion actividad, ca_descripcion canal, en_nombre entidad, ru_idcanal, ru_identidad, ru_idvendedor, ru_observaciones, ve_nombre vendedor
		 FROM aru_reservacuit ru, aca_canal, cac_actividad, xen_entidad, xve_vendedor
		WHERE ru_idcanal = ca_id(+)
			AND ru_idactividad = ac_id(+)
			AND ru_identidad = en_id(+)
			AND ru_idvendedor = ve_id(+)
			AND ru_cuit = :cuit
			AND ru_fechabaja IS NULL
			AND actualdate BETWEEN ru_fechadesde AND ru_fechahasta";
$stmt = DBExecSql($conn, $sql, $params);
if (DBGetRecordCount($stmt) > 0) {
	$row = DBGetQuery($stmt);

	$distintoVendedor = (($row["VENDEDOR"] != "") and ($row["RU_IDVENDEDOR"] != $_SESSION["vendedor"]));
	if (($row["RU_IDCANAL"] != $_SESSION["canal"]) or ($row["RU_IDENTIDAD"] != $_SESSION["entidad"]) or ($distintoVendedor)) {
		$msg = "[1] Esta C.U.I.T. se encuentra reservada por otro usuario,\\r\\r";
		$msg.= "por favor comuníquese con su Ejecutivo de Provincia ART.";
		echo "<script type='text/javascript'>window.parent.document.getElementById('observaciones').value = '".$row["RU_OBSERVACIONES"]."';</script>";
		msgBox($msg);
	}
}

// Validación 2..
// EJV 15/01/2010
// Control de vigencia de la Solicitud de Cotizacion
$params = array(":cuit" => $_REQUEST["cuit"]);
$sql =
	"SELECT ca_descripcion
		 FROM asc_solicitudcotizacion, aca_canal
		WHERE ca_id = sc_canal
			AND (actualdate - TRUNC(sc_fechasolicitud)) < 30
			AND sc_estado NOT IN('05', '07', '08', '09', '18.0', '18.1', '18.2', '18.3')
			AND sc_cuit = :cuit";
$stmt = DBExecSql($conn, $sql, $params);
if (DBGetRecordCount($stmt) > 0) {
	$rowValidation = DBGetQuery($stmt);
	msgBox("[2] Ya existe una solicitud para esta C.U.I.T., por favor comuníquese con su Ejecutivo de Provincia ART.", "cuit");
}

// Validación 3..
// EJV 01/02/2010
// Control de vigencia de la Revision de Precio
$params = array(":cuit" => $_REQUEST["cuit"]);
$sql =
	"SELECT ca_descripcion
		 FROM art.asr_solicitudreafiliacion 
		 JOIN aca_canal ON ca_id = sr_idcanal
		WHERE (art.actualdate - TRUNC(sr_fechaalta)) < 30
			AND sr_estadosolicitud NOT IN('05', '18.0', '18.1', '18.2', '18.3')
			AND sr_cuit = :cuit";
$stmt = DBExecSql($conn, $sql, $params);
if (DBGetRecordCount($stmt) > 0) {
	$rowValidation = DBGetQuery($stmt);
	msgBox("[3] Ya existe una solicitud para esta C.U.I.T., por favor comuníquese con su Ejecutivo de Provincia ART.", "cuit");
}


// Validación 4..
$params = array(":cuit" => $_REQUEST["cuit"]);
$sql = "SELECT afiliacion.check_cobertura(:cuit) FROM DUAL";
if (ValorSql($sql, "", $params) == 1) {
	msgBox("[4] Esta empresa ya tiene un contrato activo con esta aseguradora.");
}

// Validación 5.. Que no puedan colocar el CUIT de la ART.
// EJV 15/04/2010.
if ($_REQUEST["cuit"] == "30688254090") {
	msgBox("[5] Debe registrarse la C.U.I.T. del empleador (si la C.U.I.T. se registra erróneamente la solicitud no tiene validez).");
}


require_once("import_from_srt.php");


// Obtengo el nombre de la empresa de la srt..
$params = array(":cuit" => $_REQUEST["cuit"]);
$sql =
	"SELECT em_nombre
		 FROM srt.sem_empresas
		WHERE em_cuit = :cuit
 ORDER BY 1 DESC";
$razonSocial = ValorSql($sql, "", $params);


// Validación 6.. Tiene que ir debajo de la llamada a import_from_srt.php..
$params = array(":cuit" => $_REQUEST["cuit"]);
$sql =
	"SELECT 1
		 FROM srt.sem_empresas e JOIN srt.shv_historialvigencias v ON v.hv_id = art.cotizacion.get_idultimavigencia(em_cuit)
		WHERE CASE
					WHEN v.hv_idoperaciondesde = 10888 THEN ADD_MONTHS(v.hv_vigenciadesde, 10) + 11
					ELSE ADD_MONTHS(v.hv_vigenciadesde, 6)
					END <= SYSDATE
			AND e.em_cuit = :cuit";
if (($row["SS_STATUS"] != -1) and ($row["SS_STATUS"] != 1) and (!ExisteSql($sql, $params, 0))) {
	$msg = "[6] Esta C.U.I.T. no puede ser cotizada por la vigencia en la actual ART,\\r\\r";
	$msg.= "por favor comuníquese con su Ejecutivo de Provincia ART.";
	msgBox($msg);
}
?>
<script type="text/javascript">
	with (window.parent.document) {
		// Asigno el nombre que devuelve la SRT..
		getElementById('razonSocial').value = '<?= $razonSocial?>';

		try {
			if ((getElementById('statusSrtTmp').value == 2) && (getElementById('artTmp').value == 51))
				throw 'Esta empresa ya tiene un contrato vigente con esta Aseguradora.\nPara cualquier otro pedido o consulta comunicarse con su Ejecutivo de Cuenta.';
		}
		catch(e) {
			alert(e);
		}

		if ((getElementById('statusSrtTmp').value == 6) && (getElementById('artTmp').value != 51))
			if (confirm('Esta empresa tiene su contrato dado de baja por deuda. Por favor verifique la regularización de la misma\ny comuníquese con su Ejecutivo de Cuenta para hacerle llegar el Libre Deuda.\n¿ Desea continuar ?'))
				getElementById('bajaPorDeuda').value = 'F';
			else
				getElementById('bajaPorDeuda').value = 'T';

		getElementById('imgCuitLoading').style.visibility = 'hidden';
	}
</script>
<?
//require_once("import_from_bcra.php");
?>