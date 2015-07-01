<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Description" content="Intranet de Provincia ART" />
		<meta name="Language" content="Spanish" />
		<meta name="Subject" content="Intranet" />
		<title>Descargables</title>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
	</head>
	<body link="#00539B" vlink="#00539B" alink="#00539B">
		<div align="center">
<?
$list = new ListOfItems(STORAGE_PATH."descargables/otros/hospedaje_para_comisiones_de_servicio/", ":: Hospedaje para Comisiones de Servicio");
$list->addItem(new ItemList("Encuesta_hospedaje.doc", "Encuesta Hospedaje", "_blank", true));
$list->addItem(new ItemList("Hoteles_Ene2010.xls", "Hoteles Enero 2010", "_blank", true));
$list->setColsWidth(320);
$list->setImagePath("/modules/descargables/download.bmp");
$list->draw();
?>
		</div>
		<p>&nbsp;</p>
		<p align="center"><a href="/index.php?pageid=37&fldr=otros/otros.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>
	</body>
</html>