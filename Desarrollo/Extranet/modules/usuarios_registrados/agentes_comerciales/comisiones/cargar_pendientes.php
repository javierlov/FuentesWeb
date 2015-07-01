<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion($_SESSION["comisiones"]);


if (isset($_REQUEST["c"])) {		// Calculo..
	$params = array(":identidad" => $_SESSION["entidad"], ":idvendedor" => $_SESSION["vendedor"]);
	$sql =
		"SELECT TRIM(TO_CHAR(SUM(monto_a_facturar), '9,999,999,990.00'))
			 FROM (SELECT DISTINCT lc_id, art.comision.get_montosinaplicarliquidacion(lc_id) monto_a_facturar, en_id
												FROM xen_entidad, xlc_liqcomision
											 WHERE lc_identidad = en_id
												 AND lc_comision <> 0
												 AND en_id = :identidad
												 AND lc_estado = 'A'
												 AND NVL(lc_fechaliq, SYSDATE) > TO_DATE('01/01/2010', 'dd/mm/yyyy')
									 UNION ALL
						 SELECT DISTINCT lc_id, art.comision.get_montosinaplicarliquidacion(lc_id) monto_a_facturar, en_id
												FROM xen_entidad, xve_vendedor, xev_entidadvendedor, xlc_liqcomision
											 WHERE lc_identidadvendedor = ev_id
												 AND ev_idvendedor = ve_id
												 AND en_id = ev_identidad
												 AND lc_comision <> 0
												 AND ve_id = :idvendedor
												 AND lc_estado = 'A'
												 AND NVL(lc_fechaliq, SYSDATE) > TO_DATE('01/01/2010', 'dd/mm/yyyy'))";
	$montoTotalFacturar = ValorSql($sql, "0", $params);
?>
	<script type="text/javascript">
		window.parent.document.getElementById('tituloPendientes').innerHTML = 'Monto Total a Facturar: $ <?= $montoTotalFacturar?>';
	</script>
<?
}
SetDateFormatOracle("DD/MM/YYYY");

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "1";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];
?>
<html>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<div id="divContentGrid" name="divContentGrid">
<?
$params = array(":identidad" => $_SESSION["entidad"], ":idvendedor" => $_SESSION["vendedor"]);
$where = " WHERE monto_a_facturar != 0";
$sql =
	"SELECT id ¿NO_id?,
					id ¿\"Liquidación\"?,
					fechacierre ¿\"Fecha Liquidación\"?,
					TO_CHAR(cobradoneto, '$9,999,999,990.00') ¿\"Cobrado Neto de Impuestos\"?,
					TO_CHAR(comision, '$9,999,999,990.00') ¿\"Monto Liquidado\"?,
					TO_CHAR(iva, '$9,999,999,990.00') ¿\"IVA\"?,
					TO_CHAR(monto_a_facturar, '$9,999,999,990.00') ¿\"Monto Facturar\"?,
					TO_CHAR(obrasocial, '$9,999,999,990.00') ¿\"Ret. OS\"?,
					TO_CHAR(ingbrutos, '$9,999,999,990.00') ¿\"Ret. IB\"?,
					TO_CHAR(ganancias, '$9,999,999,990.00') ¿\"Ret. Gcias\"?,
					TO_CHAR(retiva, '$9,999,999,990.00') ¿\"Ret. IVA\"?,
					TO_CHAR(comisionneta, '$9,999,999,990.00') ¿neto?,
					abs_monto_a_facturar ¿NO_abs_monto_a_facturar?,
					id ¿NO_id2?,
					id ¿NO_id3?,
					identidad ¿NO_identidad?
		 FROM (SELECT DISTINCT lc_id id,
													 lc_fechaliq fechacierre,
													 lc_cobradoneto cobradoneto,
													 lc_comision comision,
													 lc_iva iva,
													 lc_obrasocial obrasocial,
													 lc_ingbrutos ingbrutos,
													 lc_comisionneta comisionneta,
													 lc_ganancias ganancias,
													 lc_retiva retiva,
													 art.comision.get_montosinaplicarliquidacion(lc_id) monto_a_facturar,
													 ABS(art.comision.get_montosinaplicarliquidacion(lc_id)) abs_monto_a_facturar,
													 lc_id id2,
													 lc_id id3,
													 en_id identidad
											FROM xen_entidad, xlc_liqcomision
										 WHERE lc_identidad = en_id
											 AND lc_comision <> 0
											 AND en_id = :identidad
											 AND '' || lc_estado = 'A'
											 AND NVL(lc_fechaliq, SYSDATE) > TO_DATE('01/01/2010', 'dd/mm/yyyy')
				UNION ALL
					 SELECT DISTINCT lc_id id,
													 lc_fechaliq fechacierre,
													 lc_cobradoneto cobradoneto,
													 lc_comision comision,
													 lc_iva iva,
													 lc_obrasocial obrasocial,
													 lc_ingbrutos ingbrutos,
													 lc_comisionneta comisionneta,
													 lc_ganancias ganancias,
													 lc_retiva retiva,
													 art.comision.get_montosinaplicarliquidacion(lc_id) monto_a_facturar,
													 ABS(art.comision.get_montosinaplicarliquidacion(lc_id)) abs_monto_a_facturar,
													 lc_id id2,
													 lc_id id3,
													 en_id identidad
											FROM xen_entidad, xve_vendedor, xev_entidadvendedor, xlc_liqcomision
										 WHERE lc_identidadvendedor = ev_id
											 AND ev_idvendedor = ve_id
											 AND en_id = ev_identidad
											 AND lc_comision <> 0
											 AND ve_id = :idvendedor
											 AND '' || lc_estado = 'A'
											 AND NVL(lc_fechaliq, SYSDATE) > TO_DATE('01/01/2010', 'dd/mm/yyyy')) _EXC1_";
$grilla = new Grid(15, 7);
$grilla->addColumn(new Column("Mov", 0, true, false, -1, "btnLink ", "/modules/usuarios_registrados/agentes_comerciales/comisiones/cambiar_solapa.php?s=m", "", -1, true, -1, "Movimientos"));
$grilla->addColumn(new Column("Liquidación", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Fecha Liquidación", -1, true, false, -1, "", "", "gridColAlignCenter", -1, false));
$grilla->addColumn(new Column("Cobrado Neto de Impuestos", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Monto Liquidado", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("IVA", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Monto Facturar", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Ret. OS", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Ret. IB", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Ret. Gcias", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Ret. IVA", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Neto", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("", 0, false));
$grilla->addColumn(new Column("V", 0, true, false, -1, "btnLink ", "/modules/usuarios_registrados/agentes_comerciales/comisiones/cambiar_solapa.php?s=v", "", -1, true, -1, "Detalle de Vendedores"));
$grilla->addColumn(new Column("R", 0, true, false, -1, "btnLink ", "/modules/usuarios_registrados/agentes_comerciales/comisiones/cambiar_solapa.php?s=r", "", -1, true, -1, "Detalle de Retenciones"));
$grilla->addColumn(new Column("", 0, false));
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setShowMessageNoResults(false);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->setUseTmpIframe(true);
$grilla->Draw();

$_SESSION["fieldsAlignment"] = array("", "right", "center", "right", "right", "right", "right", "right", "right", "right", "right", "right");
$_SESSION["sqlLiquidacionesPendientes"] = $grilla->getSqlFinal(true);
?>
		</div>
		<script type="text/javascript">
			window.parent.document.getElementById('divContentGridPendientes').innerHTML = document.getElementById('divContentGrid').innerHTML;
<?
if ($grilla->recordCount() > 0) {
?>
			window.parent.document.getElementById('btnExportarPendientes').style.display = 'inline';
<?
}
?>
			if (window.parent.document.getElementById('originalGrid') != null)
				window.parent.document.getElementById('originalGrid').style.display = 'block';
			window.parent.document.getElementById('divProcesando').style.display = 'none';
<?
if (!isset($_REQUEST["c"])) {		// Si no calculé, calculo..
?>
			window.location.href = window.location.href + '&c=s';
<?
}
?>
		</script>
	</body>
</html>