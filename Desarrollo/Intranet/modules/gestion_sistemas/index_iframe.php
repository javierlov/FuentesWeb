<script type="text/javascript" >
	function setSize() {
		var AltoVentana = 960;
		var oBody = document.getElementsByTagName("body")[0]
		var oFrame	=	document.all("iframeGS");
		oFrame.style.height = AltoVentana+"px"; 
	return true;
	}
</script>

<iframe frameborder="0" style="height:960; width:98%;"
		id="iframeGS" scrolling="auto" 
		src="/modules/gestion_sistemas/index.php?<?= $_SERVER["QUERY_STRING"]?>"  
onLoad="setSize()" />

