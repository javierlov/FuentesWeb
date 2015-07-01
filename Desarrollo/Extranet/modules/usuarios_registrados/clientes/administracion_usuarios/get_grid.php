<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


function formatEstablecimientos($establecimientos) {
	$result = "''";

	while (strlen($establecimientos) > 0) {
		$result.= " || TO_CLOB('".substr($establecimientos, 0, 200)."')";
		$establecimientos = substr($establecimientos, 200);
	}

	return $result;
}

function getGrid($idCliente) {
	$ob = "2";
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];
	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];
	$showProcessMsg = false;

	$sql = "SELECT uc_id FROM web.wuc_usuariosclientes WHERE uc_idusuarioextranet = :idusuarioextranet";
	$params = array(":idusuarioextranet" => $idCliente);
	$idUsuarioCliente = ValorSql($sql, "", $params);

	$params = array(":contrato" => $_SESSION["contrato"], ":idcliente" => $idUsuarioCliente);
	$sql =
		"SELECT ¿es_id?, ¿es_nombre?, art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, NULL) || ' ' ||
						art.utiles.armar_localidad(es_cpostal, es_cpostala, es_localidad, es_provincia) ¿direccion?,
						DECODE(INSTR(".formatEstablecimientos($_SESSION["establecimientosUsuario"]).", ',' || TO_CHAR(es_id) || ','), 0, ' ', 'checked') ¿checked?
			FROM aes_establecimiento, web.wel_establecimientoscliente
		 WHERE es_id = el_idestablecimiento(+)
			  AND es_contrato = :contrato
			  AND el_idcliente(+) = :idcliente
			  AND es_fechabaja IS NULL";
	$grilla = new Grid(10, 5);
	$grilla->addColumn(new Column(" ", 1, true, false, -1, "nada", "/modules/usuarios_registrados/clientes/administracion_usuarios/check_grid_establecimientos.php", "", -1, true, -1, "", false, "", "checkbox", 4));
	$grilla->addColumn(new Column("Nombre"));
	$grilla->addColumn(new Column("Dirección"));
	$grilla->addColumn(new Column("", -1, false));
	$grilla->setColsSeparator(true);
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRefreshIntoWindow(true);
	$grilla->setRowsSeparator(true);
	$grilla->setRowsSeparatorColor("#c0c0c0");
	$grilla->setShowTotalRegistros(false);
	$grilla->setSql($sql);
	$grilla->setTableStyle("GridTableCiiu");
	$grilla->setUseTmpIframe(true);

	return $grilla->Draw(false);
}
?>