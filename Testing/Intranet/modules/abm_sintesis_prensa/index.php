<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$showProcessMsg = false;

$page = "grilla_articulos.php";
if (isset($_REQUEST["page"]))
	$page = $_REQUEST["page"];
?>
<script language="JavaScript" src="modules/abm_sintesis_prensa/js/articulo.js"></script>
<script>
	showTitle(true, 'ABM SÍNTESIS DE PRENSA');
</script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<div align="center" id="divContent" name="divContent">
<? require_once($page) ?>
</div>
<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none"><img alt="Espere por favor..." border="0" src="/images/waiting.gif"></div>
<script>
	function CopyContent() {
		try {
			window.parent.document.getElementById('divContent').innerHTML = document.getElementById('divContent').innerHTML;
			window.parent.setCalendar();
		}
		catch(err) {
			//
		}
<?
if ($showProcessMsg) {
?>
	if (window.parent.document.getElementById('originalGrid') != null)
		window.parent.document.getElementById('originalGrid').style.display = 'block';
	window.parent.document.getElementById('divProcesando').style.display = 'none';
<?
}
?>
	}
CopyContent();
</script>