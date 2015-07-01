<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 95));


set_time_limit(120);

$pagina = $_SESSION["BUSQUEDA_AVISOS_OBRA"]["pagina"];
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = $_SESSION["BUSQUEDA_AVISOS_OBRA"]["ob"];
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$_SESSION["BUSQUEDA_AVISOS_OBRA"] = array("buscar" => "S",
																					"ob" => $ob,
																					"pagina" => $pagina);

$params = array(":contrato" => $_SESSION["contrato"]);
if ($_REQUEST["t"] == "p") {
	$sql =
		"SELECT TO_CHAR(NVL(ao_id, -1)) || '_-1_-1' ¿ids?,
						TO_CHAR(ao_id) || '_' || TO_CHAR(es_id) ¿pathpdf?,
						¿ao_estableci?,
						DECODE(ao_tipoformulario, 'AO', 'Aviso Obra', 'E', 'Extensión', 'S', 'Suspensión', 'SD', 'Suspensión Definitiva', 'R', 'Reinicio', 'M', 'Aviso Obra', 'Aviso Obra') ¿formulario?,
						¿es_calle?,
						¿es_numero?,
						¿es_localidad?,
						¿es_cpostal?,
						pv_descripcion ¿provincia?,
						'Recibido' ¿estado?,
						'btnPdf' ¿buttonclass?,
						DECODE(art.hys_avisoobraweb.get_pathavisoobra(ao_id, es_id), NULL, 'T', 'F') ¿hidecol1?
			 FROM art.cpv_provincias, art.pao_avisoobra, afi.aem_empresa, afi.aco_contrato, afi.aes_establecimiento
			WHERE es_contrato = co_contrato
				AND em_id = co_idempresa
				AND em_cuit = ao_cuit
				AND es_nroestableci = ao_estableci
				AND co_contrato = art.get_vultcontrato(em_cuit)
				AND pv_codigo = es_provincia
				AND ao_fechabaja IS NULL
				AND es_eventual = 'S'
				AND es_idestabeventual = 1
				AND NVL(ao_fechaextension, ao_fechafindeobra) >= TRUNC(SYSDATE)
				AND es_contrato = :contrato
	UNION ALL
		 SELECT TO_CHAR(NVL(aw_idavisoobra, -1)) || '_' || TO_CHAR(NVL(aw_id, -1)) || '_' || TO_CHAR(NVL(aw_resolucion, -1)) ids,
						NULL pathpdf,
						NULL,
						DECODE(aw_tipo, 'AO', 'Aviso Obra', 'E', 'Extensión', 'S', 'Suspensión', 'SD', 'Suspensión Definitiva', 'R', 'Reinicio', 'M', 'Modificación de Aviso') || DECODE(aw_tipoform, 0, ' (Res. 319/1999)', '') formulario,
						aw_calle,
						aw_numero,
						aw_localidad,
						aw_cpostal,
						pv_descripcion provincia,
						'Pendiente' estado,
						NULL buttonclass,
						'T' ¿hidecol1?
			 FROM art.cpv_provincias, hys.haw_avisoobraweb a, afi.aem_empresa, afi.aco_contrato
			WHERE em_id = co_idempresa
				AND co_contrato = art.get_vultcontrato(em_cuit)
				AND co_contrato = :contrato
				AND co_contrato = aw_contrato
				AND aw_fechabaja IS NULL
				AND aw_idavisoobra IS NULL
				AND pv_codigo = aw_provincia
				AND aw_estado = 'P'
	UNION ALL
		 SELECT '-1_' || TO_CHAR(NVL(aw_id, -1)) || '_' || TO_CHAR(NVL(aw_resolucion, -1)) ids,
						NULL pathpdf,
						NULL,
						DECODE(aw_tipo, 'AO', 'Aviso Obra', 'E', 'Extensión', 'S', 'Suspensión', 'SD', 'Suspensión Definitiva', 'R', 'Reinicio', 'M', 'Modificación de Aviso') || DECODE(aw_tipoform, 0, ' (Res. 319/1999)', '') formulario,
						aw_calle,
						aw_numero,
						aw_localidad,
						aw_cpostal,
						pv_descripcion provincia,
						NVL(am_descripcion, 'Recibido') estado,
						NULL buttonclass,
						'T' ¿hidecol1?
			 FROM hys.ham_avisoobramotivorechazo, art.cpv_provincias, hys.haw_avisoobraweb a, afi.aem_empresa, afi.aco_contrato
			WHERE em_id = co_idempresa
				AND co_contrato = art.get_vultcontrato(em_cuit)
				AND co_contrato = :contrato
				AND co_contrato = aw_contrato
				AND aw_fechabaja IS NULL
				AND aw_idavisoobra IS NULL
				AND pv_codigo = aw_provincia
				AND aw_idmotivorechazado = am_id(+)
				AND aw_fechamodif >= SYSDATE - 20
				AND aw_estado = 'R'";
}
else {
	$sql =
		"SELECT TO_CHAR(NVL(ao_id, -1)) || '_-1_-1' ¿ids?,
						¿ao_estableci?,
						'Aviso Obra' ¿formulario?,
						¿es_calle?,
						¿es_numero?,
						¿es_localidad?,
						¿es_cpostal?,
						pv_descripcion ¿provincia?
			 FROM art.cpv_provincias, art.pao_avisoobra, afi.aem_empresa, afi.aco_contrato, afi.aes_establecimiento
			WHERE es_contrato = co_contrato
				AND em_id = co_idempresa
				AND em_cuit = ao_cuit
				AND es_nroestableci = ao_estableci
				AND co_contrato = art.get_vultcontrato(em_cuit)
				AND pv_codigo = es_provincia
				AND ao_fechabaja IS NULL
				AND es_eventual = 'S'
				AND es_idestabeventual = 1
				AND es_fechabaja IS NULL
				AND NVL(ao_fechaextension, ao_fechafindeobra) >= TRUNC (SYSDATE)
				AND es_contrato = :contrato";
}
$grilla = new Grid();
$grilla->addColumn(new Column("Ver", 0, true, false, -1, "btnEditar", "/modules/usuarios_registrados/clientes/aviso_obra/editar.php?t=".$_REQUEST["t"], "", -1, true, -1, "Editar"));

if ($_REQUEST["t"] == "p")
	$grilla->addColumn(new Column("PDF", 0, true, false, -1, "", "/modules/usuarios_registrados/clientes/aviso_obra/ver_pdf.php", "", -1, true, -1, "Ver PDF", false, "", "button", -1, 11));

$grilla->addColumn(new Column("Número Establecimiento", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Tipo Formulario"));
$grilla->addColumn(new Column("Calle"));
$grilla->addColumn(new Column("Número"));
$grilla->addColumn(new Column("Localidad"));
$grilla->addColumn(new Column("Código Postal"));
$grilla->addColumn(new Column("Provincia"));

if ($_REQUEST["t"] == "p") {
	$grilla->addColumn(new Column("Estado"));
	$grilla->addColumn(new Column("", 0, false));
	$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 2));
}

$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setShowProcessMessage(true);
$grilla->setShowTotalRegistros(true);
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
	}
</script>