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
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<title>Descargables</title>
	</head>
	<body link="#00539B" vlink="#00539B" alink="#00539B">
		<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."descargables/otros/", ":: Otros");
$list->addItem(new ItemList("manual_de_redaccion_corporativa.pdf", "Manual de Redacción Corporativa", "_blank", true));
$list->addItem(new ItemList("/index.php?pageid=37&fldr=otros/hospedaje_para_comisiones_de_servicio/index.php", "Hospedaje para Comisiones de Servicio", "_self", false, true));
$list->addItem(new ItemList("Ventanilla_Electronica_2010.pdf", "Manual de Uso Ventanilla Electrónica", "_blank", true));
$list->setColsWidth(320);
$list->setImagePath("/modules/descargables/download.bmp");
$list->draw();
?>
		</div>
		<p>&nbsp;</p>
		<p align="center"><a href="index.php?pageid=37" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>
	</body>
</html>