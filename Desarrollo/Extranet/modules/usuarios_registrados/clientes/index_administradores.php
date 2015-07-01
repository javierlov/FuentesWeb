<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


validarSesion(isset($_SESSION["isCliente"]));

$showProcessMsg = false;

$ob = "2";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];


// 26.12.2013 - Lo comentado de abajo es porque se dio de baja un contrato antes de que venza y el cliente queria cobertura hasta la fecha de vencimiento..
$params = array(":idusuarioextranet" => $_SESSION["idUsuario"]);
$sql =
	"SELECT #select#
		 FROM aem_empresa, aco_contrato, web.wcu_contratosxusuarios, web.wuc_usuariosclientes
		WHERE em_id = co_idempresa
			AND co_contrato = cu_contrato
			AND cu_idusuario = uc_id
/*			AND art.afi.check_cobertura(em_cuit, SYSDATE) = 1*/
			AND uc_idusuarioextranet = :idusuarioextranet";
$stmt = DBExecSql($conn, str_replace("#select#", "1", $sql), $params);

if (DBGetRecordCount($stmt) < 2) {
	require_once("menu_clientes.php");
	exit;
}
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<div class="TituloSeccion" style="display:block; width:730px;">Acceso Clientes</div>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<div class="ContenidoSeccion" id="divPaso1" style="margin-top:16px;">	Seleccione el contrato que desea administrar en este momento haciendo clic sobre el ícono correspondiente.</div>
<div id="divContent" name="divContent" style="display:block; height:336px; left:0px; position:relative; top:40px; width:730px;">
<?
$sql = str_replace("#select#", "co_contrato ¿id?, ¿co_contrato?, art.utiles.armar_cuit(em_cuit) ¿cuit?, ¿em_nombre?", $sql);

$grilla = new Grid(15, 10);
$grilla->addColumn(new Column("S", 0, true, false, -1, "btnSeleccionar", "/modules/usuarios_registrados/clientes/seleccionar_empresa.php", "", -1, true, -1, "Seleccionar"));
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
$grilla->draw();
?>
</div>
<div align="center" id="divProcesando" name="divProcesando" style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
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