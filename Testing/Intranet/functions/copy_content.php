<script type="text/javascript">
	function CopyContent() {
		try {
			window.parent.document.getElementById('divContent').innerHTML = document.getElementById('divContent').innerHTML;
		}
		catch (err) {
			//
		}
<?
if ($showProcessMsg) {
?>
		try {
			window.parent.document.getElementById('originalGrid').style.display = 'block';
		}
		catch (err) {
			//
		}
<?
}
?>
		try {
			window.parent.document.getElementById('divProcesando').style.display = 'none';
		}
		catch(err) {
			//
		}
	}
</script>