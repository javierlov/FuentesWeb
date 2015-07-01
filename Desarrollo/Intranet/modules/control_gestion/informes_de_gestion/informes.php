<?
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

// Solo se habilita si el usuario es del sector "Análisis y Control de Gestión" o es alguno de nosotros de prueba..
$sistemas = ((getWindowsLoginName(true) == "ALAPACO") or
						 (getWindowsLoginName(true) == "FPEREZ") or
						 (getWindowsLoginName(true) == "JBALESTRINI"));
$idSector = getUserIdSectorIntranet();
$habilitarAdministarcion = (($idSector == 5014) or ($idSector == 19028) or ($sistemas));
?>
<div align="center">
	<table width="770" cellspacing="0" cellpadding="0" id="table1">
		<tr>
			<td width="45" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><p align="right" style="margin-left: 7px"><b><font size="2"><img src="/modules/control_gestion/informes_de_gestion/images/usuario.jpg" width="26" height="28"></td>
			<td width="101" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font color="#808080" style="font-size: 10pt">Usuario Actual:</font></td>
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
<br>
<div align="center">
	<table cellspacing="0" height="16" width="652" id="table3">
<?
$sql =
	"SELECT it_id, it_tema
     FROM intra.cit_informetemas
   	WHERE it_fechabaja IS NULL
 ORDER BY 2";
$stmt = DBExecSql($conn, $sql);
while ($row = DBGetQuery($stmt)) {
?>
		<tr>
			<td height="1px">
			</td>
		</tr>

		<tr>
			<td style="border: 1 solid #C0C0C0" align="center" height="20" width="21"><b><font size="2"><a target="_self" href="/index.php?pageid=35&tm=<?= $row["IT_ID"]?>&hstr=F"><img src="/modules/control_gestion/informes_de_gestion/images/flecha.gif" width="16" height="16"></a></td>
			<td align="left" height="1" width="598" style="background-color: #807F84; border: 1 solid #C0C0C0"><a target="_self" href="/index.php?pageid=35&tm=<?= $row["IT_ID"]?>&hstr=F"><font size="2" color="#FFFFFF" style="text-decoration:none">&nbsp;<?= $row["IT_TEMA"]?></font><a></td>
      <td height="1" width="22"><b><font size="2"><a target="_self" href="/index.php?pageid=35&tm=<?= $row["IT_ID"]?>&hstr=T"><img height="19" src="/modules/control_gestion/informes_de_gestion/images/historico.jpg" title="Histórico" width="21"></a></td>
		</tr>
<?
}
?>
	</table>
</div>