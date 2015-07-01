<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/modules/gestion_sistemas/ticket_funciones.php");
///////// foreach($_SERVER as $k=>$v)	echo $k." = ".$v."<p>";	
?>
		<link rel="stylesheet" type="text/css" href="styles/LineaTiempo_default.css?rnd="<?=RandomNumber(); ?> />
		<link rel="stylesheet" type="text/css" href="styles/LineaTiempo_component.css?rnd="<?=RandomNumber(); ?> />
		<script src="js/LineaTiempo_modernizr.custom.js"></script>
		
<form action="" id="formTicket" method="post" name="formTicketLineaTiempo"   >

	<div class="containerLineaTiempo">
			<header class="clearfix">
				<span>Seguimiento</span>
				<h1>Ticket <? echo $nro_ticket; ?></h1>
				<nav>																				
					<!--
					<a href="#" onclick="window.history.go(-1);" class="bp-icon icon-drop" data-info="Volver"><span>Volver</span></a>
					-->
					<a href="#" onclick="href='<?=$_SERVER['HTTP_REFERER']?>'" class="bp-icon icon-drop" data-info="Volver"><span>Volver</span></a>
				</nav>
			</header>	
			<div class="main">
				<ul class="cbp_tmtimeline">
					<? echo GetDatosLineaTiempo($idReferencia); ?>					
				</ul>
			</div>
		</div>
					
</form>

<script type="text/javascript"></script>