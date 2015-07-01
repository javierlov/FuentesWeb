<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


// Si viene vacío no tiene que devolver nada..
if ($_GET["e"] == "")
	$_GET["e"] = "1234567890";

$select = "useu.se_nombre";
if ((isset($_GET["t"])) and ($_GET["t"] == "t"))		// Si es true, se muestra el teléfono..
	$select = "useu.se_nombre || ' | ' || DECODE(useu.se_interno, NULL, 'Sin Int.', 'Int. ' || useu.se_interno)";

$params = array(":buscanombre" => "%".$_GET["e"]."%");
$sql =
	"SELECT /*+ index(art.use_usuarios ndx_use_parabusqueda)*/ ".$select." detalle, useu.se_id id
		 FROM art.use_usuarios useu, usc_sectores, computos.cse_sector cse
		WHERE useu.se_sector = sc_codigo
			AND useu.se_idsector = cse.se_id
			AND useu.se_fechabaja IS NULL
			AND sc_visible = 'S'
			AND cse.se_visible = 'S'
--			AND useu.se_sector NOT IN ('CALLCENT', 'BPAGOS', 'BAPRO', 'BANK', 'AUDGRUP', 'XUNILSA', 'GBPS', 'ESTJUD', 'DIMO', 'SML')
			AND (useu.se_usuariogenerico = 'N' OR useu.se_sector = 'RECEPCIO')
			AND useu.se_buscanombre LIKE UPPER(:buscanombre)
 ORDER BY detalle";
$stmt = DBExecSql($conn, $sql, $params);

$cantidadRegistros = DBGetRecordCount($stmt);
$i = 0;
$result = "";
while ($row = DBGetQuery($stmt)) {
	$class = (($i % 2) == 0)?"divItemEmpleado1":"divItemEmpleado2";
	$i++;

	$result.= "<div class=\"".$class."\" onClick=\"seleccionarEnListaEmpleados(this)\">".$row["DETALLE"]."</div>";
	$result.= "<input type=\"hidden\" value=\"".$row["ID"]."\" />";
	if ($i >= 10)
		break;
}
?>
<script type="text/javascript">
	with (window.parent.document.getElementById('divBusquedaListaEmpleados')) {
		innerHTML = '<?= $result?>';
		style.display = '<?= ($cantidadRegistros == 0)?"none":"block"?>';

		// Lamentablemente tengo que harcodear lo de abajo para que en la portada se vean bien las cosas que quedan abajo..
		if (parentNode.id == 'divBusquedaEmpleadoCampo') {		// Si la búsqueda se hace desde la portada..
			if (style.display == 'none')
				parentNode.style.height = '36px';
			else
				parentNode.style.height = '428px';
		}
	}

	window.parent.itemPosicionado = null;
//	with (window.parent) {
//		itemPosicionado = document.getElementById('divBusquedaListaEmpleados').firstChild;
//		itemPosicionado.style.backgroundColor = '#ccc';
//	}
</script>