<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


define("MAX_ROWS", 5);

SetDateFormatOracle("DD/MM/YYYY");


$params = array();
$sql =
	"SELECT *
		 FROM (SELECT 1, ap_contenido, ap_fecha, ap_fuente, ap_id, ap_titulo
						 FROM rrhh.rap_articulosprensa
						WHERE ap_notaprincipal = 'S'
							AND ap_fechabaja IS NULL
				UNION ALL
					 SELECT 2, ap_contenido, ap_fecha, ap_fuente, ap_id, ap_titulo
						 FROM rrhh.rap_articulosprensa
						WHERE ap_fechabaja IS NULL
				 ORDER BY ap_fecha)
 ORDER BY 1, ap_fecha DESC";
$stmt = DBExecSql($conn, $sql, $params);
$rowNotaPrincipal = DBGetQuery($stmt);

$params = array(":id" => $rowNotaPrincipal["AP_ID"]);
$sql =
	"SELECT ap_fecha, ap_id, ap_titulo, FLOOR((ROWNUM - .1) / 8) + 1 bloque
		 FROM (SELECT ap_fecha, ap_id, ap_titulo
						 FROM rrhh.rap_articulosprensa
						WHERE ap_fecha > (SYSDATE - 365)
							AND ap_notaprincipal = 'N'
							AND ap_fechabaja IS NULL
							AND ap_id <> :id
				 ORDER BY ap_fecha DESC)";
$stmt = DBExecSql($conn, $sql, $params);
?>
<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
<style type="text/css">
	a {color: #00539B;}
	a:active {color: #00539B;}
	a:hover {color: #00539B;}
	a:visited {color: #00539B;}
</style>
<script type="text/javascript" src="/js/popup/dhtmlwindow.js"></script>
<script>
	var divWin = null;
	var total_bloques = 0;

	function mostrarBloque(bloque) {
		for (i=1; i<=total_bloques; i++) {
			document.getElementById('bloque' + i).style.display = 'none';
			document.getElementById('numeracion' + i).style.color = '#fff';
			document.getElementById('numeracion' + i).style.cursor = 'pointer';
		}

		document.getElementById('bloque' + bloque).style.display = 'inline';
		document.getElementById('numeracion' + bloque).style.color = '#00539B';
		document.getElementById('numeracion' + bloque).style.cursor = 'hand';
	}

	function showNoticia(noticia) {
		if ((divWin == null) || (divWin.style.display == 'none')) {
			//medioancho = (screen.width - 760) / 2;
			medioancho = 120;
			medioalto = document.body.offsetHeight - 520;
			divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=760px,height=400px,left=' + medioancho + 'px,top=' + medioalto + 'px,resize=1,scrolling=1');
		}
		divWin.load('iframe', '/modules/sintesis_prensa/novedad.php?NoticiaId=' + noticia, 'Noticias relacionadas con el Sector');
		divWin.show();
	}

	showTitle(true, 'SÍNTESIS DE PRENSA');
</script>
<div align="center" style="padding-top:5px">
	<table border="0" width="740" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center" width="490" valign="top">
				<table width="100%" border="0" cellspacing="0">
					<tr>
						<td align="left" class="BordeCeldaGrisFondoGris FormLabelGris10Negrita"><?= $rowNotaPrincipal["AP_FECHA"]?></td>
					</tr>
					<tr>
						<td align="left" class="FormLabelAzulOscuro14Negrita"><?= htmlentities($rowNotaPrincipal["AP_TITULO"])?></td>
					</tr>
					<tr>
						<td align="left" class="FormLabelGrisSinNegrita11"><i>Fuente: <?= $rowNotaPrincipal["AP_FUENTE"]?></i></td>
					</tr>
					<tr>
						<td height="8"></td>
					</tr>
				</table>
				<div id="nota" style="width: 100%; height: 340px; overflow:auto;" class="FormLabelNegroSinNegrita11" align="left"><?= htmlentities($rowNotaPrincipal["AP_CONTENIDO"]->load())?></div>
			</td>
			<td width="20">&nbsp;</td>
			<td width="210" valign="top">
				<table border="0" width="100%" cellspacing="1">
					<tr>
						<td align="left" bgcolor="#00539B" class="FormLabelBlancoGrande">&nbsp;MAS NOTAS</td>
					</tr>
					<tr>
						<td class="FormLabelGrisSinNegrita11" style="padding-top:10px; padding-bottom:10px; padding-left:4px" align="left">
							<div id="bloque1">
<?
$bloque = 0;
while ($row = DBGetQuery($stmt)) {
	if ($row["BLOQUE"] != $bloque) {
		$bloque = $row["BLOQUE"];
		if ($row["BLOQUE"] != 1)
			echo "</div><div id='bloque".$bloque."' style='display:none;'>";
	}
?>
	<b>></b><a href="#" onClick="showNoticia('<?= $row["AP_ID"] ?>')"><?= $row["AP_TITULO"]?></a><br /><br />
<?
}
?>
							</div>
						</td>
					</tr>
					<tr>
						<td bgcolor="#B8B8B8" height="20" colspan="3" style="color:#fff; font-weight:bold; position:absolute; text-align:center; top:560px; width:216px;">
							<span id="numeracion1" style="color:#00539B; cursor:default; margin-left:4px;" onClick="mostrarBloque(1)">1</span>
<?
for ($i=2; $i<=$bloque; $i++) {
?>
	<span id="numeracion<?= $i?>" style="color:#ffffff; cursor:hand; margin-left:4px; text-decoration:none;" onClick="mostrarBloque(<?= $i?>)"><?= $i?></span>
<?
}
?>
					</tr>
				</table>	
			</td>
		</tr>
	</table>
</div>
<script>
	total_bloques = <?= $bloque?>;
</script>