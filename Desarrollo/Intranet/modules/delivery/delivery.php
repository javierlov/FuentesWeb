<link href="/modules/delivery/css/style_delivery.css" rel="stylesheet" type="text/css">
<script src="/modules/delivery/js/delivery.js" type="text/javascript"></script>
<div><a href="http://www.platosdeldia.com.ar/modules.php?name=PDD" target="_blank"><img src="/modules/delivery/images/encabezado.jpg" title="Ir a Platos del Día"></a></div>
<div><img id="imgAgregar" src="/modules/delivery/images/agregar_local.jpg" title="Agregar Local" onClick="mostrarFormularioAgregarLocal()" /></div>

<?require_once($_SERVER["DOCUMENT_ROOT"]."/modules/delivery/agregar_local.php");?>

<div id="divEditarLocal">
	<img id="imgAlerta" src="/modules/delivery/images/alerta.png" title="Avisar que el local está cerrado" onClick="avisarCierreLocal()" onMouseOver="mantenerBotones(obj)" />
<?
if ((getUserSector() == "RRHH") or (getWindowsLoginName() == "alapaco")) {
?>
	<img id="imgEditarLocal" src="/images/grilla/grid_editar.png" title="Editar" onClick="editarLocal()" onMouseOver="mantenerBotones(obj)" />
<?
}
else {
?>
	<img id="imgEditarLocal" src="/images/img_not_found.gif" style="height:0; width:0;" />
<?
}
?>
</div>

<table id="tableDelivery">
	<tr id="trDelivery">
		<td id="tdDelivery">NOMBRE / LUGAR</td>
		<td id="tdDelivery">DIRECCIÓN</td>
		<td id="tdDelivery">TELÉFONO</td>
	</tr>
<?
$params = array(":sector" => getUserSector(),":usualta" => getWindowsLoginName(true));
$sql =
	"SELECT hd_autorizado, hd_direccion, hd_id, hd_nombre, hd_telefono, hd_url
		 FROM rrhh.rhd_delivery
		WHERE hd_fechabaja IS NULL
			AND (hd_autorizado = 'S' OR hd_usualta = :usualta OR :sector = 'RRHH')
 ORDER BY hd_nombre";
$stmt = DBExecSql($conn, $sql, $params);
$bgColor = "";
while ($row = DBGetQuery($stmt)) {
	if ($bgColor == "dadada")
		$bgColor = "f6f6f6";
	else
		$bgColor = "dadada";

	$onClick = "";
	$style = "cursor:default;";
	if ($row["HD_URL"] != "") {
		$js = "window.open('".$row["HD_URL"]."', '_blank')";
		$onClick = 'onClick="'.$js.'"';
		$style = "cursor:pointer;";
	}

	if ($row["HD_AUTORIZADO"] != "S") {
		$bgColor = "f00";
		$style.= " color:#fff;";
	}
?>
	<tr bgcolor="#<?= $bgColor?>" class="trFondoLinea" id="trLocal_<?= $row["HD_ID"]?>" <?= $onClick?> onMouseOut="ocultarBotonesLocal(this)" onMouseOver="mostrarBotones(this)">
		<td id="tdItems" style="<?= $style?>"><?= $row["HD_NOMBRE"]?></td>
		<td id="tdItems" style="<?= $style?>"><?= $row["HD_DIRECCION"]?></td>
		<td id="tdItems" style="<?= $style?>"><?= $row["HD_TELEFONO"]?></td>
	</tr>
<?
}
?>
</table>