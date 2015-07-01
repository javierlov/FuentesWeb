<script>
	function setSize() {
		var oBody	=	iframeGS.document.body;
		var oFrame	=	document.all("iframeGS");
		oFrame.style.height = oBody.scrollHeight + (oBody.offsetHeight - oBody.clientHeight) + 20;
	}
</script>
<iframe frameborder="0" height="800" id="iframeGS" scrolling="no" src="/modules/obras_y_mantenimiento/index.php?<?= $_SERVER["QUERY_STRING"]?>" width="100%" onLoad="setSize()" />