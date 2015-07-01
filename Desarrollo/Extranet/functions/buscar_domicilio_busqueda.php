<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
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

set_time_limit(120);

if ((isset($_REQUEST["sd"])) and ($_REQUEST["sd"] == "t")) {		// Si es true guardo la fila seleccionada en los campos pasados como parámetro en la ventana padre..
	$ids = explode("-", $_REQUEST["id"]);

	$params = array(":usuario" => getComputerAndUserName());
	$sql =
		"SELECT mp_calle, mp_cpa, mp_cpostal, mp_idprovincia, mp_localidad, mp_provincia
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
		if (getElementById('<?= $_REQUEST["objCalle"]?>') != null)
			getElementById('<?= $_REQUEST["objCalle"]?>').value = '<?= $row["MP_CALLE"]?>';
		if (getElementById('<?= $_REQUEST["objCp"]?>') != null)
			getElementById('<?= $_REQUEST["objCp"]?>').value = '<?= $row["MP_CPOSTAL"]?>';
		if (getElementById('<?= $_REQUEST["objCpa"]?>') != null)
			getElementById('<?= $_REQUEST["objCpa"]?>').value = '<?= $row["MP_CPA"]?>';
		if (getElementById('<?= $_REQUEST["objDatosDomicilio"]?>') != null)
			getElementById('<?= $_REQUEST["objDatosDomicilio"]?>').style.display = 'block';
		if (getElementById('<?= $_REQUEST["objDomicilioManual"]?>') != null)
			getElementById('<?= $_REQUEST["objDomicilioManual"]?>').value = 'f';
		if (getElementById('<?= $_REQUEST["objIdProvincia"]?>') != null)
			getElementById('<?= $_REQUEST["objIdProvincia"]?>').value = '<?= $row["MP_IDPROVINCIA"]?>';
		if (getElementById('<?= $_REQUEST["objLocalidad"]?>') != null)
			getElementById('<?= $_REQUEST["objLocalidad"]?>').value = '<?= $row["MP_LOCALIDAD"]?>';
		if (getElementById('<?= $_REQUEST["objProvincia"]?>') != null)
			getElementById('<?= $_REQUEST["objProvincia"]?>').value = '<?= $row["MP_PROVINCIA"]?>';
		if (getElementById('<?= $_REQUEST["objSinDatosConocidos"]?>') != null)
			getElementById('<?= $_REQUEST["objSinDatosConocidos"]?>').style.display = 'none';
	}
	parent.divWin.close();
</script>
<?
	exit;
}


require_once("buscar_domicilio_busqueda_combos.php");

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "4";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$sql1 =
	"SELECT ".addQuotes(getComputerAndUserName()).", pa_id, pa_idubicacion, pa_codigoppostal, pa_cpa, pa_calle_abreviado, pa_desde, pa_hasta, pa_localidad, pv_descripcion, pa_provincia,
					pa_partido
		 FROM cpv_provincias, cpa_codigospostales
LEFT JOIN cub_ubicacion ON(ub_id = pa_idubicacion)
		WHERE pv_codigo = pa_provincia _WHERE1_";

$sql2 =
	"SELECT ".addQuotes(getComputerAndUserName()).", NULL, ub_id, ub_cpostal, NULL, ub_calle, 0, 9999, ub_localidad, pv_descripcion, ub_provincia,
					NULL
		 FROM cub_ubicacion, cpv_provincias
		WHERE pv_codigo = ub_provincia
			AND NOT EXISTS(SELECT 1
											 FROM tmp_domicilios
											WHERE mp_idubicacion = ub_id
												AND mp_usuario = ".addQuotes(getComputerAndUserName()).") _WHERE2_";

$where1 = "";
$where2 = "";

$params1 = array();
$params2 = array();

if ($_REQUEST["calle"] != "") {
	if ($_REQUEST["tipoCalle"] == "c") {		// Contiene..
		$where1.= " AND pa_calle_abreviado LIKE :calleabreviado";
		$params1[":calleabreviado"] = "%".$_REQUEST["calle"]."%";

		$where2.= " AND ub_calle LIKE :calle";
		$params2[":calle"] = "%".$_REQUEST["calle"]."%";
	}
	else {		// Empieza..
		$where1.= " AND pa_calle_abreviado LIKE :calleabreviado";
		$params1[":calleabreviado"] = $_REQUEST["calle"]."%";

		$where2.= " AND ub_calle LIKE :calle";
		$params2[":calle"] = $_REQUEST["calle"]."%";
	}
}

if ($_REQUEST["localidad"] != "") {
	$where1.= " AND pa_localidad LIKE :localidad";
	$params1[":localidad"] = "%".$_REQUEST["localidad"]."%";

	$where2.= " AND ub_localidad LIKE :localidad";
	$params2[":localidad"] = "%".$_REQUEST["localidad"]."%";
}

if ($_REQUEST["provincia"] != -1) {
	$where1.= " AND pv_codigo = :provincia";
	$params1[":provincia"] = $_REQUEST["provincia"];

	$where2.= " AND pv_codigo = :provincia";
	$params2[":provincia"] = $_REQUEST["provincia"];
}

if ($_REQUEST["codigoPostal"] != "") {
	$where1.= " AND pa_codigoppostal = :codigopostal";
	$params1[":codigopostal"] = $_REQUEST["codigoPostal"];

	$where2.= " AND ub_cpostal = :codigopostal";
	$params2[":codigopostal"] = $_REQUEST["codigoPostal"];
}

if ($_REQUEST["cpa"] != "") {
	$where1.= " AND pa_cpa = :cpa";
	$params1[":cpa"] = $_REQUEST["cpa"];

	$where2.= " AND 1 = 2";
}

if ($_REQUEST["altura"] != "") {
	$where1.= " AND :altura BETWEEN pa_desde AND pa_hasta";
	$params1[":altura"] = intval($_REQUEST["altura"]);
}

$sql1 = str_replace("_WHERE1_", $where1, $sql1);
$sql2 = str_replace("_WHERE2_", $where2, $sql2);

// INICIO - Agregar registros en tabla temporal..
$params = array(":usuario" => getComputerAndUserName());
$sql = "DELETE FROM tmp_domicilios WHERE mp_usuario = :usuario";
DBExecSql($conn, $sql, $params, OCI_DEFAULT);

$sql = "INSERT INTO tmp_domicilios (mp_usuario, mp_idcodigospostales, mp_idubicacion, mp_cpostal, mp_cpa, mp_calle, mp_desde, mp_hasta, mp_localidad, mp_provincia, mp_idprovincia, mp_partido) ".$sql1;
DBExecSql($conn, $sql, $params1, OCI_DEFAULT);

$sql = "INSERT INTO tmp_domicilios (mp_usuario, mp_idcodigospostales, mp_idubicacion, mp_cpostal, mp_cpa, mp_calle, mp_desde, mp_hasta, mp_localidad, mp_provincia, mp_idprovincia, mp_partido) ".$sql2;
DBExecSql($conn, $sql, $params2, OCI_DEFAULT);
// FIN - Agregar registros en tabla temporal..


$sql =
	"SELECT mp_idcodigospostales || '-' || mp_idubicacion ¿id?,
					¿mp_cpostal?,
					¿mp_cpa?,
					¿mp_calle?,
					¿mp_desde?,
					¿mp_hasta?,
					¿mp_localidad?,
					¿mp_provincia?,
					'Seleccionar' ¿seleccionar?
		 FROM tmp_domicilios
		WHERE mp_usuario = ".addQuotes(getComputerAndUserName());
$grilla = new Grid(10, ($_REQUEST["buscarcalle"] == "t")?10:12);
$grilla->addColumn(new Column(" ", 1, true, false, 9, "btnSeleccionar", $_SERVER["PHP_SELF"]."?sd=t&objCalle=".$_REQUEST["objCalle"]."&objCp=".$_REQUEST["objCp"]."&objCpa=".$_REQUEST["objCpa"]."&objDatosDomicilio=".$_REQUEST["objDatosDomicilio"]."&objDepartamento=".$_REQUEST["objDepartamento"]."&objIdProvincia=".$_REQUEST["objIdProvincia"]."&objLocalidad=".$_REQUEST["objLocalidad"]."&objNumero=".$_REQUEST["objNumero"]."&objPiso=".$_REQUEST["objPiso"]."&objProvincia=".$_REQUEST["objProvincia"]."&objSinDatosConocidos=".$_REQUEST["objSinDatosConocidos"]."&objDomicilioManual=".$_REQUEST["objDomicilioManual"], ""));
$grilla->addColumn(new Column("C.&nbsp;Postal", 60));
$grilla->addColumn(new Column("CPA", 28));
if ($_REQUEST["buscarcalle"] == "t") {
	$grilla->addColumn(new Column("Calle"));
	$grilla->addColumn(new Column("Desde", 44));
	$grilla->addColumn(new Column("Hasta", 44));
}
else {
	$grilla->addColumn(new Column("Calle", -1, false));
	$grilla->addColumn(new Column("Desde", -1, false));
	$grilla->addColumn(new Column("Hasta", -1, false));
}
$grilla->addColumn(new Column("Localidad"));
$grilla->addColumn(new Column("Provincia"));
$grilla->addColumn(new Column("", 0, false));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setShowProcessMessage(true);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->Draw();

if ($grilla->recordCount() == 0) {
?>
	<script type="text/javascript">
		parent.document.getElementById('divNoData').style.display = 'block';
	</script>
<?
}
?>
<script type="text/javascript">
	window.parent.document.getElementById('divProcesando').style.display = 'none';
	window.parent.document.getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
	window.parent.document.getElementById('divContentGrid').style.display = 'block';
<?
if ($grilla->recordCount() == 0) {
?>
	window.parent.document.getElementById('provincia2').parentNode.innerHTML = '<?= $comboProvincia2->draw();?>';
	window.parent.document.getElementById('divContentGrid').style.height = '56px';
	window.parent.document.getElementById('divMensaje2').style.display = 'none';
	window.parent.document.getElementById('divMensaje3').innerHTML = 'Por favor, ingrese el domicilio en forma detallada, de tal manera que resulte posible el envío de correspondencia.';
	window.parent.cambiaProvincia2(<?= $_REQUEST["provincia"]?>);
<?
}
else {
?>
	window.parent.document.getElementById('divContentGrid').style.height = '<?= ($_REQUEST["buscarcalle"] == "t")?236:285?>px';
	window.parent.document.getElementById('divMensaje2').innerHTML = 'Haga clic sobre el ícono para seleccionar los datos del domicilio.';
<?
}
?>
</script>