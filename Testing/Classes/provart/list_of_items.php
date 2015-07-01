<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/item_list.php");

/*
Clase preparada para mostrar una lista de items. Se configura pas�ndole una serie de par�metros al constructor..

La forma b�sica de uso ser�a como muestra el ejemplo de abajo:

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
	private $itemsBorderStyle = "Recuadro";		// Estilo del borde de los items..
	private $itemsStyle = "FormLabelBlanco11";		// Estilo de los items..
	private $showImage = true;		// Indica si se debe mostrar una imagen al lado de cada item..
	private $showTitle = true;		// Indica si se debe mostrar el t�tulo o no..
	private $title = "";		// T�tulo de la lista..
	private $titleAlign = "left";		// Posici�n horizontal del t�tulo de la lista..
	private $titleSeparatorStyle = "LineaHorizontalPunteada";		// Estilo que dibujar� un separador entre el t�tulo y los
																															// items, generalmente va a ser una l�nea..
	private $titleStyle = "FormLabelAzulCabecera";		// Estilo del t�tulo..


	public function __construct($dir, $title = "") {
		// Constructor..

		$this->dir = $dir;
		$this->title = $title;
	}

	public function addItem($item) {
		// M�todo encargado de agregar un item a la lista de items..

		array_push($this->items, $item);
	}

	public function draw() {
		// M�todo encargado de dibujar la lista..

		if ($this->showTitle)
			$this->drawTitle();
		$this->drawItems();
	}

	private function drawItems() {
		// M�todo encargado de dibujar los items de la lista..
?>
		<table border="0" cellpadding="0" cellspacing="0" width="760">
			<tr>
<?
		$i = 0;
		$totItems = count($this->items);
		$width = "";
		if ($this->colsWidth > -1)
			$width = $this->colsWidth;
		for ($iCols=1;$iCols<=$this->cols;$iCols++) {
?>
			<td align="center" valign="top">
				<table border="0" cellpadding="0" cellspacing="0">
<?
			$iRows = 1;
			$maxRows = ceil(($totItems - $i) / ($this->cols - $iCols + 1));
			while ($iRows <= $maxRows) {
				if (!$this->showImage) {
?>
					<tr>
						<td align="left" class="<?= $this->itemsBorderStyle?> FondoOnMouseOver" width="<?= $width?>"><a href="<?= $this->items[$i]->getLink($this->dir)?>" style="text-decoration: none" target="<?= $this->items[$i]->getTarget()?>"><span class="<?= $this->itemsStyle?>">&nbsp;<?= $this->items[$i]->getTitle()?>&nbsp;</span></a></td>
					</tr>
<?
				}
				elseif ($this->imagePosition == "OUT") {
?>
					<tr>
						<td align="left" width="24"><a href="<?= $this->items[$i]->getLink($this->dir)?>" target="<?= $this->items[$i]->getTarget()?>"><img border="0" src="<?= $this->imagePath?>"></a></td>
						<td align="left" class="<?= $this->itemsBorderStyle?> FondoOnMouseOver" width="<?= $width?>"><a href="<?= $this->items[$i]->getLink($this->dir)?>" style="text-decoration: none" target="<?= $this->items[$i]->getTarget()?>"><span class="<?= $this->itemsStyle?>">&nbsp;<?= $this->items[$i]->getTitle()?>&nbsp;</span></a></td>
					</tr>
<?
				}
				elseif ($this->imagePosition == "IN") {
?>
					<tr>
						<td align="left" class="<?= $this->itemsBorderStyle?> FondoOnMouseOver" width="<?= $width?>"><a href="<?= $this->items[$i]->getLink($this->dir)?>" style="text-decoration: none" target="<?= $this->items[$i]->getTarget()?>"><span class="<?= $this->itemsStyle?>">&nbsp;<img border="0" src="<?= $this->imagePath?>">&nbsp;<?= $this->items[$i]->getTitle()?>&nbsp;</span></a></td>
					</tr>
<?
				}
				$i++;
				$iRows++;
			}
?>
				</table>
			</td>
			<td height="1" width="16"></td>
<?
		}
?>
			</tr>
		</table>
<?
	}

	private function drawTitle() {
		// M�todo encargado de dibujar el t�tulo de la lista..
?>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="<?= $this->titleAlign?>" class="<?= $this->titleSeparatorStyle?> <?= $this->titleStyle?>"><?= $this->title?></td>
			</tr>
			<tr>
				<td height="8"></td>
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