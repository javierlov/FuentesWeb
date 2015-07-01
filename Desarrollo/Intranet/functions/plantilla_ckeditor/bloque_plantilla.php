<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT pn_id id, pn_nombre detalle
		 FROM web.wpn_plantillasintranet
		WHERE pn_modulo = ".$moduloPlantilla."
 ORDER BY 2";
$comboPlantilla = new Combo($sql, "plantilla");
$comboPlantilla->setFirstItem("- NUEVA PLANTILLA -");
?>
<link href="/functions/plantilla_ckeditor/css/plantilla.css" rel="stylesheet" type="text/css" />
<script src="/functions/plantilla_ckeditor/js/plantilla.js" type="text/javascript"></script>

<div id="divCuerpoPlantilla">
	<span>Plantillas</span>
	<?= $comboPlantilla->draw();?>
	<input id="btnCargar" name="btnCargar" type="button" onClick="cargarPlantilla()" />
	<input id="btnGuardar" name="btnGuardar" type="button" onClick="guardarPlantilla()" />
	<img id="imgPlantillaOk" src="/images/btn_ok.gif" />
</div>