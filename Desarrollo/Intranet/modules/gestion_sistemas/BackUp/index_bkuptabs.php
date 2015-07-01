<?
require_once ($_SERVER["DOCUMENT_ROOT"] . "/constants.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/database/db.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/database/db_funcs.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Classes/provart/grid.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/miscellaneous/general.php");

$showProcessMsg = true;

/* Implementación de móltiples sistemas dentro del sistema de tickets */
if (isset($_REQUEST["sistema"]))
    $sistema = $_REQUEST["sistema"];
else
    $sistema = 1;

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
?>


<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="viewport" content="width=device-width,initial-scale=1">

		<title>Provincia ART | Sistemas</title>
		<meta content="Mon, 06 Jan 1990 00:00:01 GMT" http-equiv="Expires" />

		<link href="/modules/gestion_sistemas/styles/tabs_demo.css" rel="stylesheet" type="text/css">
		</link>
		<link href="/modules/gestion_sistemas/styles/tabs_component.css" rel="stylesheet" type="text/css">
		</link>

		<script type="text/javascript" src="js/interface.js"></script>

		<script type="text/javascript" src="/Js/functions.js"></script>
		<script type="text/javascript" src="/Js/validations.js"></script>
		<?php 	Intranet_JSjqueryUI(); ?>

		<!--
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="/Js/ajax.js" charset="iso-8859-1"></script>
		-->
		<script type="text/javascript" src="Js/ticket.js?rnd=<?=RandomNumber(); ?>" charset="iso-8859-1"></script>
		<script type="text/javascript" src="/Js/grid.js"></script>

		<link href="Styles/style_sistemas1.css?sid=<?= date('YmdHis'); ?>" rel="stylesheet" type="text/css" />
		<!-- navigator
		<script type="text/javascript" src="js/responsive-nav.js"></script>

		<link href="/modules/gestion_sistemas/styles/responsive-nav.css" rel="stylesheet" type="text/css"></link>
		<link href="/modules/gestion_sistemas/styles/styles_responsive.css" rel="stylesheet" type="text/css"></link>
		-->

	</head>
	<body background="images/dock-bg2.gif" >

		<div id="tabs" class="tabs">
			<nav>
				<ul>
					<li>
						<a href="index.php?sistema=<?echo $sistema; ?>&search=no" class="icon-lab"><span>Inicio</span></a>
					</li>
					<li>
						<a href="index.php?sistema=<?echo $sistema; ?>&newticket=yes" class="icon-cup"><span>Realizar pedido</span></a>
					</li>
					<li>
						<a href="index.php?sistema=<?echo $sistema; ?>&search=yes&amp;all_tickets=no" class="icon-food"><span>Pedidos actuales</span></a>
					</li>
					<li>
						<a href="index.php?sistema=<?echo $sistema; ?>&search=yes&amp;all_tickets=yes" class="icon-shop"><span>Historial de pedidos</span></a>
					</li>

				</ul>
			</nav>
		</div>

		<div align="center" id="divContent" style="width:100%" name="divContent">

			<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg) ? "show='ok'" : "" ?> style="display:none">
				<img alt="Espere por favor..." border="0" src="images/<?echo $sistema; ?>/cargando.gif" />
			</div>

			<iframe id="iframeProcesando" name="iframeProcesando" src="about:blank" style="display:none;"></iframe>

			<table align="center" width="100%">
				<tr>
					<td> <?
                    if ((isset($_REQUEST["search"])) and ($_REQUEST["search"] == "yes")) {
                        require ($_SERVER["DOCUMENT_ROOT"] . "/Modules/Gestion_Sistemas/tickets_grid.php");
                    } else if ((isset($_REQUEST["information"])) and ($_REQUEST["information"] == "yes")) {
                        require ($_SERVER["DOCUMENT_ROOT"] . "/Modules/Gestion_Sistemas/ticket_information.php");
                    } else if ((isset($_REQUEST["qualification"])) and ($_REQUEST["qualification"] == "yes")) {
                        require ($_SERVER["DOCUMENT_ROOT"] . "/Modules/Gestion_Sistemas/ticket_qualification.php");
                    } else if (isset($_REQUEST["authorize"])) {
                        require ($_SERVER["DOCUMENT_ROOT"] . "/Modules/Gestion_Sistemas/ticket_authorization.php");
                    } else if ((isset($_REQUEST["newticket"])) and ($_REQUEST["newticket"] == "yes")) {
                        require ($_SERVER["DOCUMENT_ROOT"] . "/Modules/Gestion_Sistemas/ticket_new.php");
                    } else if ((isset($_REQUEST["ticket_detail"])) and ($_REQUEST["ticket_detail"] == "yes")) {
                        require ($_SERVER["DOCUMENT_ROOT"] . "/Modules/Gestion_Sistemas/ticket_detail.php");
                    } else {
                        require ($_SERVER["DOCUMENT_ROOT"] . "/Modules/Gestion_Sistemas/home.php");
                    }
					?> </td>
				</tr>
			</table>

			<div id="divVisorImagenes"></div>

		</div>
		<?
        require ($_SERVER["DOCUMENT_ROOT"] . "/functions/copy_content.php");
		?>
		<script>
			//CopyContent();
		</script>

	</body>
</html>