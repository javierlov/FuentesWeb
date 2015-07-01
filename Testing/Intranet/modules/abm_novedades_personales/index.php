<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$showProcessMsg = false;

$page = "grilla_novedades.php";
if (isset($_REQUEST["page"]))
	$page = $_REQUEST["page"];
?>
<script language="JavaScript" src="/modules/abm_novedades_personales/js/novedad.js"></script>
<script>
	showTitle(true, 'ABM CELEBRACIONES');
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
		}
		catch(err) {
			//
		}
<?
if ($showProcessMsg) {
?>
	with (window.parent.document) {
		if (getElementById('originalGrid') != null)
			getElementById('originalGrid').style.display = 'block';
		if (getElementById('divProcesando') != null)
			getElementById('divProcesando').style.display = 'none';
<?
}
?>
	}

	CopyContent();
</script>