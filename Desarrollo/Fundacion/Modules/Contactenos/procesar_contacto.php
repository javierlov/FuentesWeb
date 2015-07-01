<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");


$body = "<html><body>";
$body.= "<p>Nombre: <b>".$_REQUEST["nombre"]."</b></p>";
$body.= "<p>Apellido: <b>".$_REQUEST["apellido"]."</b></p>";
$body.= "<p>e-Mail: <b>".$_REQUEST["email"]."</b></p>";
$body.= "<p>Asunto: <b>".$_REQUEST["asunto"]."</b></p>";
$body.= "<p>Comentarios: <b>".$_REQUEST["comentarios"]."</b></p>";
$body.= "</body></html>";

SendEmail($body, $_REQUEST["email"], "Contacto desde el sitio web de Fundación Provincia ART", array("fundacion@provart.com.ar"), array(), array(), "H");
?>
<span style="padding-left: 4px; padding-right: 4px"><font face="Verdana" style="font-size: 8pt" color="#877F87">Gracias por comunicarse, a la brevedad será contactado.</font></span>