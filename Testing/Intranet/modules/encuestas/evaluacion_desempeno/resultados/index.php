<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


if ((GetWindowsLoginName(true) != "ALAPACO") and (GetWindowsLoginName(true) != "EVILA") and (GetWindowsLoginName(true) != "GLANCHA") and (GetWindowsLoginName(true) != "NPEREIRA") and
		(GetWindowsLoginName(true) != "VLOPEZ") and (GetWindowsLoginName(true) != "VDOMINGUEZ")) {
	echo "Usted no tiene permiso para ver este módulo!";
	exit;
}


$ano = -1;
if (isset($_REQUEST["ano"]))
	$ano = $_REQUEST["ano"];

$detalle = -1;
if (isset($_REQUEST["detalle"]))
	$detalle = $_REQUEST["detalle"];

$gerencia = -1;
if (isset($_REQUEST["gerencia"]))
	$gerencia = $_REQUEST["gerencia"];

$subdetalle = -1;
if (isset($_REQUEST["subdetalle"]))
	$subdetalle = $_REQUEST["subdetalle"];

$tema = -1;
if (isset($_REQUEST["tema"]))
	$tema = $_REQUEST["tema"];

$porGerencia = (($subdetalle == "1.1.3") or ($subdetalle == "1.2.4") or ($subdetalle == "1.2.5") or ($subdetalle == "2.1.1") or ($subdetalle == "3.1.1") or ($subdetalle == "4.1.1"));
?>
<html>
	<head>
		<link href="css/style.css" rel="stylesheet" type="text/css" />
		<script language="JavaScript" src="/js/validations.js?rnd=<?= time()?>"></script>
		<script language="JavaScript" src="js/resultados.js?rnd=<?= time()?>"></script>
		<title>Informes de la Evaluación de Desempeño</title>
	</head>
	<body>
		<div id="divAno">
			<span id="anoLabel">Año</span>
			<select id="ano" name="ano" onChange="window.location.href='index.php?ano='+document.getElementById('ano').value+'&tema='+document.getElementById('tema').value+'&detalle='+document.getElementById('detalle').value+'&subdetalle='+document.getElementById('subdetalle').value+'&gerencia='+document.getElementById('gerencia').value">
				<option value="2010" <?= ($ano == 2010)?"SELECTED":""?>>2010</option>
				<option value="2009" <?= ($ano == 2009)?"SELECTED":""?>>2009</option>
				<option value="2008" <?= ($ano == 2008)?"SELECTED":""?>>2008</option>
			</select>
		</div>
		<div id="divTema">
			<span id="temaLabel">Tema</span>
			<select id="tema" name="tema" onChange="window.location.href='index.php?ano='+document.getElementById('ano').value+'&tema='+document.getElementById('tema').value">
				<option value="-1" <?= ($tema == "-1")?"SELECTED":""?>>- Seleccione un item -</option>
				<option value="1" <?= ($tema == "1")?"SELECTED":""?>>1. Evaluación de competencias</option>
				<option value="2" <?= ($tema == "2")?"SELECTED":""?>>2. Determinación de objetivos</option>
				<option value="3" <?= ($tema == "3")?"SELECTED":""?>>3. Compromisos de mejora</option>
				<option value="4" <?= ($tema == "4")?"SELECTED":""?>>4. Comentarios</option>
			</select>
		</div>
		<div id="divDetalle">
			<span id="detalleLabel">Detalle</span>
			<select id="detalle" name="detalle" onChange="window.location.href='index.php?ano='+document.getElementById('ano').value+'&tema='+document.getElementById('tema').value+'&detalle='+document.getElementById('detalle').value">
				<option value="-1" <?= ($detalle == "-1")?"SELECTED":""?>>- Seleccione un item -</option>
<?
if ($tema == "1") {
?>
				<option value="1.1" <?= ($detalle == "1.1")?"SELECTED":""?>>1.1. Evaluación integradora de competencias</option>
				<option value="1.2" <?= ($detalle == "1.2")?"SELECTED":""?>>1.2. Evaluación de competencias organizacionales y de conducción</option>
<?
}
if ($tema == "2") {
?>
				<option value="2.1" <?= ($detalle == "2.1")?"SELECTED":""?>>2.1. Detalle de objetivos por gerencia</option>
<?
}
if ($tema == "3") {
?>
				<option value="3.1" <?= ($detalle == "3.1")?"SELECTED":""?>>3.1. Detalles de compromisos de mejora por gerencia</option>
<?
}
if ($tema == "4") {
?>
				<option value="4.1" <?= ($detalle == "4.1")?"SELECTED":""?>>4.1. Detalle de comentarios por gerencia</option>
<?
}
?>
			</select>
		</div>
		<div id="divSubdetalle">
			<span id="subdetalleLabel">Subdetalle</span>
			<select id="subdetalle" name="subdetalle" onChange="window.location.href='index.php?ano='+document.getElementById('ano').value+'&tema='+document.getElementById('tema').value+'&detalle='+document.getElementById('detalle').value+'&subdetalle='+document.getElementById('subdetalle').value">
				<option value="-1" <?= ($subdetalle == "-1")?"SELECTED":""?>>- Seleccione un item -</option>
<?
if ($detalle == "1.1") {
?>
				<option value="1.1.1" <?= ($subdetalle == "1.1.1")?"SELECTED":""?>>1.1.1. Integrador de toda la compañia</option>
				<option value="1.1.2" <?= ($subdetalle == "1.1.2")?"SELECTED":""?>>1.1.2. Integrador de mandos medios y gerentes</option>
				<option value="1.1.3" <?= ($subdetalle == "1.1.3")?"SELECTED":""?>>1.1.3. Por gerencia</option>
<?
}
if ($detalle == "1.2") {
?>
				<option value="1.2.1" <?= ($subdetalle == "1.2.1")?"SELECTED":""?>>1.2.1. Evaluación de competencias organizacionales de toda la compañia - promedios generales</option>
				<option value="1.2.2" <?= ($subdetalle == "1.2.2")?"SELECTED":""?>>1.2.2. Evaluación de competencias organizacionales de empleados sin personal a cargo - promedios generales</option>
				<option value="1.2.3" <?= ($subdetalle == "1.2.3")?"SELECTED":""?>>1.2.3. Evaluación de competencias organizacionales de mandos medios y gerentes - promedios generales</option>
				<option value="1.2.4" <?= ($subdetalle == "1.2.4")?"SELECTED":""?>>1.2.4. Evaluación de competencias organizacionales por gerencia - promedios generales</option>
				<option value="1.2.5" <?= ($subdetalle == "1.2.5")?"SELECTED":""?>>1.2.5. Evaluación de competencias organizacionales por gerencia y sector</option>
<?
}
if ($detalle == "2.1") {
?>
				<option value="2.1.1" <?= ($subdetalle == "2.1.1")?"SELECTED":""?>>2.1.1. Detalle de objetivos por gerencia</option>
<?
}
if ($detalle == "3.1") {
?>
				<option value="3.1.1" <?= ($subdetalle == "3.1.1")?"SELECTED":""?>>3.1.1. Detalle de objetivos por gerencia</option>
<?
}
if ($detalle == "4.1") {
?>
				<option value="4.1.1" <?= ($subdetalle == "4.1.1")?"SELECTED":""?>>4.1.1. Detalle de objetivos por gerencia</option>
<?
}
?>
			</select>
		</div>
		<div id="divGerencia">
			<span id="gerenciaLabel">Gerencia</span>
			<select id="gerencia" name="gerencia" onChange="window.location.href='index.php?ano='+document.getElementById('ano').value+'&tema='+document.getElementById('tema').value+'&detalle='+document.getElementById('detalle').value+'&subdetalle='+document.getElementById('subdetalle').value+'&gerencia='+document.getElementById('gerencia').value">
				<option value="-1" <?= ($gerencia == "-1")?"SELECTED":""?>>- Seleccione un item -</option>
<?
$sql =
	"SELECT DISTINCT cse3.se_id ID, cse3.se_descripcion detalle
				  		FROM use_usuarios useu, computos.cse_sector cse, computos.cse_sector cse2, computos.cse_sector cse3
             WHERE useu.se_idsector = cse.se_id
               AND useu.se_fechabaja IS NULL
               AND cse.se_idsectorpadre = cse2.se_id
               AND cse2.se_idsectorpadre = cse3.se_id
          ORDER BY 2";
$stmt = DBExecSql($conn, $sql);
while ($row = DBGetQuery($stmt)) {
?>
				<option value="<?= $row["ID"]?>" <?= ($gerencia == $row["ID"])?"SELECTED":""?>><?= $row["DETALLE"]?></option>
<?
}
?>
			</select>
		</div>
		<div align="center" id="tituloResultado"></div>
		<div id="tituloGerencia"></div>
		<div id="resultado">
<?
if ((($porGerencia) and ($gerencia != -1)) or ((!$porGerencia) and ($subdetalle != -1)))
	require_once($subdetalle.".php");
?>
		</div>
		<script>
<?
if ($porGerencia) {
	echo "document.getElementById('divGerencia').style.display = 'block';";
	// Escribo el título del informe..
	echo "if (document.getElementById('gerencia').value != -1) {";
	echo "	document.getElementById('tituloResultado').innerHTML = document.getElementById('subdetalle').options[document.getElementById('subdetalle').selectedIndex].text;";
	echo "	document.getElementById('tituloGerencia').innerHTML = document.getElementById('gerencia').options[document.getElementById('gerencia').selectedIndex].text;";
	echo "}";

}
else {
	// Escribo el título del informe..
	echo "if (document.getElementById('subdetalle').value != -1)";
	echo "	document.getElementById('tituloResultado').innerHTML = document.getElementById('subdetalle').options[document.getElementById('subdetalle').selectedIndex].text;";
}
?>
			// Agrego el botón de impresión..
			if (document.getElementById('tituloResultado').innerHTML != '')
				document.getElementById('tituloResultado').innerHTML = document.getElementById('tituloResultado').innerHTML +
					'&nbsp;&nbsp;<img alt="Imprimir pantalla" border="0" src="../images/imprimir.png" style="cursor:pointer;" onClick="printResultados()" />';

			// Pongo el foco donde corresponde..
			if (document.getElementById('ano').value == '-1')
				document.getElementById('ano').focus();
			else if (document.getElementById('tema').value == '-1')
				document.getElementById('tema').focus();
			else if (document.getElementById('detalle').value == '-1')
				document.getElementById('detalle').focus();
			else if (document.getElementById('subdetalle').value == '-1')
				document.getElementById('subdetalle').focus();
			else if (document.getElementById('divGerencia').style.display == 'block')
				document.getElementById('gerencia').focus();
			else
				document.getElementById('ano').focus();
		</script>
	</body>
</html>