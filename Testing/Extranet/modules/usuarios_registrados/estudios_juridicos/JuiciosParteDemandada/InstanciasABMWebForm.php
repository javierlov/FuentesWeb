<?php 
if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/AdminWebForm.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatos.php");

ValidarUserSession();

//print_r($_REQUEST);

	if (isset($_REQUEST['btnAceptar'])){
	    if ($_REQUEST['btnAceptar']=="Aceptar"){	    	
	     	
	     	$JuicioEnTramite = $_SESSION["NroJuicio"]; 
		    $Jurisdiccion = $_REQUEST["txtJurisdiccion"];
		      
		    $Fuero = $_REQUEST["txtFuero"]; 
		    $Juzgado = $_REQUEST["txtJuzgadoNro"]; 
		    $Secretaria = $_REQUEST["txtSecretaria"]; 
		     
		    $NroExpediente = $_REQUEST["txtNroExp"]; 
		    
		    $AnioExpediente = '';
		    if(isset($_REQUEST["txtAnioExp"]))
			    $AnioExpediente = $_REQUEST["txtAnioExp"]; //hAnioExpediente tal vez sea este valor
			    
		    $Motivo = $_REQUEST["txtMotivo"]; 
		    $Detalle = $_REQUEST["txtDetalle"];
		      
		    $LoginName = $_SESSION["usuario"]; 
		    
		    $info=ObtenerInstanciaSeleccionada($Jurisdiccion, $Fuero, $Juzgado);
		       
		    $Instancia = $info["JZ_IDINSTANCIA"]; //Este valor se obtine de la funcion ObtenerInstanciaSeleccionada
		    $nroInstancia = $_REQUEST["hIJ_ID"];
		      
		    $EstadoMediacion = ObtenerEstadoMediacion($JuicioEnTramite); //Este valor se calcula 
		    
		    $FechaIngreso = $_REQUEST["txtFecha"];
		    
		    try{        
			    if( $_REQUEST["accion"] == 'modif' )
			    {
				    $msjabmdatos = 'Los datos fueron actualizados correctamente';
				    
				    UpdateInstanciaAbmMod($JuicioEnTramite, $Jurisdiccion, 
				    	$Fuero, $Juzgado, $Secretaria, 
				        $Instancia, $NroExpediente, 
				        $AnioExpediente, $Motivo, 
				        $Detalle, $LoginName, 
				        $nroInstancia, $EstadoMediacion, $FechaIngreso);
			    }
			    if( $_REQUEST["accion"] == 'nuevo' )
			    {
				    $msjabmdatos = 'Los datos fueron insertados correctamente';
				    
			    	UpdateInstanciaABMAlta(
			    		$JuicioEnTramite, $Jurisdiccion, 
			    		$Fuero, $Juzgado, 
			    		$Secretaria, $Instancia, 
						$NroExpediente, $AnioExpediente, 
						$Motivo, $Detalle, 
						$LoginName, $EstadoMediacion, $FechaIngreso);
			    }        
			    
				    echo "<script type='text/javascript'>
			            	function VolverInstanciasG(){
							 window.location.href = '/InstanciasWebForm';
						    }	
			                alert('Operacion exitosa.".$msjabmdatos."');	
			                VolverInstanciasG();
			                exit;	                
			          </script>";   
		    }
		    catch (Exception $e) {
				echo "<script type='text/javascript'>
						alert('Error: Revise los datos ".$e->getMessage()."');
						window.history.go(-1);	
				  	  </script>"; 
				return true; 
		    }  
	    }	
	}
	

if($_REQUEST['accion'] == 'modif'){
	extract(ObtenerDatosDeJuicio($_SESSION["NroJuicio"], $_SESSION["IDESTUDIOJURIDICO"], $_SESSION["usuario"]));
	extract(ObtenerInstanciaModificar($IJ_ID));
}

if($_REQUEST['accion'] == 'nuevo'){
	$JT_IDJURISDICCION = 0; 	
	$JT_IDJUZGADO = 0;
	$JT_IDSECRETARIA = 0;
}

	$OIM_IJ_ANIOEXPEDIENTE = '05';

    $txtJurisdiccion = '0';
         
    if(!isset($_REQUEST["PRIMERA"])){    
        
        echo "<input type='hidden' value='PRIMERA' name='PRIMERA' />";
        
        if(isset($_REQUEST["JT_IDJURISDICCION"])){
            $JT_IDJURISDICCION = $_REQUEST["JT_IDJURISDICCION"];                   
        }else{
            $_REQUEST["JT_IDJURISDICCION"] = $JT_IDJURISDICCION;
            $optJurisdiccion = urlencode(CargarJurisdiccion($JT_IDJURISDICCION, FALSE));
        }                
                    
        if( isset($_REQUEST["txtJurisdiccion"])){
            $txtJurisdiccion = $_REQUEST["txtJurisdiccion"];                
        }
        
        if( isset($_REQUEST["txtFuero"])){
            $txtFuero = $_REQUEST["txtFuero"];                
        }       
        
        if(!isset($_REQUEST["optSecretaria"])){                
            $optSecretaria = CargarSecretaria($JT_IDJUZGADO, $JT_IDSECRETARIA);                
            $_REQUEST["optSecretaria"] = $optSecretaria;
        }
        
        if(!isset($_REQUEST["optMotivo"])){                
            $optMotivo = CargarMotivo();
            $_REQUEST["optMotivo"] = $optMotivo;                          
        }       
    }
     
?>

<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/legales.css" rel="stylesheet" type="text/css" />		
<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/textos.css" rel="stylesheet" type="text/css" />		
<link href="/styles/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/js/jquery.js"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/InstanciasABMWebForm.js" type="text/javascript"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/js/EstudiosJuridicos.js" type="text/javascript"></script>
	
<form name="InstanciasABMWeb" id="idInstanciasABMWeb" method="post" action="InstanciasABMWebForm"  onSubmit="return submitForm()" >

<input type="hidden" value="<?php echo $IJ_ID; ?>" name="hIJ_ID" id="idhIJ_ID" />
<input type="hidden" value="<?php echo $OIM_IJ_ANIOEXPEDIENTE; ?>" name="hAnioExpediente" id="idhAnioExpediente" />
<input type="hidden" value="<?php echo $_REQUEST['accion']; ?>" name="accion" id="idhaccion" />

<script type="text/javascript">
	function closeMsgOk() {
		if (document.getElementById('msgOk') != null)
			document.getElementById('msgOk').style.display = 'none';
	}

	function submitForm() {
		resultado = ValidarFormInstanciasABMWebForm();		
		return resultado;
	}
	
</script>

<table cellspacing="0" cellpadding="0" width="96%" align="center" bgcolor="#ffffff" class="body_border">
	<tr>
		<td height="2px" colspan="4">
				<?php								
					//TABLA CON DATOS DE ENCABEZADO										    
				   TablaDatosUsuario($_SESSION["usuario"]);	
				?>		
		</td></tr>		
	<tr><td height="16" colspan="4" bgcolor="#808080">
        <b><font face="Verdana" style="FONT-SIZE: 8pt" color="#ffffff">Datos del Juicio</font></b>
    </td></tr>
	<tr>
        <td height="16" width="11%" bgcolor="#e7e7e7" align="left">
            <font face="Verdana" style="FONT-SIZE: 8pt" color="#808080">Nro. Carpeta:</font></td>
        <td height="16" bgcolor="#e7e7e7" style="width: 31%">
            <p align="left">
            <span id="UserControl1_txtNroCarpeta">
            <b>
            <font face="Arial" color="DarkBlue" size="1">
                <?php echo $_SESSION["NUMEROCARPETA"]; ?>
            </font></b></span></td>
        <td height="16" width="6%" bgcolor="#e7e7e7" align="right">
            <font face="Verdana" style="FONT-SIZE: 8pt" color="#808080">Caratula:</font></td>
        <td height="16" width="60%" bgcolor="#e7e7e7" align="left">
            <span id="UserControl1_txtCaratula">
            <b>
            <font face="Arial" color="DarkBlue" size="1">   
                <?php echo $_SESSION["DESCRIPCARATULA"]; ?> 
            </font></b></span></td>	    
    </tr>
	<tr><td height="2px" colspan="4" ></td></tr>		
</table> 		
<br>

<table cellspacing="0" cellpadding="0" width="96%" align="center" bgcolor="#ffffff" class="body_border">		
	<tr>
        <td colspan="2" class="title_NegroFndGrisClaro">
            <label id="lblJuzgado">Juzgado</label> 
            </td></tr>
	<tr>
		<td height="2px" colspan="2">
		    <label style="color=red" id="lblMensaje"> </label>
		</td></tr>	
	<tr>
		<td class="item_grisClaro" height="2px" colspan="4"></td></tr>
		
	<tr>
		<td class="item_grisClaro" width="11%" rowspan="3"  align="left">Jurisdiccion:</td>
		<td class="item_grisClaro" align="left">
			<input name="txtJurisdiccion" type="text" readonly="readonly" id="idtxtJurisdiccion" class="input_text_form"/>
			 <!-- value="<?php echo trim($txtJurisdiccion) ?>"  /-->
		</td>  </tr>
    <tr>
		<td class="item_grisClaro" align="left">		    		    
			<select name="cmbJurisdiccion" id="idcmbJurisdiccion" class="combo_form" >
                <?php					
					echo urldecode($optJurisdiccion);                    
                ?>
			</select> </td> </tr>
	   <tr><td class="item_grisClaro" align="left" id="idJuridiccionTxtAnterior"  >
	       <label id="txtJurisdiccionAnt"  class="valor_azulOscuro">
	           <?php
	               if (isset($OIM_IJ_IDJURISDICCION)) {
					   echo "Jurisdiccion Anterior: ".$OIM_IJ_IDJURISDICCION." ".$OIM_JU_DESCRIPCION;   
				   }	               
	           ?>
	       </label>
	   </td>
	   </tr>
		
    <tr>
        <td height="2px" colspan="2"></td></tr>
        
    <tr>
        <td class="item_grisClaro" width="11%" rowspan="3"  align="left">Fuero:</td>
        <td class="item_grisClaro" align="left">            
            <input name="txtFuero" type="text" readonly="readonly" id="idtxtFuero" class="input_text_form" />                
        </td>  </tr>
    <tr>
        <td class="item_grisClaro" align="left">
            <select name="cmbFuero" id="idcmbFuero" class="combo_form" >                 
            </select>
            
       </td></tr>
       <tr><td class="item_grisClaro" align="left" id="idFueroTxtAnterior">
           <label id="txtFueroAnt"  class="valor_azulOscuro">
               <?php
                   if (isset($OIM_FU_DESCRIPCION)) {
                       echo "Fuero Anterior: ".$OIM_IJ_IDFUERO." ".$OIM_FU_DESCRIPCION;   
                   }                   
               ?>
           </label>        
       </td> </tr>

    <tr>
        <td height="2px" colspan="2"></td></tr>    
	
    <tr>
        <td class="item_grisClaro" width="11%" rowspan="3"  align="left">Juzgado Nro:</td>
        <td class="item_grisClaro" align="left">
            <input name="txtJuzgadoNro" type="text" readonly="readonly" id="idtxtJuzgadoNro" class="input_text_form" />
        </td>  </tr>
    <tr>
        <td class="item_grisClaro" align="left">            
            <select name="cmbJuzgadoNro"   id="idcmbJuzgadoNro" class="combo_form">                
            </select>
       </td></tr>
    <tr><td class="item_grisClaro" align="left" id="idJuzgadoTxtAnterior" >
           <label id="txtFueroAnt" class="valor_azulOscuro">
               <?php
                   if (isset($OIM_JZ_DESCRIPCION)) {
                       echo "Juzgado Anterior: ".$OIM_IJ_IDJUZGADO." ".$OIM_JZ_DESCRIPCION;   
                   }                   
               ?>
           </label>            
       </td></tr>
    <tr>
        <td height="2px" colspan="2"></td></tr>    
    
    <tr>
        <td class="item_grisClaro" width="11%" rowspan="3"  align="left">Secretaria:</td>
        <td class="item_grisClaro" align="left">
            <input name="txtSecretaria" type="text" readonly="readonly" id="idtxtSecretaria" class="input_text_form" />
        </td>  </tr>
    <tr>
        <td class="item_grisClaro" align="left">
            <select name="cmbSecretaria"  id="idcmbSecretaria" class="combo_form">                
            </select>
       </td>
        </tr>       
       <tr><td class="item_grisClaro" align="left" id="idSecretariaTxtAnterior" >
           <label id="txtFueroAnt" class="valor_azulOscuro">
               <?php
                   if (isset($OIM_SC_DESCRIPCION)) {
                       echo "Secretaria Anterior: ".$OIM_IJ_IDSECRETARIA." ".$OIM_SC_DESCRIPCION;   
                   }                   
               ?>
           </label>
       </td>
        </tr>
    <tr>
        <td height="2px" colspan="2"></td></tr>        		

    <tr>
        <td class="item_grisClaro" width="11%" align="left">Instancia:</td>
        <td class="item_grisClaro" align="left">
            <input name="txtInstancia" type="text" readonly="readonly" id="txtInstancia" class="input_text_form" 
                value="<?php if(isset($OIM_IN_DESCRIPCION))  echo $OIM_IN_DESCRIPCION;  ?>" />
        </td>  </tr>    

    <tr>
        <td height="2px" colspan="2"></td></tr>             

	<tr>
		<td width="9%" class="item_grisClaro">Num Exp.:</td>
		<td width="41%" class="item_grisClaro">
			    
			<input name="txtNroExp" type="text" id="txtNroExp" class="input_text_right"
			 value="<?php if(isset($OIM_IJ_NROEXPEDIENTE)) echo $OIM_IJ_NROEXPEDIENTE;  ?>" />			 
			<input name="txtAnioExp" type="text" maxlength="2" id="txtAnioExp" disabled="disabled" class="input_text_form"
			 value="<?php if(isset($OIM_IJ_ANIOEXPEDIENTE))  echo $OIM_IJ_ANIOEXPEDIENTE; ?>" />
		    </td></tr>
	<tr>
	<tr>
        <td height="2px" colspan="2"></td></tr>
        
	
	<tr>
		<td width="11%" class="item_grisClaro">F. Ingreso:</td>
		<td valign="top" class="item_grisClaro">
			
			<!-- click en el control para cambiar fecha-->
			<input id="datepicker" name="txtFecha" type="text"  maxlength="10"  class="input_text_form" 
			 value="<?php  if(isset($OIM_IJ_FECHATRASPASO) ) {echo $OIM_IJ_FECHATRASPASO; }    ?>"  />
								
			                    			
			</td></tr>	
	
	<tr>
        <td height="2px" colspan="2"></td></tr>
		
	<tr>
		<td class="title_NegroFndGrisClaro" align="left" colspan="2" >Motivo</td></tr>		
	<tr>
		<td height="2px" colspan="2"></td></tr>
	<tr>		 
		<td width="11%" align="left" valign="top" rowspan="2" class="item_grisClaro">Motivo:</td>
		
		<td width="89%" align="left" class="item_grisClaro" >
            <input name="txtMotivo" type="text" readonly="readonly"  id="idtxtMotivo" class="input_text_form" />
            
            <select name="cmbMotivo" id="idcmbMotivo" class="combo">
                <?php echo $optMotivo;   ?>                
            </select>
            
            </td></tr>
        <tr><td class="item_grisClaro" align="left" id="idMotivoTxtAnterior" >
           <label id="txtMotivoAnt" class="valor_azulOscuro">
               <?php
                   if (isset($OIM_MC_DESCRIPCION)) {
                       echo "Motivo Anterior: ".$OIM_IJ_IDMOTIVOCAMBIOJUZGADO." ".$OIM_MC_DESCRIPCION;   
                   }                   
               ?>
           </label>
       </td>
        </tr>
    <tr>
        <td height="2px" colspan="2"></td></tr>
        
        
	<tr>	  
		<td width="11%" align="left" valign="top" class="item_grisClaro">Detalle:</td>
		<td width="89%" align="left" class="item_grisClaro" height="150px">
			<input name="txtDetalle" type="text" id="txtDetalle" class="text_area"  
			 value="<?php  if(isset($OIM_IJ_OBSERVACIONES) ) {  echo $OIM_IJ_OBSERVACIONES; } ?>" /></td></tr>
	<tr>
        <td height="2px" colspan="2">
        	<div id="divMsg" style="background-color:#f0f0f0; margin-top:24px; padding:12px"> </div>
        </td></tr>
    <tr>
        <td width="11%" align="left" class="item_grisClaro" >
    <tr>
        <td height="2px" colspan="2">
	        
        </td></tr>
	<tr>
		<td align="left" valign="top" colspan="2" >
			<input type="submit" name="btnAceptar" value="Aceptar"  id="btnAceptar" class="submit" />
				
			<input type="submit" name="btnCancelar" value="Cancelar" id="btnCancelar" class="button" 
				onClick="window.location.href = '/InstanciasWebForm';" />
			</td></tr>
	<tr>
        <td height="2px" colspan="2"></td></tr>
    
	<tr>
		<td align="center" colspan="2" height="50">				
			<input class="btnVolver" type="button" value="" onClick="window.location.href = '/InstanciasWebForm';"/>				
		</td></tr>	
		
	
</table>
</form>

