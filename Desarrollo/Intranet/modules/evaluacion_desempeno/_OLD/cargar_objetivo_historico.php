<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


if ($_REQUEST["action"] == "FA") {		// Lleno el arreglo con los id..
	$params = array(":idformularioevaluacion" => $_REQUEST["formulario"], ":nroobjetivo" => $_REQUEST["numero"]);
	$sql =
		"SELECT fo_id
			 FROM rrhh.hfo_formularioobjetivo
			WHERE fo_id_formularioevaluacion = :idformularioevaluacion
				AND fo_nroobjetivo = :nroobjetivo
	 ORDER BY NVL(fo_fechamodif, fo_fechaalta)";
	$stmt = DBExecSql($conn, $sql, $params);

	$iLoop = 0;
	echo "<script>";
	while ($row = DBGetQuery($stmt)) {
		echo "window.parent.recArray[".$iLoop."] = ".$row["FO_ID"].";";
		$iLoop++;
	}
	echo "window.parent.mostrar('U');";
	echo "</script>";
}


if ($_REQUEST["action"] == "M") {		// Muestro el objetivo..
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT TO_CHAR(NVL(fo_fechamodif, fo_fechaalta), 'DD/MM/YYYY') fechamodif, fo_id_formularioevaluacion, fo_indicador, fo_motivoreemplazo, fo_motivoreemplazootros, fo_objetivo, fo_plazo,
						fo_resultado, NVL(fo_usumodif, fo_usualta) usumodif
			 FROM rrhh.hfo_formularioobjetivo
			WHERE fo_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$params = array(":id" => $row["FO_ID_FORMULARIOEVALUACION"]);
	$sql =
		"SELECT fe_anoevaluacion
			 FROM rrhh.hfe_formularioevaluacion2008
			WHERE fe_id = :id";
	$ano = valorSql($sql, "", $params);
?>
<script>
	with (window.parent) {
		document.getElementById('ano').innerHTML = '<?= $ano?>';
		document.getElementById('FechaModif').innerText = '<?= $row["FECHAMODIF"]?>';
		document.getElementById('UsuModif').innerText = '<?= $row["USUMODIF"]?>';
		document.getElementById('MotivoCambio').value = unescape('<?= rawurlencode($row["FO_MOTIVOREEMPLAZOOTROS"])?>');
		document.getElementById('Descripcion').value = unescape('<?= rawurlencode($row["FO_OBJETIVO"])?>');
		document.getElementById('Resultado').value = unescape('<?= rawurlencode($row["FO_RESULTADO"])?>');
		document.getElementById('Indicador').value = unescape('<?= rawurlencode($row["FO_INDICADOR"])?>');
		document.getElementById('PlazoEjecucion').value = unescape('<?= rawurlencode($row["FO_PLAZO"])?>');
		if (index == 0)
			document.getElementById('btnAnterior').style.display = 'none';
		else
			document.getElementById('btnAnterior').style.display = 'block';

		if (index == (recArray.length - 1))
			document.getElementById('btnPosterior').style.display = 'none';
		else
			document.getElementById('btnPosterior').style.display = 'block';
	}
</script>
<?
}
?>