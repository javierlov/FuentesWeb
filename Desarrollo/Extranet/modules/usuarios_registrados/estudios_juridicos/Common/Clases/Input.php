<?php

require_once "ControlBase.php";

class Input extends ControlBase{
	//<input id="nombre1" maxlength="25" name="nombre1" style="text-transform:uppercase; width:292px;" type="text" />		
	function __construct($texto, $name, $class, $id,  $type){				
		parent::__construct($texto, $name, $class, $id,  $type);
		$this->SetTag('input');			
	}
}