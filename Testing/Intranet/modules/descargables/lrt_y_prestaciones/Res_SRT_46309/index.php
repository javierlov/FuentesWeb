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
$list = new ListOfItems(STORAGE_PATH."descargables/lrt_y_prestaciones/", ":: Res SRT 463/09");
$list->addItem(new ItemList("Res463_09AnexoI.pdf", "Anexo I", "_blank", true));
$list->addItem(new ItemList("Res463_09AnexoIbis.pdf", "Anexo I bis", "_blank", true));
$list->addItem(new ItemList("Res463_09AnexoII.pdf", "Anexo II", "_blank", true));
$list->addItem(new ItemList("Res463_09AnexoIII.pdf", "Anexo III", "_blank", true));
$list->addItem(new ItemList("Res_463-09.doc", "Res. 463/09", "_blank", true));
$list->addItem(new ItemList("Res_529-09.pdf", "Res. 529/09", "_blank", true));
$list->setColsWidth(320);
$list->setImagePath("/modules/descargables/download.bmp");
$list->draw();
?>
		</div>
		<p>&nbsp;</p>
		<p align="center"><a href="/index.php?pageid=37&fldr=lrt_y_prestaciones/lrt_y_prestaciones.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>
	</body>
</html>