<?
// El array contiene Título=>Carpeta/Archivo..
$dirs = array(
	"Nuevo Edificio - 16/08/2013"=>"nuevo_edificio/index.html",
	"Resultados de Calidad - 01/08/2013"=>"resultados_de_calidad_2do_cuatrimestre/index.html",
	"Market-Share - 29/07/2013"=>"posicionamiento_resultados_de_mercado/index.html",
	"Compromiso Regional - 18/07/2013"=>"compromiso_regional/index.html",
	"Provincia ART Primera Mención - 06/05/2013"=>"premio_bialet_masse/index.html",
	"Resultados: Orden y Limpieza - 06/05/2013"=>"orden_y_limpieza/index.html",);
?>
<script>
	showTitle(true, 'INSTITUCIONALES');
</script>
<body link="#807F84" vlink="#807F84" alink="#807F84">
<table border="0" cellspacing="0" width="770">
	<tr>
		<td valign="top" width="50%">
			<table border="0" cellSpacing="0" cellPadding="0" width="100%">
				<tr>
					<td vAlign="top" width="450">
						<table border="0" cellSpacing="0" cellPadding="0" width="100%">
							<tr>
								<td width="8"><img border="0" src="images/articulos/programa_de_incentivos_2013.bmp"></td>
								<td width="8">&nbsp;</td> 
								<td vAlign="top">
									<table border="0" cellSpacing="0" cellPadding="0" width="100%">
										<tr>
											<td class="CuerpoArticulo">02/09/2013</td>
									 </tr>
										<tr>
											<td><a class="TituloArticulo" target="_blank" href="portada/seguimiento_programa_de_incentivos_2013/index.html">SEGUIMIENTO: PROGRAMA DE INCENTIVOS</a></td>
										</tr>
										<tr>
											<td class="CuerpoArticulo">Esta semana estamos recibiendo la planilla con los resultados parciales para el primer trimestre.</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td width="4">&nbsp;</td>
					<td class="lineaVertical"></td>
					<td width="4">&nbsp;</td>
					<td vAlign="top">
						<table border="0" cellSpacing="0" cellPadding="0" width="100%">
							<tr>
								<td class="CuerpoArticulo">29/08/2013</td>						
							</tr>
							<tr>
								<td><a class="TituloArticulo" target="_blank" href="portada/nuevo_espacio_del_cem/index.html">NUEVO ESPACIO DEL CEM</a></td>						
							</tr>
							<tr>
								<td class="CuerpoArticulo">¡Le damos la bienvenida al edificio corporativo al CEM!</td>						
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table border="0" cellspacing="0" width="100%">
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table border="0" cellspacing="0" width="770">
	<tr>
		<td align="center" valign="top" width="60%">
			<table border="0" cellspacing="0" width="80%">
				<tr>
					<td align="left" bgcolor="#807F84" class="FormLabelBlanco">&nbsp;Más noticias</td>
				</tr>
				<tr>
					<td height="3px"></td>
				</tr>
<?
$par = true;
foreach($dirs as $key => $value) {
?>
				<tr>
					<td align="left" bgcolor="#<?= ($par)?"FFFFFF":"EAEAEA"?>"?><a href="/portada/<?= $value?>" style="text-decoration: none" target="_blank">&nbsp;<?= $key?></a></td>
				</tr>		
<?
	$par = !$par;
}
?>				
			</table>
		</td>
	</tr>
	<tr>
		<td><p align="center"><a href="index.php?pageid=70">Ver Noticias Anteriores</a></p></td>
	</tr>
</table>