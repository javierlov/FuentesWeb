<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT ".$prefijo."id id, ".$prefijo."detalle detalle
		 FROM ".$tabla."
 ORDER BY 2";
$comboTipos = new Combo($sql, "tipos");
$comboTipos->setFocus(true);

if (isset($_REQUEST["tipoop"])) {
	$sql =
		"SELECT ".$prefijo."id id, ".$prefijo."detalle detalle
			 FROM ".$tabla."
	 ORDER BY 2";
	$comboGenerico = new Combo($sql, $obj);
?>
<script type="text/javascript">
	window.parent.document.getElementById('<?= $obj?>').parentNode.innerHTML = '<?= $comboGenerico->draw();?>';
</script>
<?
}
?>