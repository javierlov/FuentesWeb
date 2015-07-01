<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion($_SESSION["comisiones"]);

SetDateFormatOracle("DD/MM/YYYY");

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "1";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT lc_fechaliq
		 FROM xlc_liqcomision
		WHERE lc_id = :id";
$titulo = "Liquidación Nº ".$_REQUEST["id"]." del ".ValorSql($sql, "", $params);
?>
<html>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<div id="divContentGrid" name="divContentGrid">
<?
$where = " WHERE 1 = 1";
$params = array(":idliquidacion" => $_REQUEST["id"]);
$sql =
	"SELECT id ¿NO_id?,
					¿vendedor?,
					¿entidad?,
					TO_CHAR(cobrado, '$9,999,999,990.00') ¿\"Total Cobrado\"?,
					TO_CHAR(sumacobradoneto, '$9,999,999,990.00') ¿\"Cobrado Neto\"?,
					TO_CHAR(sumacomision, '$9,999,999,990.00') ¿\"Monto Liquidado\"?,
					¿concepto?,
					identidad ¿NO_identidad?
		 FROM (SELECT TO_CHAR(lc_id) || '_' || TO_CHAR(ve_id) id,
									ve_nombre vendedor,
									en_nombre entidad,
									SUM(pc_cobrado) cobrado,
									SUM(pc_cobradoneto) sumacobradoneto,
									SUM(pc_comision) sumacomision,
									co_descripcion concepto,
									ev_identidad identidad
						 FROM xen_entidad, avc_vendedorcontrato, xcp_cierrepago, xpc_pagocomision, xlc_liqcomision, xco_concepto, xev_entidadvendedor, xve_vendedor
						WHERE lc_idcierrepago = cp_id
							AND lc_identidad = en_id
							AND pc_idliqcomision = lc_id
							AND pc_idvendcontrato = vc_id(+)
							AND vc_identidadvend = ev_id(+)
							AND ev_idvendedor = ve_id(+)
							AND co_id = pc_idconcepto
							AND lc_id = :idliquidacion
				 GROUP BY TO_CHAR(lc_id) || '_' || TO_CHAR(ve_id), ve_nombre, en_nombre, co_descripcion, ev_identidad
				UNION ALL
					 SELECT TO_CHAR(lc_id) || '_' || TO_CHAR(ve_id) id,
									ve_nombre vendedor,
									en_nombre entidad,
									SUM(pc_cobrado) cobrado,
									SUM(pc_cobradoneto) sumacobradoneto,
									SUM(pc_comision) sumacomision,
									co_descripcion concepto,
									ev_identidad identidad
						 FROM xve_vendedor, avc_vendedorcontrato, xcp_cierrepago, xen_entidad, xev_entidadvendedor, xpc_pagocomision, xlc_liqcomision, xco_concepto
						WHERE lc_identidadvendedor = ev_id
							AND ev_idvendedor = ve_id
							AND ev_identidad = en_id
							AND (lc_identidadvendedor IS NOT NULL OR en_modoliq = '03')
							AND pc_idliqcomision = lc_id
							AND pc_idvendcontrato = vc_id(+)
							AND lc_idcierrepago = cp_id
							AND co_id = pc_idconcepto
							AND lc_id = :idliquidacion
				 GROUP BY TO_CHAR(lc_id) || '_' || TO_CHAR(ve_id), ve_nombre, en_nombre, co_descripcion, ev_identidad) _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("Mov", 0, true, false, -1, "btnLink ", "/modules/usuarios_registrados/agentes_comerciales/comisiones/cambiar_solapa.php?s=m", "", -1, true, -1, "Movimientos"));
$grilla->addColumn(new Column("Vendedor"));
$grilla->addColumn(new Column("", 0, false));
$grilla->addColumn(new Column("Total Cobrado", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Cobrado Neto", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Monto Liquidado", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Concepto"));
$grilla->addColumn(new Column("", 0, false));
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->setUseTmpIframe(true);
$grilla->Draw();

$_SESSION["fieldsAlignment"] = array("", "left", "left", "right", "right", "right", "left");
$_SESSION["sqlLiquidacionesVendedores"] = $grilla->getSqlFinal(true);
?>
		</div>
		<script type="text/javascript">
			window.parent.document.getElementById('tituloVendedores').innerHTML = '<?= $titulo?>';
			window.parent.document.getElementById('divContentGridVendedores').innerHTML = document.getElementById('divContentGrid').innerHTML;
			window.parent.document.getElementById('btnExportarVendedores').style.display = 'inline';
			window.parent.document.getElementById('originalGrid').style.display = 'block';
			window.parent.document.getElementById('divProcesando').style.display = 'none';
		</script>
	</body>
</html>