<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/PeritajesABMWebForm.Grid.php");
@session_start();
$PageBase = new PageBase(false);

ValidarUserSession();

 // try{
	if(isset($_REQUEST['btnCancelar'])){
		echo "<script type='text/javascript'> 
				window.location.href = '/InstanciasWebForm'; 
			</script>";		
	}

	if (isset($_REQUEST['btnAceptar'])){	    
	     				
	     	$JuicioEnTramite = $_SESSION["NroJuicio"]; 
		    $Jurisdiccion = $_REQUEST["cmbJurisdiccion"];
		      
		    $Fuero = $_REQUEST["cmbFuero"]; 
		    $Juzgado = $_REQUEST["cmbJuzgadoNro"]; 
		    $Secretaria = $_REQUEST["cmbSecretaria"]; 
		     
		    $NroExpediente = $_REQUEST["txtNroExp"]; 
		    
		    $AnioExpediente = '';
		    if(isset($_REQUEST["txtAnioExp"]))	
			    $AnioExpediente = $_REQUEST["txtAnioExp"]; //AnioExpediente tal vez sea este valor
			    
		    $Motivo = $_REQUEST["cmbMotivo"]; 
		    $Detalle = $_REQUEST["txtDetalle"];
		      
		    $LoginName = $_SESSION["usuario"]; 

		    $info=ObtenerInstanciaSeleccionada($Jurisdiccion, $Fuero, $Juzgado);
		       
		    $Instancia = $info["JZ_IDINSTANCIA"]; //Este valor se obtine de la funcion ObtenerInstanciaSeleccionada
		    $nroInstancia = $_REQUEST["ODDJ_IJ_ID"];

		    $EstadoMediacion = ObtenerEstadoMediacion($JuicioEnTramite); //Este valor se calcula 
		    
		    $FechaIngreso = $_REQUEST["txtFecha"];
			
		    try{        
			    if( $_REQUEST["accion"] == "EDIT" )
			    {
				    $msjabmdatos = 'Los datos fueron actualizados correctamente';

				    UpdateInstanciaAbmMod($JuicioEnTramite, $Jurisdiccion, 
				    	$Fuero, $Juzgado, $Secretaria, 
				        $Instancia, $NroExpediente, 
				        $AnioExpediente, $Motivo, 
				        $Detalle, $LoginName, 
				        $nroInstancia, $EstadoMediacion, $FechaIngreso);
			    }
			    if( $_REQUEST["accion"] == "ALTA" )
			    {
				    $msjabmdatos = 'Los datos fueron ingresados correctamente';
				    
			    	UpdateInstanciaABMAlta(
			    		$JuicioEnTramite, $Jurisdiccion, 
			    		$Fuero, $Juzgado, 
			    		$Secretaria, $Instancia, 
						$NroExpediente, $AnioExpediente, 
						$Motivo, $Detalle, 
						$LoginName, $EstadoMediacion, $FechaIngreso);
			    }        
			    
				    echo "<script type='text/javascript'>
			                alert('".$msjabmdatos."');	
			                window.location.href = '/InstanciasWebForm';			                
			          </script>";   
					  
		    }catch (Exception $e) {
				echo "<script type='text/javascript'>
						alert('Error: Revise los datos ".$e->getMessage()."');
						window.history.go(-1);	
				  	  </script>"; 
				return true; 
		    }  
	}	

	ValidarVariablesSession(array("NroJuicio", "IDESTUDIOJURIDICO", "usuario", "InstanciasABM"));
	
	
	extract(ObtenerDatosDeJuicio($_SESSION["NroJuicio"], 
								 $_SESSION["IDESTUDIOJURIDICO"], 
								 $_SESSION["usuario"]) , EXTR_PREFIX_ALL, "ODDJ");	
	
	$ODDJ_IJ_ID = '';
	if($_SESSION["InstanciasABM"]["ACCION"] == "EDIT"){	
				
		$ODDJ_IJ_ID = $_SESSION["InstanciasABM"]["ID"];		
		extract(ObtenerInstanciaModificar($ODDJ_IJ_ID)  , EXTR_PREFIX_ALL, "OIMod");	
		$infoPrev=ObtenerInstanciaSeleccionada($OIMod_OIM_IJ_IDJURISDICCION, $OIMod_OIM_IJ_IDFUERO, $OIMod_OIM_IJ_IDJUZGADO);
		
		echo "<input type='hidden' value='".$OIMod_OIM_IJ_IDJURISDICCION."' id='JurisdiccionPrev' />";		
		echo "<input type='hidden' value='".$OIMod_OIM_IJ_IDFUERO."' id='FueroPrev' />";		
		echo "<input type='hidden' value='".$OIMod_OIM_IJ_IDJUZGADO."' id='JuzgadoPrev' />";		
		echo "<input type='hidden' value='".$infoPrev["JZ_IDINSTANCIA"]."' id='InstanciaPrev' />";		
		
	}

	if($_SESSION["InstanciasABM"]["ACCION"] == "ALTA"){	
		$JT_IDJURISDICCION = 0; 	
		$JT_IDJUZGADO = 0;
		$JT_IDSECRETARIA = 0;
		
		if($_SESSION["JUICIOTERMINADO"] ) {  
			echo "<script type='text/javascript'> 
					alert('Juicio terminado. No puede ingresar nueva instancia.');
					history.back();
				</script>";		
		}
	}

	$txtJurisdiccion = '0';
	        
		
        if(isset($_REQUEST["JT_IDJURISDICCION"])){
            $JT_IDJURISDICCION = $_REQUEST["JT_IDJURISDICCION"];                   
        }else{
            $_REQUEST["JT_IDJURISDICCION"] = $ODDJ_JT_IDJURISDICCION;
            $optJurisdiccion = trim(CargarJurisdiccion(0, FALSE));
        }                
                    
        if( isset($_REQUEST["txtJurisdiccion"])){
            $txtJurisdiccion = $_REQUEST["txtJurisdiccion"];                
        }
        
        if( isset($_REQUEST["txtFuero"])){
            $txtFuero = $_REQUEST["txtFuero"];                
        }       
        
        if(!isset($_REQUEST["optSecretaria"])){                
            $optSecretaria = CargarSecretaria($ODDJ_JT_IDJUZGADO, $ODDJ_JT_IDSECRETARIA);                
            $_REQUEST["optSecretaria"] = $optSecretaria;
        }
        
        if(!isset($_REQUEST["optMotivo"])){                
            $optMotivo = CargarMotivo();
            $_REQUEST["optMotivo"] = $optMotivo;                      			
        }       

	echo "<input type='hidden' value='PRIMERA' name='PRIMERA' />";    
  /*
  }catch(Exception $e){
		VariablesSinSeteo(utf8_encode($e->getMessage()));
  }	
*/  
	
$JuicioEnTramite = $_SESSION["NroJuicio"]; 	
$LoginName = $_SESSION["usuario"]; 
$nroInstancia = $_SESSION["InstanciasABM"]["ID"];		

echo "<script type='text/javascript'> var JuicioEnTramiteSESSION = ".$JuicioEnTramite."; ".
		"	var LoginNameSESSION = '".$LoginName."'; ".
		"	var nroInstancia = ".$nroInstancia."; </script>";

$PageBase->AgregarEncabezadoJS(true,true,true,true, true, false);
$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/InstanciasABMWebForm.js?rnd=".RandomNumber());
$PageBase->AgregarEncabezadoCSS(true,true,true);
$PageBase->AgregarDivProcesando();
$PageBase->ActivarGifProcesando();
$PageBase->CrearVentanaMensajeOculta("Peritaje","mensaje","ACEPTAR");	

include($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/js/AgregaEncabezadoCalendarJS.html"); 
?>

<!-- //CAMBIO PAG 103=110 -->	
<form name="InstanciasABMWeb" id="idInstanciasABMWeb" method="post" action="/index.php?pageid=110" onsubmit="return ValidarFormInstanciasABMWebForm();" >

<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral" style="overflow-x:hidden; height:390px;">			

<input type="hidden" value="<?php echo $ODDJ_IJ_ID; ?>" name="ODDJ_IJ_ID" id="idODDJ_IJ_ID" />
<input type="hidden" value="<?php echo $_SESSION["NroJuicio"]; ?>" name="NroJuicio" id="idNroJuicio" />
<input type="hidden" value="<?php echo $_SESSION["InstanciasABM"]["ACCION"]; ?>" name="accion" id="idhaccion" />
<div id="txtInstanciaID" style="display:none;"></div>

<script type="text/javascript">
	function closeMsgOk() {
		if (document.getElementById('msgOk') != null)
			document.getElementById('msgOk').style.display = 'none';
	}	
</script>

<?php 
	echo TablaDatosUsuario($_SESSION["usuario"]); 
	echo TablaDatosJuicioEstado(); 
?>			


<table class="table_General" align='left' >
	<tr>
        <td colspan="2" class="title_NegroFndAzul">
            <label id="lblJuzgado">Juzgado</label> 
            </td></tr>
	<tr>
		<td height="2px" colspan="2">
		    <label style="color=red" id="lblMensaje"> </label>
		</td></tr>	
	<tr>
		<td class="item_Blanco" height="2px" colspan="2"></td></tr>		
		
	<tr>
		<td class="item_Blanco" width="50px" align="left">Jurisdicción:</td>		
				
		<td class="item_Blanco" align="left">		    		    				
			<select name="cmbJurisdiccion" id="idcmbJurisdiccion" class="combo_form" placeholder="combo" style='width:90%;'>
                <?php	echo $optJurisdiccion;  ?>
			</select> 		
		<br>				
<?php if (isset($OIMod_OIM_IJ_IDJURISDICCION)) { 
	echo "<label class='valor_azulOscuro'>";           
	echo "Jurisdiccion Anterior: ".$OIMod_OIM_IJ_IDJURISDICCION." ".$OIMod_OIM_JU_DESCRIPCION; 
	echo "</label>";
} ?>
	        		
		  <div class="input_textError" id="lblErrorestxtJurisdiccion"></div>
	   </td>   
	</tr>
	   
    <tr>
        <td class="item_Blanco" width="11%" align="left">Fuero:</td>        
        <td class="item_Blanco" align="left">            
            <select name="cmbFuero" id="idcmbFuero" class="combo_form" style='width:90%;'>                 
            </select>                          
<?php if (isset($OIMod_OIM_FU_DESCRIPCION)) { 
	echo "<br><label class='valor_azulOscuro'>";           
	echo "Fuero Anterior: ".$OIMod_OIM_IJ_IDFUERO." ".$OIMod_OIM_FU_DESCRIPCION; }  
	echo "</label>";
?>			
		<div class="input_textError" id="lblErrorestxtFuero"></div>
       </td> 
	   </tr>
    
    <tr>
        <td class="item_Blanco" width="11%"   align="left">Juzgado Nro:</td>
		
        <td class="item_Blanco" align="left">                        
            <select name="cmbJuzgadoNro"   id="idcmbJuzgadoNro" class="combo_form" style='width:90%;'>                
            </select>   
			<br>
<?php 
	if (isset($OIMod_OIM_JZ_DESCRIPCION)) { 
	echo "<label class='valor_azulOscuro'>";           
	echo "Juzgado Anterior: ".$OIMod_OIM_IJ_IDJUZGADO." ".$OIMod_OIM_JZ_DESCRIPCION;  
	echo "</label>";
}  ?>
			<div class="input_textError" id="lblErrorestxtJuzgado"></div>		   
       </td>
	</tr>    
	
    <tr>
        <td class="item_Blanco" width="11%" align="left">Instancia:</td>
        <td class="item_Blanco" align="left">            			
			<div name="txtInstancia" id="txtInstancia" class="input_text_form_block" style="width:250px; height:13px;" >
<?php  if(isset($OIMod_OIM_IN_DESCRIPCION))  echo $OIMod_OIM_IN_DESCRIPCION;  ?></div> 
			<div class="input_textError" id="ErrorestxtInstancia"></div>
        </td>  </tr>    
		
    <tr>
        <td class="item_Blanco" width="11%" align="left">Secretaría:</td>              
        <td class="item_Blanco" align="left">            
            <select name="cmbSecretaria"  id="idcmbSecretaria" class="combo_form" style='width:90%;'>                
            </select>
			<br>
			<label id="txtFueroAnt" class="valor_azulOscuro">
<?php if (isset($OIMod_OIM_SC_DESCRIPCION)) { echo "Secretaria Anterior: ".$OIMod_OIM_IJ_IDSECRETARIA." ".$OIMod_OIM_SC_DESCRIPCION; } ?>
           </label>

			<div class="input_textError" id="lblErrorestxtSecretaria"></div>
       </td>
    </tr>
    
	<tr>
		<td width="9%" class="item_Blanco">Num Exp.:</td>
		<td width="41%" class="item_Blanco">
			    
			<input name="txtNroExp" type="text" id="txtNroExp" class="input_text_right"  
				value="<?php if(isset($OIMod_OIM_IJ_NROEXPEDIENTE)) echo $OIMod_OIM_IJ_NROEXPEDIENTE;  ?>" />			 
				
			<input name="txtAnioExp" type="text" maxlength="2" id="txtAnioExp" class="input_text_form"  style="width:50px;"
				value="<?php 					
					if(isset($OIMod_OIM_IJ_ANIOEXPEDIENTE))  
					echo $OIMod_OIM_IJ_ANIOEXPEDIENTE; 
					?>" />
			<div id="divtxtAnioExp" style="display:none;"></div>    			
			<div class="input_textError" id="lblErrorestxtNumExp"></div>		   
		    </td></tr>
	<tr>
	
	<tr>
		<td width="11%" class="item_Blanco">F. Ingreso:</td>
		<td valign="top" class="item_Blanco">
						
			<input id="idtxtFecha" name="txtFecha"  type="text" maxlength="10"  
				class="input_text_Fecha"  value="<?php  if(isset($OIMod_OIM_IJ_FECHATRASPASO) ) {echo $OIMod_OIM_IJ_FECHATRASPASO; }    ?>" />
			<input id="idbtnFecha" type="button" name="btnFecha" value="..." class="BotonFechaEstudio" />
			<div class="input_textError" id="lblErrorestxtFecha"></div>		   
			</td></tr>		
	
	<tr>
		<td class="title_NegroFndAzul" align="left" colspan="2" >Motivo</td></tr>			
	<tr>		 
		<td width="11%" align="left" valign="top" class="item_Blanco">Motivo:</td>
		
		<td width="89%" align="left" class="item_Blanco" >            
            <select name="cmbMotivo" id="idcmbMotivo" class="combo"><?php echo $optMotivo; ?>
            </select>            			
			<br>			
           <label id="txtMotivoAnt" class="valor_azulOscuro">
<?php if (isset($OIMod_OIM_MC_DESCRIPCION)) { echo "Motivo Anterior: ".$OIMod_OIM_IJ_IDMOTIVOCAMBIOJUZGADO." ".$OIMod_OIM_MC_DESCRIPCION; } ?>
           </label>		   
		    <div id="divmotivo" style="display:none;"></div>    			
			<div class="input_textError" id="lblErrorestxtMotivo"></div>
       </td> </tr>
    
	<tr>	  
		<td width="11%" align="left" valign="top" class="item_Blanco">Detalle:</td>
		<td width="90%" align="left" class="item_Blanco" >
			<textarea name="txtDetalle" type="text" id="txtDetalle" rows="8"
				class="text_area"><?php if(isset($OIMod_OIM_IJ_OBSERVACIONES) ) {  echo $OIMod_OIM_IJ_OBSERVACIONES; } ?></textarea>
			<div class="input_textError" id="lblErrorestxtDetalle"></div>
		</td></tr>
	<tr>
	<td class="item_Blanco" colspan="2"></td></tr>
    <td height="2px" colspan="2">	
</table>
	
</div>
<div style="position:fixed; left:50%;">
<img id="imgAplicandoCambios" src="/images/loading.gif" style="display:none;" title="Aplicando, aguarde un instante por favor..." />
</div>
<div style="overflow-x:hidden; white-space:nowrap;">		

<?php if(!$_SESSION["JUICIOTERMINADO"] ) {  ?>						
	<input type="button" name="btnAceptar" value=""  id="idAceptarAjax" class="btnAceptarEJ btnHover" />				
	<input type="button" name="btnCancelar" value="" id="btnCancelar" class="btnCancelarEJ btnHover"	onClick="window.location.href = '/InstanciasWebForm';" />
<?php }  ?>							
<label class="input_textError" id="lblErrores"></label>
<br>
	<input class="btnVolver" type="button" value="" onClick="window.location.href='/InstanciasWebForm';"/>				
</div>

</form>

<?php $PageBase->DesactivarGifProcesando(); ?>