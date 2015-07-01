<?
/*
Clase utilizada dentro de la clase ListOfItems para asociar items con la lista que los crea..

La forma básica de uso sería como muestra el ejemplo de abajo:

$itemList = new ItemList("Enfermedades_Profesionales.ppt", "Enfermedades Profesionales", "_blank", true);
*/

class ItemList {
	private $absolutePath;		// Si es true indica que la ruta del link es absoluta y no se debería tener en
														// cuenta la propiedad $dir de la clase padre (List)..
	private $encode;		// Si es true se va a encodear el link, si es false no..
	private $link = "";		// Link al que se accederá al hacer clic sobre el título..
	private $target = "";		// Indica el target donde se abrirá la página a la que apunta el link..
	private $title = "";		// Título del item..


	public function __construct($link, $title, $target = "_self", $encode = false, $absolutePath = false) {
		// Constructor..

		$this->absolutePath = $absolutePath;
		$this->encode = $encode;
		$this->link = $link;
		$this->target = $target;
		$this->title = $title;
	}


	public function getLink($dir) {
		if ($this->absolutePath)
			$dir = "";

		if ($this->encode)
			return "/archivo/".base64_encode($dir.$this->link);
		else
			return $dir.$this->link;
	}

	public function getTarget() {
		return $this->target;
	}

	public function getTitle() {
		return $this->title;
	}
}
?>