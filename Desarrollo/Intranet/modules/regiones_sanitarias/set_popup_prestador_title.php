<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$localidad = "Toda la región - ";
if (isset($_REQUEST["cp"])) {
	$sql =
		"SELECT ca_localidad, COUNT(*)
			 FROM art.cpr_prestador
			WHERE ca_codpostal IN (:codpostal)
	 GROUP BY ca_localidad
	 ORDER BY 2 DESC";
	$params = array(":codpostal" => $_REQUEST["cp"]);
	$localidad = ucwords(strtolower(ValorSql($sql, "", $params)))." - ";
}

$sql =
	"SELECT tp_descripcion
		FROM mtp_tipoprestador
	  WHERE tp_codigo = :codigo";
$params = array(":codigo" => $_REQUEST["prestador"]);
$prestador = ucwords(strtolower(ValorSql($sql, "", $params)));
?>
<script>
	parent.divWin.handle.firstChild.nodeValue = '<?= $localidad.$prestador?>';
</script>