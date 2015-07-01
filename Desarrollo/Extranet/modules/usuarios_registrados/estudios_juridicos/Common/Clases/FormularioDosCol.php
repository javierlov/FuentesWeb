<?php

require_once  "Formulario.php";

class FormularioDosCol extends Formulario{
		
	private $ftablaColDer;
	private $ftablaColIzq;
	
		
	function __construct($class, $id,  $width){		
		parent::__construct('', '', $class, $id,  '');
	}
		
	public function SetColDer($Texto, $class, $id){
		$newitem = $this->NuevaCelda($Texto, $class, $id);
		if( isset($this->ftablaColDer) ){
			array_push($this->ftablaColDer, $newitem);			
		}
		else{
			$this->ftablaColDer = array($newitem);			
		}		
		$this->SetColunas();
	}
	
	public function SetColIzq($Texto, $class, $id){
		$newitem = parent::NuevaCelda($Texto, $class, $id);
		if( isset($this->ftablaColIzq) ){
			array_push($this->ftablaColIzq, $newitem);			
		}
		else{
			$this->ftablaColIzq = array($newitem);			
		}		
		$this->SetColunas();
	}
	
	private function SetColunas(){				
		//echo 'setcuerpo <br>';
		$this->ftablaCuerpo = array($this->ftablaColIzq);					
		array_push($this->ftablaCuerpo, $this->ftablaColDer);	

		echo "<prev>";
		print_r($this->ftablaCuerpo);
		echo "</prev>";
	}	
}
