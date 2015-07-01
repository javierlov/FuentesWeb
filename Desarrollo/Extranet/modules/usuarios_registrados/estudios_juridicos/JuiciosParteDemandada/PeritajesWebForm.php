<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/PeritajesWebForm.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
@session_start(); 
$PageBase = new PageBase(false);

ValidarUserSession();
unset($_SESSION["PeritoApellido"]);
unset($_SESSION["PeritoNombre"]);

LimpiarConstPeritajes();
unset($_SESSION["PeritajesABMWebForm"]["id"]);

$_SESSION["PagePrevPeritajesABMWebForm"] = $_SERVER['REQUEST_URI'];

AsignarNroJuicioSession();
$NroJuicio = $_SESSION["NroJuicio"];

list($JT_NUMEROCARPETA, $DESCRIPCARATULA, $EJ_DESCRIPCION) = ObtenerNroCarpeta($NroJuicio);

$_SESSION["NUMEROCARPETA"] = $JT_NUMEROCARPETA;
$_SESSION["DESCRIPCARATULA"] = $DESCRIPCARATULA; 

$PageBase->AgregarEncabezadoJS(true,false,true,false, false, false);
$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/PeritajesWebForm.js?rnd=".RandomNumber());
$PageBase->AgregarArchivoJS("/js/rar/JQUIDialog.js?rnd=".RandomNumber());

$PageBase->AgregarEncabezadoCSS(true,true,true);

$PageBase->AgregarEncabezadoJQUERYUI();

// $PageBase->CrearVentanaMensajeOculta("Peritajes", "mensaje","ACEPTARCANCELAR");
// $PageBase->CrearVentanaMensajeResultado("Peritajes", "mensaje");

/*
if(isset($_REQUEST["DELETE"])){
	$_ID = $_REQUEST["id"];
	
	echo "<script type='text/javascript'>
			var id = ".$_ID.";
			MostrarVentana('¿Está seguro de que desea eliminar este Peritaje..........?');
			AsignarBotones();
		</script>";
} 

if(isset($_SESSION["PeritajeEliminar"])){   
   $resultado = $_SESSION["PeritajeEliminar"]["resultado"];
   
	echo "<script type='text/javascript'>
			var resultadoEstado = ".$resultado."; 	
			var mensajeResultado = ' Peritaje eliminado. ';
			MostrarVentanaResultadoOK( mensajeResultado, resultadoEstado );
		</script>";		
		
	unset($_SESSION["PeritajeEliminar"]);
}
*/
?>

<div align="center" id="divProcesando" name="divProcesando" style="display:none">
	<img border="0" src="/images/waiting.gif" title="Espere por favor...">
</div>

<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral">		
<form name="PeritajesWebForm" method="post" action="/PeritajesWebForm" id="idPeritajesWebForm"  >

<?php	
	echo "<script> BuscarWGTrue(); </script>";
	echo TablaDatosUsuario( $_SESSION["usuario"] );		
	echo TablaDatosJuicioEstado(); 	
?>	
			
	<table class="table_General" align='left' >
	<tr>
		<td>
			<div align="left" id="divContentGrid1" name="divContentGrid1" style="height:100%; 
				margin-left:0px; margin-top:8px; overflow:auto; width:100%;">		
		<?php				
			echo getGridPeritajes($NroJuicio);
			echo "<script> BuscarWGFalseInterval(); </script>";			
		?>		
			</div>
		</td>
	</tr>				
</table>			
		
	<br>
	<input class="btnNuevoEJ btnHover" type="button" onclick=" window.location.href = '/PeritajesABMWebForm/0';" value="" name="btnNuevo">

	<br>
	<a class="btnVolver"  href="/AdminWebForm"></a>

</form>
</div>	

<?php
	$parametros = array("idDialog" => "dialogElimPeritaje",
						"idTitulo" => "idTitulo",
						"idDivInfo" => "idDivInfo",
						"idMotivo" => "idMotivo",
						"idDivLoading" => "idDivLoading",
						"txtDialog" => "Peritajes",
						"txtTitulo" => "Elimina Peritaje Dialog",
						"txtDivInfo" => "¿Está seguro de que desea eliminar este Peritaje?",
						"displayLoading" => true		);
						
	$PageBase->DialogJqueryUI(	$parametros );
 ?>