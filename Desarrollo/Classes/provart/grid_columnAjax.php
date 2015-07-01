<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid_column.php");
/*
Clase utilizada dentro de la clase Grid para asociar columnas con la grilla que los crea..
La forma básica de uso sería como muestra el ejemplo de abajo:
$column = new Column("Gerencia", 80, true, true);
*/

class columnAjax extends Column {
	private $actionButton;		// Acción que va a llevar a cabo el input..
	private $buttonClass;		// Nombre de la clase en caso de la columna ser un botón..
	private $cellClass;		// Clase de la celda de la columna..
	private $colButtonClass;		// Indica el número de columna que tiene el nombre de la clase en caso de la columna ser un botón (pisa a la clase que hubiera en $buttonClass)..
	private $colChecked;		// Indica la columna que va a traer el dato de si se debe tildar o no el check (en caso de inputType = checkbox)..
	private $colHint;		// Indica el número de columna que contiene el hint a mostrar..
	private $deletedRow;		// Indica la columna que de ser true marca como dado de baja al registro..
	private $inputType;		// Tipo de control HTML a mostrar..
	private $maxChars;		// Cantidad máxima de caracteres a mostrar en la columna..
	private $mostrarEspera;		// Indica si hay que mostrar una ventana de espera al apretar un botón..
	private $msgEspera;		// Texto del mensaje de espera a mostrar..
	private $numCellHide;		// Indica el número de columna de la celda a ocultar..
	private $title = "";		// Título de la columna..
	private $titleHint = "";		// Hint del título..
	private $useStyleForTitle;		// Indica si el estilo pasado como parámetro se usa para el título o no..
	private $visible;		// Indica si la columna se muestra o no..
	private $width;		// Indica el ancho de la columna..
/*****************************************/
	private $functionAjax; //Se pasa una funcion js para ejecutar como ajax en un boton		
	private $useIdPageinName = false; //si es true los atributos name y id son la pagina actual + el contador i
/*****************************************/
	private $colspan = 1; //indica cantidad de columnas a expandir
	private $rowspan = 1; //indica cantidad de filas a expandir
	private $canSort = false; //indica si la columna se puede ordenar	
/*****************************************/
	//private $ArrayButtonClass = array();	// Array de clases para botones en caso de la columna ser un botón y pueda tener varios estados..
	private $ArrayLinks = array();	// Array de clases segun el valor de id se mostara el elemento html correspondiente en la celda...	
/*****************************************/
	private $link = null;		// Array (valor, link) Crea un link a el link seteado en esta variable si el campo tiene el valor del campo valor...
	private $eventHTMLdblclick = '';    // Agrega un evento html a los items de la columna (dblclick="grabar()")
	private $eventHTMLdblclickParam = '';    // Es el Numero de la columna que se usara como parametro
	private $useStyleForCellData;		// Indica si el estilo pasado como parámetro se usa para la celda actual..


	public function __construct($title, $width = 0, $visible = true, $deletedRow = false, $colHint = -1,
					$buttonClass = "", $actionButton = "", $cellClass = "", $maxChars = -1,
					$useStyleForTitle = true, $numCellHide = -1, $titleHint = "", $mostrarEspera = false,
					$msgEspera = "", $inputType = "button", $colChecked = -1, $colButtonClass = -1) {
		// Constructor..

		$this->actionButton = $actionButton;
		$this->buttonClass = $buttonClass;
		$this->cellClass = $cellClass;
		$this->colButtonClass = $colButtonClass;
		$this->colChecked = $colChecked;
		$this->colHint = $colHint;
		$this->deletedRow = $deletedRow;
		$this->inputType = $inputType;
		$this->maxChars = $maxChars;
		$this->mostrarEspera = $mostrarEspera;
		$this->msgEspera = $msgEspera;
		$this->numCellHide = $numCellHide;
		$this->title = $title;
		$this->titleHint = $titleHint;
		$this->useStyleForTitle = $useStyleForTitle;
		$this->visible = $visible;
		$this->width = $width;
		$this->functionAjax = '';
	}

	private function getParameter($arrayParams, $paramName, $default=''){
		if( isset($arrayParams[$paramName] ) )
			return $arrayParams[$paramName];
		else
			return $default;
	}
	
	public function setParameters($parameters) {
		// parameters es un array con los valores a setear a las variables con el msismo nombre
		if( $this->getParameter($parameters, 'actionButton', '') != '' )  $this->actionButton = $this->getParameter($parameters, 'actionButton', '');
		if( $this->getParameter($parameters, 'buttonClass', '') != '' )  $this->buttonClass = $this->getParameter($parameters,'buttonClass', '');
		if( $this->getParameter($parameters, 'cellClass', '') != '' )  $this->cellClass = $this->getParameter($parameters,'cellClass', '');
		if( $this->getParameter($parameters, 'colButtonClass', '') != '' )  $this->colButtonClass = $this->getParameter($parameters,'colButtonClass', '');
		if( $this->getParameter($parameters, 'colChecked', '') != '' )  $this->colChecked = $this->getParameter($parameters,'colChecked', '');
		if( $this->getParameter($parameters, 'colHint', '') != '' )  $this->colHint = $this->getParameter($parameters,'colHint', '');
		if( $this->getParameter($parameters, 'deletedRow', '') != '' )  $this->deletedRow = $this->getParameter($parameters,'deletedRow', '');
		if( $this->getParameter($parameters, 'inputType', '') != '' )  $this->inputType = $this->getParameter($parameters,'inputType', '');
		if( $this->getParameter($parameters, 'maxChars', '') != '' )  $this->maxChars = $this->getParameter($parameters,'maxChars', '');
		if( $this->getParameter($parameters, 'mostrarEspera', '') != '' )  $this->mostrarEspera = $this->getParameter($parameters,'mostrarEspera', '');
		if( $this->getParameter($parameters, 'msgEspera', '') != '' )  $this->msgEspera = $this->getParameter($parameters,'msgEspera', '');
		if( $this->getParameter($parameters, 'numCellHide', '') != '' )  $this->numCellHide = $this->getParameter($parameters,'numCellHide', '');
		if( $this->getParameter($parameters, 'title', '') != '' )  $this->title = $this->getParameter($parameters,'title', '');
		if( $this->getParameter($parameters, 'titleHint', '') != '' )  $this->titleHint = $this->getParameter($parameters,'titleHint', '');
		if( $this->getParameter($parameters, 'useStyleForTitle', '') != '' )  $this->useStyleForTitle = $this->getParameter($parameters,'useStyleForTitle', '');
		
		//$this->useStyleForCellData
		if( $this->getParameter($parameters, 'useStyleForCellData', '') != '' )  $this->useStyleForCellData = $this->getParameter($parameters,'useStyleForCellData', '');
		if( $this->getParameter($parameters, 'visible', '') != '' )  $this->visible = $this->getParameter($parameters,'visible', '');
		if( $this->getParameter($parameters, 'width', '') != '' )  $this->width = $this->getParameter($parameters,'width', '');
		if( $this->getParameter($parameters, 'link', '') != '' )  $this->link = $this->getParameter($parameters,'link', '');
		$this->functionAjax = ''; //este valor se setea con otra funcion
	}

	public function getActionButton() {
		return $this->actionButton;
	}

	public function getButtonClass($row) {
		if ($this->colButtonClass > -1)
			return $row[$this->colButtonClass];
		else
			return $this->buttonClass;
	}

	public function getCellClass() {
		return $this->cellClass;
	}

	public function getColButtonClass() {
		return $this->ColButtonClass;
	}

	public function getcolChecked() {
		return $this->colChecked;
	}

	public function getColHint() {
		return $this->colHint;
	}

	public function getDeletedRow() {
		return $this->deletedRow;
	}

	public function getInputType() {
		return $this->inputType;
	}

	public function getMaxChars() {
		return $this->maxChars;
	}

	public function getMostrarEspera() {
		return $this->mostrarEspera;
	}

	public function getMsgEspera() {
		return $this->msgEspera;
	}

	public function getNumCellHide() {
		return $this->numCellHide;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getTitleHint() {
		return $this->titleHint;
	}

	public function getUseStyleForTitle() {
		return $this->useStyleForTitle;
	}

	public function getVisible() {
		return $this->visible;
	}

	public function getWidth() {
		return $this->width;
	}
//----------------------------	----------------------------	
	public function setFunctionAjax($value) {
		$this->functionAjax = $value;
	}		
	public function getFunctionAjax() {
		return $this->functionAjax;
	}			
	public function setUseIdPageinName($value) {
		//por default es false ...
		$this->useIdPageinName = $value;
	}	
	public function GetUseIdPageinName() {
		//por default es false ... es para campos tipo chek
		return $this->useIdPageinName;
	}	
//----------------------------	----------------------------	
	public function setColspan($value) {
		$this->colspan = $value;
	}	
	
	public function getColspan() {
		return $this->colspan;
	}	
	
	public function setRowspan($value) {
		$this->rowspan = $value;
	}	
	
	public function getRowspan() {
		return $this->rowspan;
	}	
	
	public function setCanSort($value) {
		//true or false default false
		$this->canSort = $value;
	}	
	
	public function getCanSort() {
		return $this->canSort;
	}	
	
//----------------------------	----------------------------	
	public function setArrayLinks($linkArray) {
		//array de elementos 
		$this->ArrayLinks = $linkArray;
	}	
	
	public function getArrayLinks() {
		/*retorna el arrayLink */
		return $this->ArrayLinks;
	}	
	
	public function getArrayButtonIDclass($id) {
		/* retorna los botones segun el id */
		if( !isset( $this->ArrayLinks[$id] ) ) return '';		
		return $this->ArrayLinks[$id];
	}	
	
	public function searchKeyArrayLinks($text) {
		/*busca en un string el codigo del array y retorna de la clase segun el codigo encotnrado*/
		foreach($this->ArrayLinks as $key => $val){			
			if (strpos($text, $key)	)			
				return $key;
		}
	}	
	
	public function searchTextArrayLinks($valor) {
		/*busca en el array de botones la opcion de texto a mostrar segun el id */
		foreach($this->ArrayLinks as $key => $val){			
			if ( $valor == $key)			
				return $val;
		}
		return '';
	}

	public function searchArrayLinks($idclass) {
		/*retorna de la clase segun el id*/
		foreach($this->ArrayLinks as $key => $val)
			if($idclass == $key)
				return $val;
	}	
	
	public function hasArrayLinks() {
		/*si esta inicializado como array y tiene elementos retorna true*/
		if( isset($this->ArrayLinks))
			if( is_array($this->ArrayLinks))
				if( count($this->ArrayLinks) > 0 )
					return true;
				
		return false;
	}	
	
//----------------------------	----------------------------	

	public function AddLink($valor, $link) {
		$this->link[$valor] = $link;
	}	
	
	public function getLink($valor) {
		if( isset($this->link[$valor]) ) 
			return $this->link[$valor];
		return false;
	}	
//----------------------------	----------------------------	
	public function setEventHTMLdblclick($value, $fieldNumberParmeter) {
		$this->eventHTMLdblclick = $value;
		$this->eventHTMLdblclickParam = $fieldNumberParmeter;
	}	
	
	public function getEventHTMLdblclick() {
		return $this->eventHTMLdblclick;
	}	
	
	public function getEventHTMLdblclickFieldNameParam() {
		return $this->eventHTMLdblclickParam;
	}	

	public function getUseStyleForCellData() {
		return $this->useStyleForCellData;
	}
	
	public function setUseStyleForCellData($value) {
		$this->useStyleForCellData = $value;
	}

}
