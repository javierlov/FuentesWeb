<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once("cargar_combo_localidad_combos.php");
?>
<script type="text/javascript">
	function setComboLocalidad() {
		with (window.parent.document) {
			getElementById('localidadCombo<?= $_REQUEST["prefijo"]?>').parentNode.innerHTML = '<?= $comboLocalidadCombo->draw();?>';
			if (getElementById('localidadCombo<?= $_REQUEST["prefijo"]?>').length > 1) {
				getElementById('localidad<?= $_REQUEST["prefijo"]?>').style.display = 'none';
				getElementById('localidadCombo<?= $_REQUEST["prefijo"]?>').style.display = 'inline';
				getElementById('localidadCombo<?= $_REQUEST["prefijo"]?>').style.backgroundColor = '#fff';
				getElementById('localidadCombo<?= $_REQUEST["prefijo"]?>').options[getElementById('localidadCombo<?= $_REQUEST["prefijo"]?>').length - 1].style.backgroundColor = '#0f539c';
				getElementById('localidadCombo<?= $_REQUEST["prefijo"]?>').options[getElementById('localidadCombo<?= $_REQUEST["prefijo"]?>').length - 1].style.color = '#fff';
				getElementById('localidad<?= $_REQUEST["prefijo"]?>').value = getElementById('localidadCombo<?= $_REQUEST["prefijo"]?>').value;
			}
			else {
				getElementById('localidad<?= $_REQUEST["prefijo"]?>').style.display = 'inline';
				getElementById('localidadCombo<?= $_REQUEST["prefijo"]?>').style.display = 'none';
			}
		}
	}

	setTimeout('setComboLocalidad()', 700);
</script>