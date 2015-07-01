<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT id, detalle
		 FROM (SELECT 1 id, 'Si' detalle
						 FROM DUAL
				UNION ALL
					 SELECT 0 id, 'No' detalle
						 FROM DUAL)
 ORDER BY 2 DESC";
$comboAccidenteTransito = new Combo($sql, "accidenteTransito", ($cargarCampos)?$row["EW_TRANSITO"]:0);
$comboAccidenteTransito->setOnBlur("getElementById('spanAccidenteTransito').innerHTML = iif((this.value == -1), '', this.options[this.selectedIndex].text);");

$sql =
	"SELECT lm_id id, lm_descripcion detalle
		 FROM sin.slm_lesionagentematerial
		WHERE lm_fechabaja IS NULL
			AND lm_subtitulo IS NULL
			AND LENGTH(lm_codigo) > 1
 ORDER BY 2";
$comboAgenteMaterial = new Combo($sql, "agenteMaterial", ($cargarCampos)?$row["EW_AGENTE"]:-1);
$comboAgenteMaterial->setOnBlur("getElementById('spanAgenteCausante').innerHTML = iif((this.value == -1), '', this.options[this.selectedIndex].text);");

$sql =
	"SELECT 0 id, 'Ajeno' detalle
		 FROM DUAL
UNION ALL
	 SELECT 1, 'Propio'
		 FROM DUAL";
$comboEstablecimientoAccidente = new Combo($sql, "establecimientoAccidente", ($cargarCampos)?$row["EW_ESTABLEPROPIO"]:1);
$comboEstablecimientoAccidente->setAddFirstItem(false);
$comboEstablecimientoAccidente->setOnBlur("getElementById('spanEstablecimientoPropio').innerHTML = iif((this.value == -1), '', this.options[this.selectedIndex].text);");

$sql =
	"SELECT es_id id, es_nombre || ' (' || art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, NULL) || art.utiles.armar_localidad(es_cpostal, NULL, es_localidad, es_provincia) || ')' detalle
		 FROM aes_establecimiento
		WHERE es_contrato = :contrato
 ORDER BY 2";
$comboEstablecimientoPropio = new Combo($sql, "establecimientoPropio", ($cargarCampos)?$row["EW_ESTABLECIMIENTO"]:-1);
$comboEstablecimientoPropio->addParam(":contrato", $_SESSION["contrato"]);
$comboEstablecimientoPropio->setOnBlur("copiarLugarOcurrencia()");
$comboEstablecimientoPropio->setOnChange("copiarDomicilioEstablecimiento(this.value, 't')");

$sql =
	"SELECT et_id id, et_nombre || ' (' || art.utiles.armar_domicilio(et_calle, et_numero, et_piso, et_departamento, NULL) || art.utiles.armar_localidad(et_cpostal, NULL, et_localidad, et_provincia) || ')' detalle
		 FROM SIN.set_establecimiento_temporal
		WHERE et_fechabaja IS NULL
			AND et_cuit = :cuit
 ORDER BY 2";
$comboEstablecimientoTercero = new Combo($sql, "establecimientoTercero", ($cargarCampos)?$row["EW_ESTABLECIMIENTO"]:-1);
$comboEstablecimientoTercero->addParam(":cuit", $_SESSION["cuit"]);
$comboEstablecimientoTercero->setOnBlur("copiarLugarOcurrencia()");
$comboEstablecimientoTercero->setOnChange("copiarDomicilioEstablecimiento(this.value, 'f')");

$sql =
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'ESTAD'
			AND tb_codigo <> '0'
 ORDER BY 2";
$comboEstadoCivil = new Combo($sql, "estadoCivil", ($cargarCampos)?$row["JW_ESTCIVIL"]:-1);
$comboEstadoCivil->setOnBlur("getElementById('spanEstadoCivil').innerHTML = iif((this.value == -1), '', this.options[this.selectedIndex].text);");

$sql =
	"SELECT lf_id id, lf_descripcion detalle
		 FROM sin.slf_lesionforma
		WHERE lf_fechabaja IS NULL
			AND lf_subtitulo = 'N'
 ORDER BY 2";
$comboFormaAccidente = new Combo($sql, "formaAccidente", ($cargarCampos)?$row["EW_FORMA"]:-1);
$comboFormaAccidente->setOnBlur("getElementById('spanFormaAccidente').innerHTML = iif((this.value == -1), '', this.options[this.selectedIndex].text);");

$sql =
	"SELECT tg_id id, tg_descripcion detalle
		 FROM sin.stg_tipogravedad
 ORDER BY 2";
$comboGravedadPresunta = new Combo($sql, "gravedadPresunta", ($cargarCampos)?$row["EW_GRAVEDAD"]:-1);
$comboGravedadPresunta->setOnBlur("getElementById('spanGravedadPresunta').innerHTML = iif((this.value == -1), '', this.options[this.selectedIndex].text);");

$sql =
	"SELECT id, detalle
		 FROM (SELECT 1 id, 'En el puesto de trabajo' detalle
						 FROM DUAL
				UNION ALL
					 SELECT 2, 'Desplazamiento en día laboral'
						 FROM DUAL
				UNION ALL
					 SELECT 3, 'Al ir/volver del trabajo'
						 FROM DUAL
				UNION ALL
					 SELECT 4, 'Otro puesto de trabajo'
						 FROM DUAL
				UNION ALL
					 SELECT 5, 'Otros (detallar)'
						 FROM DUAL)
 ORDER BY 1";
$comboLugarOcurrencia = new Combo($sql, "lugarOcurrencia", ($cargarCampos)?$row["EW_LUGAROCURRENCIA"]:-1);
$comboLugarOcurrencia->setFirstItem("- SIN DEFINIR -");
$comboLugarOcurrencia->setOnBlur("copiarLugarOcurrencia()");
$comboLugarOcurrencia->setOnChange("cambiaLugarOcurrencia(".(($tieneEstablecimientosDeTercero)?"true":"false").", this.value)");

$sql =
	"SELECT id, detalle
		 FROM (SELECT 'I' id, 'Izquierda' detalle
						 FROM DUAL
				UNION ALL
					 SELECT 'D' id, 'Derecha' detalle
						 FROM DUAL
				UNION ALL
					 SELECT 'A' id, 'Ambas' detalle
						 FROM DUAL)
 ORDER BY 2";
$comboManoHabil = new Combo($sql, "manoHabil", ($cargarCampos)?$row["EW_MANOHABIL"]:'A');
$comboManoHabil->setAddFirstItem(false);
$comboManoHabil->setOnBlur("getElementById('spanManoHabil').innerHTML = iif((this.value == -1), '', this.options[this.selectedIndex].text);");

$sql =
	"SELECT na_id id, na_descripcion detalle
		 FROM cna_nacionalidad
		WHERE na_fechabaja IS NULL
 ORDER BY 2";
$comboNacionalidad = new Combo($sql, "nacionalidad", ($cargarCampos)?$row["JW_NACIONALIDAD"]:-1);
$comboNacionalidad->setOnBlur("getElementById('spanNacionalidad').innerHTML = iif((this.value == -1), '', this.options[this.selectedIndex].text);");

$sql =
	"SELECT ln_id id, ln_descripcion detalle
		 FROM sin.sln_lesionnaturaleza
		WHERE ln_fechabaja IS NULL
 ORDER BY 2";
$comboNaturalezaLesion = new Combo($sql, "naturalezaLesion", ($cargarCampos)?$row["EW_NATURALEZA"]:-1);
$comboNaturalezaLesion->setOnBlur("getElementById('spanNaturalezaLesion').innerHTML = iif((this.value == -1), '', this.options[this.selectedIndex].text);");

$sql =
	"SELECT lz_id id, lz_descripcion detalle
		 FROM sin.slz_lesionzona
		WHERE lz_fechabaja IS NULL
 ORDER BY 2";
$comboParteCuerpoLesionada = new Combo($sql, "parteCuerpoLesionada", ($cargarCampos)?$row["EW_ZONA"]:-1);
$comboParteCuerpoLesionada->setOnBlur("getElementById('spanParteCuerpoLesionada').innerHTML = iif((this.value == -1), '', this.options[this.selectedIndex].text);");

$sql =
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'SEXOS'
			AND tb_codigo <> '0'
 ORDER BY 2";
$comboSexo = new Combo($sql, "sexo", ($cargarCampos)?$row["JW_SEXO"]:-1);
$comboSexo->setOnBlur("getElementById('spanSexo').innerHTML = iif((this.value == -1), '', this.options[this.selectedIndex].text);");

$sql =
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'STIPO'
			AND tb_codigo <> '0'
 ORDER BY 2";
$comboTipoSiniestro = new Combo($sql, "tipoSiniestro", ($cargarCampos)?$row["EW_TIPOSINIESTRO"]:-1);
$comboTipoSiniestro->setOnBlur("getElementById('spanTipoSiniestro').innerHTML = iif((this.value == -1), '', this.options[this.selectedIndex].text);");
$comboTipoSiniestro->setOnChange("cambiaTipoSiniestro(this.value)");
?>