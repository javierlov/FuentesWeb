<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/JuiciosParteDemandada.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

@session_start(); 

ValidarUserSession();

?>
<link href="/styles/style.css" rel="stylesheet" type="text/css" />		
<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/legales.css" rel="stylesheet" type="text/css" />		

<script type="text/javascript">

  function imprimePDF(){		  
	var rutaprint = "/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/ListadoJuiciosImpreso.php";
	var parametros = "?imprimir=true";
	
	var cbActivos = document.getElementById("cbActivos");
	if (cbActivos.checked){
		parametros += "&Activos";
	}
	var cbTerminado = document.getElementById("cbTerminado");
	if (cbTerminado.checked){
		parametros += "&Terminado";
	}		
	
	var tituloventana = 'ListadoJuicios';
	var path = rutaprint + parametros;
	
	newwindow = window.open(path, tituloventana);
		
	return true;
  }
</script>


<?php echo TablaDatosUsuario($_SESSION["usuario"], "center");  ?>
<br>
	<table class="table_General" align='center' >		
		<tr class="title_NegroFndAzul" >
			<td colspan="3" ><?php echo utf8_decode("Selección Listado Juicios")?></td>
		</tr>
		<tr >
			<td></td>
			<td></td>
			<td></td>
		</tr>		
		<tr>
			<td>
				<span class="item_grisClaroFndBlanco">
					<input id="cbActivos" type="checkbox" name="cbActivos" />
					<label for="cbActivos">Activos</label></span>
			</td>
			<td>
				<span class="item_grisClaroFndBlanco">
					<input id="cbTerminado" type="checkbox" name="cbTerminado" />
					<label for="cbTerminado">Terminados</label></span>
			</td>
			<td>
				<input type="button" name="btnImprimir" class="btnImprimirEJ btnHover" id="btnImprimir" value="" title="Imprimir"  
						onclick="imprimePDF();" />
			</td>
		</tr>
		<tr >
			<td></td>
			<td></td>
			<td></td>
		</tr>				
	</table>
	<br>
	<input class="btnVolver"  name="btnVolver" type="button" onClick="history.back(-1);"/>				
	
</form>
