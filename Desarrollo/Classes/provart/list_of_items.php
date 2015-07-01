<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/item_list.php");

/*
Clase preparada para mostrar una lista de items. Se configura pasándole una serie de parámetros al constructor..

La forma básica de uso sería como muestra el ejemplo de abajo:

$listOfItems = new ListOfItems("descargables/seminarios_delegaciones/2008/material_adicional/", ":: Material Adicional");
$listOfItems->addItem(new ItemList("Enfermedades_Profesionales.ppt", "Enfermedades Profesionales", "_blank", true));
$listOfItems->draw();
*/

class ListOfItems {
	private $cols = 2;		// Cantidad de columnas que va a tener la lista..
	private $colsWidth = -1;		// Indica el ancho que van a tener las columnas..
	private $dir = "";		// Ruta donde van a estar los links de los items..
	private $imagePath = "";		// Ruta donde se encuentra la imagen asociada a cada item..
	private $imagePosition = "OUT";		// Si es OUT se muestra en una celda separada con respecto al texto,
																		// si es IN se muestra en la misma celda..
	private $items = array();		// Conjunto de items a dibujar..
	private $itemsBorderStyle = "listaBordes";		// Estilo del borde de los items..
	private $itemsStyle = "listaItems";		// Estilo de los items..
	private $showImage = true;		// Indica si se debe mostrar una imagen al lado de cada item..
	private $showTitle = true;		// Indica si se debe mostrar el título o no..
	private $title = "";		// Título de la lista..
	private $titleAlign = "left";		// Posición horizontal del título de la lista..
	private $titleSeparatorStyle = "listaSeparador";		// Estilo que dibujará un separador entre el título y los items, generalmente va a ser una línea..
	private $titleStyle = "listaTitulo";		// Estilo del título..


	public function __construct($dir, $title = "") {
		// Constructor..

		$this->dir = $dir;
		$this->title = $title;
	}

	public function addItem($item) {
		// Método encargado de agregar un item a la lista de items..

		array_push($this->items, $item);
	}

	public function draw() {
		// Método encargado de dibujar la lista..

		if ($this->showTitle)
			$this->drawTitle();
		$this->drawItems();
	}

	private function drawItems() {
		// Método encargado de dibujar los items de la lista..
?>
		<table class="tableLista">
			<tr>
<?
		$i = 0;
		$totItems = count($this->items);
		$width = "";
		if ($this->colsWidth > -1)
			$width = $this->colsWidth;
		for ($iCols=1;$iCols<=$this->cols;$iCols++) {
?>
			<td align="left" valign="top">
				<table cellpadding="0" cellspacing="0" style="width:100%">
<?
			$bgColor = "";
			$iRows = 1;
			$maxRows = ceil(($totItems - $i) / ($this->cols - $iCols + 1));
			while ($iRows <= $maxRows) {
				if ($this->items[$i]->getTarget() == "_self")
					$onClick = "window.location.href = '".$this->items[$i]->getLink($this->dir)."'";
				else
					$onClick = "window.open('".$this->items[$i]->getLink($this->dir)."', '".$this->items[$i]->getTarget()."')";
				$onClick = "onClick=\"".$onClick."\"";

				$bgColor = ($bgColor == "dadada")?"f6f6f6":"dadada";
				if (!$this->showImage) {
?>
					<tr bgcolor="#<?= $bgColor?>" class="fondoOnMouseOver" <?= $onClick?>>
						<td align="left" class="<?= $this->itemsBorderStyle?>" width="<?= $width?>"><span class="<?= $this->itemsStyle?>"><?= $this->items[$i]->getTitle()?></span></td>
					</tr>
<?
				}
				elseif ($this->imagePosition == "OUT") {
?>
					<tr bgcolor="#<?= $bgColor?>" class="fondoOnMouseOver" <?= $onClick?>>
						<td style="border-bottom:1px solid #fff;" width="1"><img src="<?= $this->imagePath?>" /></td>
						<td align="left" class="<?= $this->itemsBorderStyle?>" width="<?= $width?>"><span class="<?= $this->itemsStyle?>"><?= $this->items[$i]->getTitle()?></span></td>
					</tr>
<?
				}
				elseif ($this->imagePosition == "IN") {
?>
					<tr bgcolor="#<?= $bgColor?>" class="fondoOnMouseOver" <?= $onClick?>>
						<td align="left" class="<?= $this->itemsBorderStyle?>" width="<?= $width?>"><span class="<?= $this->itemsStyle?>"><img src="<?= $this->imagePath?>" /><?= $this->items[$i]->getTitle()?></span></td>
					</tr>
<?
				}
				$i++;
				$iRows++;
			}
?>
				</table>
			</td>
<?
		}
?>
			</tr>
		</table>
<?
	}

	private function drawTitle() {
		// Método encargado de dibujar el título de la lista..
?>
		<table class="tableLista">
			<tr>
				<td align="<?= $this->titleAlign?>" class="<?= $this->titleSeparatorStyle?> <?= $this->titleStyle?>"><?= $this->title?></td>
			</tr>
		</table>
<?
	}


	public function setCols($value) {
		$this->cols = $value;
	}

	public function setColsWidth($value) {
		$this->colsWidth = $value;
	}

	public function setImagePath($value) {
		$this->imagePath = $value;
	}

	public function setImagePosition($value) {
		$this->imagePosition = $value;
	}

	public function setItemsBorderStyle($value) {
		$this->itemsBorderStyle = $value;
	}

	public function setItemsStyle($value) {
		$this->itemsStyle = $value;
	}

	public function setShowImage($value) {
		$this->showImage = $value;
	}

	public function setShowTitle($value) {
		$this->showTitle = $value;
	}

	public function setTitleAlign($value) {
		$this->titleAlign = $value;
	}

	public function setTitleSeparatorStyle($value) {
		$this->titleSeparatorStyle = $value;
	}

	public function setTitleStyle($value) {
		$this->titleStyle = $value;
	}
}
?>