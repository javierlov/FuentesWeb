<?
session_start();

unset($_SESSION["preventores"]["empresas"]);
$_SESSION["preventores"]["empresas"] = array();
?>
<script type="text/javascript">
	parent.document.getElementById('totRegSelec').value = 0;
</script>