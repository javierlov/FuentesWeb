<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$showProcessMsg = false;

$page = "busca_internos.php";
if (isset($_REQUEST["page"]))
	$page = $_REQUEST["page"];
?>
<link href="/modules/buscar_usuarios/css/style_buscar_usuarios.css" rel="stylesheet" type="text/css" />
<script>
	showTitle(true, 'AGENDA TELEFÓNICA');
</script>
<iframe height="0" id="iframeProcesando" name="iframeProcesando" src="" width="0"></iframe>
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

		try {
			window.parent.document.getElementById('Coordenada').style.left = window.parent.document.getElementById('EjeX').value - window.parent.document.getElementById('Mapa').width - 4;
			window.parent.document.getElementById('Coordenada').style.top = window.parent.document.getElementById('EjeY').value - window.parent.document.getElementById('Mapa').height + 4;
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