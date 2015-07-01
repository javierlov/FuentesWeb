<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT ar_id id, ar_nombre detalle
		 FROM aar_art
		WHERE ar_fechabaja IS NULL
 ORDER BY 2";
$comboArt = new Combo($sql, "art", ($alta)?-1:$row["IDARTANTERIOR"]);
$comboArt->setOnChange("document.getElementById('artTmp').value = this.value;");

$sql =
	"SELECT DECODE(tb_codigo, -1, 0, tb_codigo) id, tb_codigo || ' - ' || tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'STBCR'
			AND tb_codigo <> '0'
			AND tb_fechabaja IS NULL
 ORDER BY 2";
$comboStatusBcra = new Combo($sql, "statusBcra", ($alta)?-1:$row["STATUSBCRA"]);

$sql =
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'SECT'
			AND tb_codigo IN(2, 3, 4)
			AND tb_fechabaja IS NULL
 ORDER BY 2";
$comboSector = new Combo($sql, "sector", ($alta)?-1:$row["SECTOR"]);

$sql =
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'STSRT'
			AND tb_codigo <> '0'
			AND tb_fechabaja IS NULL
 ORDER BY 2";
$comboStatusSrt = new Combo($sql, "statusSrt", ($alta)?-1:$row["STATUSSRT"]);
$comboStatusSrt->setFirstItem("Desconocido");
$comboStatusSrt->setOnChange("document.getElementById('statusSrtTmp').value = this.value;");

$sql =
	"SELECT zg_id id, zg_descripcion detalle
		 FROM afi.azg_zonasgeograficas
		WHERE zg_fechabaja IS NULL
 ORDER BY 2";
$comboZonaGeografica = new Combo($sql, "zonaGeografica", ($alta)?-1:$row["ZONAGEOGRAFICA"]);
?>