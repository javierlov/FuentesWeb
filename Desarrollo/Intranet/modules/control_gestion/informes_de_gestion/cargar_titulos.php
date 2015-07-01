<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


if ($_REQUEST["valor"] < 0)
	$sql =
		"SELECT -2 id, 'Sistema de Información Ejecutiva' detalle
			 FROM DUAL
	UNION ALL
		 SELECT -3, 'Sistema de Información de Gestión'
			 FROM DUAL
	UNION ALL
		 SELECT -4, 'Sistema de Información Operativa'
			 FROM DUAL
	 ORDER BY 2";
else
	$sql =
		"SELECT ip_id id, ip_titulo detalle
			 FROM intra.cip_informepublicado
			WHERE ip_fechabaja IS NULL
				AND ip_idtema = :idtema
	 ORDER BY 2";
$comboTitulo = new Combo($sql, "titulo");

if ($_REQUEST["valor"] >= 0) {
	$comboTitulo->addParam(":idtema", $_REQUEST["valor"]);
?>
<script>
//	window.parent.document.getElementById('titulo').innerHTML = '<?= $comboTitulo->draw();?>';
	var combo = '<?= $comboTitulo->draw();?>';
	window.parent.document.getElementById('titulo').insertBefore(combo, window.parent.document.getElementById('titulo'));
</script>