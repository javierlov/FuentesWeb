<?
/*
Clase utilizada dentro de la clase Grid para asociar columnas con la grilla que los crea..

La forma b�sica de uso ser�a como muestra el ejemplo de abajo:

$column = new Column("Gerencia", 80, true, true);
*/

class Column {
	private $actionButton;		// Acci�n que va a llevar a cabo el input..
	private $buttonClass;		// Nombre de la clase en caso de la columna ser un bot�n..
	private $cellClass;		// Clase de la celda de la columna..
	private $colButtonClass;		// Indica el n�mero de columna que tiene el nombre de la clase en caso de la columna ser un bot�n (pisa a la clase que hubiera en $buttonClass)..
	private $colChecked;		// Indica la columna que va a traer el dato de si se debe tildar o no el check (en caso de inputType = checkbox)..
	private $colHint;		// Indica el n�mero de columna que contiene el hint a mostrar..
	private $deletedRow;		// Indica la columna que de ser true marca como dado de baja al registro..
	private $inputType;		// Tipo de control HTML a mostrar..
	private $maxChars;		// Cantidad m�xima de caracteres a mostrar en la columna..
	private $mostrarEspera;		// Indica si hay que mostrar una ventana de espera al apretar un bot�n..
	private $msgEspera;		// Texto del mensaje de espera a mostrar..
	private $numCellHide;		// Indica el n�mero de columna de la celda a ocultar..
	private $title = "";		// T�tulo de la columna..
	private $titleHint = "";		// Hint del t�tulo..
	private $urlAmigable = false;		// Indica si el id de la grilla se va a pasar como url amigable o no..
	private $useStyleForTitle;		// Indica si el estilo pasado como par�metro se usa para el t�tulo o no..
	private $visible;		// Indica si la columna se muestra o no..
	private $width;		// Indica el ancho de la columna..


	public function __construct($title, $width = 0, $visible = true, $deletedRow = false, $colHint = -1,
															$buttonClass = "", $actionButton = "", $cellClass = "", $maxChars = -1,
															$useStyleForTitle = true, $numCellHide = -1, $titleHint = "", $mostrarEspera = false,
															$msgEspera = "", $inputType = "button", $colChecked = -1, $colButtonClass = -1, $urlAmigable = false) {
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
		$this->urlAmigable = $urlAmigable;
		$this->useStyleForTitle = $useStyleForTitle;
		$this->visible = $visible;
		$this->width = $width;
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

	public function getUrlAmigable() {
		return $this->urlAmigable;
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
}
?>