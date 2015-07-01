<?
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
				AND me_orden IN(60, 300, 3000)";

	for ($i=5; $i<=11; $i++)
		$sql.= " UNION ALL SELECT NULL, 1300 me_orden, NULL, NULL FROM DUAL";
}

/* ABOGADOS */
if (isset($_SESSION["isAbogado"])) {
	$sql =
		"SELECT me_imagen, me_orden, me_texto, me_url
			 FROM web.wme_menuextranet
			WHERE me_idpadre = -1
				AND me_fechabaja IS NULL
				AND me_orden IN(2010, 2100, 2200, 2300, 2400)";

	for ($i=5; $i<=11; $i++) 		$sql.= " UNION ALL SELECT NULL, 3300 me_orden, NULL, NULL FROM DUAL";
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
?>
		<style>
			#menu<?= $row["ME_ORDEN"]?> {
				background-image: url(/images/menu/<?= $row["ME_IMAGEN"]?>.png);
				background-repeat: no-repeat;
				height: 17px;
				width: 185px;
			}

			#menu<?= $row["ME_ORDEN"]?>:hover {
				background-image: url(/images/menu/<?= $row["ME_IMAGEN"]?>_a.png);
			}
		</style>
		<a href="<?= $row["ME_URL"]?>"><div id="menu<?= $row["ME_ORDEN"]?>"></div></a>
<?
	}
?>
	<img src="/images/menu/linea_punteada.png" style="height:3px; width:185px;" />
<?
}