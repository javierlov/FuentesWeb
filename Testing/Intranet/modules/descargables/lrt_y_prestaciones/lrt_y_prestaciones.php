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
		<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."descargables/lrt_y_prestaciones/", ":: LRT y Prestaciones");
$list->addItem(new ItemList("Baremo_Dec_659_96.pdf", "Baremo Dec. 659/96", "_blank", true));
$list->addItem(new ItemList("658_96_Listado_EP.pdf", "Dec. 658/96 Listado de EP", "_blank", true));
$list->addItem(new ItemList("decreto_1694_2009.pdf", "Decreto 1694/2009", "_blank", true));
$list->addItem(new ItemList("Formulario_de_alta.pdf", "Formulario de Alta", "_blank", true));
$list->addItem(new ItemList("Formulario_cese_de_ilt.pdf", "Formulario de Cese de ILT", "_blank", true));
$list->addItem(new ItemList("formulario_denuncia.pdf", "Formulario de Denuncia", "_blank", true));
$list->addItem(new ItemList("GLOSARIO_ART.doc", "Glosario", "_blank", true));
$list->addItem(new ItemList("Instructivo_Reintegros.doc", "Instructivo Reintegros", "_blank", true));
$list->addItem(new ItemList("Ley_24557.htm", "Ley de Riesgos del Trabajo", "_blank", true));
$list->addItem(new ItemList("Manual_del_Sistema_de_Ventanilla.doc", "Manual Ventanilla Electrónica", "_blank", true));
$list->addItem(new ItemList("Modulador.pdf", "Modulador", "_blank", true));
$list->addItem(new ItemList("plataforma_10_autoemision.pdf", "Plataforma 10: Autoemisión", "_blank", true));
$list->addItem(new ItemList("plataforma_10_corporativo.doc", "Plataforma 10: Corporativo", "_blank", true));
$list->addItem(new ItemList("Presentacion_Liquidaciones.ppt", "Prestación - Liquidaciones", "_blank", true));
$list->addItem(new ItemList("/index.php?pageid=37&fldr=lrt_y_prestaciones/Res_SRT_46309/index.php", "Res. SRT 463/09", "_self", false, true));
$list->addItem(new ItemList("resolucion_1240-2010.doc", "Resolución 1240/2010", "_blank", true));
$list->addItem(new ItemList("tabladecodigos.pdf", "Tabla de Códigos", "_blank", true));
$list->addItem(new ItemList("Ley _26773.pdf", "Ley 26.773", "_blank", true));

$list->setColsWidth(328);
$list->setImagePath("/modules/descargables/download.bmp");
$list->draw();
?>
		</div>
		<p>&nbsp;</p>
		<p align="center"><a href="index.php?pageid=37" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>
	</body>
</html>