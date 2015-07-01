<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid_column.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/CrearLog.php");

function __autoload($clase){
	$path = $_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/";
	require_once $path.$clase.".php";
}

class GridDos extends Grid {	
	//Variables Nuevas
	protected $ListUrlExtWindows = array();//Array de Columnas las cuales se van a abrir en una ventana externa
	protected $columnParamGET = "id"; ///Es el nombre de parametro a usar el default es id campo
		
	protected function DrawRows($colOpenIfOneRecord) {
		// M�todo encargado de dibujar los resultados del query..
		$countRecords = 1;
		while ($row = DBGetQuery($this->stmt, 0)) {
			$css = "";

			if ($this->underlineSelectedRow)
				$classRow = "GridFondoOnMouseOver ";
			$classRow.= ((($countRecords % 2) == 0)?$this->row2Style:$this->row1Style);
			$this->code.= '<tr class="'.$classRow.'" style="height:'.$this->rowHeight.'px">';

			if (($this->rowsSeparator) and ($countRecords > 1))
				$css = "border-top:1px solid ".$this->rowsSeparatorColor.";";

			$i = 0;
			foreach ($row as $key => $value) {
				if ($this->decodeSpecialChars)
					$value = htmlspecialcharsDecodeUpper($value);
								
				if ($this->columns[$i]->getVisible()) {		// Si la columna est� visible..
					$hideCell = $this->getHideCell($row, $i);

					$colStyle = "";
					if (($this->colsSeparator) and ($i < (count($row) - 1)))
						$colStyle.= "border-right:1px solid ".$this->colsSeparatorColor.";";

					$title = "";
					if ($this->columns[$i]->getColHint() > -1)					
						$title = 'title="'.get_htmlspecialchars($row[$this->columns[$i]->getColHint()]).'"';									

					if ($this->columns[$i]->getButtonClass($row) != "") {
						if ((!$this->showButtonDespiteBaja) and ($this->GetColBaja() != -1) and ($row[$this->GetColBaja()] != ""))
							$this->code.= "<td class='".$this->columns[$i]->getCellClass()."' style='".$colStyle."'></td>";
						else {
							if (strrpos($this->columns[$i]->getActionButton(), "?") == 0)							
								$parametroID = "?".$this->columnParamGET."=".$value;														
							else								
								$parametroID = "&".$this->columnParamGET."=".$value;
							
							$colName = $this->columns[$i]->getTitle();
							if(in_array($colName, $this->ListUrlExtWindows) )
							{
								$url = "window.open('".$this->columns[$i]->getActionButton().$parametroID."');";
							}
							else 
							{
								if ($this->useTmpIframe)								
									$url = "document.getElementById('".$this->tmpIframeName."').src = '".$this->columns[$i]->getActionButton().$parametroID."';";
								else
									$url = "window.location.href = '".$this->columns[$i]->getActionButton().$parametroID."';";
							}

							if (($colOpenIfOneRecord == ($i)) and ($this->recordCount == 1))
								$this->linkIfOneRecord = $this->columns[$i]->getActionButton().$parametroID;

							if ($hideCell) {		// Si hay que ocultar el contenido de esta celda no muestro el bot�n..
								$input = "";
							}
							else {
								$checked = "";
								if (($this->columns[$i]->getInputType() == "checkbox") and ($this->columns[$i]->getColChecked() > -1))
									$checked = $row[$this->columns[$i]->getColChecked()];

								$msgEspera = "";
								if ($this->columns[$i]->getMostrarEspera())
									$msgEspera = "mostrarMensajeEspera('".$this->columns[$i]->getMsgEspera()."');";
																
								$input = '<input '.$checked.' class="'.$this->columns[$i]->getButtonClass($row).'" id="grid_col'.$i.'_'.$value.'" name="grid_col'.$i.'_'.$value.'" '.$title.' type="'.$this->columns[$i]->getInputType().'" onClick="'.$msgEspera.' '.$url.'" />';								
							}

							$this->code.= '<td align="center" class="'.$this->columns[$i]->getCellClass().'" style="'.$colStyle.' '.$css.'">'.$input.'</td>';
						}
					}
					else {
						if ($this->columns[$i]->getMaxChars() > -1)		// Ajusto la cantidad de caracteres a mostrar en la celda..
							$value = substr($value, 0, $this->columns[$i]->getMaxChars())."...";

						if ($hideCell)		// Si hay que ocultar el contenido de esta celda no muestro el valor..
							$value = "";

						if (($this->GetColBaja() != -1) and ($row[$this->GetColBaja()] != ""))		// Si la columna de baja est� seteada y es una baja..
							$this->code.= '<td align="left" class="'.$this->columns[$i]->getCellClass().' '.$this->textStyle.'" style="'.$colStyle.' '.$css.'"><div class="'.$this->bajaStyle.'" '.$title.'>'.$value.'</div></td>';
						else
							$this->code.= '<td align="left" class="'.$this->columns[$i]->getCellClass().' '.$this->textStyle.'" style="'.$colStyle.' '.$css.'"><div '.$title.'>'.$value.'</div></td>';
					}
				}
				$i++;
			}
			$this->code.= '</tr>';
			$countRecords++;
		}
	}
	
	public function setNameParamGET($value) {
		//Es el nombre de la variable GET por default es 'id'
		$this->columnParamGET = $value;					  
	}
		
	public function addColOpenExtWindows($value) {
		//Metodo encargado de agregar los nombres de columnas al Array de columnas que se mostraran en en otra ventana del navegador..
		array_push($this->ListUrlExtWindows, $value);		
	}
	
}
?>
