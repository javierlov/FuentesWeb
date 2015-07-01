<?php
//cambio jlovatto 17/06/2015
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/gestion_sistemas/ticket_funciones.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/miscellaneous/Intranet_comunes.php");

$estado_session = session_status();
if($estado_session == PHP_SESSION_NONE)
{
session_start();
}

function GetRequest($parametro ){
	if (isset($_REQUEST[$parametro])) {		
		return $_REQUEST[$parametro];
	}
	return false;
}

if(!isset($_SESSION['OPCIONMENU'])) 
	$_SESSION['OPCIONMENU'] = 0;
	
$_SESSION['CANTIDADBOTONES'] = 4;
$paginas = $_SESSION['CANTIDADBOTONES'];
$tienePersACargo = false;

$showProcessMsg = true;

/* Implementación de múltiples sistemas dentro del sistema de tickets */
$sistema = GetParametro("sistema", 1);

 if($sistema == 1){
	$usuarioActual = GetUsuarioAplicacion();
	$tienePersACargo = TienePersonalACargo( $usuarioActual );
 }

$all_tickets = GetParametro("all_tickets","no");
$pending_tickets = GetParametro("pending_tickets","no");
$pending_moreinfo_tickets = GetParametro("pending_moreinfo_tickets","no");

$pending_auth_tickets = GetParametro("pending_auth_tickets","no");
$pagina = GetParametro("pagina","no");
	
//-------------------------------------------------------------	
$estiloSeleccion = "";
$estiloSeleccion_newticket = "";
$estiloSeleccion_pedidosactuales = "";
$estiloSeleccion_historialpedidos = "";
$estiloSeleccion_planaccion = "";
$estiloSeleccion_permisos = "";

$estiloComun = "style='background:#f1f1f1; color:#000'"; 

if(isset($_REQUEST['MNU']))
	$_SESSION['OPCIONMENU'] = $_REQUEST['MNU'];
else
	$_SESSION['OPCIONMENU'] = '1';
	
$menu = $_SESSION['OPCIONMENU'];

	switch($menu){
		case 1: $estiloSeleccion = $estiloComun; break;
		case 2: $estiloSeleccion_newticket = $estiloComun;	  break;
		case 3: $estiloSeleccion_pedidosactuales = $estiloComun; break; 
		case 4: $estiloSeleccion_historialpedidos = $estiloComun;	break; 
		case 5: $estiloSeleccion_planaccion = $estiloComun; break; 
		case 6: $estiloSeleccion_permisos = $estiloComun;	break; 
	}
/*

//echo "<script type='text/javascript' src='/modules/gestion_sistemas/js/ticket_functions.js?rnd=".date('YmdHis')."'></script> ";
echo "<script type='text/javascript' src='/modules/gestion_sistemas/js/ticket_functions.js'></script> ";
echo "<script type='text/javascript' src='/modules/gestion_sistemas/js/ajaxBuscaDatos.js?rnd=".date('YmdHis')."'></script> ";	

include($_SERVER["DOCUMENT_ROOT"]."/js/calendar/AgregaEncabezadoCalendarJS.html");

*/
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
  <meta name="author" content="Lovatto Javier">
  <meta name="description" content="web tickets">
  
  <title>Provincia ART | Sistemas</title>

		<script type="text/javascript" src="/js/jq/jquery.js"></script>
		<?php 	Intranet_JSjqueryUI(); ?>
  <!--
  <script type="text/javascript" src="js/interface.js"></script>
  -->
  <script type="text/javascript" src="/Js/functions.js"></script>
  <script type="text/javascript" src="/Js/validations.js"></script>
  <script type="text/javascript" src="/Js/ajax.js" charset="iso-8859-1"></script>
  <script type="text/javascript" src="/Js/grid.js"></script>
  <script type="text/javascript" src="Js/ticket.js?rnd=<?=RandomNumber(); ?>" charset="iso-8859-1"></script>

  <script type="text/javascript" src="js/responsive-nav.js"></script> 

  <script type="text/javascript" src="/modules/gestion_sistemas/js/ticket_functions.js?rnd=<?=RandomNumber(); ?>" ></script>
  <script type="text/javascript" src="/modules/gestion_sistemas/js/ajaxBuscaDatos.js?rnd=<?=RandomNumber(); ?>" ></script>
  
  <link href="/modules/gestion_sistemas/styles/responsive-nav.css" rel="stylesheet" type="text/css" />
  <link href="styles/style_sistemas.css?sid=<?=RandomNumber(); ?>" rel="stylesheet" type="text/css" />
  <link href="styles/style_sistemas_GI.css?sid=<?=RandomNumber(); ?>" rel="stylesheet" type="text/css" />
  <link href="/modules/gestion_sistemas/styles/styles_responsive.php?id=<?=RandomNumber(); ?>" rel="stylesheet" type="text/css" />  
  <link href="/styles/ticket/ticket_style.css<?=RandomNumber(); ?>" rel="stylesheet">
  	
	
</head>
<body class="bodywrapper"  >
<div class="wrapperPage"  >
	<div class="wrapperMenu"  >
		<div class="nav-collapse closed" style="z-index:1000;" >		
			<ul>
				<li id="itemInicio" >				
				
				<a href="index.php?sistema=<? echo $sistema; ?>&MNU=1&search=no" <?php echo $estiloSeleccion; ?> >
				
				<div  class="centrar" >					
					<img src="images/<?echo $sistema;?>/home.png" alt="Inicio" title="Inicio" class="imagen_format" />					
				</div>
				<div class="TextoBoton">INICIO</div></a>				
				</li>
				
				<li class="itemlist" >
				<a  href="index.php?sistema=<?echo $sistema;?>&MNU=2&newticket=yes" <?php echo $estiloSeleccion_newticket; ?> >
				<div  class="centrar" >
					<img src="images/<?echo $sistema;?>/portfolio.png" alt="Realizar pedido" title="Realizar pedido" class="imagen_format" />
				</div>
				<div class="TextoBoton">REALIZAR PEDIDO</div></a>
				</li>
				
				<li class="itemlist" >
				<a  href="index.php?sistema=<?echo $sistema;?>&MNU=3&search=yes&amp;all_tickets=no" name="PEDIDOSACTUALES" <?php echo $estiloSeleccion_pedidosactuales; ?>>
				<div  class="centrar" >
					<img src="images/<?echo $sistema;?>/calendar.png" alt="Pedidos actuales" title="Pedidos actuales" class="imagen_format" />
				</div>
				<div class="TextoBoton">PEDIDOS ACTUALES</div></a>
				</li>
				
				<li class="itemlist" >
				<a href="index.php?sistema=<?echo $sistema;?>&MNU=4&search=yes&amp;all_tickets=yes"  <?php echo $estiloSeleccion_historialpedidos; ?> >
				<div  class="centrar" >
					<img src="images/<?echo $sistema;?>/history.png" alt="Historial de pedidos" title="Historial de pedidos" class="imagen_format" />
				</div>
				<div class="TextoBoton">HISTORIAL DE PEDIDOS</div></a></li>    
				
				<?PHP 
				if($tienePersACargo == 1 ){ 
					$_SESSION['CANTIDADBOTONES'] += 1;					
					echo "<li class='itemlist' >
						<a href='index.php?sistema=".$sistema."&MNU=5&search=yes&amp;all_tickets=no&PlanAccion=yes&check=".rand(1154833, 9054843)."' ".$estiloSeleccion_planaccion." >
						<div  class='centrar' >
							<img src='images/".$sistema."/calendar.png' alt='Inicio' title='Inicio' class='imagen_format' />
						</div>
						<div class='TextoBoton'>PLAN DE ACCION</div>
						</a>
					</li>";    
				} 					  
				
				if($tienePersACargo == 1 ){ 
					$_SESSION['CANTIDADBOTONES'] += 1;					
					echo "<li class='itemlist' >
						<a href='index.php?sistema=".$sistema."&MNU=6&subsistema=1&check=".rand(3054833, 9054843)."' ".$estiloSeleccion_permisos." >
						<div  class='centrar' >
							<img src='images//".$sistema."/calendar.png' alt='Inicio' title='Inicio' class='imagen_format' />
						</div>
						<div class='TextoBoton'>PERMISOS</div>
						</a>
					</li>";    
				} 					  
					
				?>
			<ul>		
		</div>
	</div>
			
	<div id="divContenido" name="divContent" 
			class="ContenedorIndex"
		styles="width:100%; margin:0 auto; display:inline;" >

	<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none; padding-top:50px;">
	  <img alt="Espere por favor..." border="0" src="images/<?echo $sistema;?>/cargando.gif" />
	</div>

	<iframe id="iframeProcesando1" name="iframeProcesando1" src="about:blank" style="display:none; overflow:hidden;"></iframe>
	
	
      <script>
	 // var nav = responsiveNav(".nav-collapse");

	  var navigation = responsiveNav(".nav-collapse", {
        animate: true,                    // Boolean: Use CSS3 transitions, true or false
        transition: 284,                  // Integer: Speed of the transition, in milliseconds
        label: "Menu",                    // String: Label for the navigation toggle
        insert: "after",                  // String: Insert the toggle before or after the navigation
        customToggle: "",                 // Selector: Specify the ID of a custom toggle
        closeOnNavClick: false,           // Boolean: Close the navigation when one of the links are clicked
        openPos: "relative",              // String: Position of the opened nav, relative or static
        navClass: "nav-collapse",         // String: Default CSS class. If changed, you need to edit the CSS too!
        navActiveClass: "js-nav-active",  // String: Class that is added to <html> element when nav is active
        jsClass: "js",                    // String: 'JS enabled' class which is added to <html> element
        init: function(){},               // Function: Init callback
        open: function(){},               // Function: Open callback
        close: function(){}               // Function: Close callback
      });
    </script>
	
  <div align="center"  >
        <?
 
        if ((isset($_REQUEST["Permisos"])) and ($_REQUEST["Permisos"] == "yes")) {
          include($_SERVER["DOCUMENT_ROOT"]."/Modules/Gestion_Sistemas/ticket_permisosUpdate.php");		  
		  return true;
        }
		else if ((isset($_REQUEST["LineaTiempo"])) and ($_REQUEST["LineaTiempo"] == "yes")) {			
		  $idReferencia = GetParametro("idReferencia");
		  $nro_ticket = GetParametro("nro_ticket");
          include($_SERVER["DOCUMENT_ROOT"]."/Modules/Gestion_Sistemas/tickets_LineaTiempo.php");
		  return true;
        }
        else if ((isset($_REQUEST["search"])) and ($_REQUEST["search"] == "yes")) {
          require($_SERVER["DOCUMENT_ROOT"]."/Modules/Gestion_Sistemas/tickets_grid.php");		  
		  return true;
        }
        else if ((isset($_REQUEST["information"])) and ($_REQUEST["information"] == "yes")) {
          require($_SERVER["DOCUMENT_ROOT"]."/Modules/Gestion_Sistemas/ticket_information.php");
		  return true;
        }
        else if ((isset($_REQUEST["qualification"])) and ($_REQUEST["qualification"] == "yes")) {
          require($_SERVER["DOCUMENT_ROOT"]."/Modules/Gestion_Sistemas/ticket_qualification.php");
		  return true;
        }
        else if (isset($_REQUEST["authorize"])) {
          require($_SERVER["DOCUMENT_ROOT"]."/Modules/Gestion_Sistemas/ticket_authorization.php");
		  return true;
        }
        else if ((isset($_REQUEST["newticket"])) and ($_REQUEST["newticket"] == "yes")) {
          require($_SERVER["DOCUMENT_ROOT"]."/Modules/Gestion_Sistemas/ticket_new.php");		  
		  return true;
        }
        else if ((isset($_REQUEST["subsistema"])) and ($_REQUEST["subsistema"] == "1")) {		  	
          require($_SERVER["DOCUMENT_ROOT"]."/Modules/Gestion_Sistemas/ticket_permisos.php");          
		  return true;
        }
        else if ((isset($_REQUEST["ticket_detail"])) and ($_REQUEST["ticket_detail"] == "yes")) {
          require($_SERVER["DOCUMENT_ROOT"]."/Modules/Gestion_Sistemas/ticket_detail.php");
		  return true;
        }
        else {
          require($_SERVER["DOCUMENT_ROOT"]."/Modules/Gestion_Sistemas/home.php");		  
        }

        ?>
    
  </div>

  <div id="divVisorImagenes"></div>

  </div>

</div>

<?
	require($_SERVER["DOCUMENT_ROOT"]."/functions/copy_content.php");
?>

</body>
</html>

<script language='Javascript'>
	var ancho=screen.width;
	var alto=screen.height;
	var AnchoVentana = window.innerWidth;
	var AltoVentana = window.innerHeight;
	 var jqancho = $( window ).width();
</script>