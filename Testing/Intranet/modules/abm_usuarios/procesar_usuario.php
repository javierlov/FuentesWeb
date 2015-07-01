<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");


// Valido que el legajo rrhh sea único..
try {
	if (($_REQUEST["RelacionLaboral"] == 1) and ($_REQUEST["LegajoRRHH"] != 0)) {
		$params = array(":id" => $_REQUEST["Id"], ":legajorrhh" => $_REQUEST["LegajoRRHH"]);
		$sql =
			"SELECT 1
				 FROM use_usuarios
				WHERE se_legajorrhh = :legajorrhh
					AND se_id <> :id";
		if (ExisteSql($sql, $params, 0))		// Si el nº de legajo ya existe, salgo..
			throw new Exception("El Legajo RR.HH. ya fue asignado a otro usuario.");
	}
}
catch (Exception $e) {
?>
	<script>
		alert(unescape('<?= rawurlencode($e->getMessage())?>'));
	</script>
<?
	exit;
}


// Subo la foto..
$imgFotoPath = "";
if ($_FILES["Foto"]["name"] != "") {
	$tempfile = $_FILES["Foto"]["tmp_name"];
	$filename = StringToLower($_REQUEST["UserName"].strrchr($_FILES["Foto"]["name"], "."));

	$uploadOk = false;
	if (is_uploaded_file($tempfile))
		if (move_uploaded_file($tempfile, IMAGES_FOTOS_PATH.$filename)) {
			$uploadOk = true;
			$imgFotoPath = $filename;
		}

	if (!$uploadOk) {
?>
		<script>
			alert('Ocurrió un error al cargar la Foto. Inténtelo nuevamente.');
			history.go(-1);
		<script>
<?
		exit;
	}
}

// Guardo los datos en la tabla..
$params = array(":cargo" => $_REQUEST["Cargo"],
								":contrato" => nullIsEmpty($_REQUEST["RelacionLaboral"]),
								":delegacion" => $_REQUEST["Delegacion"],
								":edificio" => nullIfCero($_REQUEST["Edificio"]),
								":ejex" => nullIsEmpty($_REQUEST["EjeX"]),
								":ejey" => nullIsEmpty($_REQUEST["EjeY"]),
								":fechacumple" => $_REQUEST["FechaNacimiento"],
								":horarioatencion" => $_REQUEST["HorarioAtencion"],
								":idsector" => IIF(($_REQUEST["Sector"] == "-1"), NULL, $_REQUEST["Sector"]),
								":interno" => $_REQUEST["Interno"],
								":legajo" => nullIsEmpty($_REQUEST["Legajo"]),
								":legajorrhh" => nullIsEmpty($_REQUEST["LegajoRRHH"]),
								":piso" => nullIsEmpty($_REQUEST["Piso"]),
								":respondea" => IIF(($_REQUEST["RespondeA"] == "-1"), NULL, $_REQUEST["RespondeA"]),
								":usumodif" => GetWindowsLoginName());
$sql =
	"UPDATE use_usuarios
			SET se_fechamodif = SYSDATE,
					se_usumodif = UPPER(:usumodif),
					se_interno = :interno,
					se_fechacumple = TO_DATE(:fechacumple, 'dd/mm/yyyy'),
					se_idsector = :idsector,
					se_cargo = :cargo,
					se_delegacion = :delegacion,
					se_legajo = :legajo,
					se_legajorrhh = :legajorrhh,				
					se_contrato = :contrato,
					se_respondea = :respondea,
					se_horarioatencion = :horarioatencion,
					se_iddelegacionsede = :edificio,
					se_piso = :piso,
					se_ejex = :ejex,
					se_ejey = :ejey";

if ($imgFotoPath != "") {
	$sql.= ", se_foto = :foto";
	$params[":foto"] = $imgFotoPath;
}

$sql.= " WHERE se_id = :id";
$params[":id"] = $_REQUEST["Id"];
DBExecSql($conn, $sql, $params);
?>
<script>
<?
if ($dbError["offset"]) {
?>
	alert('<?= $dbError["message"]?>');
<?
}
else {
	if ($imgFotoPath != "") {
		SendEmail("Se ha cargado la foto del usuario ".$_REQUEST["UserName"].".", "Contacto Web", "Nueva foto cargada desde la intranet", array("aangiolillo@provart.com.ar"), array(), array());
		echo "window.parent.document.getElementById('NombreFoto').value = '<?= $imgFotoPath?>';";
	}
?>
	function closeWindow() {
		divWin.close();
	}

	setInterval("closeWindow()", 2000);
	medioancho = (screen.width - 320) / 2;
	medioalto = (screen.height - 200) / 2;
	divWin = window.parent.dhtmlwindow.open('divBox', 'div', 'msgOk', 'Aviso', 'width=320px,height=40px,left=' + medioancho + 'px,top=' + medioalto + 'px,resize=0,scrolling=0');

//	window.parent.document.getElementById('spanMensaje').style.display = 'block';
<?
}
?>
</script>