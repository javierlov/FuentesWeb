<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$modulePath = "/Modules/ABM_Usuarios/";
$params = array(":id" => $_REQUEST["id"]);
$sql = 
	"SELECT NVL(se_cargo, -1) cargo, NVL(se_delegacion, -1) delegacion, TO_CHAR(se_fechacumple, 'dd/mm/yyyy') fechanacimiento,
					NVL(DECODE(se_contrato, 0, -1, se_contrato), -1) relacionlaboral, NVL(se_respondea, -1) respondea, se_delegacion, se_ejex, se_ejey, se_foto, se_horarioatencion,
					se_iddelegacionsede, se_interno, se_legajo, se_legajorrhh, se_nivel, se_nombre, se_piso, se_ubica, se_usuario, NVL(se_idsector, -1) sector
		 FROM use_usuarios
		WHERE se_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<html>
	<head>
		<script language="JavaScript" src="/js/constants.js"></script>
		<script language="JavaScript" src="/js/functions.js"></script>
		<script language="JavaScript" src="<?= $modulePath?>js/usuario.js"></script>
		<script>
			with (window.parent.document) {
				getElementById('formUsuario').reset();
				getElementById('Id').value = '<?= $_REQUEST["id"]?>';
				getElementById('Usuario').value = '<?= $_REQUEST["id"]?>';
				getElementById('UserName').value = '<?= $row["SE_USUARIO"]?>';
				getElementById('NombreFoto').value = '<?= $row["SE_FOTO"]?>';
				getElementById('Nombre').innerText = "<?= $row["SE_NOMBRE"]?>";
				getElementById('Interno').value = '<?= $row["SE_INTERNO"]?>';
				getElementById('FechaNacimiento').value = '<?= $row["FECHANACIMIENTO"]?>';
				getElementById('Sector').value = '<?= $row["SECTOR"]?>';
				getElementById('Cargo').value = '<?= $row["CARGO"]?>';
				getElementById('Delegacion').value = '<?= $row["DELEGACION"]?>';
				getElementById('Edificio').value = '<?= $row["SE_IDDELEGACIONSEDE"]?>';
				getElementById('Piso').value = '<?= $row["SE_PISO"]?>';
				getElementById('Legajo').value = '<?= $row["SE_LEGAJO"]?>';
				getElementById('LegajoRRHH').value = '<?= $row["SE_LEGAJORRHH"]?>';
				getElementById('RelacionLaboral').value = '<?= $row["RELACIONLABORAL"]?>';
				getElementById('RespondeA').value = '<?= $row["RESPONDEA"]?>';
				getElementById('HorarioAtencion').value = '<?= $row["SE_HORARIOATENCION"]?>';
				getElementById('imgFoto').src = '<?= "/functions/get_image.php?file=".base64_encode(IMAGES_FOTOS_PATH.$row["SE_FOTO"])?>';
				getElementById('EjeX').value = '<?= $row["SE_EJEX"]?>';
				getElementById('EjeY').value = '<?= $row["SE_EJEY"]?>';
<?
if (is_file(IMAGES_FOTOS_PATH.$row["SE_FOTO"])) {
?>
				getElementById('spanFoto').style.display = 'inline';
				getElementById('spanFoto').style.marginLeft = '99px';
				getElementById('Foto').style.marginLeft = '8px';
<?
}
else {
?>
				getElementById('spanFoto').style.display = 'none';
				getElementById('spanFoto').style.marginLeft = '0px';
				getElementById('Foto').style.marginLeft = '99px';
<?
}
?>
				CambiaDelegacion(window.parent.document);
				CambiaPiso(window.parent.document);
//				SetCoordenadaPuesto(window.parent.document, getElementById('EjeX').value, getElementById('EjeY').value);

				getElementById('divProcesando').style.display = 'none';
				getElementById('datos').style.display = 'block';
				getElementById('divMapa').style.display = 'block';
				getElementById('divBtnGuardar').style.display = 'block';
			}
		</script>
	</head>
	<body>
	</body>
</html>