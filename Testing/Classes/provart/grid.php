<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid_column.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/functions/CrearLog.php");

/*
Clase preparada para dibujar una grilla pasando un query de ORACLE como par�metro, no est� preparada para soportar otras bases de datos..

La forma b�sica de uso ser�a como muestra el ejemplo de abajo:

$pagina = 1;
$sql = "SELECT �se_nombre?, �se_usuario?, �se_mail?, DECODE(se_id, 1413, 'T', 'F') �hidecell1? FROM use_usuarios";
$grilla = new Grid();
$grilla->addColumn(new Column("", 8, true, false, -1, "BotonInformacion", "index.php?pageid=5&page=informacion.php", "GridFirstColumn"));
$grilla->addColumn(new Column("Sector"));
$grilla->addColumn(new Column("Gerencia"));
$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 1));
$grilla->setOrderBy("2_D_");
$grilla->setPageNumber($pagina);
$grilla->setSql($sql);
$grilla->Draw();
*/

class Grid {
	protected $bajaStyle = "GridBaja";
	protected $blocksPerPage;		// Cantidad m�xima de links del footer..
	protected $classToHideLastPartOfTheFooter = "";		// Clase utilizada para ocultar la �ltima parte del footer..
	protected $code = "";		// C�digo (HTML + Javascript) que genera la clase..
	protected $columns = array();		// Conjunto de columnas de la grilla..
	protected $colsSeparator = false;		// Indica si se muestra o no un separador entre columnas..
	protected $colsSeparatorColor = "#fff";		// Color del separador de columnas..
	protected $colsSeparatorStyle = "GridColSeparator";		// Estilo del separador de columnas..
	protected $decodeSpecialChars = false;		// Si es true llama a la funci�n que decodea los caracteres especiales HTML..
	protected $emptyStyle = "GridEmpty";
	protected $extraConditions = array();		// Es un arreglo con porciones de sql a insertarse dentro del sql primario..
	protected $fieldBaja = "";		// Indica el nombre del campo que marca como baja un registro..
	protected $footerStyle = "GridFooter";		// Estilo del footer..
	protected $headerStyle = "GridHeader";		// Estilo del header..
	protected $linkIfOneRecord = -1;		// Link a abrir si el query devuelve un solo registro y se quiere abrir automaticamente..
	protected $orderBy = "";		// Indica por que columna est� ordenada la grilla. Si se quiere descendente agregar la cadena "_D_"..
	protected $pageNumber = 1;		// N�mero de p�gina actual..
	protected $params = array();		// Par�metros a usar con el sql..
	protected $recordCount = 0;		// Cantidad de registros del query..
	protected $refreshIntoWindow = false;		// Indica si la p�gina se recarga sobre la misma p�gina o se hace en un iframe..
	protected $row1Style = "GridRow1";		// Estilo de las filas impares..
	protected $row2Style = "GridRow2";		// Estilo de las filas pares..
	protected $rowHeight;		// Alto de las filas..
	protected $rowsPerPage;		// Cantidad m�xima de filas que dibujar� la grilla..
	protected $rowsSeparator = false;		// Indica si se muestra o no un separador entre filas..
	protected $rowsSeparatorColor = "#fff";		// Color del separador de filas..
	protected $showBajas = false;		// Indica si se muestran los registros dados de baja o no..
	protected $showButtonDespiteBaja = true;		// Indica si se muestra el bot�n aunque el registro est� dado de baja..
	protected $showFooter = true;		//Indica si se muestra el footer o no..
	protected $showMessageNoResults = true;		// Indica si se muestra el mensaje de que no se encontraron registros o no..
	protected $showProcessMessage = false;		// Indica si se muestra un mensaje de que se est� procesando el query de b�squeda..
	protected $showTotalRegistros = false;		// Indica si se muestra el total de registros al pie de la grilla..
	protected $sql = "";
	protected $sqlFinal = "";		// Contiene el sql limpio de los caracteres necesarios para mostrarlo en la grilla..
	protected $stmt;
	protected $tableStyle = "GridTable";
	protected $textStyle = "GridText";		// Estilo del texto de los datos que muestra la grilla..
	protected $titleStyle = "GridTitle";
	protected $tmpIframeName = "iframeTmpGrid";		// Nombre del iframe temporal..
	protected $totalPages = 0;		// Cantidad de p�ginas que tiene la grilla..
	protected $underlineSelectedRow = true;		// Resalta la fila donde est� parado el mouse..
	protected $useTmpIframe = false;		// Indica si se va a usar un iframe temporal para procesar las acciones de los botones..

	public function __construct($blocksPerPage = 15, $rowsPerPage = 10) {
		// Constructor..

		$this->blocksPerPage = $blocksPerPage;
		$this->rowsPerPage = $rowsPerPage;

		$this->addColumn(new Column("", 0, false));		// Esta primer columna es el ROWNUM..
		$this->writeJs();
	}

	public function addColumn($col) {
		// M�todo encargado de agregar una columna al arreglo de columnas..

		array_push($this->columns, $col);
	}

	public function BuildUrl($page, $orderBy = -1) {
		// M�todo encargado de armar la url asociada a los links de las p�ginas..

		$_REQUEST["pagina"] = $page;
		if ($orderBy == -1)
			$_REQUEST["ob"] = $this->orderBy;
		else
			$_REQUEST["ob"] = $orderBy;

		$result = $_SERVER["PHP_SELF"]."?";
		foreach ($_REQUEST as $key => $value)
			$result.= $key."=".$value."&";

		return AddSlashes($result);
	}

	public function Draw($draw = true, $colOpenIfOneRecord = -1) {
		// M�todo encargado de dibujar la grilla..

		$this->PrepareSql();

		if ($this->recordCount == 0) {
			if ($this->showMessageNoResults)
				$this->DrawMessageNoResults();
		}
		else {
			$this->code.= '<div align="center" id="originalGrid" name="originalGrid"><table class="'.$this->tableStyle.'">';
			if ($this->useTmpIframe)
				$this->code.= '<iframe id="'.$this->tmpIframeName.'" name="'.$this->tmpIframeName.'" src="" style="display:none;"></iframe>';
			$this->DrawColTitles();
			$this->DrawRows($colOpenIfOneRecord);
			if ($this->showFooter)
				$this->DrawFooter();

			if (($colOpenIfOneRecord > 0) and ($this->recordCount == 1))
				$this->drawOpenIfOneRecord($colOpenIfOneRecord);

			$this->code.= '</table></div>';
			$this->code.= '<div id="divGridEspera">&nbsp;</div>';
			$this->code.= '<div id="divGridEsperaTexto">&nbsp;</div>';
		}

		if ($draw) {
			echo $this->code;
			return "";
		}
		else
			return $this->code;
	}

	protected function DrawColTitles() {
		// M�todo encargado de dibujar los t�tulos de las columnas..

		$this->code.= '<tr>';
		$i = 0;
		foreach ($this->columns as $col) {
			if ($col->getVisible()) {		// Si la columna est� visible..
				$colStyle = 'style="';
				if (($this->colsSeparator) and ($i < (count($this->columns) - 1)))
					$colStyle.= 'border-right: 1px solid '.$this->colsSeparatorColor.';';

				$width = "";
				if ($this->columns[$i]->getWidth() > 0)
					$width = ' width="'.$this->columns[$i]->getWidth().'"';

				$colStyle.= '"';
				$showMessage = ($this->showProcessMessage)?"true":"false";

				if ($this->orderBy == $i) {
					$img = '';
					if (!strpos($this->orderBy, "_D_")) {
						if ($i > 0)
							$img = '<img border="0" src="/images/grid_asc.gif" title="Ascendente" />';
						$url = $this->BuildUrl(1, $i."_D_");
					}
					else {
						if ($i > 0)
							$img = '<img border="0" src="/images/grid_desc.gif" title="Descendente" />';
						$url = $this->BuildUrl(1, $i);
					}
				}
				else {
					$img = '';
					$url = $this->BuildUrl(1, $i);
				}

				if (($this->columns[$i]->getUseStyleForTitle()) and ($this->columns[$i]->getCellClass() != ""))
					$class = $this->columns[$i]->getCellClass();
				else
					$class = $this->headerStyle;

				$title = "";
				if ($this->columns[$i]->getTitleHint() != "")
					$title = 'title="'.$this->columns[$i]->getTitleHint().'"';

				$this->code.= '<td class="'.$class.'" '.$colStyle.$width.'>';
				$this->code.= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
				$this->code.= '<tr>';
				$this->code.= '<td width="28"></td>';
				$this->code.= '<td align="center" '.$title.'><a class="'.$this->titleStyle.'" href="#" onClick="changePage(\''.$url.'\', '.$showMessage.', '.(($this->refreshIntoWindow)?'true':'false').')">'.$col->getTitle().'</a></td>';
				$this->code.= '<td align="right" width="28">'.$img.'</td>';
				$this->code.= '</tr>';
				$this->code.= '</table>';
				$this->code.= '</td>';
			}
			$i++;
		}
		$this->code.= '</tr>';
	}

	protected function DrawFooter() {
		// M�todo encargado de dibujar el pie de la grilla..

		$this->code.= '<tr class="'.$this->footerStyle.'">';
		if (($this->columns[1]->getVisible()) and ($this->columns[1]->getTitle() == ""))
			$this->code.= '<td class="'.$this->columns[1]->getCellClass().'" style="border-right: 1px solid #fff;"></td>';
		$this->code.= '<td colspan="'.(count($this->columns) - 1).'" align="center">';
		$this->code.= '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
		$this->code.= '<tr>';

		$total = "";
		if ($this->showTotalRegistros)
			if ($this->recordCount == 1)
				$total = "Total: 1 registro";
			else
				$total = "Total: ".$this->recordCount." registros";
		$this->code.= '<td align="left" class="GridFooterFontSelected" width="750"><b>'.$total.'</b></td>';

		$bloque = ceil($this->pageNumber / $this->blocksPerPage);
		$showMessage = ($this->showProcessMessage)?"true":"false";
		if ($bloque > 1) {
			$url = $this->BuildUrl(($bloque - 1) * $this->blocksPerPage);
			$this->code.= '<td align="center"><a class="GridFooterFont" href="#" onClick="changePage(\''.$url.'\', '.$showMessage.', '.(($this->refreshIntoWindow)?'true':'false').')"><<</a></td>';
			$this->code.= '<td>&nbsp;&nbsp;</td>';
		}

		for ($i = ((($bloque - 1) * $this->blocksPerPage) + 1); $i <= ($bloque * $this->blocksPerPage); $i++) {
			if ($i > $this->totalPages)
				break;

			if ($i == $this->pageNumber)
				$this->code.= '<td align="center" class="GridFooterFontSelected"><b>'.$i.'</b></td>';
			else {
				$url = $this->BuildUrl($i);
				$this->code.= '<td align="center"><a class="GridFooterFont" href="#" onClick="changePage(\''.$url.'\', '.$showMessage.', '.(($this->refreshIntoWindow)?'true':'false').')">'.$i.'</a></td>';
			}

			$this->code.= '<td width="12"></td>';
		}

		if ($bloque < (ceil($this->totalPages / $this->blocksPerPage))) {
			$url = $this->BuildUrl(($bloque * $this->blocksPerPage) + 1);
			$this->code.= '<td>&nbsp;&nbsp;</td>';
			$this->code.= '<td align="center"><a class="GridFooterFont" href="#" onClick="changePage(\''.$url.'\', '.$showMessage.', '.(($this->refreshIntoWindow)?'true':'false').')">>></a></td>';
		}

		$this->code.= '<td align="right" width="750">';

		if ($this->GetColBaja() != -1) {		// Si est� configurada cual es la columna de baja..
			$this->SetOnClickVerOcultarBajas();
			$url = $this->BuildUrl(1);
			if ($this->showBajas)
				$this->code.= '<img border="0" src="/images/grid_ocultar_bajas.gif" style="cursor:pointer" title="Ocultar registros dados de baja" onClick="changePage(\''.$url.'\', '.$showMessage.', '.(($this->refreshIntoWindow)?'true':'false').')" />';
			else {
				$this->code.= '<img border="0" src="/images/grid_ver_bajas.gif" style="cursor:pointer" title="Ver registros dados de baja" onClick="changePage(\''.$url.'\', '.$showMessage.', '.(($this->refreshIntoWindow)?'true':'false').')" />';
			}
		}

		$this->code.= '</td>';

		if ($this->classToHideLastPartOfTheFooter != "")
			$this->code.= '<td><table border="0" cellpadding="0" cellspacing="0" class="'.$this->classToHideLastPartOfTheFooter.'" width="100%" style="top: -1px;"><tr><td>&nbsp;</td></tr></table><table border="0" cellpadding="0" cellspacing="0" class="'.$this->classToHideLastPartOfTheFooter.'" width="100%"><tr><td>&nbsp;</td></tr></table></td>';

		$this->code.= '</tr>';
		$this->code.= '</table>';
		$this->code.= '</td>';
		$this->code.= '</tr>';
	}

	protected function DrawMessageNoResults() {
		// M�todo encargado de dibujar el mensaje de que no se encontraron resultados..

		$this->code.= '<div align="center" id="originalGrid" name="originalGrid">';
		$this->code.= '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
		$this->code.= '<tr>';
		$this->code.= '<td align="center"><input class="'.$this->emptyStyle.'" type="button"></td>';
		$this->code.= '</tr>';
		$this->code.= '</table>';
		$this->code.= '</div>';
	}

	protected function drawOpenIfOneRecord($col) {
		// M�todo que agrega el c�digo para abrir el link de la columna pasada como par�metro..

		$this->code.= "<script type='text/javascript'>location.replace('".$this->linkIfOneRecord."');</script>";
	}

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
			foreach ($row as $value) {
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
								$parametroID = "?id=".$value;
							else
								$parametroID = "&id=".$value;

							if ($this->useTmpIframe)
								$url = "document.getElementById('".$this->tmpIframeName."').src = '".$this->columns[$i]->getActionButton().$parametroID."';";
							else
								$url = "window.location.href = '".$this->columns[$i]->getActionButton().$parametroID."';";

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

	protected function FormatExtraConditions($sql) {
		// M�todo que reemplaza las ocurrencias "_EXC1_", "_EXC2_", "_EXCn_" por las extra conditions..

		for ($i=0; $i<count($this->extraConditions); $i++)
			$sql = str_replace("_EXC".($i + 1)."_", StringToUpper($this->extraConditions[$i]), $sql);

		return $sql;
	}

	protected function FormatFields($fields) {
		// M�todo encargado de formatear los campos para que funcione el query..

		// Meto los campos en un array para poder parsearlo bien..
		$arrFields = explode("�", trim($fields));
		for ($i=1;$i<count($arrFields);$i++)
			$arrFields[$i] = substr($arrFields[$i], 0, strrpos($arrFields[$i], "?"));
		array_shift($arrFields);

		return implode(",", $arrFields);
	}

	protected function FormatOrderBy($sql) {
		if ($this->orderBy != "") {
			$arr = explode("ORDER BY", StringToUpper($sql));

			$pos = strpos($this->orderBy, "_D_");
			if ($pos)
				return $arr[0]." ORDER BY ".substr($this->orderBy, 0, $pos)." DESC ";
			else
				return $arr[0]." ORDER BY ".$this->orderBy;
		}
		else
			return $sql;
	}

	protected function GetBajaCondition() {
		// Funci�n que devuelve la condici�n para filtrar los registros dados de baja..

		$bajas = "";
		if ($this->GetColBaja() != -1)		// Si est� configurada cual es la columna de baja..
			if (!$this->showBajas)
				$bajas = " AND ".$this->fieldBaja." IS NULL ";

		return $bajas;
	}

	protected function GetColBaja() {
		// Devuelve el �ndice en el array de columnas de la primer columna marcada como columna de baja..

		$i = 0;
		foreach ($this->columns as $col) {
			if ($col->getDeletedRow())
				return $i;
			$i++;
		}

		return -1;
	}

	protected function getHideCell($row, $col) {
		// M�todo que devuelve si la celda de la columa pasada como par�metro tiene que ocultarse o no..

		for ($i=0; $i<count($row); $i++) {
			$num = $this->columns[$i]->getNumCellHide();
			if (($num > 0) and ($num == $col) and ($row[$i] == "T"))		// Si hay una celda que ocultar y es la de la columna pasada como par�metro y hay que ocultar esta celda..
				return true;
		}

		return false;
	}

	protected function GetRecordCount() {
		// M�todo que calcula la cantidad de registros que devuelve el query..

		global $conn;

		$sqlCount = "SELECT COUNT(*) FROM(".$this->FormatExtraConditions($this->sql).$this->GetBajaCondition().")";
		$sqlCount = str_replace("?", "", str_replace("�", "", $sqlCount));

		$this->stmt = DBExecSql($conn, $sqlCount, $this->params);
		$row = DBGetQuery($this->stmt, 0);
		$this->recordCount = $row[0];
		$this->totalPages = ceil($row[0] / $this->rowsPerPage);
	}

	protected function PrepareSql() {
		// M�todo que prepara el query..

		global $conn;

		$rowFrom = (($this->pageNumber - 1) * $this->rowsPerPage) + 1;
		$rowTo = $rowFrom + $this->rowsPerPage - 1;
		$fields = $this->FormatFields(substr($this->sql, strpos($this->sql, "SELECT ") + 5, strpos($this->sql, " FROM ") - 5));
		$iniSel = "SELECT * FROM (SELECT ROWNUM cols__,".$fields." FROM (";
		$finSel = ")) WHERE cols__ BETWEEN ".$rowFrom." AND ".$rowTo;

		$sqlFinal = $iniSel.$this->FormatOrderBy($this->FormatExtraConditions($this->sql).$this->GetBajaCondition()).$finSel;
		$sqlFinal = str_replace("?", "", str_replace("�", "", $sqlFinal));

		$this->sqlFinal = $this->FormatOrderBy($this->FormatExtraConditions($this->sql).$this->GetBajaCondition());
		$this->sqlFinal = str_replace("?", "", str_replace("�", "", $this->sqlFinal));

 		$this->stmt = DBExecSql($conn, $sqlFinal, $this->params);
	}

	protected function SetOnClickVerOcultarBajas() {
		// M�todo que agrega a la url si se deben mostrar u ocultar las bajas al hacer click en los botones de ver o ocultar bajas..

		if ($this->showBajas)
			$_REQUEST["sb"] = "F";
		else
			$_REQUEST["sb"] = "T";
	}

	protected function writeJs() {
		// M�todo encargado de escribir el c�digo javascript relacionado con la grilla..

		$this->code.= '<script src="/js/grid.js" type="text/javascript"></script>';
	}


	public function getShowProcessMessage() {
		return $this->showProcessMessage;
	}

	public function getSqlFinal($replaceParams = false) {
		// Devuelve el SQL final reemplazando los par�metros por los valores correspondientes, si correspondiera..
		$sql = $this->sqlFinal;

		if ($replaceParams)
			foreach ($this->params as $key => $val)
				$sql = str_replace(strtoupper($key), "'".$this->params[$key]."'", $sql);

		return $sql;
	}


	public function recordCount() {
		return $this->recordCount;
	}

	public function setBaja($colBaja, $showBajas, $showButton) {
		$this->colBaja = $colBaja;
		$this->showBajas = $showBajas;
		$this->showButtonDespiteBaja = $showButton;
	}

	public function setBajaStyle($value) {
		$this->bajaStyle = $value;
	}

	public function setClassToHideLastPartOfTheFooter($value) {
		$this->classToHideLastPartOfTheFooter = $value;
	}

	public function setColsSeparator($value) {
		$this->colsSeparator = $value;
	}

	public function setColsSeparatorColor($value) {
		$this->colsSeparatorColor = $value;
	}

	public function setDecodeSpecialChars($value) {
		$this->decodeSpecialChars = $value;
	}

	public function setEmptyStyle($value) {
		$this->emptyStyle = $value;
	}

	public function setExtraConditions($value) {
		$this->extraConditions = $value;
		if ($this->sql != "")
			$this->GetRecordCount();
	}

	public function setFieldBaja($value) {
		$this->fieldBaja = $value;
	}

	public function setFooterStyle($value) {
		$this->footerStyle = $value;
	}

	public function setHeaderStyle($value) {
		$this->headerStyle = $value;
	}

	public function setOrderBy($value) {
		$this->orderBy = $value;
	}

	public function setPageNumber($value) {
		$this->pageNumber = $value;
	}

	public function setParams($value) {
		$this->params = $value;
	}

	public function setRefreshIntoWindow($value) {
		$this->refreshIntoWindow = $value;
	}

	public function setRow1Style($value) {
		$this->row1Style = $value;
	}

	public function setRow2Style($value) {
		$this->row2Style = $value;
	}

	public function setRowHeight($value) {
		$this->rowHeight = $value;
	}

	public function setRowsSeparator($value) {
		$this->rowsSeparator = $value;
	}

	public function setRowsSeparatorColor($value) {
		$this->rowsSeparatorColor = $value;
	}

	public function setShowFooter($value) {
		$this->showFooter = $value;
	}

	public function setShowMessageNoResults($value) {
		$this->showMessageNoResults = $value;
	}

	public function setShowProcessMessage($value) {
		$this->showProcessMessage = $value;
	}

	public function setShowTotalRegistros($value) {
		$this->showTotalRegistros = $value;
	}

	public function setSql($value) {
		$this->sql = StringToUpper($value);
		$this->GetRecordCount();
	}

	public function setTableStyle($value) {
		$this->tableStyle = $value;
	}

	public function setTextStyle($value) {
		$this->textStyle = $value;
	}

	public function setTitleStyle($value) {
		$this->titleStyle = $value;
	}

	public function setUnderlineSelectedRow($value) {
		$this->underlineSelectedRow = $value;
	}

	public function setUseTmpIframe($value) {
		$this->useTmpIframe = $value;
	}
}
?>