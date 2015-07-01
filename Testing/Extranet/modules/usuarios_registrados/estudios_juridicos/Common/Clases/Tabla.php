<?php 

class Tabla{ 
    private $tabla;     
    private $error; 	
    private $INI_TABLA,$FIN_TABLA; 
	private $item = '';  

	private $INITABLE;
	private $INITR;
	private $INITD;		

    function __construct($atributos){ 
        
		$this->INITABLE = "<TABLE";
		$this->INITR = "<TR";
		$this->INITD = "<TD";		
		$this->INI_TABLA = $this->INITABLE." $atributos>\n"; 
        $this->FIN_TABLA = "</table>\n"; 		
		$this->item = 'TABLE';

        RETURN TRUE; 
    } 
	
	public function DibujarTabla(){
		
		$result = $this->INI_TABLA; 				

        if(isset($this->tabla)){
			$i = 0;
			foreach($this->tabla as $tabla_fila){				
				$result .= $tabla_fila;          
				
				if(strncmp($this->INITD, $tabla_fila, 3)==0){ 
					$result .= "</TD>";	 }
				elseif(strncmp($this->INITR, $tabla_fila, 3)==0){
					if($i>0) {$result .= "</TR>";}
					$i += 1; 
				}
			} 
		}
		$result .= $this->FIN_TABLA; 				
		//echo "<prev> ".print_r($this->tabla)." </prev>";		
		echo $result;
	}

	public function TR($atributos, $texto){		
		$this->item = 'TR';
		$item = '<TR '.$atributos.' > '.$texto;		
		$this->AgregarItemTabla($item);				
	}
	public function TD($atributos, $texto){		
		$this->item = 'TD';
		$item = "<TD ".$atributos." > ".$texto." ";				
		$this->AgregarItemTabla($item);				
	}	
	public function TREmpty(){		
		$this->item = 'TR';
		$item = '<TR>';		
		$this->AgregarItemTabla($item);				
	}
	public function TDEmpty(){		
		$this->item = 'TD';
		$item = "<TD>";				
		$this->AgregarItemTabla($item);				
	}
			
	private function AgregarItemTabla($item)
	{		
		//var_dump($item); echo "<br>";			
		if(isset($this->tabla))
		{	
			array_push($this->tabla, $item);			
		}
		else{ 			
			$this->tabla = array($item);
		}									         		
	}		
}