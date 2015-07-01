<?
require_once ($_SERVER["DOCUMENT_ROOT"] . "/constants.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/database/db.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/database/db_funcs.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Classes/provart/grid.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/miscellaneous/general.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/modules/gestion_sistemas/ticket_funciones.php");
session_start();

function GetRequest($parametro) {
    if (isset($_REQUEST[$parametro]))
        return $_REQUEST[$parametro];

    return false;
}
/*
echo "<script type='text/javascript' src='/modules/gestion_sistemas/js/ticket_functions.js'></script> ";
echo "<script type='text/javascript' src='/modules/gestion_sistemas/js/ajaxBuscaDatos.js'></script> ";
*/
if (!isset($_SESSION['OPCIONMENU']))
    $_SESSION['OPCIONMENU'] = 0;

$_SESSION['CANTIDADBOTONES'] = 4;
$paginas = $_SESSION['CANTIDADBOTONES'];
$tienePersACargo = false;

$showProcessMsg = true;
/* Implementación de múltiples sistemas dentro del sistema de tickets */
if (isset($_REQUEST["sistema"]))
    $sistema = $_REQUEST["sistema"];
else
    $sistema = 1;

if ($sistema == 1) {
    $usuarioActual = GetUsuarioAplicacion();
    $tienePersACargo = TienePersonalACargo($usuarioActual);
}

$all_tickets = "no";
if (isset($_REQUEST["all_tickets"]))
    $all_tickets = $_REQUEST["all_tickets"];

$pending_tickets = "no";
if (isset($_REQUEST["pending_tickets"]))
    $pending_tickets = $_REQUEST["pending_tickets"];

$pending_moreinfo_tickets = "no";
if (isset($_REQUEST["pending_moreinfo_tickets"]))
    $pending_moreinfo_tickets = $_REQUEST["pending_moreinfo_tickets"];

$pending_auth_tickets = "no";
if (isset($_REQUEST["pending_auth_tickets"]))
    $pending_auth_tickets = $_REQUEST["pending_auth_tickets"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
    $pagina = $_REQUEST["pagina"];

//-------------------------------------------------------------
$estiloSeleccion = "";
$estiloSeleccion_newticket = "";
$estiloSeleccion_pedidosactuales = "";
$estiloSeleccion_historialpedidos = "";
$estiloSeleccion_planaccion = "";
$estiloSeleccion_permisos = "";
$estiloComun = "style='background: #f1f1f1; color:#000'";

if (isset($_REQUEST['MNU']))
    $_SESSION['OPCIONMENU'] = $_REQUEST['MNU'];

$menu = $_SESSION['OPCIONMENU'];
unset($_SESSION['OPCIONMENU']);

switch($menu) {
    case 1 :
        $estiloSeleccion = $estiloComun;
        break;
    case 2 :
        $estiloSeleccion_newticket = $estiloComun;
        break;
    case 3 :
        $estiloSeleccion_pedidosactuales = $estiloComun;
        break;
    case 4 :
        $estiloSeleccion_historialpedidos = $estiloComun;
        break;
    case 5 :
        $estiloSeleccion_planaccion = $estiloComun;
        break;
    case 6 :
        $estiloSeleccion_permisos = $estiloComun;
        break;
    default : { $estiloSeleccion = $estiloComun;
        $menu = 1;
    }
}

//-------------------------------------------------------------
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<!-- -->
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" >
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<meta meta http-equiv="expires" content="mon, 22 jul 2002 11:12:01 gmt" />
		<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="pragma" content="no-cache">
		<meta name="author" content="lovatto javier">
		<meta name="description" content="web tickets">

		<title>Provincia ART | Sistemas</title>

		<script type="text/javascript" src="js/interface.js"></script>
		<script type="text/javascript" src="/Js/functions.js"></script>
		<script type="text/javascript" src="/Js/validations.js"></script>
		<?php 	Intranet_JSjqueryUI(); ?>
		<!--
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="/Js/ajax.js" charset="iso-8859-1"></script>
		-->
		<script type="text/javascript" src="/Js/grid.js"></script>
		<script type="text/javascript" src="Js/ticket.js?rnd=<?=RandomNumber(); ?>" charset="iso-8859-1"></script>

		<script type="text/javascript" src="js/responsive-nav.js"></script>

		<link href="/modules/gestion_sistemas/styles/responsive-nav.css" rel="stylesheet" type="text/css">
		</link>
		<link href="/modules/gestion_sistemas/styles/styles_responsive.php?id=<? echo date('YmdHis'); ?>" rel="stylesheet" type="text/css">
		</link>
		<link href="styles/style_sistemas1.css?sid=<?=RandomNumber(); ?>" rel="stylesheet" type="text/css" />

	</head>
	<body class="bodywrapper"  >

		<div class="wrapperPage"  >
			<div class="wrapper" style="width:100%" >

				<div class="nav-collapse closed" >
					<ul>
						<li id="itemInicio" >

							<a href="#1" onclick="CargarPaginaHome('<? echo $sistema; ?> ' );" <? echo $estiloSeleccion; ?> >
							<div  class="centrar" >
								<img src="images/<?echo $sistema; ?>/home.png" alt="Inicio" title="Inicio" class="imagen_format" />
							</div>
							<div class="TextoBoton">
								INICIO
							</div></a>
						</li>

						<li class="itemlist" >

							<a href="#2" onclick="CargarPaginaRealizarPedido('<? echo $sistema; ?> ' );" <? echo $estiloSeleccion_newticket; ?> >
							<div  class="centrar" >
								<img src="images/<?echo $sistema; ?>/portfolio.png" alt="Realizar pedido" title="Realizar pedido" class="imagen_format" />
							</div>
							<div class="TextoBoton">
								REALIZAR PEDIDO
							</div></a>
						</li>

						<li class="itemlist" >
							<a  href="indexAJX.php?sistema=<?echo $sistema; ?>&MNU=3&search=yes&amp;all_tickets=no" name="PEDIDOSACTUALES" <? echo $estiloSeleccion_pedidosactuales; ?>>
							<div  class="centrar" >
								<img src="images/<?echo $sistema; ?>/calendar.png" alt="Pedidos actuales" title="Pedidos actuales" class="imagen_format" />
							</div>
							<div class="TextoBoton">
								PEDIDOS ACTUALES
							</div></a>
						</li>

						<li class="itemlist" >
							<a href="indexAJX.php?sistema=<?echo $sistema; ?>&MNU=4&search=yes&amp;all_tickets=yes"  <? echo $estiloSeleccion_historialpedidos; ?> >
							<div  class="centrar" >
								<img src="images/<?echo $sistema; ?>/history.png" alt="Historial de pedidos" title="Historial de pedidos" class="imagen_format" />
							</div>
							<div class="TextoBoton">
								HISTORIAL DE PEDIDOS
							</div></a>
						</li>

						<?PHP
    if ($tienePersACargo == 1) {
        $_SESSION['CANTIDADBOTONES'] += 1;
        echo "<li class='itemlist' >
<a href='indexAJX.php?sistema=" . $sistema . "&MNU=5&search=yes&amp;all_tickets=no&PlanAccion=yes&check=" . rand(1154833, 9054843) . "' " . $estiloSeleccion_planaccion . " >
<div  class='centrar' >
<img src='images/" . $sistema . "/calendar.png' alt='Inicio' title='Inicio' class='imagen_format' />
</div>
<div class='TextoBoton'>PLAN DE ACCION</div>
</a>
</li>";
    }

    if ($tienePersACargo == 1) {
        $_SESSION['CANTIDADBOTONES'] += 1;
        echo "<li class='itemlist' >
<a href='indexAJX.php?sistema=" . $sistema . "&MNU=6&subsistema=1&check=" . rand(3054833, 9054843) . "' " . $estiloSeleccion_permisos . " >
<div  class='centrar' >
<img src='images//" . $sistema . "/calendar.png' alt='Inicio' title='Inicio' class='imagen_format' />
</div>
<div class='TextoBoton'>PERMISOS</div>
</a>
</li>";
    }
						?>
						<ul>
				</div>

				<script>
					// var nav = responsiveNav(".nav-collapse");

					var navigation = responsiveNav(".nav-collapse", {
						animate : true, // Boolean: Use CSS3 transitions, true or false
						transition : 284, // Integer: Speed of the transition, in milliseconds
						label : "Menu", // String: Label for the navigation toggle
						insert : "after", // String: Insert the toggle before or after the navigation
						customToggle : "", // Selector: Specify the ID of a custom toggle
						closeOnNavClick : false, // Boolean: Close the navigation when one of the links are clicked
						openPos : "relative", // String: Position of the opened nav, relative or static
						navClass : "nav-collapse", // String: Default CSS class. If changed, you need to edit the CSS too!
						navActiveClass : "js-nav-active", // String: Class that is added to <html> element when nav is active
						jsClass : "js", // String: 'JS enabled' class which is added to <html> element
						init : function() {
						}, // Function: Init callback
						open : function() {
						}, // Function: Open callback
						close : function() {
						} // Function: Close callback
					});
				</script>

				<div align="center" id="divContent" style="width:100%" name="divContent" class="ContenedorIndex">

					<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg) ? "show='ok'" : "" ?> style="display:none">
						<img alt="Espere por favor..." border="0" src="images/<?echo $sistema; ?>/cargando.gif" />
					</div>

					<iframe id="iframeProcesando1" name="iframeProcesando1" src="about:blank" style="display:none; overflow:hidden;"></iframe>

					<span id="resultado"></span>

					<div id="divVisorImagenes"></div>

				</div>
			</div>
		</div>

		<?
        require ($_SERVER["DOCUMENT_ROOT"] . "/functions/copy_content.php");
		?>

	</body>
</html>
