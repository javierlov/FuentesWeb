<?
// Valido que sea un cliente o un agente comercial que entre desde la página de certificados de cobertura..
validarSesion(isset($_SESSION["isCliente"]) or ((isset($_SESSION["isAgenteComercial"])) and (isset($_REQUEST["page"])) and ($_REQUEST["page"] == "certificado_de_cobertura/seleccion_de_trabajadores_paso2.php")));

if (isset($_REQUEST["page"]))
	@require_once($_REQUEST["page"]);
elseif ($_SESSION["isAdminTotal"])
	require_once("index_administradores_art.php");
//elseif ($_SESSION["isAdmin"])
else
	require_once("index_administradores.php");
//else
//	require_once("menu_clientes.php");
?>