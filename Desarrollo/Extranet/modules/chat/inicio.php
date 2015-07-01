<?
require_once("inicio_combos.php");


$html.= '<div id="divProvartGrande"><img id="imgProvartGrande" src="/images/provart_blanco.png" /></div>';
$html.= '<div id="divInicio">';
$html.= 	'<div>Por favor, ingrese los siguientes datos para comenzar la conversación.</div>';
$html.= 	'<form action="/modules/chat/procesar_inicio.php" id="formChatInicio" method="post" name="formChatInicio" target="iframeChat">';
$html.= 		'<div class="divTitulo">NOMBRE<span class="spanError" id="spanErrornombre"></span></div>';
$html.= 		'<div><input autofocus class="campo" id="nombre" maxlength="255" name="nombre" type="text" value="" onFocus="cambiarFondo(this)" /></div>';
$html.= 		'<div class="divTitulo">E-MAIL<span class="spanError" id="spanErroremail"></span></div>';
$html.= 		'<div><input class="campo" id="email" maxlength="255" name="email" type="text" value="" onFocus="cambiarFondo(this)" /></div>';
$html.= 		'<div class="divTitulo">SECTOR<span class="spanError" id="spanErrorsector"></span></div>';
$html.= 		'<div>'.$comboSector->draw(true).'</div>';
$html.= 		'<div class="divTitulo" id="divTituloDni">D.N.I.<span class="spanError" id="spanErrordniChat"></span></div>';
$html.= 		'<div><input class="campo" id="dniChat" maxlength="10" name="dniChat" type="text" value="" onFocus="cambiarFondo(this)" /></div>';
$html.= 		'<div class="divTitulo">MENSAJE<span class="spanError" id="spanErrormensaje"></span></div>';
$html.= 		'<div><textarea class="campo" id="mensaje" maxlength="255" name="mensaje" onFocus="cambiarFondo(this)"></textarea></div>';
$html.= 		'<div><input id="generico" name="generico" type="hidden" value="" /><span id="spanErrorgenerico"></span></div>';
$html.= 		'<div id="divContenedorBtnIniciarChat"><div id="divBtnIniciarChat" onClick="iniciarChat()"><b>INICIAR CHAT</b></div></div>';
$html.= 	'</form>';
$html.= '</div>';
?>