<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$msgError = "";

switch ($_REQUEST["tipotabla"]) {
	case "E":
		$obj = "empresa";
		$prefijo = "em_";
		$tabla = "rrhh.rem_empresas";
		break;
	case "G":
		$obj = "gerencia";
		$prefijo = "ge_";
		$tabla = "rrhh.rge_gerencias";
		break;
	case "P":
		$obj = "puesto";
		$prefijo = "pu_";
		$tabla = "rrhh.rpu_puestos";
		break;
	case "R":
		$obj = "grupo";
		$prefijo = "gr_";
		$tabla = "rrhh.rgr_grupos";
		break;	
}


if (isset($_REQUEST["tipoop"])) {		// Si tengo que guardar los datos..
	try {
		if ($_REQUEST["tipoop"] == "A") {		// Alta..
			$params = array(":detalle" => $_REQUEST["item"]);
			$sql =
				"INSERT INTO ".$tabla." (".$prefijo."id, ".$prefijo."detalle)
												 VALUES (-1, :detalle)";
			DBExecSql($conn, $sql, $params);
		}
		if ($_REQUEST["tipoop"] == "M") {		// Modificación..
			$params = array(":detalle" => $_REQUEST["item"], ":id" => $_REQUEST["id"]);
			$sql =
				"UPDATE ".$tabla."
						SET ".$prefijo."detalle = :detalle
				  WHERE ".$prefijo."id = :id";
			DBExecSql($conn, $sql, $params);
		}
		if ($_REQUEST["tipoop"] == "B") {		// Baja..
			$params = array(":id" => $_REQUEST["id"]);
			$sql =
				"DELETE FROM ".$tabla."
							 WHERE ".$prefijo."id = :id";
			DBExecSql($conn, $sql, $params);
		}
	}
	catch (Exception $e) {
		$msgError = $e->getMessage();
	}
}

require_once("tablas_auxiliares_combos.php");
?>
<html>
	<head>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<link href="/modules/evaluacion_puesto/abm_descripcion_de_puesto/css/style.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="/js/functions.js"></script>
		<script type="text/javascript" src="/js/validations.js"></script>
		<script type="text/javascript" src="/modules/evaluacion_puesto/abm_descripcion_de_puesto/js/tablas_auxiliares.js"></script>
<?
if ($msgError != "") {
	if (strpos(strtolower($msgError), "integrity constraint")) {
?>
		<script type="text/javascript">
			alert('Este item no puede ser eliminado porque hay datos que hacen referencia a el.');
		</script>
<?
	}
	else {
?>
		<script type="text/javascript">
			alert('<?= $msgError?>');
		</script>
<?
	}
}
?>
	</head>
	<body>
		<input id="tipoOp" type="hidden" value="-1" />
		<?= $comboTipos->draw();?>
		<input class="BotonBlanco" id="btnAgregar" type="button" value="Agregar" onClick="agregar()" />
		<input class="BotonBlanco" id="btnModificar" type="button" value="Modificar" onClick="modificar()" />
		<input class="BotonBlanco" id="btnEliminar" type="button" value="Eliminar" onClick="eliminar('<?= $_REQUEST["tipotabla"]?>')" />
		<input id="item" type="text" style="display:none" />
		<input class="BotonBlanco" id="btnAceptar" type="button" value="Aceptar" style="display:none" onClick="aceptar('<?= $_REQUEST["tipotabla"]?>')" />
		<input class="BotonBlanco" id="btnCancelar" type="button" value="Cancelar" style="display:none" onClick="cancelar()" />
		<script type="text/javascript">
			var valorAnterior = window.parent.document.getElementById('<?= $obj?>').value;
			window.parent.document.getElementById('<?= $obj?>').value = valorAnterior;
			document.getElementById('tipos').value = <?= (isset($_REQUEST["id"]))?$_REQUEST["id"]:-1?>;
		</script>
	</body>
</html>