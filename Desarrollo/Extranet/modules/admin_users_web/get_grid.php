<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


function getGrid() {
	$ob = "2";
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];

	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$showProcessMsg = false;

	$params = array();
	$where = "";

	if ($_REQUEST["email"] != "") {
		$params[":email"] = "%".$_REQUEST["email"]."%";
		$where.= " AND uc_email LIKE :email";
	}
	if ($_REQUEST["id"] != "") {
		$params[":idusuarioextranet"] = $_REQUEST["id"];
		$where.= " AND uc_idusuarioextranet = :idusuarioextranet";
	}
	if ($_REQUEST["usuario"] != "") {
		$params[":nombre"] = "%".$_REQUEST["usuario"]."%";
		$where.= " AND uc_nombre LIKE :nombre";
	}

	$sql =
		"SELECT DISTINCT ¿uc_idusuarioextranet?, ¿uc_nombre?, ¿uc_email?
								FROM web.wuc_usuariosclientes, web.wcu_contratosxusuarios
							 WHERE uc_id = cu_idusuario
								 AND uc_fechabaja IS NULL
								 AND cu_contrato in (".$_SESSION["contratos"].") _EXC1_";
	$grilla = new Grid(15, 10);
	$grilla->addColumn(new Column("", 8, true, false, -1, "btnLupa", "show_usuario.php", "gridFirstColumn"));
	$grilla->addColumn(new Column("Nombre", 0, true, false, -1, "", "", "", -1, false));
	$grilla->addColumn(new Column("e-Mail", 0, true, false, -1, "", "", "", -1, false));
	$grilla->setColsSeparator(false);
	$grilla->setExtraConditions(array($where));
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRowsSeparator(true);
	$grilla->setRowsSeparatorColor("#c0c0c0");
	$grilla->setShowTotalRegistros(true);
	$grilla->setSql($sql);
	$grilla->setUseTmpIframe(true);

	return $grilla->Draw(false);
}
?>