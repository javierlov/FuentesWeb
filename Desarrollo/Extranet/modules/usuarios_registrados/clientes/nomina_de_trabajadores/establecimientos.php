<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 52));

SetDateFormatOracle("DD/MM/YYYY");

$showProcessMsg = false;

$ob = "2";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<script src="/js/functions.js" type="text/javascript"></script>
	</head>
	<body style="margin:0;">
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/solicitud_afiliacion/establecimientos.php" id="formEstablecimientos" method="get" name="formEstablecimientos">
			<div align="center" id="divContent" name="divContent">
<?
$params = array();
$sql =
	"SELECT ¿es_id?,
				  es_nombre || ' (' || art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, NULL) || art.utiles.armar_localidad(es_cpostal, NULL, es_localidad, es_provincia) || ')' ¿nombre?,
				  ART.WEBART.get_fecha_ingreso_establecimie(es_id, 0".$_REQUEST["rl"].") ¿fechaingreso?
		 FROM aes_establecimiento
		WHERE es_id IN(".$_REQUEST["e"].")";
$grilla = new Grid(10, 5);
$grilla->addColumn(new Column("Q", 0, true, false, -1, "btnQuitar", "/modules/usuarios_registrados/clientes/nomina_de_trabajadores/eliminar_establecimiento.php?rl=".$_REQUEST["rl"], "", -1, true, -1, "Quitar"));
$grilla->addColumn(new Column("Establecimiento"));
$grilla->addColumn(new Column("Fecha Ingreso"));
$grilla->setColsSeparator(true);
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRefreshIntoWindow(true);
$grilla->setRowsSeparator(true);
$grilla->setRowsSeparatorColor("#c0c0c0");
$grilla->setShowTotalRegistros(false);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->setUseTmpIframe(true);
$grilla->Draw();
?>
			</div>
			<div align="center" id="divProcesando" name="divProcesando" style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		</form>
	</body>
	<script type="text/javascript">
		function CopyContent() {
			try {
				window.parent.document.getElementById('divContentGrid').innerHTML = document.getElementById('divContentGrid').innerHTML;
			}
			catch(err) {
				//
			}
		}

		CopyContent();
	</script>
</html>