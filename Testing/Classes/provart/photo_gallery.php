<?
/*
Clase preparada para dibujar una galería de fotos. Se configura pasandole una serie de parámetros al constructor..

La forma básica de uso sería como muestra el ejemplo de abajo:

$sql = "SELECT ¿se_nombre?, ¿se_usuario?, ¿se_mail? FROM use_usuarios";
$photoGallery = new PhotoGallery(3, "X:\Fotos", 250, 6);
$photoGallery->Draw();
$photoGallery->setPageNumber($pagina);
*/

class PhotoGallery {
	private $cols = 1;		// Cantidad de columnas que va a tener la galería..
	private $dir = "";		// Ruta de donde se van a leer las fotos..
	private $emptyStyle = "GridEmpty";
	private $footerStyle = "GridFooter";		// Estilo del footer..
	private $gridWidth = 400;		// Ancho que va a tener la galería..
	private $imageStyle = "PhtoGalleryImage";		// Estilo de las imagenes..
	private $pageNumber = 1;		// Número de página actual..
	private $photos = array();		// Nombre de las fotos que se encuentran en la ruta definida..
	private $rows = 1;		// Cantidad de filas que va a tener la galería..
	private $title = "Galería de Fotos";		// Título de la página..
	private $titleStyle = "PhotoGalleryTitle";		// Estilo del título..
	private $totalPages = 0;		// Cantidad de páginas que tiene la galería..
	private $wishDrawOnlyContent = true;		// Indica si se desea escribir solo el contenido de la galería, sin la sección HEAD, ni los tags HTML, BODY, etc..


	public function __construct($cols, $dir, $gridWidth, $rows) {
		// Constructor..

		$this->cols = $cols;
		$this->dir = $dir;
		$this->gridWidth = $gridWidth;
		$this->rows = $rows;

		$this->GetPhotos();

		$this->totalPages = ceil(count($this->photos) / ($this->cols * $this->rows));
	}

	public function BuildUrl($page) {
		// Método encargado de armar la url asociada a los links de las páginas..

		$_REQUEST["pagina"] = $page;

		$result = $_SERVER["PHP_SELF"]."?";
		foreach ($_REQUEST as $key => $value)
			$result.= $key."=".$value."&";

		return AddSlashes($result);
	}

	public function Draw() {
		// Método encargado de dibujar la galería..

  	if (count($this->photos) == 0)
  		$this->DrawMessageNoResults();
		else {
			if (!$this->wishDrawOnlyContent) {
				echo '<html>';
				$this->DrawHead();
			}
			$this->DrawBody();
			if (!$this->wishDrawOnlyContent)
				echo '</html>';
  	}
	}

	private function DrawBody() {
		// Método encargado de escribir la sección BODY..

		if (!$this->wishDrawOnlyContent) {
?>
			<body alink="#336699" link="#336699" vlink="#336699">
			<div align="center">
<?
		}
?>
		<table border="0" cellpadding="0" cellspacing="3" width="100%">
			<tr>
				<td>
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td class="<?= $this->titleStyle?>"><?= $this->title?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center">
					<table border="5" cellpadding="0" cellspacing="0" width="<?= $this->gridWidth?>">
						<tr>
							<td>
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		$this->DrawImages();
?>
								</table>
							</td>
						</tr>
						<tr>
							<td>
<?
		$this->DrawFooter();
?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
<?
		if (!$this->wishDrawOnlyContent) {
?>
		</div>
		</body>
<?
		}
	}

	private function DrawFooter() {
		// Método encargado de dibujar el pie de la galería..
?>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr class="<?= $this->footerStyle?>">
				<td bgcolor="#FFFFFF" style="border-right: 1px solid #FFFFFF"></td>
				<td align="center">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="800"></td>
<?
		$fotosPorPagina = $this->rows * $this->cols;
		$bloque = ceil($this->pageNumber / $fotosPorPagina);
		if ($bloque > 1) {
			$url = $this->BuildUrl(($bloque - 1) * $fotosPorPagina);
?>
			<td align="center"><a class="GridFooterFont" href="#" onClick="window.location.href='<?= $url?>'"><<</a></td>
			<td>&nbsp;&nbsp;</td>
<?
		}

		for ($i = ((($bloque - 1) * $fotosPorPagina) + 1); $i <= ($bloque * $fotosPorPagina); $i++) {
			if ($i > $this->totalPages)
				break;

			if ($i == $this->pageNumber) {
?>
				<td align="center" class="GridFooterFontSelected"><b><?= $i?></b></td>
<?
			}
			else {
				$url = $this->BuildUrl($i);
?>
				<td align="center"><a class="GridFooterFont" href="#" onClick="window.location.href='<?= $url?>'"><?= $i?></a></td>
<?
			}
?>
			<td width="12"></td>
<?
		}

		if ($bloque < (ceil($this->totalPages / $fotosPorPagina))) {
			$url = $this->BuildUrl(($bloque * $fotosPorPagina) + 1);
?>  
			<td>&nbsp;&nbsp;</td>
			<td align="center"><a class="GridFooterFont" href="#" onClick="window.location.href='<?= $url?>'">>></a></td>
<?
		}
?>
							<td align="right" width="800"></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
<?
	}

	private function DrawHead() {
		// Método encargado de escribir la sección HEAD..
?>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
			<meta name="Author" content="Gerencia de Sistemas">
			<meta name="Description" content="Intranet de Provincia ART">
			<meta name="Language" content="Spanish">
			<meta name="Subject" content="Intranet">
			<title><?= $this->title?></title>
			<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		</head>
<?
	}

	private function DrawImages() {
		// Método encargado de dibujar las imágenes..

		// Creo el arreglo en javascript para guardar las imágenes en el visor..
		$arrImagenes = "arrVisorImagenes = new Array(";
		foreach ($this->photos as $value)
			$arrImagenes.= "'".base64_encode($this->dir.$value)."',";
		$arrImagenes = substr($arrImagenes, 0, -1);
		$arrImagenes.= ");";

		$index = ($_REQUEST["pagina"] - 1) * ($this->cols * $this->rows);
		for ($i=1;$i<=$this->rows;$i++) {
			echo "<tr>";
			for ($j=1;$j<=$this->cols;$j++)
				if (isset($this->photos[$index])) {
					$linkPhoto = "mostrarImagen(".$index.");";

					$width = floor(($this->gridWidth - 6) / $this->cols);
					$urlPhoto = "/functions/get_image.php?file=".base64_encode($this->dir.$this->photos[$index])."&width=".$width;

					echo '<td align="center" class="'.$this->imageStyle.'" valign="middle"><a href="#" onClick="'.$linkPhoto.'"><img border="0" src="'.$urlPhoto.'"></a></td>';
					$index++;
				}
				else
					echo "<td class='".$this->imageStyle."' width='".$width."'>&nbsp;</td>";
			echo "</tr>";
		}

		// Muestro el arreglo en javascript..
		echo "<script type='text/javascript'>".$arrImagenes."</script>";
	}

	private function DrawMessageNoResults() {
		// Método encargado de dibujar el mensaje de que no se encontraron resultados..
?>
		<div align="center" id="originalGrid" name="originalGrid">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center"><input class="<?= $this->emptyStyle?>" type="button"></td>
				</tr>
			</table>
		</div>
<?
	}

	private function GetPhotos() {
		// Método que obtiene las fotos del directorio base..

		if (is_dir($this->dir))
			if ($gd = opendir($this->dir)) {
				while (($photo = readdir($gd)) !== false)
					if (($photo != ".") and ($photo != "..") and ($photo != "Thumbs.db"))
						array_push($this->photos, $photo);
				closedir($gd);
			}
	}


	public function setEmptyStyle($value) {
		$this->emptyStyle = $value;
	}

	public function setFooterStyle($value) {
		$this->footerStyle = $value;
	}

	public function setImageStyle($value) {
		$this->imageStyle = $value;
	}

	public function setPageNumber($value) {
		$this->pageNumber = $value;
	}

	public function setTitle($value) {
		$this->title = $value;
	}

	public function setTitleStyle($value) {
		$this->titleStyle = $value;
	}

	public function setWishDrawOnlyContent($value) {
		$this->wishDrawOnlyContent = $value;
	}
}
?>