<?
// El array contiene Título=>Carpeta..
$dirs = array(
	"Fiesta de Fin de Año 2013"=>"fiesta_2013",
	"Jornada de Trabajo e Integración 2013 "=>"jornada_de_integracion_2013",
	"Premios Compromiso y Gestión 2012"=>"premios_compromiso_y_gestion2012",
	"Olimpiadas de Valores 2012"=>"olimpiadas_de_valores_2012",
	"Expoestrategas 2012"=>"expoestrategas_2012",
	"Jornada Little Ranch 2012"=>"jornada_little_ranch_2012",
	"Expoagro 2012"=>"expoagro_2012",
	"Brindis 2011"=>"brindis_2011",
	"Jornada en General Rodriguez - Noviembre 2011"=>"gral_rodriguez_noviembre_2011",
	"Jornadas de Seguridad 2011"=>"jornada_seguridad_tortuguitas_2011",
	"Jornada en General Rodriguez - Mayo 2011"=>"gral_rodriguez_mayo_2011",);
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
					<td align="left" bgcolor="#00539B" class="FormLabelBlanco">&nbsp;Últimas Fotos</td>
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