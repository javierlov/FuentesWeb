<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/Provart/ListOfItems.php");
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
		<script language="JavaScript" src="/js/efects.js"></script>
	</head>
	<body link="#00539B" vlink="#00539B" alink="#00539B">
		<div align="center">
<?
$list = new ListOfItems(STORAGE_PATH."descargables/material_web/", ":: Material de la Web");
$list->addItem(new ItemList("Folleto_WEB_diptico.pdf", "Folleto Web Díptico", "_blank", true));
$list->addItem(new ItemList("Form_Solicitud_Alta_Web.doc", "Formulario Solicitud Alta Web", "_blank", true));
$list->addItem(new ItemList("Manual_Central_de_Servicios_en_linea.pdf", "Manual Central de Servicios en Línea", "_blank", true));
$list->setColsWidth(320);
$list->setImagePath("/modules/descargables/icono_descargable.jpg");
$list->draw();
?>
		</div>
		<p>&nbsp;</p>
		<p align="center"><a href="index.php?pageid=37" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>
	</body>
</html>