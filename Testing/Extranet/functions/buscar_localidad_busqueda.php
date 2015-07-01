<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function getComputerAndUserName() {
//	$host = "SIN_HOST";
//	if (isset($_SERVER["REMOTE_HOST"]))
//		$host = $_SERVER["REMOTE_HOST"];
	$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	return substr(strtoupper($_SESSION["usuario"]."/".$host), 0, 64);
}


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));

set_time_limit(60);


if ((isset($_REQUEST["sd"])) and ($_REQUEST["sd"] == "t")) {		// Si es true guardo la fila seleccionada en los campos pasados como parámetro en la ventana padre..
	$ids = explode("-", $_REQUEST["id"]);

	$params = array(":usuario" => getComputerAndUserName());
	$sql =
		"SELECT mp_cpostal, mp_localidad
			 FROM tmp_domicilios
			WHERE mp_usuario = :usuario";

	if ($ids[0] != "") {
		$params[":idcodigospotales"] = nullIsEmpty(substr($ids[0], 0, 8));
		$sql.= " AND mp_idcodigospostales = :idcodigospotales";
	}
	if ($ids[1] != "") {
		$params[":idubicacion"] = nullIsEmpty(substr($ids[1], 0, 8));
		$sql.= " AND mp_idubicacion = :idubicacion";
	}

	$stmt = DBExecSql($conn, $sql, 	$params);
	$row = DBGetQuery($stmt);
?>
<script type="text/javascript">
	with (parent.document) {
		getElementById('codigoPostal2').value = '<?= $row["MP_CPOSTAL"]?>';
		getElementById('localidad2').value = '<?= $row["MP_LOCALIDAD"]?>';
	}
	parent.divWinLocalidad.close();
</script>
<?
	exit;
}


$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "4";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$sql1 = 
	"SELECT ".addQuotes(getComputerAndUserName()).", pa_id, pa_idubicacion, pa_codigoppostal, pa_cpa, pa_calle_abreviado, pa_desde, pa_hasta, pa_localidad, pv_descripcion, pa_provincia, pa_partido
		 FROM cpv_provincias, cpa_codigospostales
LEFT JOIN cub_ubicacion ON(ub_id = pa_idubicacion)
		WHERE pv_codigo = pa_provincia _WHERE1_";

$sql2 =
	"SELECT ".addQuotes(getComputerAndUserName()).", NULL, ub_id, ub_cpostal, NULL, ub_calle, 0, 9999, ub_localidad, pv_descripcion, ub_provincia, NULL
		 FROM cub_ubicacion, cpv_provincias
		WHERE pv_codigo = ub_provincia
			AND NOT EXISTS(SELECT 1
											 FROM tmp_domicilios
											WHERE mp_idubicacion = ub_id
												AND mp_usuario = ".addQuotes(getComputerAndUserName()).") _WHERE2_";

$params1 = array(":provincia" => $_REQUEST["p"]);
$params2 = array(":provincia" => $_REQUEST["p"]);

$where1 = " AND pv_codigo = :provincia";
$where2 = " AND pv_codigo = :provincia";

if ($_REQUEST["localidad"] != "") {
	$where1.= " AND pa_localidad LIKE :localidad";
	$params1[":localidad"] = "%".strtoupper($_REQUEST["localidad"])."%";

	$where2.= " AND ub_localidad LIKE :localidad";
	$params2[":localidad"] = "%".strtoupper($_REQUEST["localidad"])."%";
}

$sql1 = str_replace("_WHERE1_", $where1, $sql1);
$sql2 = str_replace("_WHERE2_", $where2, $sql2);

// INICIO - Agregar registros en tabla temporal..
$params = array(":usuario" => getComputerAndUserName());
$sql = "DELETE FROM tmp_domicilios WHERE mp_usuario = :usuario";
DBExecSql($conn, $sql, $params, OCI_DEFAULT);

$sql =
	"INSERT INTO tmp_domicilios
							(mp_usuario, mp_idcodigospostales, mp_idubicacion, mp_cpostal, mp_cpa, mp_calle, mp_desde, mp_hasta, mp_localidad, mp_provincia, mp_idprovincia, mp_partido) ".$sql1;
DBExecSql($conn, $sql, $params1, OCI_DEFAULT);

$sql =
	"INSERT INTO tmp_domicilios
							(mp_usuario, mp_idcodigospostales, mp_idubicacion, mp_cpostal, mp_cpa, mp_calle, mp_desde, mp_hasta, mp_localidad, mp_provincia, mp_idprovincia, mp_partido) ".$sql2;
DBExecSql($conn, $sql, $params2, OCI_DEFAULT);
// FIN - Agregar registros en tabla temporal..


$sql =
	"SELECT mp_idcodigospostales || '-' || mp_idubicacion ¿id?, ¿mp_cpostal?, ¿mp_localidad?, ¿mp_provincia?, 'Seleccionar' ¿seleccionar?
		 FROM tmp_domicilios
		WHERE mp_usuario = ".addQuotes(getComputerAndUserName());

$grilla = new Grid(10, 10);
$grilla->addColumn(new Column(" ", 1, true, false, 5, "btnSeleccionar", $_SERVER["PHP_SELF"]."?sd=t", ""));
$grilla->addColumn(new Column("C.&nbsp;Postal", 60));
$grilla->addColumn(new Column("Localidad"));
$grilla->addColumn(new Column("Provincia"));
$grilla->addColumn(new Column("", 0, false));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setShowProcessMessage(true);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->Draw();
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divProcesando').style.display = 'none';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
	}
</script>