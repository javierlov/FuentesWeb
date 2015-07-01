<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


// Feriados..
$params = array(":fecha" => "01/".$_REQUEST["m"]."/".$_REQUEST["a"]);
$sql =
	"SELECT TO_CHAR(fe_fecha, 'DD') dia, fe_descripcion
		 FROM cfe_feriados
		WHERE fe_fecha BETWEEN TRUNC(TO_DATE(:fecha, 'DD/MM/YYYY'), 'month') AND TRUNC(LAST_DAY(TO_DATE(:fecha, 'DD/MM/YYYY')))
UNION ALL
	 SELECT TO_CHAR(fd_fecha, 'DD') dia, el_nombre || ' - '|| fd_descripcion
		 FROM comunes.cfd_feriadosdelegaciones, del_delegacion
		WHERE fd_iddelegacion = el_id
			AND fd_fechabaja IS NULL
			AND fd_fecha BETWEEN TRUNC(TO_DATE(:fecha, 'DD/MM/YYYY'), 'month') AND TRUNC(LAST_DAY(TO_DATE(:fecha, 'DD/MM/YYYY')))
 ORDER BY dia";
$stmt = DBExecSql($conn, $sql, $params);
$totalFeriados = DBGetRecordCount($stmt);
$feriados = "<table style=\"cursor:default;\">";
while ($row = DBGetQuery($stmt))
	$feriados.= "<tr><td align=\"right\" style=\"vertical-align:top;\">".(int)$row["DIA"].".</td><td id=\"feriado".(int)$row["DIA"]."\">".$row["FE_DESCRIPCION"]."</td></tr>";
$feriados.= "</table>";


// Eventos..
$params = array(":fechaevento" => "01/".$_REQUEST["m"]."/".$_REQUEST["a"]);
if ($_REQUEST["vp"] == "t")
	$sql =
		"SELECT cl_destino, cl_link, cl_textoevento, TO_CHAR(cl_fechaevento, 'DD') dia
			 FROM rrhh.rcl_calendario
			WHERE cl_vistaprevia = 'S'
				AND cl_fechabaja IS NULL
				AND cl_fechaevento BETWEEN TRUNC(TO_DATE(:fechaevento, 'DD/MM/YYYY'), 'month') AND TRUNC(LAST_DAY(TO_DATE(:fechaevento, 'DD/MM/YYYY')))
	 ORDER BY dia";
else
	$sql =
		"SELECT cl_destino, cl_link, cl_textoevento, TO_CHAR(cl_fechaevento, 'DD') dia
			 FROM rrhh.rcl_calendario
			WHERE art.actualdate BETWEEN TRUNC(cl_fechavigenciadesde) AND cl_fechavigenciahasta
				AND cl_fechabaja IS NULL
				AND cl_fechaevento BETWEEN TRUNC(TO_DATE(:fechaevento, 'DD/MM/YYYY'), 'month') AND TRUNC(LAST_DAY(TO_DATE(:fechaevento, 'DD/MM/YYYY')))
	 ORDER BY dia";
$stmt = DBExecSql($conn, $sql, $params);
$totalEventos = DBGetRecordCount($stmt);
$eventos = "<div id=\"divEventosTitulo\">EVENTOS</div>";
$eventos.= "<table style=\"cursor:default;\">";
while ($row = DBGetQuery($stmt)) {
	$eventos.= "<tr>";
	$eventos.= "<td align=\"right\" style=\"vertical-align:top;\">".(int)$row["DIA"].".</td>";
	$eventos.= "<td id=\"evento".(int)$row["DIA"]."\">";

	if ($row["CL_LINK"] != "")		// Si tiene link, abro el tag a y el href..
		$eventos.= "<a style=\"color:#317282;\" href=\"";

	if (strpos($row["CL_LINK"], "@"))		// Si el link es un e-mail, agrego el mailto..
		$eventos.= "mailto:";

	if ($row["CL_LINK"] != "")		// Si tiene link, cierro el href
		$eventos.= $row["CL_LINK"]."\"";

	if (($row["CL_LINK"] != "") and ($row["CL_DESTINO"] != ""))		// Si tiene link y tiene destino, agrego el target..
		$eventos.= " target=\"".$row["CL_DESTINO"]."\"";

	if ($row["CL_LINK"] != "")		// Si tiene link, cierro el tag a..
		$eventos.= ">";

	$eventos.= $row["CL_TEXTOEVENTO"];

	if ($row["CL_LINK"] != "")		// Si tiene link, abro el tag /a..
		$eventos.= "</a>";

	$eventos.= "</td></tr>";
}
$eventos.= "</table>";
?>
<script>
	with (window.parent.document) {
		getElementById('divFeriados').innerHTML = '<?= $feriados?>';
		getElementById('divEventos').innerHTML = '<?= $eventos?>';

		getElementById('divFeriados').style.display = '<?= ($totalFeriados == 0)?"none":"block"?>';
		getElementById('divEventos').style.display = '<?= ($totalEventos == 0)?"none":"block"?>';
	}
</script>