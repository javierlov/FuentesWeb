<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

class AjaxDatosDB{ 

	private $AD_nombeFuncion; //Nombre de la funcion a ejecutar....
	private $AD_parametros; //array de parametros a grabar en la base
	private $AD_divResultado; //div  donde se mostraran los resulados errores o vacio..
	private $AD_urlfunciones; //urlfunciones donde esta la funcion a ejecutar..
		
	protected function objetoAjax(){
		$resultado = "var xmlhttp=false;
			try {
				xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
			} catch (e) {		 
				try {
					xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
				} catch (E) {
					xmlhttp = false;
				}
			}
		 
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
			  xmlhttp = new XMLHttpRequest();
			}
			return xmlhttp;";
		return $resultado;
	}
	
	function __construct($nombeFuncion, $parametros, $divResultado, $urlfunciones){ 
		$this->AD_nombeFuncion = $nombeFuncion;
		$this->AD_parametros = $parametros;
		$this->AD_divResultado = $divResultado;
		$this->AD_urlfunciones = $urlfunciones;		
	}
	
	protected function LimpiarCampos(){
		//por ahora sin implementar... limpia los campos 
		return " function LimpiarCampos() { return false;} ";
	}
	
	protected function ArmarParametros(){
		$resultado = 'FUNCION='.$this->AD_nombeFuncion;
		foreach($this->AD_parametros as $clave=>$parametro){
			$resultado .= '&'.$clave.'=encodeURIComponent('.$parametro.')';			
		}
		return $resultado;
	}
		
	public function ArmaFuncionJSDB(){		
		$resultado = "var divResultado = document.getElementById($this->AD_divResultado); ";		
		$resultado .= "var ajax=".$this->objetoAjax().";";
		$resultado .= "var pagefunciones=".$this->AD_urlfunciones.";";
		//false = modo sincrono...
		$resultado .= "ajax.open('POST', pagefunciones,false);"; 
		
		$resultado .= "ajax.onreadystatechange=function(){		
				if (ajax.readyState==4){			
					divResultado.innerHTML = ajax.responseText;
					".$this->LimpiarCampos().";
				}	
			}";
		
		$resultado .= "ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');";
		$resultado .= "ajax.send(".$this->ArmarParametros().");";				
		$resultado .= "if(divResultado.innerHTML == ''){return true;} 	return false;";
		
		return $resultado;
	}
}

//require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/AjaxDatosDB.php");
function JSMasDatosABM(){

	$nombeFuncion = 'UpdateMasDatosJuicios'; 
	$divResultado = 'lblErrores';
	$urlfunciones = '/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/MasDatosJuicioWebForm.Grid.php';
	$parametros = array("Domicilio"=>$Domicilio,"Telefonos"=>$Telefonos,"Fax"=>$Fax,"Email"=>$Email,"usuario"=>$usuario,"idJuicio"=>$idJuicio);
	
	$jsmasdatos = new AjaxDatosDB($nombeFuncion, $parametros, $divResultado, $urlfunciones);
	return $jsmasdatos->ArmaFuncionJSDB();
}