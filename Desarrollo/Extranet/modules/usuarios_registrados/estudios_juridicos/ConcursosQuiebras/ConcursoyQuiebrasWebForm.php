<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ConcursosQuiebras/ConcursoyQuiebrasWebForm.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
@session_start(); 
$PageBase = new PageBase(false);

ValidarUserSession();

$PageBase->AgregarEncabezadoCSS(true,false,true);

$HEADjquery=true;
$HEADComunes=true;
$HEADEstudiosJuridicos=true;
$HEADGrabaDatos=false;
$HEADvalidations=false;
$HEADAutocompletar=false;
$PageBase->AgregarEncabezadoJS($HEADjquery, $HEADComunes, $HEADEstudiosJuridicos, $HEADGrabaDatos, $HEADvalidations, $HEADAutocompletar);
$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/ConcursosQuiebras/js/ConcursoyQuiebrasWebForm.js");

$PageBase->CrearVentanaMensajeOculta("Concurso y Quiebras","mensaje","ACEPTAR");
?>
<title>Concursos y Quiebras</title>

<!-- //CAMBIO PAG 109=116 -->
<form name="ConcursosQuiebras" method="POST" action="/index.php?pageid=116"	id="idConcursosQuiebras">
	
<div align="center" id="divProcesando" name="divProcesando" style="display:none">
	<img border="0" src="/images/waiting.gif" title="Espere por favor...">
</div>

<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral" style="overflow: hidden;">			
	
<?php 
	echo TablaDatosUsuario($_SESSION["usuario"]);	
?>

<table class="table_General" border="0" align="left">
	<tr>
		<td colspan="5" class="title_NegroFndAzul">Búsqueda de Juicios</td>
	</tr>
	<tr>
		<td colspan="5" height="5"></td>
	</tr>
	<tr>
		<td width="100" class="item_grisClaroFndBlanco">Nro de Orden:</td>
		<td width="144" class="item_grisClaroFndBlanco">
			<input name="txtNroOrden" type="text" id="txtNroOrden" class="numerico" />	</td>
		<td> </td></tr>		
	<tr>
		<td width="44" class="item_grisClaroFndBlanco">CUIT:</td>
		<td width="635" class="item_grisClaroFndBlanco">
			<input name="txtcuil1" type="text" maxlength="2" id="txtcuil1" class="numerico" style="width:20px; " />
			<input name="txtcuil2" type="text" maxlength="8" id="txtcuil2" class="numerico" style="width:80px; " />
			<input name="txtcuil3" type="text" maxlength="1" id="txtcuil3" class="numerico" style="width:20px; " />
			</td>
			<td></td>			
			</tr>
	<tr>			
		<td>  </td>
		<td>  </td>
		<td>  </td>
	</tr>
	
	<tr>
		<td WIDTH="100" align="left" class="item_grisClaroFndBlanco" rowspan="2">R. Social:</td>
		<td align="left" colspan="1" class="item_grisClaroFndBlanco">
			<input name="txtPerito" type="text" id="txtPerito" class="input_text" />
			<input type="button" class="btnBuscarEmpresaEJ" name="btnPerito" id="idbtnPerito" value="" />
			<td align="right" class="item_grisClaroFndBlanco">
				<input type="Reset" name="Limpiar" class="btnLimpiarEJ" id="idbtnLimpiar" title="Limpiar Filtros" value="" />
			</td>	
		</tr>
	<tr>	
		<td>
			<select name="cmbRsocial" id="idcmbRsocial" class="combo">
			</select>
		</td>
		
		<td align="right" class="item_grisClaroFndBlanco">
			<input type="submit" class="btnBuscarEJ" name="Busqueda" id="btnBusqueda1" title="Buscar" value="" />
		</td>
		</tr>
	<tr>
		<td colspan="3" height="10"></td>
		</tr>	
</table>
<hr>
 <?php				
			if(isset($_REQUEST["Busqueda"]) ) {					
							
				$NroOrden = '';
				$cmbRSocial = '';
				$Cuil = '';
				$estudio = $_SESSION["IDESTUDIOJURIDICO"];				
				$NroOrden = ValorParametroRequest('txtNroOrden');
				$cmbRSocial = ValorParametroRequest('cmbRsocial');
				
				$Cuil = ValorParametroRequest('txtcuil1');
				$Cuil .= ValorParametroRequest('txtcuil2');
				$Cuil .= ValorParametroRequest('txtcuil3');
				
				echo getGridCyQ($NroOrden, $cmbRSocial, $Cuil, $estudio);								
} ?>

<br>
		<input class="btnVolver"  name="btnVolver" type="button" onClick="window.location.href = '/SeleccionAplicacion'" />				
</div>
</form>