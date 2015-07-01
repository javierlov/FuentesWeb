<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


function getGrid() {
	$ob = "1_D_";
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];
	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];
	$showProcessMsg = false;

	$sql =
		"SELECT ab_id ¿id?, ab_fechaalta ¿fecha?, ab_nombre ¿archivo?, ab_id ¿numerotransferencia?
			FROM web.wab_archivobapro
		 WHERE ab_tipo = 'R'";
	$grilla = new Grid(15, 10);
	$grilla->addColumn(new Column("", 0, false));
	$grilla->addColumn(new Column("Fecha", 0, true, false, -1, "", "", "colFecha", -1, false));
	$grilla->addColumn(new Column("Archivo", 0, true, false, -1, "", "", "colFecha", -1, false));
	$grilla->addColumn(new Column("Nº Transferencia", 0, true, false, -1, "", "", "colFecha", -1, false));
	$grilla->setColsSeparator(false);
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setRowsSeparator(true);
	$grilla->setRowsSeparatorColor("#c0c0c0");
	$grilla->setShowTotalRegistros(true);
	$grilla->setSql($sql);

	return $grilla->Draw(false);
}
?>