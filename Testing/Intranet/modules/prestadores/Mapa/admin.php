<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=windows-1252">
	<title>Mantenimiento del Mapa Interactivo</title>
	
	<!-- Estilos -->
	<link href="css/sitio.css" rel="stylesheet" type="text/css" />
	<link href="css/abm.css" rel="stylesheet" type="text/css" />

	<!-- MooTools -->
	<script type="text/javascript" src="js/mootools-1.2.3-core.js"></script>
	<script type="text/javascript" src="js/mootools-1.2.3.1-more.js"></script>
	
	<!--FormCheck-->
	<script type="text/javascript" src="js/formcheck/lang/es.js"></script>
	<script type="text/javascript" src="js/formcheck/formcheck.js"></script>
	<link rel="stylesheet" href="js/formcheck/theme/classic/formcheck.css" type="text/css" media="screen" />

	<!--Datepicker-->
	<link rel="stylesheet" href="js/datepicker/datepicker_vista/datepicker_vista.css" type="text/css" media="screen" />
	<script type="text/javascript" src="js/datepicker/datepicker.js"></script>

</head>
<body>

<?
require("comun/class_db.php");
require("comun/class_abm.php");
require("comun/class_paginado.php");
require("comun/class_orderby.php");

ini_set('error_reporting', E_ALL ^ E_NOTICE ^ E_DEPRECATED);

//conexión a la bd
$db = new class_db("192.168.0.194", "user_ipboard", "ipAstonMartin..00", "ipboard");
$db->mostrarErrores = true;
$db->connect();


$abm = new class_abm();
$abm->tabla = '_mapa'; 
$abm->campoId = 'id'; 
$abm->orderByPorDefecto = 'id'; 
$abm->registros_por_pagina = 50; 
$abm->textoTituloFormularioAgregar = "Agregar"; 
$abm->textoTituloFormularioEdicion = "Editar"; 
$abm->campos = array( 
	array('campo' => 'tipo',
	      'tipo' => 'combo',
              'requerido' => true,
              'datos' => array("0"=>"Sin seleccionar", "1"=>"Taller", "2"=>"Neumáticos/Llantas", "3"=>"Lubricentro", "4"=>"Alfa Care", "5"=>"Concecionaria", "6"=>"Audio", "7"=>"Casa de Repuestos"),
              'maxLen' => 1,
              'titulo' => 'Tipo'
	),
	array('campo' => 'nombre',
		'tipo' => 'texto',
		'requerido' => true,
		'maxLen' => 255,
		'titulo' => 'Nombre',
		'hint' => 'Nombre de la empresa'
	),
	array('campo' => 'lat',
		'tipo' => 'texto',
		'requerido' => true,
		'titulo' => 'Latitud',
		'hint' => 'Latitud de acuerdo a Google Maps'
	),
	array('campo' => 'lng',
		'tipo' => 'texto',
		'requerido' => true,
		'titulo' => 'Longitud',
		'hint' => 'Longitud de acuerdo a Google Maps'
	),
	array('campo' => 'direccion',
		'tipo' => 'texto',
		'maxLen' => 255,
		'titulo' => 'Domicilio',
		'hint' => 'Bien escrito por favor, de acuerdo a Google Maps'
	),
	array('campo' => 'telefonos',
		'tipo' => 'texto',
		'maxLen' => 255,
		'titulo' => 'Teléfonos'
	),
	array('campo' => 'info_extra',
		'tipo' => 'texto',
		'maxLen' => 255,
		'titulo' => 'Información adicional',
		'hint' => 'Web: ... Correo: .... / Otra información relevante'
	),
	array('campo' => 'url',
		'tipo' => 'texto',
		'maxLen' => 255,
		'titulo' => 'URL del Foro'
	)
);
$abm->generarAbm('', 'Administrar Entidades');

echo "<br><br>";

if ( $_GET['vercodigo'] ){
	highlight_file(__FILE__);
}else{
	echo "<a href='?vercodigo=1'>Ver código fuente</a>";
}
?>

</body>
</html>