<?
// El array contiene Título=>Carpeta..
$dirs = array(
	"Fiesta de Fin de Año 2010"=>"Fiesta_2010",
	"Brindis 2010"=>"brindis_2010",
	"Jornadas de Integración y Planificación 2010 "=>"integracion_y_planificacion_vicente_lopez_2010",
	"Nuevas Oficinas Córdoba 2010"=>"nuevas_oficinas_cordoba_2010",
	"Jornadas de Seguridad 2010"=>"jornada_seguridad_tortuguitas_2010",
	"Expoestrategas 2010"=>"expoestrategas_2010",
	"Fiesta de Fin de Año 2007"=>"Fiesta_2007",
	"Fiesta de Fin de Año 2006"=>"Fiesta_2006",
	"Fiesta de Fin de Año 2005"=>"fotos_brindis_05",
	"Fiesta de Fin de Año 2004"=>"fotos_brindis_04");
?>
<script>
	showTitle(true, 'FOTOS');
</script>
<style type="text/css">
a {
	text-decoration: none;
	}

a:hover {
	text-decoration: none;
	}

a:active {
	text-decoration: none;
	}
</style>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="center">
<table border="0" cellspacing="1" width="770">
	<tr>
		<td colspan="5">
			<table border="0" cellspacing="1" width="100%" align="center">
				<tr>	
					<td align="left" bgcolor="#00539B" class="FormLabelBlanco">&nbsp;Histórico</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellspacing="0" height="1" width="375">
<?
$i = 0;
foreach($dirs as $key => $value) {
	if ($i < (count($dirs)/2)) {
?>
				<tr>
					<td width="35" align="center"><a href="/index.php?pageid=23&crp=<?= base64_encode($value."/")?>&ttl=<?= base64_encode($key)?>"><img border="0" src="/modules/fotos/images/icono_fotos.jpg" width="25" height="19"></a></td>
					<td align="left" bgcolor="#807F84" class="FondoOnMouseOver"><a href="/index.php?pageid=23&crp=<?= base64_encode($value."/")?>&ttl=<?= base64_encode($key)?>"><span class="FormLabelBlanco11" style="text-decoration: none">&nbsp;<?= $key?></span></td>
				</tr>
				<tr>
					<td colspan="2" height="1"></td>
				</tr>
<?
	}
	$i++;
}
?>
			</table>
		</td>
		<td width="20"></td>
		<td valign="top">
			<table border="0" cellspacing="0" height="1" width="375">
<?
$i = floor(count($dirs) / 2);
foreach($dirs as $key => $value) {
	if ($i >= count($dirs)) {
?>
				<tr>
					<td width="35" align="center"><a href="/index.php?pageid=23&crp=<?= base64_encode($value."/")?>&ttl=<?= base64_encode($key)?>"><img border="0" src="/modules/fotos/images/icono_fotos.jpg" width="25" height="19"></a></td>
					<td align="left" bgcolor="#807F84" class="FondoOnMouseOver"><a href="/index.php?pageid=23&crp=<?= base64_encode($value."/")?>&ttl=<?= base64_encode($key)?>"><span class="FormLabelBlanco11" style="text-decoration: none">&nbsp;<?= $key?></span></td>
				</tr>
				<tr>
					<td colspan="2" height="1"></td>
				</tr>
<?
	}
	$i++;
}
?>
			</table>
    </td>
  </tr>
</table>
<table cellpadding="0" cellspacing="0" width="730">
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="right"><a href="index.php?pageid=22" style="text-decoration: none; font-weight: 700"><< VOLVER</a></td>
	</tr>
</table>
</div>