<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/excel/excel_reader2.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
session_start();


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 70));

register_shutdown_function("shutdownFunction");
function shutDownFunction() {
	global $conn;

	$error = error_get_last();
	if ($error["type"] == 1) {
		DBRollback($conn);
		echo "<script type='text/javascript'>alert(unescape('".rawurlencode($error["message"])."'));</script>";
	}
}

try {
	set_time_limit(1800);

	if ($_FILES["archivo"]["name"] == "")
		throw new Exception("Debe elegir el Archivo a subir.");

	if (!validarExtension($_FILES["archivo"]["name"], array("xls")))
		throw new Exception("El Archivo a subir debe ser de extensión \".xls\".");


	error_reporting(E_ALL ^ E_NOTICE);
	$excel = new Spreadsheet_Excel_Reader($_FILES["archivo"]["tmp_name"]);

	$cuiles = array();
	$filasErroneas = array();

	for ($row=1; $row<=$excel->rowcount(); $row++) {
		$cuil = sacarGuiones($excel->val($row, "A"));

		// Si la primer columna está vacía lo tomo como un EOF y salgo del loop principal..
		if (trim($cuil) == "")
			break;

		if (validarCuit($cuil))
			$cuiles[] = $cuil;
		else
			$filasErroneas[] = $row;
	}

	$_SESSION["CUILES_A_AGREGAR"] = array_unique($cuiles);

	if (count($filasErroneas) > 0) {
?>
		<script type="text/javascript">
			with (window.parent.document) {
				getElementById('imgProcesando').style.display = 'none';
				getElementById('spanErrores').innerText = '<?= implode(", ", $filasErroneas)?>';
				getElementById('divErrores').style.display = 'inline';
			}
		</script>
<?
	}
	else {
?>
		<script type="text/javascript">
			window.location.href = '/modules/usuarios_registrados/clientes/certificado_de_cobertura/procesar_archivo.php';
		</script>
<?
	}
}
catch (Exception $e) {
?>
<script type="text/javascript">
	alert(unescape('<?= rawurlencode($e->getMessage())?>'));
	with (parent.document) {
		getElementById('archivo').readOnly = false;
		getElementById('btnCargar').style.display = 'inline';
		getElementById('btnVerEjemplo').style.display = 'inline';
		getElementById('imgProcesando').style.display = 'none';
	}
</script>
<?
	exit;
}
?>