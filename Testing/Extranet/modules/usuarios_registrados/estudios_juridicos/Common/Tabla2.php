<?php 
class Tabla{ 

/*Variables de la clase*/ 
    var $tabla; 
    var $tabla_; 
    var $class; 
    var $id; 
    var $error; 
	/*********************/
	var $border = 0; 
	var $cellpadding = 3; 
	var $cellspacing = 1; 	
	var $width = "100%"; 

    var $INI_TABLA,$FIN_TABLA,$INI_FILA,$FIN_FILA,$INI_COLUMNA,$FIN_COLUMNA; 

/* function Tabla(), Constructor de la clase*/ 
    function __construct($class,$id, $width){ 

        $this->INI_TABLA = "<TABLE id=$id class=$class width=$width border=0>\n"; 
        $this->FIN_TABLA = "</table>\n"; 
		
        $this->INI_FILA = "<TR>\n"; 
        $this->FIN_FILA = "</TR>\n"; 
		
        $this->INI_COLUMNA = "<TD>\n"; 
        $this->FIN_COLUMNA = "</TD>\n"; 
        
        $this->class=$class; 
        $this->id=$id; 

        RETURN TRUE; 
    } 
	
	public function DibujarTabla(){
		
		$this->tabla_ = $this->INI_TABLA; 
		//$this->tabla_ .= $this->INI_FILA; 

        foreach($this->tabla as $tabla_fila){
            $this->tabla_ .= $tabla_fila;             
        } 
		
        $this->tabla_ .= $this->FIN_FILA; 
		$this->tabla_ .= $this->FIN_TABLA; 
		
			echo "<prev>";
			echo print_r($this->tabla);
			echo "</prev>";
		
		return $this->tabla_;
	}

	public function NuevoRegistro($class){		
			$this->AgregarRegistro($class);		
	}
	
	public function PrimerRegistro($class){
		if(isset($class) and ($class != "")) 
		{
			$celdaclass = " <TD class='".$class."' >";			
			$this->AgregarRegistro($celdaclass);		
		}else
		{
			$celdainifin = $this->FIN_FILA.$this->INI_FILA;
			$this->AgregarRegistro($celdainifin);		
		}
	}
	
	private function AgregarRegistro($class){
		if(isset($class) and ($class != "")) 
		{
			$celdaclass = " <TD class='".$class."' >";			
			$this->AgregarItemArray($celdaclass);
		}else
		{
			$celdainifin = $this->FIN_FILA.$this->INI_FILA;
			$this->AgregarItemArray($celdainifin);
		}
	}
	
	
	public function InsertarCelda($texto, $class){
		//<b><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">Datos del Estudio Juridico</font></b></td>		
			$celda = "";
			$celdatexto = "";
			$celdaclass = "";
			
			if(isset($class) and ($class != "")) $celdaclass = "class='".$class."' ";			
			if(isset($texto) and ($texto != "")) $celdatexto = $texto;									         		
			
			$celda = "<b><font ".$celdaclass."> ".$celdatexto." </font> </b> ";			
			$this->InsertarNuevaCelda($celda);
	}
	
	private function InsertarNuevaCelda($celda){
		$completaCelda = $this->INI_COLUMNA.$celda.$this->FIN_COLUMNA; 		
		$this->AgregarItemArray($completaCelda);
	}
	
	private function AgregarItemArray($item)
	{
		if(isset($this->tabla))
		{				
			array_push($this->tabla, $item);			
		}
		else{ $this->tabla = array($item);}									         		
	}
	/**************************************************************************/
	/*
	function Insertar_Registro($datos){
		If( $this->IsValidArray($datos) ){         
			if(isset($this->tabla))
			{
				$count = count($this->tabla);
				$datostabla	= array($count => $datos);	
				$this->tabla = array_merge($this->tabla, $datostabla);			
			}
			else{$this->tabla = array(1 => $datos);}									
        } 		
	}
	
	private function IsValidArray($datos){
		If(!is_array($datos)){ 
            $this->error = "El parametro no es un array()"; 
            RETURN FALSE; 
        } 
		If(!isset($datos)){ 
            $this->error="El array no tiene datos"; 
            RETURN FALSE; 
        } 
		
		return true;
	}
	

    function llenar_tabla($datos){ 
        If(!is_array($datos)){ 
            $this->error = "El parametro no es un array()"; 
            RETURN FALSE; 
        } 
        If(!isset($datos)){ 
            $this->error="El array no tiene datos"; 
            RETURN FALSE; 
        } 

        $this->tabla=$datos; 
        RETURN TRUE; 
    } 

    function armar_tabla(){ 
        $this->tabla_ = $this->INI_TABLA; 

        foreach($this->tabla as $tabla_fila){
            $this->tabla_ = $this->tabla_ . $this->INI_FILA; 

            foreach($tabla_fila as $dato_columna){
                $this->tabla_ = $this->tabla_ . $this->INI_COLUMNA; 
                $this->tabla_ = $this->tabla_ . $dato_columna;
                $this->tabla_ = $this->tabla_ . $this->FIN_COLUMNA; 
            } 
            $this->tabla_ = $this->tabla_ . $this->FIN_FILA; 
        } 
        $this->tabla_ = $this->tabla_ .$this->FIN_TABLA; 

        RETURN TRUE; 
    } 
     
    function get_tabla(){ 
        IF(!isset($this->tabla_)){ 
            $this->error = "La tabla en  no tiene datos, primero tiene que llamar a armar_tabla()"; 
            RETURN FALSE; 
        } 
        RETURN $this->tabla_; 
    } 
     
    function get_error(){ 
        RETURN $this->error; 
    } 
} 
*/