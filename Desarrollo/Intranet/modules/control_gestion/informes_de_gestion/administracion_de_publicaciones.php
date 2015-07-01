<?
$publicacionFiltro = "";
if (isset($_REQUEST["publicacionFiltro"]))
	$publicacionFiltro = $_REQUEST["publicacionFiltro"];

$temaFiltro = -1;
if (isset($_REQUEST["temaFiltro"]))
	$temaFiltro = $_REQUEST["temaFiltro"];

require_once("administracion_de_publicaciones_combos.php");
?>
<script type="text/javascript" src="/modules/control_gestion/informes_de_gestion/js/administracion_publicaciones.js"></script>
<iframe id="iframePublicaciones" name="iframePublicaciones" src="" style="display:none;"></iframe>
<div align="center">
	<table width="770" cellspacing="0" cellpadding="0" id="table1">
		<tr>
			<td width="45" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><p align="right" style="margin-left: 7px"><font size="2"><img src="/modules/control_gestion/informes_de_gestion/images/usuario.jpg" width="26" height="28"></td>
			<td width="101" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font color="#808080" style="font-size: 10pt">Usuario Actual:</font></td>
			<td align="left" width="529" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font style="font-size: 8pt; " color="#000000"><?= GetUserName()?></font></td>
			<td width="54" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><p align="right">&nbsp;</td>
		</tr>
	</table>
</div>
<br />
<div align="center">
	<table width="652" cellspacing="0" cellpadding="0" id="table4">
		<tr>
			<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="30"><b><font size="2"><a href="/index.php?pageid=34"><img src="/modules/control_gestion/informes_de_gestion/images/administracion.jpg" width="30" height="27"></a></td>
			<td align="left" style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><span style="font-weight: 700"><font size="3" color="#00A4E4">Administración de Publicación</font></span></td>
		</tr>
	</table>
</div>
<div align="center" style="margin-top:8px;">
	<form action="/modules/control_gestion/informes_de_gestion/procesar_publicacion.php?action=B" id="formBuscarPublicacion" method="post" name="formBuscarPublicacion" target="iframePublicaciones">
		<span color="#808080" style="font-size:10pt;">Tema</span>
		<?= $comboTemaFiltro->draw();?>
		<span color="#808080" style="font-size:10pt; margin-left:24px;">Publicación</span>
		<input id="publicacionFiltro" name="publicacionFiltro" type="text" value="<?= $publicacionFiltro?>" />
		<input id="btnFiltrar" name="btnFiltrar" type="submit" value="FILTRAR" style="border:1px solid #808080; background-color:#fff; color:#808080; font-size:8pt; margin-left:8px; padding-bottom:0px; padding-left:4px; padding-right:4px; padding-top:0px;" />
	</form>
	<table cellspacing="0" height="16" width="600" id="table3">
		<tr>
			<td style="border-color:#C0C0C0; padding:0; " align="center" height="7" width="437" colspan="3"></td>
		</tr>
		<tr>
			<td align="center" height="20" width="21"><p align="left">&nbsp;</td>
			<td align="center" height="20" width="21"><p align="left">&nbsp;</td>
			<td style="border-style:solid; border-width:1px; padding-left:4px; padding-right:4px" align="center" height="20" width="404" bgcolor="#CCCCCC" bordercolor="#808080"><b><font style="font-size: 11pt" color="#807F84">Título</font></b></td>
		</tr>
<?
$where = "ip_fechabaja IS NULL";
$params = array();
if (isset($_REQUEST["temaFiltro"])) {
	if ($_REQUEST["publicacionFiltro"] != "") {
		$where.= " AND UPPER(ip_titulo) LIKE UPPER(:titulo)";
		$params[":titulo"] = "%".$_REQUEST["publicacionFiltro"]."%";
	}

	if ($_REQUEST["temaFiltro"] != -1) {
		$where.= " AND ip_idtema = :idtema";
		$params[":idtema"] = $_REQUEST["temaFiltro"];
	}
}
$sql =
	"SELECT TO_DATE(TO_CHAR(NVL(ip_fechamodif, ip_fechaalta), 'dd/mm/yyyy'), 'dd/mm/yyyy') fechamodif, ip_id, ip_titulo,
					NVL(ip_usumodif, ip_usualta) usumodif
  	 FROM intra.cip_informepublicado
   	WHERE ".$where."
 ORDER BY 1 DESC";
$stmt = DBExecSql($conn, $sql, $params);
while ($row = DBGetQuery($stmt)) {
?>
		<tr>
			<td align="center" height="20" width="21"><b><font size="2"><a target="_self" href="#" onclick="editarPublicacion(<?= $row["IP_ID"]?>)"><img height="17" src="/modules/control_gestion/informes_de_gestion/images/nuevo_tema.jpg" title="Modificar Publicación" width="18"></a></td>
			<td align="center" height="20" width="21"><b><font size="2"><a target="_self" href="#" onclick="eliminarPublicacion(<?= $row["IP_ID"]?>)"><img height="17" src="/modules/control_gestion/informes_de_gestion/images/eliminar.jpg" title="Eliminar Publicación" width="18"></a></td>
			<td align="left" style="background-color:#999999; border:1 solid #666666; padding-left:4px;" height="1" width="410"><font size="2" color="#FFFFFF"><?= $row["IP_TITULO"]?>&nbsp;&nbsp;&nbsp;(<?= $row["USUMODIF"]?> - <?= $row["FECHAMODIF"]?>)</font></td>
		</tr>
		<tr>
			<td height="1px"></td>	
		</tr>
<?
}
?>
		<tr>
			<td align="center" height="10" width="435" colspan="3"></td>
		</tr>
		<tr>
			<td align="center" height="10" width="435" colspan="3"><font size="2"><input id="btnNuevaPublicacion" name="btnNuevaPublicacion" type="submit" value="NUEVA PUBLICACIÓN" style="color: #808080; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; float:left" onClick="verPublicacion(-1, 0, '', '', -1)"></td>
		</tr>
	</table>
</div>
<div align="center" id="divAbm" name="divAbm" style="display:none">
	<form action="/modules/control_gestion/informes_de_gestion/procesar_publicacion.php?action=G" enctype="multipart/form-data" id="formPublicacion" method="post" name="formPublicacion" target="iframePublicaciones" onSubmit="return validarFormPublicacion(formPublicacion)">
		<input id="Id" name="Id" type="hidden" value="-1" />
		<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="10000000" />
		<table cellspacing="0" height="16" width="437">
			<tr>
				<td align="center" height="20" width="433" colspan="6" style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px">&nbsp;</td>
			</tr>
			<tr>
				<td align="center" height="5" width="433" colspan="6"></td>
			</tr>
			<tr>
				<td align="center" height="20" width="65"><p align="left"><font style="font-size: 10pt" color="#808080">Tema</font><font style="font-size: 8pt; font-weight: 700" color="#808080">:</font></td>
				<td align="center" height="20" width="368" colspan="5"><p align="left"><?= $comboTema->draw();?></p></td>
			</tr>
			<tr>
				<td align="center" height="20" width="65"><p align="left"><span id="LinkArchivo" name="LinkArchivo" style="color:#808080; font-size: 10pt; cursor:pointer" onClick="verArchivo()">Archivo</span><font style="font-size: 10pt;" color="#808080">:</td>
				<td align="center" height="20" width="184" colspan="5"><font size="2"><input id="Archivo" name="Archivo" size="40" type="file" style="color: #808080; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; float:left"></td>
			</tr>
			<tr>
				<td align="center" height="20" width="65"><p align="left"><font style="font-size: 10pt" color="#808080">Título</font><font style="font-size: 10pt" color="#808080" size="2">:</font></td>
				<td align="center" height="20" width="368" colspan="5"><font size="2"><input id="Titulo" name="Titulo" size="60" type="text" validar="true" title="Título" style="color: #808080; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF"></td>
			</tr>
			<tr>
				<td align="center" height="20" width="65"><p align="left"><font style="font-size: 10pt" color="#808080">Act</font><font size="2"><font style="font-size: 10pt" color="#808080">ivo</font><font style="font-size: 10pt; color="#808080">:</font></td>
				<td align="center" height="20" width="20"><p align="left"><input id="Activo" name="Activo" type="radio" value="1"></p></td>
				<td align="center" height="20" width="12"><font style="font-size: 8pt">Si</font></td>
				<td align="center" height="20" width="20"><b><font size="2"><input id="Activo" name="Activo" type="radio" value="0"></td>
				<td align="center" height="20" width="187"><p align="left"><font style="font-size: 8pt">No (histórico)</font></td>
				<td align="center" height="20" width="184">&nbsp;</td>
			</tr>
			<tr>
				<td align="center" height="7" width="433" colspan="6"></td>
			</tr>
			<tr>
				<td align="center" height="20" width="65">&nbsp;</td>
				<td align="center" height="20" width="368" colspan="5"><font size="2"><input id="btnGrabar" name="btnGrabar" type="submit" value="GRABAR" style="color: #808080; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; float:left"></td>
			</tr>
		</table>
	</form>
</div>