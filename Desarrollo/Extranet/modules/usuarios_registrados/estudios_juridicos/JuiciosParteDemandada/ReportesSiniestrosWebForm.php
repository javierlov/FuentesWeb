<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");

@session_start(); 
$PageBase = new PageBase(false);

ValidarUserSession();

AsignarNroJuicioSession();

$HEADjquery=true;
$HEADComunes=false;
$HEADEstudiosJuridicos=true;
$HEADGrabaDatos=false;
$HEADvalidations=false;
$HEADAutocompletar=false;
$PageBase->AgregarEncabezadoJS($HEADjquery, $HEADComunes, $HEADEstudiosJuridicos, $HEADGrabaDatos, $HEADvalidations, $HEADAutocompletar);


$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/ReportesSiniestrosWebForm.js?rnd=".RandomNumber());

$PageBase->AgregarArchivoJS("/js/rar/JQUIDialog.js?rnd=".RandomNumber());
$PageBase->AgregarEncabezadoCSS(true,true,true);
$PageBase->AgregarEncabezadoJQUERYUI();

?>

<div align="center" id="divProcesando" name="divProcesando" style="display:none">
	<img border="0" src="/images/waiting.gif" title="Espere por favor...">
</div>

<form name="EventosWebForm" method="post" action="/EventosWebForm" id="idEventosWebForm">
<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral" style="overflow:hidden;">		
	
<?php			
	echo "<script> BuscarWGTrue(); </script>";
	echo TablaDatosUsuario( $_SESSION["usuario"] );				
	echo TablaDatosJuicioEstado(); 
	echo "<table class='table_General' align='left'><tr>	<td colspan='2' class='title_NegroFndAzul' >Siniestro ".$_SESSION["ReportesSiniestros"]["ID"]."</td> </tr></table>";	
	
?>		
	
	<table style='padding: 10px 50px;' align='left'>
		<tr>	<td class='paginado_links_Reporte' ><a class='paginado_links_Reporte' target="_blank" href = '/modules/usuarios_registrados/estudios_juridicos/redirect.php?PrintReportesSiniestros=123' >Resumen Siniestros</a></td> </tr>
		<tr>	<td class='paginado_links_Reporte' ><a class='paginado_links_Reporte' target="_blank" href = '/modules/usuarios_registrados/estudios_juridicos/redirect.php?PrintSeguimientodeIncapacidad=123'>Seguimiento de Incapacidad</a></td> </tr>
		<tr>	<td class='paginado_links_Reporte' ><a class='paginado_links_Reporte' target="_blank" href = '/modules/usuarios_registrados/estudios_juridicos/redirect.php?PrintEvolutivodeSiniestro=123'>Evolutivo de Siniestro</a></td> </tr>
		<tr>	<td class='paginado_links_Reporte' ><a class='paginado_links_Reporte' target="_blank" href = '/modules/usuarios_registrados/estudios_juridicos/redirect.php?PrintFichaTrabajador=123'>Ficha Trabajador</a></td> </tr>
		<tr>	<td class='paginado_links_Reporte' ><a class='paginado_links_Reporte' target="_blank" href = '/modules/usuarios_registrados/estudios_juridicos/redirect.php?PrintReporteDatosdelaEmpresa=123'>Datos de la Empresa</a></td> </tr>
	</table>

</div>	

	<a class="btnVolver"  href="<?=$_SERVER['HTTP_REFERER']?>"></a>
	
</form>


<?php
		
	$parametros = array("idDialog" => "dialogElimEvento",
						"idTitulo" => "idTitulo",
						"idDivInfo" => "idDivInfo",
						"idMotivo" => "idMotivo",
						"idDivLoading" => "idDivLoading",
						"txtDialog" => "Eventos",
						"txtTitulo" => "Elimina Evento Dialog",
						"txtDivInfo" => "¿Está seguro de que desea eliminar este Evento?",
						"displayLoading" => true		);
	
 ?>
 
 