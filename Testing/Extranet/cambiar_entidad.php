<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));

if (isset($_REQUEST["e"])) {
	$_SESSION["entidad"] = $_REQUEST["e"];
	$_SESSION["sucursal"] = "";

	$params = array(":identidad" => $_SESSION["entidad"]);
	$sql =
		"SELECT ve_id, ve_vendedor || ' - ' || ve_nombre
			 FROM xve_vendedor, xev_entidadvendedor
			WHERE ev_idvendedor = ve_id
				AND ve_fechabaja IS NULL
				AND ev_fechabaja IS NULL
				AND ev_identidad = :identidad
	 ORDER BY 2";
	$_SESSION["vendedor"] = ValorSql($sql, "", $params);
}
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<script src="/js/functions.js" type="text/javascript"></script>
	</head>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<div class="ContenidoSeccion" style="margin-top:40px;">
			<label for="entidad">Entidad</label>
			<select id="entidad" name="entidad" style="margin-left:4px; position:relative; top:0;"></select>
			<input class="btnAplicar" style="margin-left:24px; vertical-align:-3px;" type="button" value="" onClick="iframeProcesando.location.href = window.location.href + '?e=' + document.getElementById('entidad').value;" />
			<img border="0" id="imgOk" src="/images/seleccionar.png" style="display:none; margin-left:8px;" />
		</div>
		<script type="text/javascript">
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "entidad";
$RCparams = array(":identidad" => $_SESSION["entidadReal"]);
$RCquery =
	 "SELECT en_id id, en_codbanco || ' - ' || en_nombre detalle, TO_NUMBER(en_codbanco)
			FROM xen_entidad
		 WHERE en_id = :identidad
		 UNION
		SELECT en_id id, en_codbanco || ' - ' || en_nombre detalle, TO_NUMBER(en_codbanco)
			FROM xgo_granorganizador, xen_entidad
		 WHERE go_fechabaja IS NULL
			 AND go_identidad = en_id
START WITH go_identorganizador = :identidad
CONNECT BY NOCYCLE PRIOR go_identidad = go_identorganizador
  ORDER BY 3";
$RCselectedItem = $_SESSION["entidad"];
FillCombo(false);

if (isset($_REQUEST["e"])) {
?>
			function closeWindow() {
				window.parent.parent.divWin.close();
			}

			window.parent.document.getElementById('imgOk').style.display = 'inline';
			setInterval("closeWindow()", 2000);
<?
}
?>

			try {
				document.getElementById('entidad').focus();
			}
			catch(err) {
				//
			}
		</script>
	</body>
</html>