<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid_column.php");

class ColumnDos extends Column{

	protected $cssClassData = ''; 	// Esta clase css se aplica solo a los datos no al titulo 
	private $Is_Link = false; 		// Convierte la columna en un link
	private $ColumnIDRefernce = 0; //La columna donde esta el id para linkear
	private $cssStyleCss = ''; 		// Esta clase css se aplica solo a los datos no al titulo 
	private $IDdivHeader = ''; 		// Es el id que se le asigna al control que contine el texto del encabezado 
	private $functionOnClick = ''; //Asigna una funcion al click de los items de la columna esto no se usa en el header and footer
	private $ButtonCell  = array("CLASS" => '', "URL" => '', "NUMCOLUMNREF" => '' , "TITLE" => '' );  	// Agrega un boton a la celda, al final del texto de la columna, es un array de 3 posiciones CLASS clase css, URL = link del boton, NUMCOLUMNREF columna con los datos/parametros
	private $ParamFriendly = false; // indica si los parametros de la url deben ser armados con formato amigable /
	
	//------------Is Link-----------------
	public function GetIsLink() {	
		return $this->Is_Link;
	}
	
	public function SetIsLink($value) {	
		$this->Is_Link = $value;
	}	
	//------------Column Id-----------------
	public function GetColumnIDRefernce() {	
		return $this->ColumnIDRefernce;
	}
	
	public function SetColumnIDRefernce($value) {	
		$this->ColumnIDRefernce = $value;
	}	
	//------------Class data-----------------
	public function GetClassData() {	
		
		$clase = '';
		
		if(isset($this->cssClassData))
			$clase = $this->cssClassData;
		
		if($clase == '')
			$clase = $this->getCellClass();	
		
		return $clase;
	}
	
	public function SetClassData($value) {	
		$this->cssClassData = $value;
	}	
	//------------Style Row-----------------
	public function GetStyleCssRow() {	
		return $this->cssStyleCss;
	}
	
	public function SetStyleCssRow($value) {	
		$this->cssStyleCss = $value;
	}
	//------------Div Header-----------------
	public function GetIDdivHeader() {	
		return $this->IDdivHeader;
	}
	
	public function SetIDdivHeader($value) {	
		$this->IDdivHeader = $value;
	}	
	//------------functionOnClick-----------------
	public function GetOnClickFunction() {	
		return $this->functionOnClick;
	}
	
	public function SetOnClickFunction($value) {	
		$this->functionOnClick = $value;
	}
	//------------ParamFriendly-----------------
	public function GetParamFriendly() {	
		return $this->ParamFriendly;
	}
	
	public function SetParamFriendly($value) {	
		//valor del parametro true/false 
		//si es true separa los parametros con / formato amigable..
		$this->ParamFriendly = $value;
	}
	//------------ButtonCell-----------------
	public function GetButtonCell() {	
		return $this->ButtonCell;
	}
	
	public function SetButtonCell($class, $urllink, $columnQuery, $title = '') {	
		//("CLASS"=>'', "URL"=>'', "NUMCOLUMNREF"=>'');
		$this->ButtonCell["CLASS"] = $class; // CLASE DEL BOTON "btnreporte"
		$this->ButtonCell["URL"] = $urllink; // URL DEL BOTON 
		$this->ButtonCell["NUMCOLUMNREF"] = $columnQuery; //NUM DE COLUMNA DONDE ESTAN LOS PARAMETROS DEL LINK ESTA COLUMNA PUEDE ESTAR INVISIBLE
		$this->ButtonCell["TITLE"] = $title; //TITULO DEL BOTON
	}
	
}
