<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 70));

unset($_SESSION["certificadoCobertura"]["trabajadores"]);
$_SESSION["certificadoCobertura"]["trabajadores"] = array();
?>
<script type="text/javascript">
	function hideMsg() {
		window.parent.document.getElementById('divMsgLimpiarOk').style.visibility = 'hidden';
	}

	function uncheckAll() {
		var form = window.parent.document.form;
		var arr;

		for (i=0; i<form.elements.length; i++)
			if (form.elements[i].type == 'checkbox')
				form.elements[i].checked = false;
	}


	uncheckAll();

	window.parent.document.getElementById('cantidadTrabajadoresSeleccionados').innerHTML = '0';
	window.parent.document.getElementById('divMsgLimpiarOk').style.visibility = 'visible';

	setTimeout('hideMsg()', 3000);
</script>