<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


function showInformesGestion() {
	$params = array(":usuario" => getWindowsLoginName(true));
	$sql =
		"SELECT 1
			 FROM web.wpt_permisostablerocontrol
			WHERE pt_usuario = :usuario
				AND pt_fechabaja IS NULL";
	return existeSql($sql, $params);
}


if (!showInformesGestion()) {
	echo "USTED NO TIENE PERMISO PARA ACCEDER A ESTE MÓDULO";
	return false;
}


validarParametro(isset($_REQUEST["tm"]));

// Solo se habilita si el usuario es del sector "Análisis y Control de Gestión" o alguno de nosotros de prueba..
$sistemas = (getWindowsLoginName(true) == "ALAPACO");
$idSector = getUserIdSectorIntranet();
$habilitarAdministarcion = (($idSector == 5014) or ($idSector == 19028) or ($sistemas));

$params = array(":id" => $_REQUEST["tm"]);
$sql =
	"SELECT it_tema
		 FROM intra.cit_informetemas
		WHERE it_id = :id";

$filtro = "";
if (isset($_REQUEST["filtro"]))
	$filtro = $_REQUEST["filtro"];
?>
<iframe id="iframeInformes" name="iframeInformes" src="" style="display:none;"></iframe>

<div align="center">
	<table width="770" cellspacing="0" cellpadding="0" id="table1">
		<tr>
			<td width="45" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><p align="right" style="margin-left: 7px"><b><font size="2"><img src="/modules/control_gestion/informes_de_gestion/images/usuario.jpg" width="26" height="28" /></td>
			<td width="101" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font color="#808080" style="font-size: 10pt">Usuario Actual:</font></b></td>
			<td align="left" width="529" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font style="font-size: 8pt; " color="#000000"><?= GetUserName()?></font></td>
<?
if ($habilitarAdministarcion) {
?>
			<td width="54" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><p align="right"><a target="_self" href="/index.php?pageid=34"><img height="27" src="/modules/control_gestion/informes_de_gestion/images/administracion.jpg" title="Administración" width="30"></a></td>
<?
}
?>
		</tr>
	</table>
</div>

<br />

<?
if ($_REQUEST["hstr"] == "T") {
?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td><b><font color="#00A4E4" style="font-size: 14pt">Históricos</font></b></td>
		</tr>
	</table>
<?
}
?>

<table width="652" cellspacing="0" cellpadding="0" id="table6">
	<tr>
		<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="27"><b><font size="2"><a target="_self" href="/index.php?pageid=33"><img src="/modules/control_gestion/informes_de_gestion/images/temas.jpg" width="27" height="25"></a></td>
		<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="45"><font color="#807F84" style="font-size: 11pt">Tema:</font></td>
		<td align="left" style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><b><font color="#807F84" style="font-size: 11pt"><?= ValorSql($sql, "", $params)?></font></b></td>
	</tr>
</table>

<br />

<div style="margin-bottom:8px;">
	<form action="<?= $_SERVER["REQUEST_URI"]?>" id="formFiltro" method="post" name="formFiltro">
		<span>Título informe</span>
		<input id="filtro" name="filtro" type="text" value="<?= $filtro?>" />
		<input type="submit" value="FILTRAR" />
	</form>
</div>

<div>
<?
$list = new ListOfItems("", "");

$params = array(":idtema" => $_REQUEST["tm"], ":activo" => (($_REQUEST["hstr"] == "T")?0:1));
$where = "";

if ($filtro != "") {
	$params["titulo"] = "%".$filtro."%";
	$where = " AND UPPER(ip_titulo) LIKE UPPER(:titulo)";
}

$sql =
	"SELECT TO_CHAR(NVL(ip_fechamodif, ip_fechaalta), 'dd/mm/yyyy') fecha, ip_archivo, ip_id, ip_titulo
  	 FROM intra.cip_informepublicado
 		WHERE ip_idtema = :idtema
   		AND ip_activo = :activo
   		AND ip_fechabaja IS NULL".$where."
 ORDER BY NVL(ip_fechamodif, ip_fechaalta) DESC";
$stmt = DBExecSql($conn, $sql, $params);
while ($row = DBGetQuery($stmt)) {
	$titulo = $row["IP_TITULO"];
	if ($_REQUEST["hstr"] == "T")
		$titulo.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(".$row["FECHA"].")";
	$list->addItem(new ItemList("/modules/control_gestion/informes_de_gestion/ver_informe.php?pblccn=".$row["IP_ID"], $titulo, "iframeInformes"));
}

$list->setCols(1);
$list->setColsWidth(600);
$list->setImagePath("/modules/control_gestion/informes_de_gestion/images/flecha.gif");
$list->setShowTitle(false);
$list->draw();
?>
</div>

<script>
	with (document.getElementById('filtro')) {
		select();
		focus();
	}
</script>