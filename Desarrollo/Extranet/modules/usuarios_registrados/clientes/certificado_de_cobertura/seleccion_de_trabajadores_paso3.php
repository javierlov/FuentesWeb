<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


function getIdsTrabajadoresSeleccionados() {
	$i = 1;
	$ids = "-1";
	$sql = " AND (tj_id IN(-1)";

	foreach ($_SESSION["certificadoCobertura"]["trabajadores"] as $value) {
		$ids.= ",".$value;
		if (($i % 990) == 0) {
			$sql.= " OR tj_id IN(".$ids.")";
			$ids = "-1";
		}

		$i++;
	}
	$sql.= " OR tj_id IN(".$ids.")";
	$sql.= ")";

	return $sql;
}


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 71));

ini_set("memory_limit", "512M");
set_time_limit(120);

$showProcessMsg = false;

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "1";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];


$nomina = "";
switch ($_SESSION["certificadoCobertura"]["tipoCertificado"]) {
	case "ccc":
		$nomina.= "Certificado de Cobertura Común";
		break;
	case "cccr":
		$nomina.= "Certificado de Cobertura con cláusula de no repetición";
		break;
	case "cce":
		$nomina.= "Certificado de Cobertura al exterior";
		break;
}

switch ($_SESSION["certificadoCobertura"]["seleccionNomina"]) {
	case "p":
		$nomina.= " con nómina parcial";
		break;
	case "sn":
		$nomina.= " sin nómina";
		break;
	case "t":
		$nomina.= " con nómina total";
		break;
}

switch ($_SESSION["certificadoCobertura"]["tipoNomina"]) {
	case "c":
		$nomina.= " completa";
		break;
	case "s":
		$nomina.= " simple";
		break;
}


$paramUrl = "";
if (isset($_SESSION["isAgenteComercial"]))
	$paramUrl = "/".$_SESSION["contrato"];
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<script src="/modules/usuarios_registrados/clientes/js/certificados.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<div class="TituloSeccion" id="divTituloCertificado" style="display:block; width:730px;">Certificado de Cobertura</div>
<br />
<div class="ContenidoSeccion" id="contenidoPaso3" style="margin-top:-5px;">
<table cellpadding="0" cellspacing="0">
	<tr>
		<td>Usted seleccionó <b><?= $nomina?></b>.</td>
		<td>
			<img border="0" src="/modules/usuarios_registrados/images/nueva_seleccion.jpg" style="cursor:pointer; margin-left:16px;" onClick="window.location.href='/certificados-cobertura<?= $paramUrl?>'" />
		</td>
	</tr>
	<tr>
		<td height="20" colspan="2"></td>
	</tr>
<?
if (($_SESSION["certificadoCobertura"]["seleccionNomina"] != "sn") and ($_SESSION["certificadoCobertura"]["tipoCertificado"] != "cce")) {
?>
	<tr>
		<td colspan="2" class="SubtituloSeccionAzul">TRABAJADORES SELECCIONADOS</td>
	</tr>
	<tr>
		<td colspan="2" class="ContenidoSeccion">
			<div align="center" id="divContentGrid" name="divContentGrid">
				<form id="form" name="form">
<?
	$params = array(":idempresa" => $_SESSION["idEmpresa"]);
	$sql =
		"SELECT ¿tj_nombre?, ¿tj_cuil?, ¿es_nombre?, ¿rl_tarea?
			 FROM ctj_trabajador, crl_relacionlaboral, cre_relacionestablecimiento, aes_establecimiento
			WHERE tj_id = rl_idtrabajador
				AND rl_id = re_idrelacionlaboral(+)
				AND re_idestablecimiento = es_id(+)
				AND rl_contrato = art.afiliacion.get_contratovigente((SELECT em_cuit
																																FROM aem_empresa
																															 WHERE em_id = :idempresa), SYSDATE)";
	if ($_SESSION["certificadoCobertura"]["seleccionNomina"] == "p")
		$sql.= getIdsTrabajadoresSeleccionados();

	$grilla = new Grid();
	$grilla->addColumn(new Column("Trabajador"));
	$grilla->addColumn(new Column("CUIL"));
	$grilla->addColumn(new Column("Establecimiento"));
	$grilla->addColumn(new Column("Tarea"));
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setSql($sql);
	$grilla->setTableStyle("GridTableCiiu");
	$grilla->setUseTmpIframe(true);
	$grilla->Draw();
?>
				</form>
			</div>
			<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
			<script type="text/javascript">
				function CopyContent() {
					try {
						window.parent.document.getElementById('divContentGrid').innerHTML = document.getElementById('divContentGrid').innerHTML;
					}
					catch(err) {
						//
					}
<?
if ($showProcessMsg) {
?>
					if (document.getElementById('originalGrid') != null)
						document.getElementById('originalGrid').style.display = 'block';
					document.getElementById('divProcesando').style.display = 'none';
<?
}
?>
				}

				CopyContent();
			</script>
		</td>
	</tr>
<?
}
?>
	<tr>
		<td colspan="2">
			<img border="0" id="imgDescargarNomina" src="/modules/usuarios_registrados/images/descargar_en_pdf.jpg" style="cursor:pointer;" onClick="descargarNomina()" />
			<img border="0" id="imgProcesando" src="/images/loading.gif" style="visibility:hidden;" title="Procesando, aguarde unos segundos por favor..." />
			<br />
			<br />
			<i>(Si aún no tiene el Adobe Acrobat Reader, descárguelo en forma gratuita haciendo <a target="_blank" href="http://www.adobe.com/products/acrobat/readstep2.html">clic aquí</a> ).</i>
		</td>
	</tr>	
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>	
</table>
</div>
<iframe id="iframePdf" name="iframePdf" src="" style="display:none; height:360px; width:736px;"></iframe>
<iframe id="iframePdfNomina" name="iframePdfNomina" src="" style="display:none; height:360px; width:736px;"></iframe>
<table style="width:712px;">
	<tr>
		<td align="left">
			<img id="btnVerNomina" src="/modules/usuarios_registrados/images/ver_nomina.jpg" style="border:0px none; display:none; padding:0; background-color: #fff; width:60; height:18; color:#FFFFFF; cursor:pointer; font-family:Verdana; font-size:4; float:right;" onClick="verNomina();">
			<img id="btnVerCertificado" src="/modules/usuarios_registrados//images/ver_certificado.jpg" style="border:0px none; display:none; padding:0; background-color: #fff; width:60; height:18; color:#FFFFFF; cursor:pointer; font-family:Verdana; font-size:4; float:right;" onClick="verCertificado()">
		</td>
		<td><input class="btnVolver" type="button" value="" onClick="window.location.href = '/certificados-cobertura<?= $paramUrl?>';" /></td>
	</tr>
</table>