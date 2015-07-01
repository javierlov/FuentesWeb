<?
$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT bn_html, bn_nombre
		 FROM rrhh.rbn_beneficios
		WHERE bn_fechabaja IS NULL
			AND bn_idmenu = :id";
$stmt = DBExecSql($conn, $sql, $params);

if (DBGetRecordCount($stmt) == 0) {
	echo '<h2 id="divError">Este beneficio no está disponible.</h2>';
	return;
}
$row = DBGetQuery($stmt);
?>
<div style="margin-right:24px;"><?= preg_replace("/[\n|\r|\n\r]/i", "", $row["BN_HTML"]->load())?></div>
<script type="text/javascript">
	document.getElementById('divTituloSeccion').innerText = 'BENEFICIOS - <?= StringToUpper($row["BN_NOMBRE"])?>';
</script>