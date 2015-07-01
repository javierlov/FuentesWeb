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
<!--		<script language="JavaScript" src="/js/efects.js"></script>-->
	</head>
	<body link="#00539B" vlink="#00539B" alink="#00539B">
		<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."descargables/logos/", ":: Logos");
$list->addItem(new ItemList("Logo-PART.jpg", "Logo Provincia ART", "_blank", true));
$list->addItem(new ItemList("Logo-PART_ByN.jpg", "Logo Provincia ART ByN", "_blank", true));
$list->addItem(new ItemList("Logo-FPART.jpg", "Logo Fundación Provincia ART", "_blank", true));
$list->addItem(new ItemList("Logo-GBP.jpg", "Logo Grupo Banco Provincia", "_blank", true));
$list->addItem(new ItemList("Logo-BP.jpg", "Logo Banco Provincia", "_blank", true));
$list->setColsWidth(320);
$list->setImagePath("/modules/descargables/download.bmp");
$list->draw();
?>
		</div>
		<p>&nbsp;</p>
		<p align="center"><a href="index.php?pageid=37" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>
	</body>
</html>