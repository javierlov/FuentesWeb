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

$vendedor = "";
if ($_REQUEST["idven"] > 0) {
	$params = array(":id" => $_REQUEST["idven"]);
	$sql =
		"SELECT ve_nombre
			 FROM xve_vendedor
			WHERE ve_id = :id";
	$vendedor = "Vendedor: ".ValorSql($sql, "", $params);
}
?>
<html>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<div id="divContentGrid" name="divContentGrid">
<?
$where = " WHERE 1 = 1";
$params = array(":idliquidacion" => $_REQUEST["id"], ":idvendedor" => $_REQUEST["idven"]);
$sql =
	"SELECT ¿contrato?,
					¿cuit?,
					razonsocial ¿\"Razón Social\"?,
					periodo ¿\"Período\"?,
					TO_CHAR(cobradoneto, '$9,999,999,990.00') ¿\"Cobrado Neto de Impuestos\"?,
					TO_CHAR(comision, '$9,999,999,990.00') ¿\"Monto Liquidado\"?,
					concepto ¿\"Concepto\"?,
					identidad ¿NO_identidad?
		 FROM (SELECT co_contrato contrato,
									em_cuit cuit,
									em_nombre razonsocial,
									art.utiles.armar_periodo(pc_periodo) periodo,
									pc_cobradoneto cobradoneto,
									pc_comision comision,
									co_descripcion concepto,
									ev_identidad identidad
						 FROM aem_empresa, aco_contrato, avc_vendedorcontrato, xpc_pagocomision, xlc_liqcomision, xco_concepto, xev_entidadvendedor
						WHERE pc_idliqcomision = lc_id
							AND pc_idvendcontrato = vc_id(+)
							AND vc_contrato = co_contrato(+)
							AND co_idempresa = em_id(+)
							AND co_id = pc_idconcepto
							AND vc_identidadvend = ev_id(+)
							AND lc_id = :idliquidacion
							AND (ev_idvendedor = :idvendedor OR :idvendedor = -1)) _EXC1_";
/*
				UNION ALL
					 SELECT co_contrato contrato,
									em_cuit cuit,
									em_nombre razonsocial,
									art.utiles.armar_periodo(pc_periodo) periodo,
									pc_cobradoneto cobradoneto,
									pc_comision comision,
									co_descripcion concepto,
									ev_identidad identidad
						 FROM aem_empresa, aco_contrato, avc_vendedorcontrato, xen_entidad, xev_entidadvendedor,
									xpc_pagocomision, xlc_liqcomision, xco_concepto
						WHERE lc_identidadvendedor = ev_id
							AND ev_identidad = en_id
							AND (lc_identidadvendedor IS NOT NULL OR en_modoliq = '03')
							AND pc_idliqcomision = lc_id
							AND pc_idvendcontrato = vc_id(+)
							AND co_idempresa = em_id(+)
							AND vc_contrato = co_contrato(+)
							AND co_id = pc_idconcepto
							AND lc_id = :idliquidacion
							AND (ev_idvendedor = :idvendedor OR :idvendedor = -1)) _EXC1_";
*/
$grilla = new Grid();
$grilla->addColumn(new Column("Contrato", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("C.U.I.T.", -1, true, false, -1, "", "", "gridColAlignCenter", -1, false));
$grilla->addColumn(new Column("Razón Social"));
$grilla->addColumn(new Column("Período", -1, true, false, -1, "", "", "gridColAlignCenter", -1, false));
$grilla->addColumn(new Column("Cobrado Neto de Impuestos", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
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

$_SESSION["fieldsAlignment"] = array("right", "center", "left", "center", "right", "right", "left");
$_SESSION["sqlLiquidacionesMovimientos"] = $grilla->getSqlFinal(true);
?>
		</div>
		<script type="text/javascript">
			window.parent.document.getElementById('tituloMovimientos').innerHTML = '<?= $titulo?>';
			window.parent.document.getElementById('tituloMovimientosVendedor').innerHTML = '<?= $vendedor?>';
			window.parent.document.getElementById('divContentGridMovimientos').innerHTML = document.getElementById('divContentGrid').innerHTML;
			window.parent.document.getElementById('btnExportarMovimientos').style.display = 'inline';
			window.parent.document.getElementById('originalGrid').style.display = 'block';
			window.parent.document.getElementById('divProcesando').style.display = 'none';
		</script>
	</body>
</html>