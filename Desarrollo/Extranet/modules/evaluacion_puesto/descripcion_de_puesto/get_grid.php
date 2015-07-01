<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


function getGrid() {
	$ob = "1_D_";
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];
	$pagina = 1;
	$showProcessMsg = false;

	$params = array(":idlogin" => $_SESSION["idEvaluado"]);
	$sql =
		"SELECT pd_id ¿id?, pd_quehace ¿quehace?, pd_paraquelohace ¿paraquelohace?, pd_comolohizo ¿comolohizo?
			 FROM rrhh.dpd_descripcion
			WHERE pd_idlogin = :idlogin";
	$grilla = new Grid(1, 100);
	$grilla->addColumn(new Column("", 0, true, false, -1, "BotonInformacion", "seleccionar_tarea.php", "gridFirstColumn"));
	$grilla->addColumn(new Column("Que hace"));
	$grilla->addColumn(new Column("Para que lo hace"));
	$grilla->addColumn(new Column("Como se sabe lo que hizo"));
	$grilla->setColsSeparator(true);
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRowsSeparator(true);
	$grilla->setShowFooter(false);
	$grilla->setSql($sql);
	$grilla->setUseTmpIframe(true);

	return $grilla->Draw(false);
}
?>