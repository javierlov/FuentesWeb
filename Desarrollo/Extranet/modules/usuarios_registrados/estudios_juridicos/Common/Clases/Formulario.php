<?php

require_once  "ControlBase.php";

class Formulario extends ControlBase{
	private $ftabla;
	private $ftablaINI;
	private $ftablaFIN;
	private $borde;
	
	private $ftablaEncabezado;
	private $ftablaCuerpo;
	private $ftablaPie;	
		
	function __construct($class, $id,  $width){		
		parent::__construct('', '', $class, $id,  '');
		$this->ftablaINI = "<TABLE id=$id class=$class width=$width ";
		$this->ftablaFIN = "</TABLE>";
				
		/*
		para testear
		$idhead = $id."head";
		$idbody = $id."body";
		$idfoot = $id."foot";
		$this->SetEncabezado('Encabezado',$class, $idhead);
		$this->SetCuerpo('Cuerpo','principal', $idhead);
		$this->SetPie('Pie',$class, $idhead);
		*/
		return true;	
	}
		
	public function SetEncabezado($Texto, $class, $id){
		$newitem = $this->NuevaCelda($Texto, $class, $id);				
		if( isset($this->ftablaEncabezado) ){
			array_push($this->ftablaEncabezado, $newitem);			
		}
		else{			
			$this->ftablaEncabezado = array($newitem);			
		}		
	}
	
	public function SetCuerpo($Texto, $class, $id){
		$newitem = $this->NuevaCelda($Texto, $class, $id);
		if( isset($this->ftablaCuerpo) ){
			array_push($this->ftablaCuerpo, $newitem);			
		}
		else{
			$this->ftablaCuerpo = array($newitem);			
		}		
	}
	
	public function SetPie($Texto, $class, $id){
		$newitem = $this->NuevaCelda($Texto, $class, $id);
		if( isset($this->ftablaPie) ){
			array_push($this->ftablaPie, $newitem);			
		}
		else{
			$this->ftablaPie = array($newitem);			
		}		
	}
	public function SetBorde($value){		
		if( trim($value) != '' ){
			$this->borde = " border=".trim("$value");
		}		
	}
	
	private function NuevoRegistro($texto, $class, $id){
		$newitem  = '';
		
		$newitem .= '<TR';
		if( trim($class) != '' ){
			$newitem .= " class=$class ";
		}
		if( trim($id) != '' ){
			$newitem .= " id=$id ";
		}		
		
		$newitem .= $texto;
				
		return $newitem.'</TR>';		
	}
	
	protected function NuevaCelda($texto, $class, $id){
		$newitem  = '<TD';
		if( trim($class) != '' ){
			$newitem .= " class=$class ";
		}
		if( trim($id) != '' ){
			$newitem .= " id=$id ";
		}		
		$newitem .= ">";
		if( trim($texto) != '' ){
			$newitem .= " $texto ";
		}
		
		$newitem .= '</TD>';
		
		return $newitem;
	}
	
	public function Draw(){ return $this->DibujarFormulario(); }
	
	public function DibujarFormulario(){
				
		$result = $this->ftablaINI;
		if( isset($this->borde) ) 
			$result .= $this->borde;
			
		$result .= " >";
		
		if (isset($this->ftablaEncabezado) ){
			foreach($this->ftablaEncabezado as $item ){
				$result .= '<tr>'.$item.'</tr>';
			}		
		}
		
		if (isset($this->ftablaCuerpo) ){
			foreach($this->ftablaCuerpo as $item ){
				$result .= '<tr>'.$item.'</tr>';
			}		
		}
		
		if (isset($this->ftablaPie) ){
			foreach($this->ftablaPie as $item ){
				$result .= '<tr>'.$item.'</tr>';
			}		
		}
		
		$result .= $this->ftablaFIN;
		
		return  $result;
	}
	
	public function test(){
		
		echo "<prev>";
		echo print_r($this->ftablaEncabezado);
		echo print_r($this->ftablaCuerpo);
		echo print_r($this->ftablaPie);
		echo "</prev>";
	}
	
}
