<script>
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window.parent";

$RCfield = "usuarios";
$RCparams = array();
$RCquery = 
	"SELECT se_usuario ID, se_nombre detalle
		 FROM use_usuarios
		WHERE se_usuariogenerico = 'N'
			AND se_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = -1;
FillCombo();
?>
	with (window.parent.document) {
		body.style.cursor = 'default';
		getElementById('divCargandoDatos').style.display = 'none';
	}
</script>