<script type="text/javascript" src="/modules/control_gestion/informes_de_gestion/js/administracion_temas.js"></script>

<iframe id="iframeTemas" name="iframeTemas" src="" style="display:none;"></iframe>

<div align="center">
	<table width="770" cellspacing="0" cellpadding="0" id="table1">
		<tr>
			<td width="45" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><p align="right" style="margin-left: 7px"><font size="3"><img src="/modules/control_gestion/informes_de_gestion/images/usuario.jpg" width="26" height="28"></td>
			<td width="101" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font color="#808080" style="font-size: 10pt">Usuario Actual:</font></td>
			<td align="left" width="529" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font style="font-size: 8pt; " color="#000000"><?= GetUserName()?></font></td>
			<td width="54" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><p align="right">&nbsp;</td>
		</tr>
	</table>

	<br />

	<table width="652" cellspacing="0" cellpadding="0" id="table4">
		<tr>
			<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="21"><b><font size="2"><a href="/index.php?pageid=34"><img src="/modules/control_gestion/informes_de_gestion/images/administracion.jpg" width="30" height="27"></a></td>
			<td align="left" style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><span style="font-weight: 700"><font size="3" color="#00A4E4">Administración de Temas</font></span></td>
		</tr>
	</table>

	<table cellspacing="0" height="16" width="437" id="table3">
		<tr>
			<td style="border-color:#C0C0C0; padding:0; " align="center" height="7" width="437" colspan="3"></td>
		</tr>
		<tr>
			<td align="center" height="20" width="22"><p align="left">&nbsp;</td>
				<td align="center" height="20" width="22"><p align="left">&nbsp;</td>
			<td style="border-style:solid; border-width:1px; padding-left:4px; padding-right:4px" align="center" height="20" width="403" bgcolor="#CCCCCC" bordercolor="#808080"><b><font style="font-size: 11pt" color="#807F84">Nombre</font></b></td>
		</tr>
<?
$sql =
	"SELECT it_id, it_tema
     FROM intra.cit_informetemas
   	WHERE it_fechabaja IS NULL
 ORDER BY 2";
$stmt = DBExecSql($conn, $sql);
while ($row = DBGetQuery($stmt)) {
	$sql =
		"SELECT COUNT(*)
  		 FROM intra.cip_informepublicado
 			WHERE ip_idtema = :idtema
   			AND ip_fechabaja IS NULL";
	$params = array(":idtema" => $row["IT_ID"]);
?>
	<tr>
		<td align="center" height="20" width="22"><b><font size="2"><a target="_self" href="#" onclick="editarTema(<?= $row["IT_ID"]?>)"><img height="17" src="/modules/control_gestion/informes_de_gestion/images/nuevo_tema.jpg" title="Modificar Tema" width="18"></a></td>
		<td align="center" height="20" width="22"><b><font size="2"><a target="_self" href="#" onclick="eliminarTema(<?= $row["IT_ID"]?>, <?= ValorSql($sql, "", $params)?>)"><img height="17" src="/modules/control_gestion/informes_de_gestion/images/eliminar.jpg" title="Eliminar Tema" width="18"></a></td>
		<td style="background-color: #999999; border: 1 solid #666666; " height="1" width="409"><font size="2" color="#FFFFFF"><?= $row["IT_TEMA"]?></font></td>
	</tr>
	<tr>
		<td height="1"></td>
	</tr>

<?
}
?>
		<tr>
			<td align="center" height="10" width="435" colspan="3"></td>
		</tr>
		<tr>
			<td align="center" height="10" width="22"></td>
			<td align="center" height="10" width="22"></td>
			<td align="center" height="10" width="411"><p align="left"><b><font size="2"><input id="btnNuevoTema" name="btnNuevoTema" type="button" value="NUEVO TEMA" style="color: #808080; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; float:left" onClick="verTema(-1, '')"></td>
		</tr>
	</table>
</div>

<div align="center" id="divAbm" name="divAbm" style="display:none">
	<form action="/modules/control_gestion/informes_de_gestion/procesar_tema.php?action=G" id="formTema" method="post" name="formTema" target="iframeTemas" onSubmit="return ValidarForm(formTema)">
		<input id="Id" name="Id" type="hidden" value="-1" />
		<table cellspacing="0" height="16" width="437">
			<tr>
				<td align="center" height="20" width="433" colspan="2" style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px">&nbsp;</td>
			</tr>
			<tr>
				<td align="center" height="5" width="433" colspan="2"></td>
			</tr>
			<tr>
				<td align="center" height="20" width="129"><p align="left"><font style="font-size: 10pt" color="#808080">Nombre del Tema:</font></td>
				<td align="center" height="20" width="304"><p align="left"><input id="NombreTema" maxlength="200" name="NombreTema" size="48" validar="true" title="Nombre del Tema" style="color: #808080; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; float:left"></p></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td align="center" height="20" width="435" colspan="2"><font size="2"><input id="btnGrabar" name="btnGrabar" type="submit" value="GRABAR" style="color: #808080; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; float:right"></td>
			</tr>
		</table>
	</form>
</div>