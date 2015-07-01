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


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 61));

if ((isset($_REQUEST["sd"])) and ($_REQUEST["sd"] == "t")) {		// Si es true le paso el trabajador a la ventana de la denuncia..
	SetDateFormatOracle("DD/MM/YYYY");

	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT art.utiles.armar_cuit(tj_cuil) cuil, NVL(tj_estadocivil, -1) estadocivil, mt_calle, mt_cpostal, mt_departamento, mt_localidad, mt_numero, mt_piso, mt_provincia,
						NVL(tj_idnacionalidad, -1) nacionalidad, pv_descripcion,
						DECODE(mt_codareatelefono, NULL, mt_telefono, '(' || mt_codareatelefono || ') ' || mt_telefono) telefono, tj_fnacimiento, tj_nombre, rl_fechaingreso, NVL(tj_sexo, -1) sexo
			 FROM ctj_trabajador, crl_relacionlaboral, cmt_movitrabajador, cpv_provincias
			WHERE tj_id = rl_idtrabajador(+)
				AND mt_idtrabajador = tj_id(+)
				AND mt_provincia = pv_codigo(+)
				AND tj_id = :id
	 ORDER BY mt_id DESC";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
?>
<script src="/js/functions.js" type="text/javascript"></script>
<script type="text/javascript">
	with (parent.document) {
		getElementById('apellidoNombre').innerHTML	= unescape('<?= rawurlencode($row["TJ_NOMBRE"])?>');
		getElementById('calle').value								= unescape('<?= rawurlencode($row["MT_CALLE"])?>');
		getElementById('codigoPostal').value				= unescape('<?= rawurlencode($row["MT_CPOSTAL"])?>');
		getElementById('cuil').innerHTML						= unescape('<?= rawurlencode($row["CUIL"])?>');
		getElementById('departamento').value				= unescape('<?= rawurlencode($row["MT_DEPARTAMENTO"])?>');
		getElementById('estadoCivil').value					= unescape('<?= rawurlencode($row["ESTADOCIVIL"])?>');
		getElementById('fechaIngreso').value				= unescape('<?= rawurlencode($row["RL_FECHAINGRESO"])?>');
		getElementById('fechaNacimiento').value			= unescape('<?= rawurlencode($row["TJ_FNACIMIENTO"])?>');
		getElementById('idProvincia').value					= unescape('<?= rawurlencode($row["MT_PROVINCIA"])?>');
		getElementById('idTrabajador').value				= unescape('<?= rawurlencode($_REQUEST["id"])?>');
		getElementById('localidad').value						= unescape('<?= rawurlencode($row["MT_LOCALIDAD"])?>');
		getElementById('nacionalidad').value				= unescape('<?= rawurlencode($row["NACIONALIDAD"])?>');
		getElementById('numero').value							= unescape('<?= rawurlencode($row["MT_NUMERO"])?>');
		getElementById('piso').value								= unescape('<?= rawurlencode($row["MT_PISO"])?>');
		getElementById('provincia').value						= unescape('<?= rawurlencode($row["PV_DESCRIPCION"])?>');
		getElementById('sexo').value								= unescape('<?= rawurlencode($row["SEXO"])?>');
		getElementById('telefono').value						= unescape('<?= rawurlencode($row["TELEFONO"])?>');

		getElementById('spanApellidoNombre').innerHTML	= getElementById('apellidoNombre').innerHTML;
		getElementById('spanCalle').innerHTML					 	= getElementById('calle').value;
		getElementById('spanCodigoPostal').innerHTML		= getElementById('codigoPostal').value;
		getElementById('spanCuil').innerHTML						= getElementById('cuil').innerHTML;
		getElementById('spanDepartamento').innerHTML		= getElementById('departamento').value;
		getElementById('spanEstadoCivil').innerHTML			= iif((getElementById('estadoCivil').value == -1), '', getElementById('estadoCivil').options[getElementById('estadoCivil').selectedIndex].text);
		getElementById('spanFechaNacimiento').innerHTML = getElementById('fechaNacimiento').value;
		getElementById('spanLocalidad').innerHTML				= getElementById('localidad').value;
		getElementById('spanNacionalidad').innerHTML		= iif((getElementById('nacionalidad').value == -1), '', getElementById('nacionalidad').options[getElementById('nacionalidad').selectedIndex].text);
		getElementById('spanNumero').innerHTML				 	= getElementById('numero').value;
		getElementById('spanPais').innerHTML						= 'Argentina';
		getElementById('spanPiso').innerHTML						= getElementById('piso').value;
		getElementById('spanProvincia').innerHTML				= getElementById('provincia').value;
		getElementById('spanSexo').innerHTML					 	= iif((getElementById('sexo').value == -1), '', getElementById('sexo').options[getElementById('sexo').selectedIndex].text);
		getElementById('spanTelefono').innerHTML				= getElementById('telefono').value;

		getElementById('pSinDatosconocidos').style.display = 'none';
		getElementById('divDatosDomicilio').style.display = 'block';
	}
	parent.divWin.close();
</script>
<?
	exit;
}


$nombre = "";
if (isset($_REQUEST["nombre"]))
	$nombre = $_REQUEST["nombre"];

$cuil = "";
if (isset($_REQUEST["cuil"]))
	$cuil = $_REQUEST["cuil"];

$establecimiento = -1;
if (isset($_REQUEST["establecimiento"]))
	$establecimiento = $_REQUEST["establecimiento"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];


$params = array(":idempresa" => $_SESSION["idEmpresa"]);
$where = "";

if ($cuil != "") {
	$params[":cuil"] = str_replace("-", "", $cuil);
	$where.= " AND tj_cuil = :cuil";
}

if ($nombre != "") {
	$params[":nombre"] = $nombre."%";
	$where.= " AND UPPER(tj_nombre) LIKE UPPER(:nombre)";
}

if ($establecimiento != -1) {
	$params[":idestablecimiento"] = $establecimiento;
	$where.= " AND es_id = :idestablecimiento";
}

$sql =
	"SELECT ¿tj_id?, ¿tj_nombre?, ¿tj_cuil?, ¿es_nombre?, ¿rl_tarea?
		 FROM ctj_trabajador, aes_establecimiento, cre_relacionestablecimiento, crl_relacionlaboral
		WHERE tj_id = rl_idtrabajador
			AND rl_id = re_idrelacionlaboral
			AND re_idestablecimiento = es_id
			AND rl_contrato = art.afiliacion.get_contratovigente((SELECT em_cuit
																															FROM aem_empresa
																														 WHERE em_id = :idempresa), SYSDATE) _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("", 8, true, false, -1, "btnSeleccionar", $_SERVER["PHP_SELF"]."?sd=t", ""));
$grilla->addColumn(new Column("Trabajador"));
$grilla->addColumn(new Column("CUIL"));
$grilla->addColumn(new Column("Establecimiento"));
$grilla->addColumn(new Column("Tarea"));
$grilla->setColsSeparator(true);
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRowsSeparator(true);
$grilla->setShowProcessMessage(true);
$grilla->setSql($sql);
$grilla->Draw();
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divProcesando').style.display = 'none';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
	}
</script>