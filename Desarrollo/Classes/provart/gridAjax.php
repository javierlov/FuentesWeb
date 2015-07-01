<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid_columnAjax.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");
/*
Clase preparada para dibujar una grilla pasando un query de ORACLE como parámetro, no está preparada para soportar otras bases de datos..

La forma básica de uso sería como muestra el ejemplo de abajo:

$pagina = 1;
$sql = "SELECT ¿se_nombre?, ¿se_usuario?, ¿se_mail?, DECODE(se_id, 1413, 'T', 'F') ¿hidecell1? FROM use_usuarios";
$grilla = new Grid();
$grilla->addColumn(new Column("", 8, true, false, -1, "BotonInformacion", "index.php?pageid=5&page=informacion.php", "gridFirstColumn"));
$grilla->addColumn(new Column("Sector"));
$grilla->addColumn(new Column("Gerencia"));
$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 1));
$grilla->setOrderBy("2_D_");
$grilla->setPageNumber($pagina);
$grilla->setSql($sql);
$grilla->Draw();
*/

class gridAjax extends Grid {
	private $bajaStyle = "GridBaja";
	private $blocksPerPage;		// Cantidad máxima de links del footer..
	private $classToHideLastPartOfTheFooter = "";		// Clase utilizada para ocultar la última parte del footer..
	private $code = "";		// Código (HTML + Javascript) que genera la clase..
	private $columns = array();		// Conjunto de columnas de la grilla..
	private $colsSeparator = false;		// Indica si se muestra o no un separador entre columnas..
	private $colsSeparatorColor = "#fff";		// Color del separador de columnas..
	private $colsSeparatorStyle = "gridColSeparator";		// Estilo del separador de columnas..
	private $decodeSpecialChars = false;		// Si es true llama a la función que decodea los caracteres especiales HTML..
	private $emptyStyle = "gridEmpty";
	private $extraConditions = array();		// Es un arreglo con porciones de sql a insertarse dentro del sql primario..
	private $fieldBaja = "";		// Indica el nombre del campo que marca como baja un registro..
	private $footerStyle = "gridFooter";		// Estilo del footer..
	private $headerStyle = "gridHeader";		// Estilo del header..
	private $linkIfOneRecord = -1;		// Link a abrir si el query devuelve un solo registro y se quiere abrir automaticamente..
	private $orderBy = "";		// Indica por que columna está ordenada la grilla. Si se quiere descendente agregar la cadena "_D_"..
	private $pageNumber = 1;		// Número de página actual..
	private $params = array();		// Parámetros a usar con el sql..
	private $recordCount = 0;		// Cantidad de registros del query..
	private $refreshIntoWindow = false;		// Indica si la página se recarga sobre la misma página o se hace en un iframe..
	private $row1Style = "GridRowAjx1";//"GridRow1";		// Estilo de las filas impares..
	private $row2Style = "GridRowAjx2";//"GridRow2";		// Estilo de las filas pares..
	private $rowHeight;		// Alto de las filas..
	private $rowsPerPage;		// Cantidad máxima de filas que dibujará la grilla..
	private $rowsSeparator = false;		// Indica si se muestra o no un separador entre filas..
	private $rowsSeparatorColor = "#fff";		// Color del separador de filas..
	private $showBajas = false;		// Indica si se muestran los registros dados de baja o no..
	private $showButtonDespiteBaja = true;		// Indica si se muestra el botón aunque el registro está dado de baja..
	private $showFooter = true;		//Indica si se muestra el footer o no..
	private $showMessageNoResults = true;		// Indica si se muestra el mensaje de que no se encontraron registros o no..
	private $showProcessMessage = false;		// Indica si se muestra un mensaje de que se está procesando el query de búsqueda..
	private $showTotalRegistros = false;		// Indica si se muestra el total de registros al pie de la grilla..
	private $sql = "";
	private $sqlFinal = "";		// Contiene el sql limpio de los caracteres necesarios para mostrarlo en la grilla..
	private $stmt;
	private $tableStyle = " GridTableAjx ";
	private $textStyle = " GridTextAjx ";		// Estilo del texto de los datos que muestra la grilla..
	private $textStyleLink = " GridTextLinkAjx ";		// Estilo del texto de los datos que muestra la grilla como link..
	private $titleStyle = " GridTitleAjx ";
	private $tmpIframeName = "iframeTmpGrid";		// Nombre del iframe temporal..
	private $totalPages = 0;		// Cantidad de páginas que tiene la grilla..
	private $underlineSelectedRow = true;		// Resalta la fila donde está parado el mouse..
	private $useTmpIframe = false;		// Indica si se va a usar un iframe temporal para procesar las acciones de los botones..
//-----------------------------------------------------------------------------------	
	private $ArrayChecks;	//Array de checks 
	private $FuncionAjaxJS = false;		// Nombre de la funcion Ajax a ejecutar 
	private $CrearArray;	//True crea el Array de checks 
	private $ArraySeleccion;	//Array de selecciones 
	private $CurrentURL;	//Es la ultima url ingresada en la grilla, es para casos donde se retorne un solo registro
//-----------------------------------------------------------------------------------	
	private $arrayColTitle = null; //Array con columnas para titulos con mas de una fila....
	private $FuncionAjaxOBJS = false;		// Funcion Js con la Columna por la cual se va a ordenar.
//-----------------------------------------------------------------------------------	
	private $AddRecordInsertData = null; // Array con controles html, si no es null esto indica que se agrega un registro mas para la insersion de un nuevo registro.....
	private $ChangeBackGroundColumns = null; //Array con el id de las columnas que cambian el color 
	private $ChangeBackGroundStyle = '#3F4C6B'; //Color que se usa para las columnas en  ChangeBackGroundColumns cambia color de fondo
	private $ChangeBackGroundFieldNum = 0; //Columna de las enviadas en el query que se compara para ver si existe en el array ChangeBackGroundColumns
//-----------------------------------------------------------------------------------	
	private $LastID = 0; //Es el ultimo id que se recorrio en la consulta de registros
//-----------------------------------------------------------------------------------		
	private $textStyleFooterSelected = " gridFooterFontSelected ";		// Estilo de la pagina seleccionada al pie de la grilla
//-----------------------------------------------------------------------------------	
	private $StyleunderlineSelectedRow = " GridFondoOnMouseOverAjx ";		// clase css que resalta el fondo de la grilla al pasar el mouse		
	private $StyleCellText = " max-height: 13px;  overflow: hidden;  height: expression(this.scrollHeight > 14? '13px' : 'auto' ); ";		// style que formatea el texto en la grilla
//-----------------------------------------------------------------------------------	

	public function __construct($blocksPerPage = 15, $rowsPerPage = 10, $ArraySeleccion = '') {
		// Constructor..		
		if($ArraySeleccion != ''){
			$this->CrearArray = false;			
			$this->ArraySeleccion = explode(",",$ArraySeleccion);			
		}else{
			$this->CrearArray = true;
			$this->ArrayChecks = array();
		}

		$this->blocksPerPage = $blocksPerPage;
		$this->rowsPerPage = $rowsPerPage;

		$this->addColumn(new Column("", 0, false));		// Esta primer columna es el ROWNUM..
		$this->writeJs();		
	}

	public function addColumn($col) {
		// Método encargado de agregar una columna al arreglo de columnas..
		array_push($this->columns, $col);
	}

	public function BuildUrl($page, $orderBy = -1) {
		// Método encargado de armar la url asociada a los links de las páginas..
		if ($orderBy == '') return '';
		
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
		// Método encargado de dibujar la grilla..

		$this->PrepareSql();

		if ($this->recordCount == 0 and !$this->isEditMode() ) {
			if ($this->showMessageNoResults)
				$this->DrawMessageNoResults();
		}
		else {
			$this->code.= '<div align="center" id="originalGrid" name="originalGrid" class="'.$this->tableStyle.'" >
							<table class="'.$this->tableStyle.'">';
			if ($this->useTmpIframe)
				$this->code.= '<iframe id="'.$this->tmpIframeName.'" name="'.$this->tmpIframeName.'" src="" style="display:none;"></iframe>';
			
			if( isset($this->arrayColTitle) )
				$this->DrawColTitles2Rows();	//$this->DrawColTitles();
			else
				$this->DrawColTitles();			
			
			$this->DrawRows($colOpenIfOneRecord);
					
			//if( isset($this->AddRecordInsertData) and  count($this->AddRecordInsertData) > 0 ) 
			if( $this->isEditMode() )
				$this->DrawEditRow();
			
			if ($this->showFooter)	
				$this->DrawFooter();			

			if (($colOpenIfOneRecord > 0) and ($this->recordCount == 1))
				$this->drawOpenIfOneRecord($colOpenIfOneRecord);

			$this->code.= '</table></div>';			
		}

		if ($draw) {
			echo $this->code;
			return "";
		}
		else
			return $this->code;
	}

	private function DrawEditRow(){
		$classRow = " ";
		//if ($this->underlineSelectedRow) $classRow = "gridFondoOnMouseOverAjx ";		
		$classRow = $this->row1Style;				
				
		$this->code.= '<tr class="'.$classRow.'" style="height:'.$this->rowHeight.'px">';
		
		$colStyle = "";
		if ($this->colsSeparator) 
			$colStyle.= "border-right:1px solid ".$this->colsSeparatorColor.";";
		
		$css = "";
		if ($this->rowsSeparator)
			$css = "border-top:1px solid ".$this->rowsSeparatorColor.";";		

		foreach($this->columns as $key=>$value){			
			$controlHTML = '';
			if( isset( $this->AddRecordInsertData[$key] ) ) {
				$controlHTML = $this->AddRecordInsertData[$key];				
			}
									
			$title = "";						
			if ($value->getColHint() > -1)
				//$title = 'title="'.get_htmlspecialchars($row[$value->getColHint()]).'"';				
				$title = 'title="'.get_htmlspecialchars( $value->getColHint() ).'"';				
				
			if ($value->getVisible()) 
				$this->code.= '<td align="left" class="'.$value->getCellClass().' '.$this->textStyle.'" style="'.$colStyle.' '.$css.'"><div '.$title.'>'.$controlHTML.'</div></td>';
		}
		$this->code.= '</tr>';
	}
	
	private function DrawColTitles2Rows() {
		// Método encargado de dibujar los títulos de las columnas..
		// $this->code.= '<tr>';		
		$x = 0;		
		foreach ($this->arrayColTitle as $rows) {
			$this->code.= '<tr>';
			$i = 0;
			foreach ($rows as $col) {				
				$colVisible = true;//las columnas siempre estan visibles...
				
				if ($colVisible) {		// Si la columna está visible..
					$colStyle = 'style="';
					if (($this->colsSeparator) and ($i < (count($rows) - 1) ))
						$colStyle.= 'border-right: 1px solid '.$this->colsSeparatorColor.';';

					$width = "";
					if ($rows[$i]->getWidth() > 0)
						$width = ' width="'.$rows[$i]->getWidth().'"';

					$colStyle.= '"';
					$showMessage = ($this->showProcessMessage)?"true":"false";				
					$img = '';
					
					$FuncionClick = '';
					if($x == 0 and  $col->getCanSort() ){						
						$numcol = $i+1;
						if ( $this->orderBy == $numcol){
							$img = '';
							if (!strpos($this->orderBy, "_D_")){
								if ($numcol > 0)
									$img = '<img border="0" src="/images/grid_asc.gif" title="Ascendente" />';
								//$url = $this->BuildUrl(1, $numcol."_D_");
								$parametro = " '".($numcol)."_D_' ";
								$FuncionClick = ' onClick="'.$this->FuncionAjaxOBJS.'( '.$parametro.' );" ';
							}
							else{
								if ($numcol > 0)
									$img = '<img border="0" src="/images/grid_desc.gif" title="Descendente" />';
								//$url = $this->BuildUrl(1, $numcol);
								$FuncionClick = ' onClick="'.$this->FuncionAjaxOBJS.'( '.$numcol.' );" ';
							}
						}
						else{
							$img = '';
							//$url = $this->BuildUrl(1, $i);
							$FuncionClick = ' onClick="'.$this->FuncionAjaxOBJS.'( '.$numcol.' );" ';
						}
					}								
					
					if (($rows[$i]->getUseStyleForTitle()) and ($rows[$i]->getCellClass() != ""))
						$class = $rows[$i]->getCellClass();
					else
						$class = $this->headerStyle;

					$title = "";
					if ($rows[$i]->getTitleHint() != "")
						$title = 'title="'.$rows[$i]->getTitleHint().'"';
					
					$title = utf8_decode($title);
					
					$colspan = " colspan=".$rows[$i]->getColspan();
					$rowspan = " rowspan=".$rows[$i]->getRowspan();
					
					$this->code.= '<td class="'.$class.'" '.$colStyle.$width.' '.$colspan.' '.$rowspan.' >';
					$this->code.= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
					$this->code.= '<tr>';
					$this->code.= '<td width="28"></td>';
					
					$this->code.= '<td align="center" '.$title.'><a '.$FuncionClick.' ><div class="'.$this->titleStyle.'">'.$col->getTitle().'</div></a></td>';
					
					$this->code.= '<td align="right" width="28">'.$img.'</td>';
					$this->code.= '</tr>';
					$this->code.= '</table>';
					$this->code.= '</td>';
				}
				$i++;			 
			}
			$x++;
			$this->code.= '</tr>';
		}				
	}


	private function DrawColTitles() {
		// Método encargado de dibujar los títulos de las columnas..

		$this->code.= '<tr>';
		$i = 0;		
		foreach ($this->columns as $col) {
			if ($col->getVisible()) {		// Si la columna está visible..
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
				
				if ($this->orderBy == '') 
					$this->code.= '<td align="center" '.$title.'><div class="'.$this->titleStyle.'" >'.$col->getTitle().'</div></td>';
				else
					$this->code.= '<td align="center" '.$title.'><a href="#" ><div class="'.$this->titleStyle.'" >'.$col->getTitle().'</div></a></td>';
				
				$this->code.= '<td align="right" width="28">'.$img.'</td>';
				$this->code.= '</tr>';
				$this->code.= '</table>';
				$this->code.= '</td>';
			}
			$i++;
		}
		$this->code.= '</tr>';
	}

	private function DrawFooter() {
		// Método encargado de dibujar el pie de la grilla..

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
		$this->code.= '<td align="left" class="'.$this->textStyleFooterSelected.'" width="750"><b id="gridTotalRegistros">'.$total.'</b></td>';

		$bloque = ceil($this->pageNumber / $this->blocksPerPage);
		$showMessage = ($this->showProcessMessage)?"true":"false";
		if ($bloque > 1) {
			$url = $this->BuildUrl(($bloque - 1) * $this->blocksPerPage);
			
			$bloquei = ($bloque - 1) * $this->blocksPerPage;
			$FuncionClick = 'onClick="'.$this->FuncionAjaxJS.'('.$bloquei.');"';
			$this->code.= '<td align="center"><a class="gridFooterFont" href="#" '.$FuncionClick.' ><<</a></td>';
			$this->code.= '<td>&nbsp;&nbsp;</td>';
			
		}

		for ($i = ((($bloque - 1) * $this->blocksPerPage) + 1); $i <= ($bloque * $this->blocksPerPage); $i++) {
			if ($i > $this->totalPages)
				break;

			if ($i == $this->pageNumber)
				$this->code.= '<td align="center" class="'.$this->textStyleFooterSelected.'"><b>'.$i.'</b></td>';
			else {
				$url = $this->BuildUrl($i);
				
				$FuncionClick = 'onClick="'.$this->FuncionAjaxJS.'('.$i.');" ';
				$this->code.= '<td align="center"><a class="gridFooterFont" href="#" '.$FuncionClick.' >'.$i.'</a></td>';
			}

			$this->code.= '<td width="12"></td>';
		}

		if ($bloque < (ceil($this->totalPages / $this->blocksPerPage))) {
			$url = $this->BuildUrl(($bloque * $this->blocksPerPage) + 1);
			$this->code.= '<td>&nbsp;&nbsp;</td>';
						
			$FuncionClick = 'onClick="'.$this->FuncionAjaxJS.'('.$i.');" ';
			$this->code.= '<td align="center"><a class="gridFooterFont" href="#" '.$FuncionClick.' >>></a></td>';
		}

		$this->code.= '<td align="right" width="750">';

		if ($this->GetColBaja() != -1) {		// Si está configurada cual es la columna de baja..
			$this->SetOnClickVerOcultarBajas();
			$url = $this->BuildUrl(1);
			if ($this->showBajas)
			
				$this->code.= '<img border="0" src="/images/grid_ocultar_bajas.gif" style="cursor:pointer" title="Ocultar registros dados de baja" onClick="'.$this->FuncionAjaxJS.'('.$i.');" />';
			else {
			
				$this->code.= '<img border="0" src="/images/grid_ver_bajas.gif" style="cursor:pointer" title="Ver registros dados de baja" onClick="'.$this->FuncionAjaxJS.'('.$i.');" />';
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

	private function DrawMessageNoResults() {
		// Método encargado de dibujar el mensaje de que no se encontraron resultados..

		$this->code.= '<div align="center" id="originalGrid" name="originalGrid">';
		$this->code.= '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
		$this->code.= '<tr>';
		$this->code.= '<td align="center"><input class="'.$this->emptyStyle.'" type="button"></td>';
		$this->code.= '</tr>';
		$this->code.= '</table>';
		$this->code.= '</div>';
		//$this->code.= $this->GetArrayChecksJS();
	}

	private function drawOpenIfOneRecord($col) {
		// Método que agrega el código para abrir el link de la columna pasada como parámetro..

		$this->code.= "<script type='text/javascript'>location.replace('".$this->linkIfOneRecord."');</script>";
	}
	
	private function DrawRows($colOpenIfOneRecord) {
		// Método encargado de dibujar los resultados del query..
		$countRecords = 1;
		$styleCell = "";
		$checked = "";
		$classRow = "";
		
		while ( $row = DBGetQuery( $this->stmt, 0) ) {
			$css = "";
			$classRow = "";
			$this->SetCurrentURL('');

			if ($this->underlineSelectedRow)				
				$classRow = $this->StyleunderlineSelectedRow;
			
			$classRow .= ((($countRecords % 2) == 0)?$this->row2Style:$this->row1Style);
			
			$this->code.= '<tr class="'.$classRow.'" style="height:'.$this->rowHeight.'px">';

			if (($this->rowsSeparator) and ($countRecords > 1))				
				$css = "border-top:1px solid ".$this->rowsSeparatorColor.";";
						
			$i = 0;			
			foreach ($row as $value) {
			
				//$value = utf8_encode($value);				
				$value = html_entity_decode($value);				
				$LastID = html_entity_decode($value);				
				/*
				if (mb_detect_encoding($value)=="UTF-8"){ 
					//$value = utf8_decode($value);				
				}
				*/
				if ($this->decodeSpecialChars)
					$value = htmlspecialcharsDecodeUpper($value);
	
				$value = trim($value);

				if ($this->columns[$i]->getVisible()) {		// Si la columna está visible..
					$hideCell = $this->getHideCell($row, $i);

					$colStyle = "";
					if (($this->colsSeparator) and ($i < (count($row) - 1)))
						$colStyle.= "border-right:1px solid ".$this->colsSeparatorColor."; ";

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
														
							if ( strrpos($parametroID, "+") != 0 ) $parametroID = str_replace('+', '&', $parametroID);							
							//------------------------------------------------------ -----------------------------------------------------
							if($this->columns[$i]->getFunctionAjax() == ''){
								if ($this->useTmpIframe){
									$url = "document.getElementById('".$this->tmpIframeName."').src = '".$this->columns[$i]->getActionButton().$parametroID."';";
								}
								else
									$url = "window.location.href = '".$this->columns[$i]->getActionButton().$parametroID."';";
							}else{								
									$url = " ".$this->columns[$i]->getFunctionAjax()."(".$value."); ";
							}							
						
							$this->SetCurrentURL($this->columns[$i]->getActionButton().$parametroID);
							//------------------------------------------------------  ------------------------------------------------------ 
							if (($colOpenIfOneRecord == ($i)) and ($this->recordCount == 1))
								$this->linkIfOneRecord = $this->columns[$i]->getActionButton().$parametroID;

							if ($hideCell) {		// Si hay que ocultar el contenido de esta celda no muestro el botón..
								$input = "";
							}
							else {
								$checked = "";
								$activacheck = "";								
								
								if (($this->columns[$i]->getInputType() == "checkbox") 
								and ($this->columns[$i]->getColChecked() > -1)){
									$checked = $row[$this->columns[$i]->getColChecked()];

									if($this->CrearArray){
										if(intval($value) > 0)	
											$activacheck = " checked ";
									}else{										
										if($this->IsArrayCheckSeleccion($row[$i+1]))
											$activacheck = " checked ";
									}
								}

								$msgEspera = "";
								if ($this->columns[$i]->getMostrarEspera())
									$msgEspera = "mostrarMensajeEspera('".$this->columns[$i]->getMsgEspera()."');";
									
								$eventOnClick = 'onClick="'.$msgEspera.' '.$url.'" ';
																
								if(isset($row[$i+1])){
									if($this->columns[$i]->GetUseIdPageinName()){
										$valPageRegCol = $this->pageNumber.'_'.$countRecords.'_'.$i;
										$idcheck = ' id="ID_'.$valPageRegCol.'" ';
										$nameCheck = ' name="GRID_COL'.$valPageRegCol.'" ';
									}else{
										$idcheck = ' id="'.utf8_encode($row[$i+1]).'" ';
										$nameCheck= ' name="GRID_COL'.$i.'_'.$value.'" ';
									 }
									
									if($activacheck != "")
										$this->AddArrayChecks($row[$i+1]);										
									
									if ($this->columns[$i]->getInputType() == "checkbox"){
										//$idColumn = $this->columns[$i]->getColChecked();
										//$eventOnClick = 'onClick="CheckOption(\''.$row[$idColumn].'\'); " ';
										$paramFuncion = utf8_encode($row[$i+1]);
										$eventOnClick = 'onClick="CheckOption(\''.$paramFuncion.'\'); " ';
										$checked = "";
									}
								}else{									
									$valPageRegCol = $this->pageNumber.'_'.$countRecords.'_'.$i;
									$idcheck = ' id="ID_ELSE_'.$valPageRegCol.'" ';
									$nameCheck = ' name="GRID_COL_ELSE'.$valPageRegCol.'" ';
								}							
							
								if( $this->columns[$i]->hasArrayLinks() ) {							
										
									$codigoKey = $this->columns[$i]->searchTextArrayLinks($value);
									$codigoClass = $this->columns[$i]->searchKeyArrayLinks($value);
																		
									if($value == '0' or $codigoClass == 'TEXT'){
										$valorText = $this->columns[$i]->searchTextArrayLinks($value);																			
										$input = '<div>'.$valorText.'</div>';
										
									}elseif($this->IsLinkCell($value)){									
										$funcionAjax = " ".$this->columns[$i]->getFunctionAjax()."(".$value."); ";
										$eventOnClick = 'onClick=" '.$funcionAjax.' " ';
										
										$codigoClass = $this->ExtraeTextoCell($codigoClass, '_LINK_');
										$input =  ' <a class="'.$this->textStyleLink.'" '.$eventOnClick.' > '.$codigoClass.' </a> ';
										
									}else{												
										$input = '<input  '.$checked.' class="'.$this->columns[$i]->getArrayButtonIDclass($codigoClass).'" '.$idcheck.' '.$eventOnClick.' '.$nameCheck.' '.$title.' type="'.$this->columns[$i]->getInputType().'"  '.$activacheck.' />';
									}
									
								}else{
									//entra aca 	
									$input = '<input  '.$checked.' class="'.$this->columns[$i]->getButtonClass($row).'" '.$idcheck.' '.$eventOnClick.' '.$nameCheck.' '.$title.' type="'.$this->columns[$i]->getInputType().'"  '.$activacheck.' />';
								}								
							}
							
							$classCell = ' class="'.$this->columns[$i]->getCellClass().' " ';
							$styleCell = ' style="'.$colStyle.' '.$css.'" ';
							
							if( $this->ChangeBackGround( $row ) ){
								$styleCell = ' style="'.$colStyle.' '.$css.'  background-color:'.$this->ChangeBackGroundStyle.'; " ';
							}
																
							$this->code.= '<td align="center" '.$classCell.' '.$styleCell.' >'.$input.'</td>';
						}
					}
					else{
						
						if ($this->columns[$i]->getMaxChars() > -1)		// Ajusto la cantidad de caracteres a mostrar en la celda..
							$value = substr($value, 0, $this->columns[$i]->getMaxChars())."...";

						if ($hideCell)		// Si hay que ocultar el contenido de esta celda no muestro el valor..
							$value = "";
							
							$value = utf8_encode($value);
						//--------------------------------------------------------------------------------------------	
						if($this->columns[$i]->getLink($value) != ''){		
							$eventOnClick = 'onClick=" '.$this->columns[$i]->getLink( $value ).'(\''.$value.'\'); " ';
							$value =  ' <a class="'.$this->textStyle.'" '.$eventOnClick.' > '.$value.' </a> ' ;								
						}						
						//--------------------------------------------------------------------------------------------
						$EventHTML = '';						
						$IdNameColumna = '';
						if($this->columns[$i]->getEventHTMLdblclick() != '' ){
							$NumColum = $this->columns[$i]->getEventHTMLdblclickFieldNameParam();							
							$valorParam = $row[$NumColum];
							
							$IdNameColumna = " ID='CELDA_".$i."_".$valorParam."' ";							
							$EventHTML = "ondblclick= ' ".$this->columns[$i]->getEventHTMLdblclick()."(".$i.", ".$valorParam.") ' ";
							
							$title = ' title="'.$value.'" ';
						}
						
						$classCell = '';
						if($this->columns[$i]->getCellClass() != ''){
							$classCell = ' class="'.$this->columns[$i]->getCellClass().' '.$this->textStyle.'" ';
							$styleCell = ' style="'.$colStyle.' '.$css.'" ';
							
							if( $this->ChangeBackGround( $row ) ){
								$styleCell = ' style="'.$colStyle.' '.$css.'  background-color:'.$this->ChangeBackGroundStyle.'; " ';
							}
						}
						
						if (($this->GetColBaja() != -1) and ($row[$this->GetColBaja()] != ""))		// Si la columna de baja está seteada y es una baja..
							$this->code.= '<td align="left" '.$classCell.'  '.$styleCell.' ><div class="'.$this->bajaStyle.'" '.$title.'>'.$value.'</div></td>';
						else{							
							$colStyle = $colStyle.$this->StyleCellText;
							$this->code.= '<td align="left" '.$classCell.'  '.$styleCell.'  '.$EventHTML.' > <div '.$title.'  '.$EventHTML.' '.$IdNameColumna.'  style="'.$colStyle.' '.$css.'" >'.$value.'</div></td>';
						}
					}
				}
				$i++;
			}
			$this->code.= '</tr>';
			$countRecords++;
		}
	}
	
	private function IsArrayCheckSeleccion($valor){

		if(!isset($this->ArraySeleccion)) return '';
		if($this->ArraySeleccion == '') return '';
		
		$valor = trim($valor);

		if(in_array($valor, $this->ArraySeleccion)){			
			return true;
		}
		else
			return false;

	}

	private function FormatExtraConditions($sql) {
		// Método que reemplaza las ocurrencias "_EXC1_", "_EXC2_", "_EXCn_" por las extra conditions..

		for ($i=0; $i<count($this->extraConditions); $i++)
			$sql = str_replace("_EXC".($i + 1)."_", StringToUpper($this->extraConditions[$i]), $sql);

		return $sql;
	}

	private function FormatFields($fields) {
		// Método encargado de formatear los campos para que funcione el query..

		// Meto los campos en un array para poder parsearlo bien..
		$arrFields = explode("¿", trim($fields));
		for ($i=1;$i<count($arrFields);$i++)
			$arrFields[$i] = substr($arrFields[$i], 0, strrpos($arrFields[$i], "?"));
		array_shift($arrFields);

		return implode(",", $arrFields);
	}

	private function FormatOrderBy($sql) {
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

	private function GetBajaCondition() {
		// Función que devuelve la condición para filtrar los registros dados de baja..

		$bajas = "";
		if ($this->GetColBaja() != -1)		// Si está configurada cual es la columna de baja..
			if (!$this->showBajas)
				$bajas = " AND ".$this->fieldBaja." IS NULL ";

		return $bajas;
	}

	private function GetColBaja() {
		// Devuelve el índice en el array de columnas de la primer columna marcada como columna de baja..

		$i = 0;
		foreach ($this->columns as $col) {
			if ($col->getDeletedRow())
				return $i;
			$i++;
		}

		return -1;
	}

	private function getHideCell($row, $col) {
		// Método que devuelve si la celda de la columa pasada como parámetro tiene que ocultarse o no..

		for ($i=0; $i<count($row); $i++) {
			$num = $this->columns[$i]->getNumCellHide();
			if (($num > 0) and ($num == $col) and ($row[$i] == "T"))		// Si hay una celda que ocultar y es la de la columna pasada como parámetro y hay que ocultar esta celda..
				return true;
		}

		return false;
	}

	public function GetRecordTotal($sql, $params) {
		global $conn;
		$sqlCount = "SELECT COUNT(*) FROM(".$sql.")";		
		$sqlCount = str_replace("?", "", str_replace("¿", "", $sqlCount));
		$this->stmt = DBExecSql($conn, $sqlCount, $params);
		$row = DBGetQuery($this->stmt, 0);
		return $row[0];		
	}
	
	private function GetRecordCount() {
		// Método que calcula la cantidad de registros que devuelve el query..

		global $conn;

		$sqlCount = "SELECT COUNT(*) FROM(".$this->FormatExtraConditions($this->sql).$this->GetBajaCondition().")";
		$sqlCount = str_replace("?", "", str_replace("¿", "", $sqlCount));

		$this->stmt = DBExecSql($conn, $sqlCount, $this->params);
		$row = DBGetQuery($this->stmt, 0);
		$this->recordCount = $row[0];
		$this->totalPages = ceil($row[0] / $this->rowsPerPage);
	}

	private function PrepareSql() {
		// Método que prepara el query..

		global $conn;

		$rowFrom = (($this->pageNumber - 1) * $this->rowsPerPage) + 1;
		$rowTo = $rowFrom + $this->rowsPerPage - 1;
		$fields = $this->FormatFields(substr($this->sql, strpos($this->sql, "SELECT ") + 5, strpos($this->sql, " FROM ") - 5));
		$iniSel = "SELECT * FROM (SELECT ROWNUM cols__,".$fields." FROM (";
		$finSel = ")) WHERE cols__ BETWEEN ".$rowFrom." AND ".$rowTo;

		$sqlFinal = $iniSel.$this->FormatOrderBy($this->FormatExtraConditions($this->sql).$this->GetBajaCondition()).$finSel;
		$sqlFinal = str_replace("?", "", str_replace("¿", "", $sqlFinal));

		$this->sqlFinal = $this->FormatOrderBy($this->FormatExtraConditions($this->sql).$this->GetBajaCondition());
		$this->sqlFinal = str_replace("?", "", str_replace("¿", "", $this->sqlFinal));

 		$this->stmt = DBExecSql($conn, $sqlFinal, $this->params);
	}

	private function SetOnClickVerOcultarBajas() {
		// Método que agrega a la url si se deben mostrar u ocultar las bajas al hacer clic en los botones de ver o ocultar bajas..

		if ($this->showBajas)
			$_REQUEST["sb"] = "F";
		else
			$_REQUEST["sb"] = "T";
	}

	private function writeJs() {
		// Método encargado de escribir el código javascript relacionado con la grilla..

		$this->code.= '<script src="/js/grid.js" type="text/javascript"></script>';
	}


	public function getShowProcessMessage() {
		return $this->showProcessMessage;
	}

	public function getSqlFinal($replaceParams = false) {
		// Devuelve el SQL final reemplazando los parámetros por los valores correspondientes, si correspondiera..
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

	public function setTextStyleLink($value) {
		$this->textStyleLink = $value;
	}

	public function setTitleStyle($value) {
		$this->titleStyle = $value;
	}

	public function setUnderlineSelectedRow($value) {
		$this->underlineSelectedRow = $value;
	}
	
	public function setStyleunderlineSelectedRow($value) {
		$this->StyleunderlineSelectedRow = $value;
	}

	public function setUseTmpIframe($value) {
		$this->useTmpIframe = $value;
	}
//--------------------------------------------------------	
	public function setFuncionAjaxJS($value) {
		$this->FuncionAjaxJS = $value;
	}
	
	public function setFuncionAjaxOrderByJS($value) {
		$this->FuncionAjaxOBJS = $value;
	}
		
	private function AddArrayChecks($usuario){		
		//if($this->CrearArray){
		$this->ArrayChecks[] = $usuario;								
		//}
	}
	
	public function GetArrayChecks(){							
		if($this->CrearArray){			
			return $this->ArrayChecks;
		}
		return false;
	}
	
	public function SetArrayColTitle($arrayCols) {
		$this->arrayColTitle = $arrayCols;
	}
	
	public function GetArrayColTitle() {
		return $this->arrayColTitle;
	}
	
	public function SetCurrentURL($value) {
		$this->CurrentURL = $value;
	}
	
	public function GetCurrentURL() {
		return $this->CurrentURL;
	}
	
	public function GetArrayChecksJS(){		
		$result=' <script type="text/javascript"> ';
		$result.=' var recordCount = '.$this->recordCount.'; ';
				
		/*
		$result.=' var ArraysUsuariosPermisosGrid = [';
		$values = '';		
		
		$arrayvalores = $this->GetArrayChecks();
				
		if(is_array($arrayvalores)){
			foreach($arrayvalores as $val){
				if($values == '') $values=' "'.$val.'" ';
				else $values.=', "'.$val.'" ';
			}
		}
		else $values=$arrayvalores;
		
		$result.=$values.']; ';						
		*/		
		$result.=" </script> ";
		return $result;
	}
//--------------------------------------------------------	
	public function setAddRecordInsertData($arrayValue) {
		$this->AddRecordInsertData = $arrayValue;
	}
	private function isEditMode(){
		// si el array de controles de edicion tiene datos entonces esta en modo edicion 
		if(isset($this->AddRecordInsertData) and  count($this->AddRecordInsertData) > 0 )
			return true;
		return false;
	}
	
	public function setBackGroundColumns($arrayValue, $styleBack, $fieldNameNum) {
		//array de id de las columnas que se deben diferenciar
		$this->ChangeBackGroundColumns = $arrayValue;
		$this->ChangeBackGroundStyle = $styleBack;	
		$this->ChangeBackGroundFieldNum = $fieldNameNum;	
	}
	
	private function ChangeBackGround($row){
		$ChangeBackGround = false;
		if(isset($this->ChangeBackGroundColumns) ){															
			$valueCompare = $row[$this->ChangeBackGroundFieldNum+1];					
						
			if (in_array($valueCompare, $this->ChangeBackGroundColumns)) {				
				$ChangeBackGround = true;
			}
		}
		return $ChangeBackGround;
	}
	
	public function IsLinkCell($valor){
		$resultado = BuscaSubStr($valor, '_LINK_');
		// EscribirLogTxt1("IsLinkCell", $resultado );
		if($resultado)
			return true;
	}

	public function ExtraeTextoCell($valor, $removeText){
		$resultado = str_replace($removeText,'',$valor);				
		return trim($resultado);
	}

	public function GetLastID(){
		return $this->LastID;
	}
	
	public function SetFooterSelected($value) {
		$this->textStyleFooterSelected  = $value;
	}
	
	public function SetStyleCellText($value) {
		$this->StyleCellText  = $value;
	}
	
}
