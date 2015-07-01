<script>
	function setSize() {
		var oBody	=	iframeGS.document.body;
		var oFrame	=	document.all("iframeGS");
		oFrame.style.height = oBody.scrollHeight + (oBody.offsetHeight - oBody.clientHeight) + 20;
	}
</script>
<iframe frameborder="0" width="800" height="800" id="iframeGS" scrolling="auto" src="/modules/gestion_sistemas/index.php?<?= $_SERVER["QUERY_STRING"]?>" onLoad="setSize()"></iframe>
<?
if ((isset($_REQUEST["gs"])) and ($_REQUEST["gs"] = "true")) {
?>
<script type="text/javascript">
	document.getElementById('divTituloSeccion').innerText = 'SOLICITUD A OBRAS & MANTENIMIENTO';
</script>
<?
}
?>