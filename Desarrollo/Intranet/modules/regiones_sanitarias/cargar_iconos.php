<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");

?>
<script src="/modules/regiones_sanitarias/js/regiones_sanitarias.js?rnd=<?= date("Ymdhisu")?>" type="text/javascript"></script>
<script>
	obj = parent.document.getElementById('divMapa').firstChild;
	while (obj != null) {
		if ((obj.id != null) && (obj.id != 'imgMapa')) {
			var obj2 = obj.nextSibling;
			var padre = obj.parentNode;
			padre.removeChild(obj);
			obj = obj2;
		}
		else
			obj = obj.nextSibling;
	}

<?
$params = array(":especialidad" => $_REQUEST["c"], ":id" => $_REQUEST["id"]);
$sql =
	"SELECT DISTINCT cp_codigo, ra_coordenadax, ra_coordenaday
							FROM comunes.cra_coordregionessanitarias, ccp_codigopostal, art.cpr_prestador
						 WHERE ra_codigopostal = cp_codigo
							 AND cp_codigo = ca_codpostal
							 AND ca_cartillaweb IN ('A', 'M')
							 AND ra_fechabaja IS NULL
							 AND cp_fechabaja IS NULL
							 AND ca_fechabaja IS NULL".
							 (($_REQUEST["t"] == "p")?" AND cp_provincia = :id":" AND cp_regionsanitaria = :id")."
							 AND ca_especialidad = :especialidad";
$stmt = DBExecSql($conn, $sql, $params);
while ($row = DBGetQuery($stmt)) {
?>
	agregarImagen('img<?= $row["CP_CODIGO"]?>', <?= $row["RA_COORDENADAX"]?>, <?= $row["RA_COORDENADAY"]?>, parent.document);
<?
}
?>
</script>