<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");


$showProcessMsg = false;

$nombre = "";
if (isset($_REQUEST["usrBusquedaRapida"]))
	$nombre = $_REQUEST["usrBusquedaRapida"];
elseif (isset($_REQUEST["Nombre"]))
	$nombre = $_REQUEST["Nombre"];

$sector = "";
if (isset($_REQUEST["Sector"]))
	$sector = $_REQUEST["Sector"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = 2;
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];
?>
<link href="/modules/buscar_usuarios/css/style_buscar_usuarios.css" rel="stylesheet" type="text/css" />
<script>
	showTitle(true, 'AGENDA TELEFÓNICA');
</script>
<div id="divBotones" style="margin-bottom:8px; margin-top:-8px;">
	<a href="/modules/buscar_usuarios/instructivo_telefonico.php" target="_blank"><img border="0" id="imgTelefonico" src="/modules/buscar_usuarios/images/instructivo_telefonico.jpg" /></a>
	<a href="mailto:mesadeayuda@provart.com.ar"><img border="0" id="imgAyuda" src="/modules/buscar_usuarios/images/mesa_de_ayuda.jpg" /></a>
	<img border="0" id="imgEmergencias" src="/modules/buscar_usuarios/images/emergencias.jpg" />
	<img border="0" id="imgVigilancia" src="/modules/buscar_usuarios/images/vigilancia.jpg" />
	<a href="mailto:mantenimiento@provart.com.ar"><img border="0" src="/modules/buscar_usuarios/images/mantenimiento.jpg" /></a>
</div>
<form action="/index.php?pageid=5" id="formBuscaInternos" method="get" name="formBuscaInternos">
	<input id="pageid" name="pageid" type="hidden" value="5">
	<input id="buscar" name="buscar" type="hidden" value="yes">
	<div align="center" id="divBuscarUsuarios">
		<label class="FormLabelBlanco" for="Nombre" id="labelNombre">Nombre o Apellido</label>
		<input class="FormInputText" id="Nombre" name="Nombre" type="text" value="<?= $nombre ?>" />
		<label class="FormLabelBlanco" for="Sector" id="labelSector">Sector o Gerencia</label>
		<input class="FormInputText" id="Sector" name="Sector" type="text" value="<?= $sector ?>" />
		<input class="BotonBlanco" id="btnBuscar" type="submit" value="BUSCAR" />
	</div>
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	$params = array();
	$where = "";

	if ($nombre != "") {
		$params[":apenom"] = "%".RemoveAccents(str_replace("ñ", "Ñ", $nombre))."%";
		$where.= " AND useu.se_buscanombre LIKE UPPER(:apenom)";
	}
	if ($sector != "") {
		$params[":sector"] = "%".RemoveAccents($sector)."%";
		$where.= " AND (UPPER(ART.UTILES.reemplazar_acentos(cse3.se_descripcion)) LIKE UPPER(:sector) OR UPPER(ART.UTILES.reemplazar_acentos(cse.se_descripcion)) LIKE UPPER(:sector))";
	}
	if ($where == "")
		$where = " AND 1 = 2";

	$sql =
		"SELECT /*+ index(art.use_usuarios ndx_use_parabusqueda)*/ useu.se_id ¿se_id?,
						useu.se_nombre ¿se_nombre?,
						cse.se_descripcion ¿sector?,
						cse3.se_descripcion ¿gerencia?,
						useu.se_interno ¿se_interno?
			 FROM art.use_usuarios useu, usc_sectores, computos.cse_sector cse, computos.cse_sector cse2, computos.cse_sector cse3
			WHERE useu.se_idsector = cse.se_id
				AND useu.se_fechabaja IS NULL
				AND useu.se_sector = sc_codigo
				AND sc_visible = 'S'
				AND cse.se_visible = 'S'
-- 		    AND useu.se_sector NOT IN ('CALLCENT', 'BPAGOS', 'BAPRO', 'BANK', 'AUDGRUP', 'XUNILSA', 'GBPS', 'ESTJUD', 'DIMO', 'SML')
				AND (useu.se_usuariogenerico = 'N' OR useu.se_sector = 'RECEPCIO')
				AND cse.se_idsectorpadre = cse2.se_id
				AND cse2.se_idsectorpadre = cse3.se_id _EXC1_";
	$grilla = new Grid();
	$grilla->addColumn(new Column("", 8, true, false, -1, "BotonInformacion", "index.php?pageid=56", "GridFirstColumn"));
	$grilla->addColumn(new Column("Nombre"));
	$grilla->addColumn(new Column("Sector"));
	$grilla->addColumn(new Column("Gerencia"));
	$grilla->addColumn(new Column("Interno"));
	$grilla->setColsSeparator(true);
	$grilla->setExtraConditions(array($where));
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRowsSeparator(true);
	$grilla->setShowMessageNoResults((strlen($nombre) <= 2) or (strlen($sector) > 0));
	$grilla->setSql($sql);
	$grilla->Draw();

	if ((strlen($nombre) > 2) and (strlen($sector) == 0) and ($grilla->recordCount() == 0)) {
		$params = array();
		$sql = " AND (1=2";

		// Este for reemplaza cada caracter por un comodin..
		for ($i=0; $i<strlen($nombre); $i++) {
			$texto = $nombre;
			$texto[$i] = "_";

			$params[":nombre1_".$i] = "%".RemoveAccents(strtoupper(str_replace("ñ", "Ñ", $texto)))."%";
			$sql.= " OR useu.se_buscanombre LIKE :nombre1_".$i;
		}

		// Este for quita el caracter en el que se está loopeando..
		for ($i=0; $i<strlen($nombre); $i++) {
			$texto = substr($nombre, 0, $i).substr($nombre, $i + 1);

			$params[":nombre2_".$i] = "%".RemoveAccents(strtoupper(str_replace("ñ", "Ñ", $texto)))."%";
			$sql.= " OR useu.se_buscanombre LIKE :nombre2_".$i;
		}

		// Este for agrega un comodin antes de cada caracter..
		for ($i=0; $i<strlen($nombre); $i++) {
			$texto = substr($nombre, 0, $i)."_".substr($nombre, $i);

			$params[":nombre3_".$i] = "%".RemoveAccents(strtoupper(str_replace("ñ", "Ñ", $texto)))."%";
			$sql.= " OR useu.se_buscanombre LIKE :nombre3_".$i;
		}

		$sql.= ")";
		$grilla->setParams($params);
		$grilla->setExtraConditions(array($sql));
		echo "<div align='center' style='width:100%' ><div id='noDatos' style='padding:1px; width:80%'>No se encontraron datos con las caracteristicas buscadas, quizás quiso buscar a</div></div>";
		$grilla->setShowMessageNoResults(true);
		$grilla->Draw();

		if ($grilla->recordCount() == 0) {
			echo "<script>document.getElementById('noDatos').style.display = 'none';</script>";
		}
	}
}
?>
</form>
<script>
	document.getElementById('Nombre').focus();
</script>