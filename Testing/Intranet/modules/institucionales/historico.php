<?
// El array contiene Título=>Carpeta/Archivo..
$dirs = array(
	"Programa de Incentivos 2013 - 22/04/2013"=>"programa_de_incentivos_2013/index.html",
	"Posicionamiento - 11/04/2012"=>"posicionamiento/index.html",
	"Sorteo de San Valentín - 15/02/2013"=>"san_valentin/index.html",
	"Concurso de Fotos - 22/02/2012"=>"concurso_bco_provincia/index.html",
	"Tips para Reuniones - 28/01/2013"=>"tips_para_reuniones_eficientes/index.html",
	"Nueva Intra Corprativa - 11/12/2012"=>"nueva_intra_corporativa/index.html",
	"Nuevos Clientes - 17/09/2012"=>"nuevos_clientes/index.html",
	"+ Eventos - 14/09/2012"=>"+eventos/index.html",
	"Olimpíadas de Valores - 27/07/2012"=>"olimpiadas_londres_2012/index.html",
	"Nuevo Manual PLA- 19/07/2012"=>"nuevo_manual_pla/index.html",
	"Posicionamiento - 10/07/2012"=>"resumen_de_posicionamiento/index.html",
	"Programa de Incentivos - 05/07/2012"=>"programa_de_incentivos_2012/index.html",
	"Plan de Incentivos - 15/05/2012"=>"plan_de_incentivos/index.html",
	"Máquinas de Snacks - 09/05/2012"=>"nuevas_maquinas_de_snacks/index.html",
	"Mejorando el Clima - 20/03/2012"=>"avances_estudio_de_clima/index.html",
	"Expoagro 2012 - 19/03/2012"=>"expoagro_2012/index.html",	
	"Evaluación de Desempeño 2012 - 07/03/2012"=>"evaluacion_de_desempenio_2012/index.html",
	"Nuevas Recepciones - 19/01/2012"=>"nuevas_recepciones_1/index.html",
	"Nuevo Centro Médico Propio - 09/01/2012"=>"nuevo_centro_medico_propio/index.html",
	"Despedimos el 2011 - 22/12/2011"=>"adios_2011/index.html",
	"Resultados GPTW - 20/12/2011"=>"estudio_de_clima/index.html",
	"II Jornadas - 05/12/2011"=>"jornadas_gral_rodriguez_nov2011/index.html",
	"Nuevo Modelo de Trabajo - 01/12/2011"=>"delegaciones_nuevo_modelo_de_trabajo/index.html",
	"Campus Virtual - 21/11/2011"=>"campus_virtual/index.html",
	"Nueva Sucursal - 09/11/2011"=>"delegacion_acassuso/index.html",
	"Presentación de Lineamientos Estratégicos - 07/11/2011"=>"planes_y_lineamientos/index.html",
	"Encuesta de Clima 2011 - 03/11/2011"=>"encuesta_clima_laboral/index.html",
	"Nueva Imágen del Portal - 28/09/2011"=>"portal/index.html",
	"Jornadas de Presupesto - 23/09/2011"=>"jornada_de_presupuestos/index.html",
	"Plan Estratégico GBP - 13/09/2011"=>"planificacion_gp_2012-2014/index.html",
	"Seguridad Informática - 09/09/2011"=>"capacitacion_seg_info/index.html",
	"Balance y Perspectivas - 06/09/2011"=>"balance_y_perspectivas/index.html",
	"Firma Institucional - 02/08/2011"=>"firma_institucional/index.html",
	"La Rural 2011 - 19/07/2011"=>"la_rural_2011/index.html",
	"Nueva Web para Clientes - 04/07/2011"=>"nueva_seccion_web/index.html",
	"Feliz Cumpleaños - 01/07/2011"=>"aniversario_nro_15/index.html",
	"Clientes VIP - 07/06/2011"=>"clientes_vip/index.html",
	"El Prode de la Copa América 2011- 07/06/2011"=>"prode_ca2011/index.html",
	"Convenios y Avances - 05/06/2011"=>"nota_adaipro/index.html",
	"Maratón del Consejo - 30/05/2011"=>"maraton_del_consejo/index.html",
	"Jornadas en Gral Rodriguez- 20/05/2011"=>"jornadas_en_gral_rodriguez/index.html",
	"Nueva Oficina Chivilcoy - 18/05/2011"=>"nueva_oficina_chivilcoy/index.html",
	"Nuevo Cliente: GCBA - 05/05/2011"=>"nuevo_cliente/index.html",
	"FISA - 15/04/2011"=>"fisa/index.html",
	"Expoagro 2011 - 29/03/2011"=>"expoagro_2011/index.html",
	"Campaña de Verano Banco Provincia - 24/01/2011"=>"parador_corporativo/index.html",
	"Nuevos Monitores - 17/01/2011"=>"monitores_nuevos/index.html",
	"Taller de Bienestar Laboral- 10/01/2011"=>"taller_de_bienestar/index.html",
	"Balance General del Sistema - 05/01/2011"=>"balance_srt/index.html",
	"Diagnóstico de Comunicación y Liderazgo - 29/12/2010"=>"res_diagn_com_y_lid/index.htm",
	"Solidaridad Cordobesa - 15/11/2010"=>"solidaridad_cordobesa/index.html",
	"Sistema Integrado de Comunicaciones - 23/08/2010"=>"SIC/index.html",
	"Premio Biallet Massé - 30/04/2010"=>"premio_bialet _masse/index.html",
	"Nuevas Impresoras Multifunción"=>"impresoras_lexmark/index.html",
	"Resolución 1024/2010"=>"resolucion_traslados/index.html",
	"Resolución 1068/2010"=>"resolucion_1068/index.html",
	"Instrucción Nro. 4/10"=>"srt_instruccion_para_el_registro_de_juicios/index.html",
	"Nor Patagonia"=>"nor_patagonia/index.html",
	"Expoagro 2010"=>"expoagro2010/index.html",
	"Nueva Resolución 37/10 de la SRT"=>"resolucion_37-10/index.html",
	"Hospedaje para Comisiones de Servicio"=>"hospedaje/index.html",
	"Semana de la Seguridad Informática II"=>"seguridad_informatica_afiches/index.html",
	"Semana de la Seguridad Informática I"=>"seguridad_informatica/index.html",
	"Entrevista a Sergio Mileo"=>"nota_mileo/index.html",
	"Cómo organizar la información"=>"sistema_archivos/index.html",
	"Resolución SRT 771"=>"SRT_771/index.html",
	"Resolución UIF 125/2009"=>"res125UIF/index.html",
	"Resolución SRT 463/09 529/09"=>"resolucion_SRT/index.html",
	"Un día como hoy"=>"un_dia_como_hoy/index.html",
	"Jornada de Delegaciones"=>"Jornada2008/index.htm",
	"Plataforma 10"=>"Plataforma_10/index.htm",
	"Integrando Realidades"=>"Integrando_Realidades/index.htm",
	"Autoseguro GPBA"=>"Autoseguro_GPBA/index.htm",
	"Atención al Cliente III"=>"At_al_Cliente_3/index.htm",
	"Atención al Cliente II"=>"At_al_Cliente_2/index.htm",
	"Atención al Cliente I"=>"At_al_Cliente_1/index.htm",
	"Conociendo Nuestra Compañia V"=>"NuestraCompania/Conociendo_nuestra_compania_5/index.htm",
	"Conociendo Nuestra Compañia IV"=>"NuestraCompania/Conociendo_nuestra_compania_4/index.htm",
	"Conociendo Nuestra Compañia III"=>"NuestraCompania/Conociendo_nuestra_compania_3/index.htm",
	"Conociendo Nuestra Compañia II"=>"NuestraCompania/Conociendo_nuestra_compania_2/index.htm",
	"Conociendo Nuestra Compañia I"=>"NuestraCompania/Conociendo_nuestra_compania_1/index.htm",
	"Conociendo Nuestra Compañia "=>"NuestraCompania/index.htm",);
?>
<script>
	showTitle(true, 'INSTITUCIONALES');
</script>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<table border="0" cellspacing="0" width="600" align="center">
	<tr>
		<td align="center" valign="top" width="50%">
			<table border="0" cellspacing="0" width="100%">
				<tr>
					<td align="right"><a href="index.php?pageid=25" style="color:#00539B; text-decoration: none; font-weight: 700"><< VOLVER</a></td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td align="left" bgcolor="#807F84" class="FormLabelBlanco">&nbsp;Histórico</td>
				</tr>
				<tr>
					<td height="3px"></td>
				</tr>
				<?
					$par = true;
					foreach($dirs as $key => $value) {
				?>
				<tr>
					<td align="left" bgcolor="#<?= ($par)?"FFFFFF":"EAEAEA"?>"?><a href="/portada/historico_institucionales/<?= $value?>" style="text-decoration: none" target="_blank">&nbsp;<?= $key?></a></td>
				</tr>
				<?
					$par = !$par;
					}
				?>	
			</table>
		</td>
	</tr>
</table>