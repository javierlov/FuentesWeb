<?
function FillCombo($agregarPrimerItem = true, $maxItems = 0, $firstItem = "- SELECCIONAR -", $disabled = false) {
	global $conn;
	global $RCfield;
	global $RCparams;
	global $RCquery;
	global $RCselectedItem;
	global $RCwindow;

	$stmt = DBExecSql($conn, $RCquery, $RCparams);

	// Función que va a ser llamada luego de transcurridos unos segundos para cargar los combos..
	$timestamp = str_replace(array("[", "]"), array("", ""), $RCfield)."_".date("YmdHis");
	$functionName = "internalFillCombo_".$timestamp;
	echo "function ".$functionName."() {";

	// Quito todos los items..
	echo $RCwindow.".document.getElementById('".$RCfield."').options.length = 0;";

	// Agrego el primer item..
	if ($agregarPrimerItem)
		echo $RCwindow.".AddItemToDropDown('".$RCfield."', '-1', '".$firstItem."', ".(($disabled)?"true":"false").");";

	// Si el query devuelve mas registros de los que se quiere, pregunto si muestro todo o no..
	if (($maxItems != 0) and (DBGetRecordCount($stmt) > $maxItems)) {
		echo "if (!confirm('Este combo tiene muchos items y puede demorar mucho su carga.\\n¿ Desea cargar sus items de todas formas ?')) {";
		echo $RCwindow.".document.getElementById('".$timestamp."').style.display = 'none';";
		echo "return;";
		echo "}";
	}

	// Agrego todos los nuevos items..
	if ($agregarPrimerItem)
		$index = 1;
	else
		$index = 0;
	$selectedIndex = 0;
	while ($row = DBGetQuery($stmt)) {
		if ($RCselectedItem == $row["ID"])
			$selectedIndex = $index;

		echo $RCwindow.".AddItemToDropDown('".$RCfield."', '".$row["ID"]."', unescape('".rawurlencode($row["DETALLE"])."'), ".(($disabled)?"true":"false").");";
		$index++;
	}

	echo $RCwindow.".document.getElementById('".$RCfield."').selectedIndex = ".$selectedIndex.";";
	echo $RCwindow.".document.getElementById('".$timestamp."').style.display = 'none';";
	echo $RCwindow.".document.getElementById('".$RCfield."')[".$RCwindow.".document.getElementById('".$RCfield."').selectedIndex].disabled = false;";

	if ($RCwindow == "window.opener")
		echo "window.close();";

	echo "}";		// Cierro la función..

	// Muestro la imagen de carga..
	echo "elem = document.createElement('img');";
	echo "elem.id = '".$timestamp."';";
	echo "elem.src = '/images/loading.gif';";
	echo "elem.style.marginLeft = '8px';";
	echo "elem.style.verticalAlign = '-3px';";
	echo "\n";
	echo "\n";
	echo "\n";
	echo $RCwindow.".document.getElementById('".$RCfield."').parentNode.insertBefore(elem, ".$RCwindow.".document.getElementById('".$RCfield."').nextSibling);";

	echo "setTimeout('".$functionName."()', 300);";
}


$RCquery = "";
$RCwindow = "window.opener";
if (isset($_REQUEST["field"]))
	$RCfield = $_REQUEST["field"];
if (isset($_REQUEST["selectedItem"]))  
	$RCselectedItem = $_REQUEST["selectedItem"];

if (!isset($excludeHtml)) {
//  session_start();
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
?>
<html>
	<head>
		<title>Procesando...</title>
		<link href="/css/style.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript">
			function Change() {
				<? FillCombo(); ?>
			}
		</script>
	</head>
	<body bgcolor="#EEE9E0" onContextMenu="return false">
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td class="Procesando" align="center" height="48" valign="center">Procesando su requerimiento, aguarde un instante por favor...</td>
			</tr>
		</table>
		<script type="text/javascript">
			setTimeout('Change()', 300);
		</script>
	</body>
</html>
<?
}
?>