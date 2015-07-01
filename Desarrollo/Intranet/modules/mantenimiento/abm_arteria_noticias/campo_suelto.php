<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT TO_CHAR(ba_".$_REQUEST["tipo"].")
		 FROM rrhh.rba_boletinesarteria
		WHERE ba_id = :id";
?>
<html>
	<head>
		<link href="/styles/style.css" rel="stylesheet" type="text/css">
		<link href="/modules/arteria_noticias/css/style.css" rel="stylesheet" type="text/css" />
		<script language="JavaScript" src="/js/functions.js"></script>
		<script language="JavaScript" src="/js/validations.js"></script>
		<!-- INICIO CALENDARIO.. -->
		<style type="text/css">@import url(/js/Calendario/calendar-system.css);</style>
		<script type="text/javascript" src="/js/Calendario/calendar.js"></script>
		<script type="text/javascript" src="/js/Calendario/calendar-es.js"></script>
		<script type="text/javascript" src="/js/Calendario/calendar-setup.js"></script>
		<!-- FIN CALENDARIO.. -->
	</head>
	<body>
		<form action="/modules/mantenimiento/abm_arteria_noticias/procesar_campo.php" id="formCampo" method="post" name="formCampo" onSubmit="return ValidarForm(formCampo)">
			<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
			<input id="tipo" name="tipo" type="hidden" value="<?= $_REQUEST["tipo"]?>" />
			<div style="margin-left:16px; margin-top:16px;">
				<label class="FormLabelAzul" for="campo"><?= $_REQUEST["titulo"]?></label>
<?
if ($_REQUEST["tipo"] == "fecha") {
?>
				<input class="FormInputText" id="campo" maxlength="10" name="campo" size="12" type="text" title="Fecha" validar="true" validarFecha="true" value="<?= valorSql($sql, "", $params)?>" /><input class="BotonFecha" id="btnFecha" name="btnFecha" type="button" value="" />
<?
}
elseif ($_REQUEST["tipo"] == "emailsContacto") {
?>
				<input class="FormInputText" id="campo" maxlength="256" name="campo" size="32" title="Valor" type="text" validar="true" validarEmail="true"  value="<?= valorSql($sql, "", $params)?>" />
<?
}
else {
?>
				<input class="FormInputText" id="campo" name="campo" size="4" title="Valor" type="text" validar="true" validarEntero="true" value="<?= valorSql($sql, "", $params)?>" />
<?
}
?>
			</div>
			<div style="margin-left:16px; margin-top:16px;">
				<input class="BotonBlanco" name="btnGuardar" type="submit" value="Guardar" />
			</div>
		</form>
		<script>
			if (document.getElementById('btnFecha') != null)
				Calendar.setup ({
					inputField: "campo",
					ifFormat  : "%d/%m/%Y",
					button    : "btnFecha"
				});
		</script>
	</body>
</html>