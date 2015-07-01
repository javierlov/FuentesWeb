<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");

function addQuerySubMenu($idorden, $addunionall = false, $idUsuario = "") {
	$sqlresult = "";
	if ($addunionall)
		$sqlresult.= " UNION ALL ";

	$sqlresult.=
		"SELECT me_imagen, me_orden, me_texto, me_url
			 FROM web.wme_menuextranet
			WHERE me_fechabaja IS NULL
				AND me_orden = ".$idorden;       

	$idmenu = 0;
	switch ($idorden) {
		case 2100:
			$idmenu = 1;
			break;
		case 2200:
			$idmenu = 2;
			break;
		case 2300:
			$idmenu = 3;
			break;
		case 2400:
			$idmenu = 4;
			break;
	}

	if ($idUsuario != "")
		$sqlresult.=
			" AND EXISTS(SELECT 1
										 FROM legales.lum_usuariomenu 
										WHERE um_idusuario = ".$idUsuario." 
											AND um_idmenuweb = ".$idmenu.") ";

	return $sqlresult;
}

function dibujoEncabezadoTitulo($textotitulo) {
	echo "<script>
			var divh = document.getElementById('divHeader');
			var subdiv = document.createElement('div');
			subdiv.innerHTML = '".$textotitulo."';
			subdiv.setAttribute('style', 'z-index:21; position:relative; font: bold 10px Verdana;top:122px; left:25px;padding: 1px 4px 1px 4px; color: #808080;');
			divh.appendChild(subdiv);
			var divh = document.getElementById('divHeader');
		  </script>";
}

function getItemsCliente() {
	global $servidorContingenciaActivo;
	$result = "-2";

	if (!$servidorContingenciaActivo) {
		if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 33))
			$result.= ",930";
		if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 52))
			$result.= ",740";
		if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 55))
			$result.= ",750";
		if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 58))
			$result.= ",760";
		if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 60))
			$result.= ",780";
		if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 61))
			$result.= ",820";
		if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 64))
			$result.= ",840";
	//	if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 66))		No se muestra por ahora..
	//		$result.= ",960";
		if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 68))
			$result.= ",920";
		if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 94))
			$result.= ",940";
		if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 95))
			$result.= ",950";
		if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 100))
			$result.= ",960";
		if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 101))
			$result.= ",970";
		
		$result.= ",980";
	}
	if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 70))
		$result.= ",720";

	return $result;
}

function totalItemsCliente() {
	$arr = explode(",", getItemsCliente());

	$esadmin = 0;
	if (($_SESSION["isAdmin"]) or ($_SESSION["isAdminTotal"]))
		$esadmin = 1;

	// Le resto 1 por el -2 que pongo en la función getItemsCliente..
	// Le sumo 2 por los 2 items públicos que se muestran al final..
	// Le sumo 1 mas si es admin..
	return ((count($arr) - 1) + 2 + $esadmin);
}


$sql =
	"SELECT me_imagen, me_orden, me_texto, me_url
		 FROM web.wme_menuextranet
	  WHERE me_idpadre = -1
		  AND me_tipoitem = 'U'
		  AND me_fechabaja IS NULL";


/* CLIENTES */
if ((isset($_SESSION["isCliente"])) and ($_SESSION["idEmpresa"] != -1)) {
	$sql =
		"SELECT me_imagen, me_orden, me_texto, me_url
			 FROM web.wme_menuextranet
			WHERE me_idpadre = -1
			  AND me_fechabaja IS NULL
			  AND me_orden IN(".getItemsCliente().")";

	if (!$servidorContingenciaActivo) {
		for ($i=totalItemsCliente(); $i<=15; $i++)
			$sql.= " UNION ALL SELECT NULL, 1300 me_orden, NULL, NULL FROM DUAL";

		$sql.=
			" UNION ALL
					 SELECT me_imagen, me_orden, me_texto, me_url
						 FROM web.wme_menuextranet
						WHERE me_idpadre = -1
							AND me_fechabaja IS NULL
							AND me_orden IN(1500, ".((($_SESSION["isAdmin"]) or ($_SESSION["isAdminTotal"]))?1600:-2).", 3000)";
	}
}


/* AGENTES COMERCIALES */
if (isset($_SESSION["isAgenteComercial"])) {
	$sql =
		"SELECT me_imagen, me_orden, me_texto, me_url
			 FROM web.wme_menuextranet
			WHERE me_idpadre = -1
				AND me_fechabaja IS NULL
				AND me_orden IN(680, 700, ".(($_SESSION["comisiones"])?710:-1).", 780, 1000, 1300)";

	for ($i=5; $i<=($_SESSION["comisiones"]?7:8); $i++)
		$sql.= " UNION ALL SELECT NULL, 1300 me_orden, NULL, NULL FROM DUAL";

	$sql.=
		" UNION ALL
				 SELECT me_imagen, me_orden, me_texto, me_url
					 FROM web.wme_menuextranet
					WHERE me_idpadre = -1
						AND me_fechabaja IS NULL
						AND me_orden IN(3000)";
}


/* PREVENTORES */
if (isset($_SESSION["isPreventor"])) {
	$sql =
		"SELECT me_imagen, me_orden, me_texto, me_url
			 FROM web.wme_menuextranet
			WHERE me_idpadre = -1
				AND me_fechabaja IS NULL
				AND me_orden IN(60, 300, 301, 3000)";

	for ($i=5; $i<=11; $i++)
		$sql.= " UNION ALL SELECT NULL, 1300 me_orden, NULL, NULL FROM DUAL";
}


/* ABOGADOS */
if (isset($_SESSION["isAbogado"])) {
	$idUsuario = 0;
	if (isset($_SESSION['idUsuario']))
		$idUsuario = $_SESSION['idUsuario'];

	$filtarmenu = false;
	$filtarmenu = true;

	$sql = addQuerySubMenu(2100, false, $idUsuario);
	$sql.= addQuerySubMenu(2200, true, $idUsuario);
	$sql.= addQuerySubMenu(2300, true, $idUsuario);
	$sql.= addQuerySubMenu(2400, true, $idUsuario);

	if (isset($_REQUEST["pageid"])) {
		$pageid = trim($_REQUEST["pageid"]);
		
		$arrayPageSubMenues = array(106, 107, 108, 109, 110, 111, 112,113,114,115,123, 134,135,136);
		$agregarMenu = false;
		if (in_array($pageid, $arrayPageSubMenues)) {			
			$agregarMenu = true;
		}
		
		if ($agregarMenu) {
			//$sql.= AddQuerySubMenu(2110, true);
			//$sql.= AddQuerySubMenu(2111, true);
			$sql.= addQuerySubMenu(2112, true);
			$sql.= addQuerySubMenu(2113, true);
			$sql.= addQuerySubMenu(2114, true);
		}
	}

	dibujoEncabezadoTitulo("ADMINISTRACION DE ESTUDIOS JURIDICOS");

	for ($i=5; $i<=11; $i++)
		$sql.= " UNION ALL SELECT NULL, 3300 me_orden, NULL, NULL FROM DUAL";
}

$sql.= " ORDER BY me_orden";

$stmt = DBExecSql($conn, $sql);
while ($row = DBGetQuery($stmt)) {
	if ($row["ME_IMAGEN"] == "") {
?>
		<div style="height:17px;"></div>
<?
	}
	else {
		$title = "";
		if ($row["ME_ORDEN"] == 960)		// RGRL..
			$title = "Relevamiento General de Riesgos Laborales";
?>
		<style>
			#menu<?= $row["ME_ORDEN"]?> {
				background-image: url(/images/menu/<?= $row["ME_IMAGEN"]?>.png);
				background-repeat: no-repeat;
				height: 17px;
				width: 185px;			
				<? if($row["ME_ORDEN"] >= 2110 and  $row["ME_ORDEN"] <= 2114) echo "background-position: 10px 0px;"; ?>				 
			}

			#menu<?= $row["ME_ORDEN"]?>:hover {
				background-image: url(/images/menu/<?= $row["ME_IMAGEN"]?>_a.png);
			}
		</style>
		<a href="<?= $row["ME_URL"]?>"><div id="menu<?= $row["ME_ORDEN"]?>"></div></a>
		<img src="/images/menu/linea_punteada.png" style="height:3px; width:185px;" />
<?
	}
}
?>