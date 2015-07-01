<?
/*
Clase preparada para dibujar un combo pasando un query..

La forma básica de uso sería como muestra el ejemplo de abajo:

$comboCargo = new Combo($sql, "cargo", $row["SE_CARGO"]);
$comboCargo->draw();
*/

class Combo {
	private $addFirstItem = true;		// Indica si se agrega un primer item o no..
	private $class = "";		// Nombre de la clase del combo..
	private $code = "";		// Código HTML que genera la clase..
	private $disabled = false;		// Indica si los items van a estar deshabilitados o no..
	private $firstItem = "- SELECCIONAR -";		// Texto a mostrarse en el primer item..
	private $focus = "";		// Código HTML que indica si el combo tiene el foco o no..
	private $id = "";		// Id del combo..
	private $multiple = "";		// Código HTML que indica si el combo permite multiple selección o no..
	private $name = "";		// Nombre del combo..
	private $onBlur = "";		// Código javascript a ejecutar en el evento onBlur..
	private $onChange = "";		// Código javascript a ejecutar en el evento onChange..
	private $onFocus = "";		// Código javascript a ejecutar en el evento onFocus..
	private $params = array();		// Parámetros del sql a ejecutarse..
	private $selected = -1;		// Valor de la opción que tiene que aparecer como seleccionada..
	private $sql = "";		// SQL a ejecutarse para llenar el combo..


	public function __construct($sql, $id, $selected = -1) {
		// Constructor..

		$this->id = $id;
		$this->name = $id;
		$this->selected = $selected;
		$this->sql = $sql;
	}

	public function draw($getCode = false) {
		// Método encargado de dibujar la lista..

		$this->code.= "<div id=\"divCombo".$this->id."\" style=\"display:inline;\"><select ".$this->class." ".$this->focus." id=\"".$this->id."\" ".$this->multiple." name=\"".$this->name."\" ".$this->onBlur." ".$this->onChange." ".$this->onFocus.">";
		$this->code.= $this->drawOptions();
		$this->code.= "</select></div>";

		if ($getCode)
			return $this->code;
		else
			echo $this->code;
	}

	private function drawOptions() {
		// Método encargado de dibujar las opciones del combo..

		global $conn;

		$result = "";
		$disabled = ($this->disabled)?"disabled":"";

		if ($this->addFirstItem) {
			$selected = ($this->selected == -1)?"selected":"";
			$result.= "<option ".$disabled." ".$selected." value=\"-1\">".$this->firstItem."</option>";
		}

		$stmt = DBExecSql($conn, $this->sql, $this->params);
		while ($row = DBGetQuery($stmt)) {
			$selected = ($row["ID"] == $this->selected)?"selected":"";

			$result.= "<option ".$disabled." ".$selected." value=\"".$row["ID"]."\">".$row["DETALLE"]."</option>";
		}

		return $result;
	}


	public function addParam($key, $value) {
		$this->params[$key] = $value;
	}

	public function setAddFirstItem($value) {
		$this->addFirstItem = $value;
	}

	public function setClass($value) {
		if ($value != "")
			$this->class = "class=\"".$value."\"";
	}

	public function setDisabled($value) {
		$this->disabled = $value;
	}

	public function setFirstItem($value) {
		$this->firstItem = $value;
	}

	public function setFocus($value) {
		if ($value)
			$this->focus = "autofocus";
	}

	public function setMultiple($value) {
		if ($value)
			$this->multiple = "multiple";
	}

	public function setOnBlur($value) {
		if ($value != "")
			$this->onBlur = "onBlur=\"".$value."\"";
	}

	public function setOnChange($value) {
		if ($value != "")
			$this->onChange = "onChange=\"".$value."\"";
	}

	public function setOnFocus($value) {
		if ($value != "")
			$this->onFocus = "onFocus=\"".$value."\"";
	}
}
?>