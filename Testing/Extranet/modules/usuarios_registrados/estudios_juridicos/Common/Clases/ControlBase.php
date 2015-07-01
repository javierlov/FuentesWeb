<?php
class ControlBase{
	private $tag;
	private $texto; 
	private $clase; 
	private $id;
	private $width;
	
	private $maxlength;
	private $style;
	private $type;	
	private $name; 
	
	private $value; 
	
	function __construct($texto, $name, $class, $id,  $type){						
		
		$this->SetTag('Base');
		$this->SetTexto($texto);
		$this->SetClass($class);
		$this->SetName($name);
		$this->SetID($id);
		$this->SetType($type);
		
	}
	
	protected function SetTag($value){		
		if( trim($value) != '' ){
			$this->tag = trim("$value");
		}		
	}
	protected function SetTexto($value){		
		if( trim($value) != '' ){
			$this->texto = " $value ";
		}		
	}
	
	protected function SetClass($value){		
		if( trim($value) != '' ){
			$this->clase = " class='$value' ";
		}		
	}
	protected function SetName($value){		
		if( trim($value) != '' ){
			$this->name = " name='$value' ";
		}		
	}
	protected function SetID($value){		
		if( trim($value) != '' ){
			$this->id = " id='$value' ";
		}		
	}
	
	protected function SetWidth($value){		
		if( trim($value) != '' ){
			$this->width = " width='$value' ";
		}		
	}
	
	protected function SetType($value){		
		if( trim($value) != '' ){
			$this->type = " type='$value' ";
		}		
	}
	
	public function SetMaxLength($value){		
		if( trim($value) != '' ){
			$this->maxlength = " maxlength='$value' ";
		}		
	}
	public function SetStyle($value){		
		if( trim($value) != '' ){
			$this->style = " style='$value' ";
		}		
	}
	
	public function SetValue($value){		
		if( trim($value) != '' ){
			$this->value = " value='$value' ";
		}		
	}
		
	
	public function Get(){
				
		$result = '';
		$result .= " <$this->tag";
		
		$result .= " $this->clase "; 
		$result .= " $this->id ";
		$result .= " $this->width ";
		
		$result .= " $this->maxlength ";
		$result .= " $this->style ";
		$result .= " $this->type ";	
		$result .= " $this->name "; 
		$result .= " $this->value "; 
		
		$result .= " > $this->texto </$this->tag>"; 		
		
		return $result;
	}
	
	public function test(){
		echo "<prev>";
		echo $this->Get();		
		echo "</prev>";
	}
}
