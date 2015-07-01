<?php
require_once "ControlBase.php";
class Label extends ControlBase{
	
	function __construct($texto, $name, $class, $id,  $type){				
		parent::__construct($texto, $name, $class, $id,  $type);
		$this->SetTag('label');				
	}	
}