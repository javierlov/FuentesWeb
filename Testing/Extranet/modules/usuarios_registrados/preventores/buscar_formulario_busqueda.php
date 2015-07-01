<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function setInPrioridad($num, $prioridad) {
	if ($prioridad == "checked")
		return $num;
	else
		return -1;
}

validarSesion(isset($_SESSION["isPreventor"]));
SetDateFormatOracle("DD/MM/YYYY");
set_time_limit(180);

$prioridad1 = "";
if (isset($_REQUEST["prioridad1"]))
	$prioridad1 = "checked";

$prioridad2 = "";
if (isset($_REQUEST["prioridad2"]))
	$prioridad2 = "checked";

$prioridad3 = "";
if (isset($_REQUEST["prioridad3"]))
	$prioridad3 = "checked";

$prioridad4 = "";
if (isset($_REQUEST["prioridad4"]))
	$prioridad4 = "checked";

$prioridad5 = "";
if (isset($_REQUEST["prioridad5"]))
	$prioridad5 = "checked";

$prioridad6 = "";
if (isset($_REQUEST["prioridad6"]))
	$prioridad6 = "checked";

$prioridad7 = "";
if (isset($_REQUEST["prioridad7"]))
	$prioridad7 = "checked";

$pagina = $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["pagina"];
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["ob"];
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"] = array("buscar" => "S",
																										"codigoPostal" => $_REQUEST["codigoPostal"],
																										"contrato" => $_REQUEST["contrato"],
																										"cuit" => $_REQUEST["cuit"],
																										"establecimiento" => $_REQUEST["idEstablecimiento"],
																										"fechaDesde" => $_REQUEST["fechaDesde"],
																										"fechaHasta" => $_REQUEST["fechaHasta"],
																										"idEstablecimiento" => $_REQUEST["idEstablecimiento"],
																										"idPreventor" => $_REQUEST["idPreventor"],
																										"idProvincia" => $_REQUEST["idProvincia"],
																										"ob" => $ob,
																										"pagina" => $pagina,
																										"preventor" => $_REQUEST["idPreventor"],
																										"prioridad1" => $prioridad1,
																										"prioridad2" => $prioridad2,
																										"prioridad3" => $prioridad3,
																										"prioridad4" => $prioridad4,
																										"prioridad5" => $prioridad5,
																										"prioridad6" => $prioridad6,
																										"prioridad7" => $prioridad7,
																										"provincia" => $_REQUEST["idProvincia"],
																										"razonSocial" => $_REQUEST["razonSocial"]);


$params = array();
$where = "";
if ((setInPrioridad(1, $prioridad1) != -1) or (setInPrioridad(2, $prioridad2) != -1) or
		(setInPrioridad(3, $prioridad3) != -1) or (setInPrioridad(4, $prioridad4) != -1) or
		(setInPrioridad(5, $prioridad5) != -1) or (setInPrioridad(6, $prioridad6) != -1) or
		(setInPrioridad(7, $prioridad7) != -1))
	$where = " AND vp_prioridad IN (".setInPrioridad(1, $prioridad1).", ".setInPrioridad(2, $prioridad2).", ".setInPrioridad(3, $prioridad3).", ".setInPrioridad(4, $prioridad4).", ".setInPrioridad(5, $prioridad5).", ".setInPrioridad(6, $prioridad6).", ".setInPrioridad(7, $prioridad7).")";

if ($_REQUEST["codigoPostal"] != "") {
	$params[":cpostal"] = $_REQUEST["codigoPostal"];
	$where.= " AND es_cpostal = :cpostal";
}

if ($_REQUEST["cuit"] != "") {
	$params[":cuit"] = str_replace("-", "", $_REQUEST["cuit"]);
	$where.= " AND em_cuit = :cuit";
}

if ($_REQUEST["idEstablecimiento"] != -1) {
	$params[":estableci"] = $_REQUEST["idEstablecimiento"];
	$where.= " AND ep_estableci = :estableci";
}

if ($_REQUEST["fechaDesde"] != "") {
	$params[":fechadesde"] = $_REQUEST["fechaDesde"];
	$where.= " AND (:fechadesde <= vp_fechadesde or :fechadesde <= vp_fechahasta)";
}

if ($_REQUEST["fechaHasta"] != "") {
	$params[":fechahasta"] = $_REQUEST["fechaHasta"];
	$where.= " AND (:fechahasta >= vp_fechadesde or :fechahasta >= vp_fechahasta)";
}

if ($_REQUEST["idPreventor"] != -1) {
	$params[":idfirmante"] = $_REQUEST["idPreventor"];
	$where.= " AND ep_idfirmante = :idfirmante";
}

if ($_REQUEST["idProvincia"] != -1) {
	$params[":provincia"] = $_REQUEST["idProvincia"];
	$where.= " AND es_provincia = :provincia";
}


$sql =
	"SELECT em_id || '-' || ep_estableci ¿id?,
					¿ep_cuit?,
					¿em_nombre?,
					¿co_contrato?,
					¿ep_estableci?,
					art.afi.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, es_localidad) || NVL2(es_telefonos, ' Tel.' ||(NVL(es_codareatelefonos, '') || es_telefonos), '') ¿domicilioestab?,
					¿es_localidad?,
					¿es_cpostal?,
					art.utiles.get_partido(aes.es_cpostal, aes.es_provincia) ¿departamento?,
					art.getdescripcionprovincia(es_provincia) ¿prov?,
					pit.it_nombre ¿preventor?,
					¿vp_tarea?,
					¿vp_fechadesde?,
					¿vp_fechahasta?,
					¿vp_prioridad?,
					te_codigo ¿tipoestabprevencion?,
					co_tipo ¿tipoempresasrt?,
					DECODE(art.afiliacion.is_empresavip(co_contrato), 'S', 'VIP', 'N', NULL) ¿empresavip?,
					¿co_vigenciadesde?,
					¿co_vigenciahasta?
		 FROM hys.hvp_visitaplan, hys.hco_cuitoperativo hco, hys.hep_estabporpreventor hep, afi.aem_empresa, afi.aco_contrato aco, afi.aes_establecimiento aes, hys.hte_tipoempresaprev,
					art.pit_firmantes pit
		WHERE co_contrato = art.get_vultcontrato(em_cuit)
			AND hep.ep_idempresa = em_id
			AND em_id = aco.co_idempresa
			AND aes.es_nroestableci = hep.ep_estableci
			AND aco.co_contrato = aes.es_contrato
			AND hco.co_id(+) = ep_idcooperativo
			AND ep_idtipoestabprev = te_id(+)
			AND hep.ep_idfirmante = pit.it_id(+)
			AND NVL(co_totempleadosactual, co_totempleados) >= 1
			AND em_sector + 0 IN(2, 3, 4, 5)
			AND aes.es_fechabaja IS NULL
			AND vp_idempresa(+) = hep.ep_idempresa
			AND vp_establecimiento(+) = hep.ep_estableci
			AND vp_mes(+) = TO_CHAR(SYSDATE, 'YYYYMM')
			AND vp_fechabaja(+) IS NULL
			AND (es_idestabeventual <> 3 OR es_idestabeventual IS NULL)
			AND art.afiliacion.check_cobertura(co_contrato, SYSDATE) = 1 _EXC1_";
$grilla = new Grid(15, 30);
$grilla->addColumn(new Column(" ", 1, true, false, -1, "xbtnSeleccionar", "/modules/usuarios_registrados/preventores/seleccionar_empresa.php", "", -1, true, -1, "", false, "", "checkbox"));
$grilla->addColumn(new Column("C.U.I.T."));
$grilla->addColumn(new Column("Razón Social"));
$grilla->addColumn(new Column("Contrato"));
$grilla->addColumn(new Column("Nº Establecimiento"));
$grilla->addColumn(new Column("Domicilio Establecimiento"));
$grilla->addColumn(new Column("Localidad"));
$grilla->addColumn(new Column("Código Postal"));
$grilla->addColumn(new Column("Departamento"));
$grilla->addColumn(new Column("Provincia"));
$grilla->addColumn(new Column("Preventor"));
$grilla->addColumn(new Column("Tareas"));
$grilla->addColumn(new Column("Fecha Desde"));
$grilla->addColumn(new Column("Fecha Hasta"));
$grilla->addColumn(new Column("Prioridad"));
$grilla->addColumn(new Column("Tipo Estab. Prev."));
$grilla->addColumn(new Column("Tipo Empresa SRT"));
$grilla->addColumn(new Column("VIP"));
$grilla->addColumn(new Column("Vigencia Desde"));
$grilla->addColumn(new Column("Vigencia Hasta"));
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setShowProcessMessage(true);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->setUseTmpIframe(true);
$grilla->Draw();
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divProcesando').style.display = 'none';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
		getElementById('iframe2').src = '/modules/usuarios_registrados/preventores/check_grid.php';
	}
</script>