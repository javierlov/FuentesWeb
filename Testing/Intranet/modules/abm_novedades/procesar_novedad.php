<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


if ($_REQUEST["TipoOp"] == "A") {		// Alta..
	$params = array(":id" => -1,
									":idsectordesde" => IIF(($_REQUEST["SectorDesde"] == "-1"), NULL, $_REQUEST["SectorDesde"]),
									":idsectorhasta" => IIF(($_REQUEST["SectorHasta"] == "-1"), NULL, $_REQUEST["SectorHasta"]),
									":idusuario" => $_REQUEST["Usuario"],
									":tipomovimiento" => $_REQUEST["TipoMovimiento"],
									":usualta" => GetWindowsLoginName(true));
	$sql =
		"INSERT INTO rrhh.rhn_novedades (hn_id, hn_idusuario, hn_tipomovimiento, hn_idsectordesde, hn_idsectorhasta, hn_fechaalta, hn_usualta)
														 VALUES (:id, :idusuario, :tipomovimiento, :idsectordesde, :idsectorhasta, SYSDATE, :usualta)";
	DBExecSql($conn, $sql, $params);
}

if ($_REQUEST["TipoOp"] == "M") {		// Modificación..
	$params = array(":id" => $_REQUEST["id"],
									":idsectordesde" => IIF(($_REQUEST["SectorDesde"] == "-1"), NULL, $_REQUEST["SectorDesde"]),
									":idsectorhasta" => IIF(($_REQUEST["SectorHasta"] == "-1"), NULL, $_REQUEST["SectorHasta"]),
									":tipomovimiento" => $_REQUEST["TipoMovimiento"],
									":usumodif" => GetWindowsLoginName(true));
	$sql =
		"UPDATE rrhh.rhn_novedades
				SET hn_tipomovimiento = :tipomovimiento,
						hn_idsectordesde = :idsectordesde,
						hn_idsectorhasta = :idsectorhasta,
						hn_fechamodif = SYSDATE,
						hn_usumodif = :usumodif
		  WHERE hn_id = :id";
	DBExecSql($conn, $sql, $params);
}

if ($_REQUEST["TipoOp"] == "B") {		// Baja..
	$params = array(":id" => $_REQUEST["id"], ":usubaja" => GetWindowsLoginName(true));
	$sql =
		"UPDATE rrhh.rhn_novedades
				SET hn_fechabaja = SYSDATE,
						hn_usubaja = :usubaja
		  WHERE hn_id = :id";
	DBExecSql($conn, $sql, $params);
}
?>
<script>
<?
if ($dbError["offset"]) {
?>
	alert('<?= $dbError["message"]?>');
<?
}
else {
	if (isset($_REQUEST["Usuario"]))
		$user = $_REQUEST["Usuario"];
	else {
		$params = array(":id" => $_REQUEST["id"]);
		$sql =
			"SELECT hn_idusuario
				 FROM rrhh.rhn_novedades
				WHERE hn_id = :id";
		$user = ValorSql($sql, "-1", $params);
	}
?>
	window.parent.location.href = '/index.php?pageid=10&buscar=yes&Usuario=<?= $user?>';
<?
}
?>
</script>