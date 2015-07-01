<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");

validarSesion(isset($_SESSION["isAgenteComercial"]));

set_time_limit(120);

$pagina = $_SESSION["BUSQUEDA_COTIZACIONES_AFILIACIONES"]["pagina"];
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = $_SESSION["BUSQUEDA_COTIZACIONES_AFILIACIONES"]["ob"];
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$_SESSION["BUSQUEDA_COTIZACIONES_AFILIACIONES"] = array("buscar" => "S",
																												"cuit" => $_REQUEST["cuit"],
																												"fechaDesde" => $_REQUEST["fechaDesde"],
																												"fechaHasta" => $_REQUEST["fechaHasta"],
																												"numero" => $_REQUEST["numero"],
																												"ob" => $ob,
																												"pagina" => $pagina,
																												"razonSocial" => $_REQUEST["razonSocial"]);


$params = array(":usuario" => "W_".$_SESSION["usuario"]);

$where = "";
$where2 = "";

if ($_SESSION["canal"] != "") {
	$params[":idcanal"] = $_SESSION["canal"];
	$where.= " AND sc_canal = :idcanal";
	$where2.= " AND sr_idcanal = :idcanal";
}

if ($_SESSION["entidad"] != "") {
	$params[":identidad"] = $_SESSION["entidad"];
	$where.= " AND sc_identidad = :identidad";
	$where2.= " AND sr_identidad = :identidad";
}


//******  INICIO PEDIDO EVILA EL 11.9.2012  *******
if ($_SESSION["usuario"] == "N00001") {
	$where.= " AND EXISTS(SELECT 1
													FROM asc_solicitudcotizacion sc2, asu_sucursal
												 WHERE sc2.sc_idsucursal = su_id
													 AND su_idcentroreg = 50
													 AND sc.sc_id = sc2.sc_id)";
}
//******  FIN PEDIDO EVILA EL 11.9.2012  *******


if ($_SESSION["sucursal"] != "") {
	$params[":idsucursal"] = $_SESSION["sucursal"];
	$where.= " AND sc_idsucursal = :idsucursal";
	$where2.= " AND sr_idsucursal = :idsucursal";
}

if ($_SESSION["vendedor"] != "") {
	$params[":idvendedor"] = $_SESSION["vendedor"];
	$where.= " AND sc_idvendedor = :idvendedor";
	$where2.= " AND sr_idvendedor = :idvendedor";
}

if ($_REQUEST["numero"] != "") {
	$params[":nrosolicitud"] = $_REQUEST["numero"];
	$where.= " AND sc_nrosolicitud = :nrosolicitud";
	$where2.= " AND sr_nrosolicitud = :nrosolicitud";
}

if ($_REQUEST["fechaDesde"] != "") {
	$params[":fechadesde"] = $_REQUEST["fechaDesde"];
	$where.= " AND sc_fechasolicitud >= TO_DATE(:fechadesde, 'dd/mm/yyyy')";
	$where2.= " AND sr_fechaalta >= TO_DATE(:fechadesde, 'dd/mm/yyyy')";
}

if ($_REQUEST["fechaHasta"] != "") {
	$params[":fechahasta"] = $_REQUEST["fechaHasta"];
	$where.= " AND sc_fechasolicitud <= TO_DATE(:fechahasta, 'dd/mm/yyyy')";
	$where2.= " AND sr_fechaalta <= TO_DATE(:fechahasta, 'dd/mm/yyyy')";
}

if ($_REQUEST["cuit"] != "") {
	$params[":cuit"] = sacarGuiones($_REQUEST["cuit"]);
	$where.= " AND sc_cuit = :cuit";
	$where2.= " AND sr_cuit = :cuit";
}

if ($_REQUEST["razonSocial"] != "") {
	$params[":razonsocial"] = "%".strtoupper($_REQUEST["razonSocial"])."%";
	$where.= " AND UPPER(sc_razonsocial) LIKE :razonsocial";
	$where2.= " AND UPPER(em_nombre) LIKE :razonsocial";
}

$sql =
	"SELECT ¿modulo?,
					¿sc_nrosolicitud?,
					sc_fechasolicitud ¿fechasolicitud?,
					art.utiles.armar_cuit(sc_cuit) ¿cuit?,
					¿sc_razonsocial?,
					¿sc_canttrabajador?,
					TO_CHAR(tc_sumafija, '$9,990.00') ¿sumafija?,
					DECODE(tc_porcvariable, NULL, NULL, to_char(tc_porcvariable, '990.0000') || '%') ¿porcvariable?,
					TO_CHAR(tc_costofinal, '$9,990.00') ¿costofinal?,
					¿fechavencimiento?,
					¿estado?,
					id1 ¿id1?,
					id2 ¿id2?,
					id3 ¿id3?,
					id4 ¿id4?,
					hidecol1 ¿hidecol1?,
					hidecol2 ¿hidecol2?,
					hidecol3 ¿hidecol3?
		 FROM (SELECT 'C' modulo,
									sc_nrosolicitud,
									sc_fechasolicitud,
									sc_cuit,
									sc_razonsocial,
									sc_canttrabajador,
									CASE WHEN ((UPPER(art.webart.get_estado_solicitud(sc_id)) = 'En proceso de análisis.') OR (co_fechacerrado IS NULL))
										THEN TO_NUMBER(NULL)
										ELSE tc_sumafija
									END tc_sumafija,
									CASE WHEN ((UPPER(art.webart.get_estado_solicitud(sc_id)) = 'En proceso de análisis.') OR (co_fechacerrado IS NULL))
										THEN TO_NUMBER(NULL)
										ELSE tc_porcvariable
									END tc_porcvariable,
									CASE WHEN ((UPPER(art.webart.get_estado_solicitud(sc_id)) = 'En proceso de análisis.') OR (co_fechacerrado IS NULL))
										THEN TO_NUMBER(NULL)
										ELSE tc_costofinal
									END tc_costofinal,
									sc_fechavigencia + CASE WHEN ca_tipo = 'B' THEN 60 ELSE 30 END fechavencimiento,
 									art.webart.get_estado_solicitud(sc_id) estado,
									'c' || sc_id id1,
									'c' || sc_id id2,
									'c' || sc_id id3,
									'c' || sc_id id4,
									DECODE(art.cotizacion.get_imprimircotizacion(sc_id, 'C'), 'T', 'F', 'T') hidecol1,
									CASE WHEN sc_nrosolicitud in (65060, 66376, 90832, 90891, 138794)
										THEN 'F'
										ELSE DECODE(art.afiliacion.get_imprimirsolicitud(sc_id, 'C'), 'T', 'F', 'T')
									END hidecol2,
									DECODE(art.webart.get_imprimir_f817(sc_id, 'C'), 'T', 'F', 'T') hidecol3
						 FROM asc_solicitudcotizacion sc, aco_cotizacion cotizaciones, atc_tarifacobrar, aca_canal
						WHERE sc_idcotizacion = co_id
							AND tc_id = art.cotizacion.get_idultimatarifa(co_id)
							AND sc_canal = ca_id
							AND sc_fechaalta > (sysdate - 183)
							AND art.cotizacion.essuscripciones(sc_usuariosolicitud, :usuario) = 1 _EXC1_
				UNION ALL
					 SELECT 'C',
									sc_nrosolicitud,
									sc_fechasolicitud,
									sc_cuit,
									sc_razonsocial,
									sc_canttrabajador,
									CASE WHEN (UPPER(art.webart.get_estado_solicitud(sc_id)) = 'En proceso de análisis.')
										THEN TO_NUMBER(NULL)
										ELSE sc_finalsumafija
									END,
									CASE WHEN (UPPER(art.webart.get_estado_solicitud(sc_id)) = 'En proceso de análisis.')
										THEN TO_NUMBER(NULL)
										ELSE sc_finalporcmasa
									END,
									CASE WHEN (UPPER(art.webart.get_estado_solicitud(sc_id)) = 'En proceso de análisis.')
										THEN TO_NUMBER(NULL)
										ELSE sc_finalportrabajador
									END,
									sc_fechavigencia + CASE WHEN ca_tipo = 'B' THEN 60 ELSE 30 END fechavencimiento,
				  				art.webart.get_estado_solicitud(sc_id),
									'c' || sc_id,
									'c' || sc_id,
									'c' || sc_id,
									'c' || sc_id,
				  				DECODE(art.cotizacion.get_imprimircotizacion(sc_id, 'C'), 'T', 'F', 'T') hidecol1,
									CASE WHEN sc_nrosolicitud in (65060, 66376, 90832, 90891, 138794)
										THEN 'F'
										ELSE DECODE(art.afiliacion.get_imprimirsolicitud(sc_id, 'C'), 'T', 'F', 'T')
									END hidecol2,
									DECODE(art.webart.get_imprimir_f817(sc_id, 'C'), 'T', 'F', 'T') hidecol3
						 FROM asc_solicitudcotizacion sc, aca_canal
						WHERE sc_canal = ca_id
							AND sc_idcotizacion IS NULL
							AND sc_fechaalta > (sysdate - 183)
							AND art.cotizacion.essuscripciones(sc_usuariosolicitud, :usuario) = 1 _EXC2_
				UNION ALL
					 SELECT 'R',
									sr_nrosolicitud,
									sr_fechaalta,
									sr_cuit,
									em_nombre,
									sr_canttrabajadores,
									sr_costofijocotizado,
									sr_porcentajevariablecotizado,
									sr_costofinalcotizado,
									sr_fechanotificacioncomercial + CASE WHEN ca_tipo = 'B' THEN 60 ELSE 30 END fechavencimiento,
									est.tb_descripcion,
									'r' || sr_id,
									'r' || sr_id,
									'r' || sr_id,
									'r' || sr_id,
									DECODE(art.cotizacion.get_imprimircotizacion(sr_id, 'R'), 'T', 'F', 'T') hidecol1,
									DECODE(art.afiliacion.get_imprimirsolicitud(sr_id, 'R'), 'T', 'F', 'T') hidecol2,
									DECODE(art.webart.get_imprimir_f817(sr_id, 'R'), 'T', 'F', 'T') hidecol3
						 FROM asr_solicitudreafiliacion, aem_empresa, aca_canal, ctb_tablas est
						WHERE sr_cuit = em_cuit
							AND sr_idcanal = ca_id
							AND est.tb_codigo(+) = sr_estadosolicitud
							AND est.tb_clave(+) = 'ACOES'
							AND sr_idmotivosolicitud IN (9, 21)
							AND sr_fechaalta > (sysdate - 183)
							AND art.cotizacion.essuscripciones(sr_usualta, :usuario) = 1 _EXC3_)";
$grilla = new Grid();
$grilla->addColumn(new Column("Tipo", -1, true, false, -1, "", "", "colFecha", -1, false));
$grilla->addColumn(new Column("Nº", -1, true, false, -1, "", "", "colAlignRight", -1, false));
$grilla->addColumn(new Column("Fecha Solicitud", -1, true, false, -1, "", "", "colFecha", -1, false));
$grilla->addColumn(new Column("CUIT", -1, true, false, -1, "", "", "colFecha", -1, false));
$grilla->addColumn(new Column("Razón Social"));
$grilla->addColumn(new Column("Cant. Trab.", -1, true, false, -1, "", "", "colAlignRight", -1, false));
$grilla->addColumn(new Column("Suma Fija", -1, true, false, -1, "", "", "colAlignRight", -1, false));
$grilla->addColumn(new Column("Porc. Masa", -1, true, false, -1, "", "", "colAlignRight", -1, false));
$grilla->addColumn(new Column("Final x Trab.", -1, true, false, -1, "", "", "colAlignRight", -1, false));
$grilla->addColumn(new Column("Fecha Vencimiento", -1, true, false, -1, "", "", "colFecha", -1, false));
$grilla->addColumn(new Column("Estado"));
$grilla->addColumn(new Column("S", -1, true, false, -1, "btnVer", "/cotizacion", "gridFirstColumn", -1, false, -1, "Ver Solicitud", false, "", "button", -1, -1, true));
$grilla->addColumn(new Column("C", 0, true, false, -1, "btnCarta", "/carta-cotizacion", "gridFirstColumn", -1, false, -1, "Ver Carta", false, "", "button", -1, -1, true));
$grilla->addColumn(new Column("A", 0, true, false, -1, "btnAfiliacion", "/solicitud-afiliacion", "gridFirstColumn", -1, false, -1, "Afiliar", false, "", "button", -1, -1, true));
$grilla->addColumn(new Column("F817", 0, true, false, -1, "btnPdf", "/formulario-817", "gridFirstColumn", -1, false, -1, "Imprimir Formulario 817", false, "", "button", -1, -1, true));
$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 13));
$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 14));
$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 15));
$grilla->setColsSeparator(true);
$grilla->setColsSeparatorColor("#c0c0c0");
$grilla->setExtraConditions(array($where, $where, $where2));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRowsSeparator(true);
$grilla->setRowsSeparatorColor("#c0c0c0");
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