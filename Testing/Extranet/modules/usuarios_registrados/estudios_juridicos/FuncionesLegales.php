<?php
if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Common/Clases/Tabla.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/CrearLog.php");

	function clearString($text) { 
		$result = preg_replace('([^A-Za-z0-9])', '', $text); 
		return $result; 
	}

	function clearNumber($text) { 
		$result = preg_replace('([^0-9])', '', $text); 
		return $result; 
	}
	
	function ValidarAlfaNum($text) { 
		$result = clearString($text); 
		return ($result == $text); 
	}
	
	function ValidarNumerico($text) { 
		$result = clearNumber($text); 
		return ($result == $text); 
	}

	function ValidarCampos($CodCaratula, $NroExpediente, $NroCarpeta, $tipoJuicio)
	{
		$result = (ValidarAlfaNum($CodCaratula) 
			and ValidarNumerico($NroExpediente) 
			and ValidarNumerico($NroCarpeta) 
			and  ValidarNumerico($tipoJuicio));
			
		return $result; 
	}

	/*
     * ValidarUserSession: validacion general para determinar si el user es valido para estudio juridico
     */
	function ValidarUserSession(){		
		if ((!isset($_SESSION["isAbogado"])) and (!$_SESSION["isAbogado"]) and (!isset($_SESSION["idUsuario"])) ) {			
			echo "<script type='text/javascript'>window.location.href = '/logout.php'</script>";			
			exit;			
		}		
	}
	
	/*
     * ComparaInt: compara dos int y retorna true si son iguales
     */ 
	function ComparaInt($int1, $int2){						
		$result = FALSE;	
		try	{			
			if( (is_numeric($int1)) and (is_numeric($int2)) ) {
				if( intval($int1) == intval($int2)){
					$result = TRUE;								
				}				
			}			
			return $result;
		} 
		catch(Exception $e) {			
			return FALSE;
		}
	}	
	
    /*
     * ComparaStr: compara dos strings y retorna true si son iguales
     */ 
	function ComparaStr($str1, $str2){		
		$str1 = strtoupper(trim($str1));
		$str2 = strtoupper(trim($str2));

		$result = strcmp($str1, $str2);				 

		return $result;
	}
	
    /*
     * AddComboOption: Agrega items a un combo 
     */
	function AddComboOption($clave, $texto, $isSelected=false){
		$result ='<option value='.$clave;	
		if($isSelected == true){			
			$result .= " selected='selected' ";	
		}
		$result .= '>'.$texto.'</option> ';
		return $result;
	}
	
    /*
     * CargarComboOpt: Define cual es el item seleccionado y
     *  Agrega items al combo y selecciona si corresponde 
     */
    function CargarComboOpt($id, $value, $idComp, $selectedId){
   
        $selectOpt = FALSE;                             
        if($selectedId) {
            
              $selectOpt = ComparaInt($id, $idComp);
        }           
        $result = AddComboOption($id, $value, $selectOpt);    
        return $result;
        
    }
    
	
	function SelectArrayOptions($tipoJuicio){
		$result = " <select name='cmbTipoJuicio' id='cmbTipoJuicio' class='input_text'>";
	
		$options = array(
			 '0' => '',
			 '1' => 'CONTENCIOSO ADMINISTRATIVO',
			 '9' => 'CRIMINAL Y CORRECCIONAL',
			 '8' => 'FEDERAL',
			 '2' => 'FEDERAL CIVIL Y COMERCIAL',
			 '3' => 'NACIONAL CIVIL',
			 '5' => 'NACIONAL COMERCIAL',
			 '4' => 'NACIONAL LABORAL',
			 '7' => 'PROVINCIAL CIVIL',
			 '6' => 'PROVINCIAL LABORAL'
			); 

		if (isset($tipoJuicio)){
			$optselected = $tipoJuicio;
		}
		
		foreach ($options as $value=>$text){ 		
			$result .=  ' <option ';
			
			if($value == $optselected){
				$result .= ' selected="selected" '; 
			}
			$result .= 'value='.$value.'> ';
			$result .=  $text; 
			$result .= ' </option> ';
		}	
		$result .= ' </select> ';
		
		return $result;
	}
	
	
	function TablaDatosUsuario($usuario){		
		$NombreEstudio = '';
		if( isset($_SESSION["NOMBREESTUDIO"]) ) {
			$NombreEstudio = $_SESSION["NOMBREESTUDIO"];}
			
		$tab = new Tabla(" cellspacing='0' cellpadding='2' width='100%' border='0' align='center' ");
		$tab->TR(" height='2px' colspan='4' ","");
		$tab->TR("","");
			$tab->TD(" colspan='4' bgcolor='#808080' align='left' ","<b><font face='Verdana' style='font-size: 8pt' color='#FFFFFF'>Datos del Estudio Juridico</font></b>");
		$tab->TR("","");
			$tab->TD(" width='6%' bgcolor='#E7E7E7' align='left' ","<font color='#808080' face='Verdana' style='font-size: 8pt'>Usuario:</font>");
			$tab->TD(" width='33%' bgcolor='#E7E7E7' align='left' ","<font face='Verdana' style='font-size: 8pt'>			
							<span id='DatosEstudioUserControl_txtUsuario'><b>
							<font face='Arial' color='DarkBlue' size='1'>$usuario</font></b>
							</span></font>");
			$tab->TD("width='13%' bgcolor='#E7E7E7' align='right'","<font face='Verdana' style='font-size: 8pt; ' color='#808080'>Estudio Juridico:</font>");
			$tab->TD("width='65%' bgcolor='#E7E7E7' align='left'","<font face='Verdana' style='font-size: 8pt'>
						<span id='DatosEstudioUserControl_txtEstudio'><b><font face='Arial' color='DarkBlue' size='1'>$NombreEstudio</font></b></span></font>");
		$tab->TR("","");
			$tab->TD("colspan='4'","");	
		$tab->DibujarTabla();
		
	}
	
	function TablaDatosJuicio($NUMEROCARPETA, $DESCRIPCARATULA){		
		$resultado = "<table cellspacing='0' cellpadding='0' width='100%' border='0' align='center'>
					    <tr>
					        <td colspan='4' bgcolor='#808080' style='height: 16px'>
								<b><font face='Verdana' style='FONT-SIZE: 8pt' color='#ffffff'>Datos del Juicio</font></b>	</td>   </tr>
					     <tr>
					        <td height='16' width='11%' bgcolor='#e7e7e7' align='left'>
					        	<font face='Verdana' style='FONT-SIZE: 8pt' color='#808080'>Nro. Carpeta:</font></td>
					        <td height='16' bgcolor='#e7e7e7' style='width: 31%'>
					          <p align='left'>
					          <span id='UserControl1_txtNroCarpeta'><b><font face='Arial' color='DarkBlue' size='1'>".$NUMEROCARPETA."</font></b></span>   </td>
					        <td height='16' width='6%' bgcolor='#e7e7e7' align='right'>
								<font face='Verdana' style='FONT-SIZE: 8pt' color='#808080'>Caratula:</font></td>
					        <td height='16' width='60%' bgcolor='#e7e7e7' align='left'>
					          <span id='UserControl1_txtCaratula'><b><font face='Arial' color='DarkBlue' size='1'>".$DESCRIPCARATULA."</font></b></span></td></tr></table>";
						
		return $resultado;
	}
	
	function GetStrToDate($strdate){
	 	if (trim($strdate) == '') 	
	 		$strdate= NULL;
		else 
			$strdate = date( "d-m-Y", strtotime($strdate) );	
			
		return $strdate;
	}
	
	function Getfloat($str) {
	  	  
	  if(is_null($str) )
	  	$str = '0';
	 
	  if(strstr($str, ",")) {
	    $str = str_replace(".", "", $str); 
	    $str = str_replace(",", ".", $str); 
	  }

	  if(!is_numeric($str) )
	  	$str = '0';
	 
	  if(preg_match("#([0-9\.]+)#", $str, $match)) { 
	    $return = floatval($match[0]);
	  } else {
	    $return = floatval($str); 
	  }
	  
	  
	  if($return == '') 
	  	$return = '0';
	  	
	  return $return;
	   	
	}
	
	function formatearDinero($valor){
		return number_format($valor, 2, ',', '');
	}

