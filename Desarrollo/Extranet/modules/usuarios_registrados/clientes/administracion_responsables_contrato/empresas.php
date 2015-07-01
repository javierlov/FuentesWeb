<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(($_SESSION["isAdminTotal"]) or (validarPermisoClienteXModulo($_SESSION["idUsuario"], 66)));

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
	"SELECT co_contrato ¿id?, ¿co_contrato?, art.utiles.armar_cuit(em_cuit) ¿cuit?, ¿em_nombre?
		 FROM aem_empresa, aco_contrato
		WHERE em_id = co_idempresa
			AND art.afiliacion.check_cobertura(co_contrato, SYSDATE) = 1
			AND co_contrato IN(".$_REQUEST["c"].")";

$grilla = new Grid(10, 5);
$grilla->addColumn(new Column("Q", 0, true, false, -1, "btnQuitar", "/modules/usuarios_registrados/clientes/administracion_responsables_contrato/eliminar_empresa.php", "", -1, true, -1, "Quitar"));
$grilla->addColumn(new Column("Contrato"));
$grilla->addColumn(new Column("CUIT"));
$grilla->addColumn(new Column("Razón Social"));
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