<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT carredudesabr id, carredudesabr detalle
		 FROM rhpror3.cap_carr_edu
 ORDER BY 2";
$comboCarrera1 = new Combo($sql, "carrera1");
$comboCarrera1->setClass("carrera");

$sql =
	"SELECT carredudesabr id, carredudesabr detalle
		 FROM rhpror3.cap_carr_edu
 ORDER BY 2";
$comboCarrera2 = new Combo($sql, "carrera2");
$comboCarrera2->setClass("carrera");

$sql =
	"SELECT carredudesabr id, carredudesabr detalle
		 FROM rhpror3.cap_carr_edu
 ORDER BY 2";
$comboCarrera3 = new Combo($sql, "carrera3");
$comboCarrera3->setClass("carrera");

$sql =
	"SELECT carredudesabr id, carredudesabr detalle
		 FROM rhpror3.cap_carr_edu
 ORDER BY 2";
$comboCarrera4 = new Combo($sql, "carrera4");
$comboCarrera4->setClass("carrera");

$sql =
	"SELECT eltanadesabr id, eltanadesabr detalle
		 FROM rhpror3.eltoana
 ORDER BY 2";
$comboElemento1 = new Combo($sql, "elemento1");
$comboElemento1->setClass("elemento");

$sql =
	"SELECT eltanadesabr id, eltanadesabr detalle
		 FROM rhpror3.eltoana
 ORDER BY 2";
$comboElemento2 = new Combo($sql, "elemento2");
$comboElemento2->setClass("elemento");

$sql =
	"SELECT eltanadesabr id, eltanadesabr detalle
		 FROM rhpror3.eltoana
 ORDER BY 2";
$comboElemento3 = new Combo($sql, "elemento3");
$comboElemento3->setClass("elemento");

$sql =
	"SELECT eltanadesabr id, eltanadesabr detalle
		 FROM rhpror3.eltoana
 ORDER BY 2";
$comboElemento4 = new Combo($sql, "elemento4");
$comboElemento4->setClass("elemento");

$sql =
	"SELECT idnivdesabr id, idnivdesabr detalle
		 FROM rhpror3.idinivel
 ORDER BY 2";
$comboEscribeNivel1 = new Combo($sql, "escribeNivel1");
$comboEscribeNivel1->setClass("escribeNivel");

$sql =
	"SELECT idnivdesabr id, idnivdesabr detalle
		 FROM rhpror3.idinivel
 ORDER BY 2";
$comboEscribeNivel2 = new Combo($sql, "escribeNivel2");
$comboEscribeNivel2->setClass("escribeNivel");

$sql =
	"SELECT idnivdesabr id, idnivdesabr detalle
		 FROM rhpror3.idinivel
 ORDER BY 2";
$comboEscribeNivel3 = new Combo($sql, "escribeNivel3");
$comboEscribeNivel3->setClass("escribeNivel");

$sql =
	"SELECT idnivdesabr id, idnivdesabr detalle
		 FROM rhpror3.idinivel
 ORDER BY 2";
$comboEscribeNivel4 = new Combo($sql, "escribeNivel4");
$comboEscribeNivel4->setClass("escribeNivel");

$sql =
	"SELECT estcivdesabr id, estcivdesabr detalle
		 FROM rhpror3.estcivil
 ORDER BY 2";
$comboEstadoCivil = new Combo($sql, "estadoCivil");

$sql =
	"SELECT idnivdesabr id, idnivdesabr detalle
		 FROM rhpror3.idinivel
 ORDER BY 2";
$comboHablaNivel1 = new Combo($sql, "hablaNivel1");
$comboHablaNivel1->setClass("hablaNivel");

$sql =
	"SELECT idnivdesabr id, idnivdesabr detalle
		 FROM rhpror3.idinivel
 ORDER BY 2";
$comboHablaNivel2 = new Combo($sql, "hablaNivel2");
$comboHablaNivel2->setClass("hablaNivel");

$sql =
	"SELECT idnivdesabr id, idnivdesabr detalle
		 FROM rhpror3.idinivel
 ORDER BY 2";
$comboHablaNivel3 = new Combo($sql, "hablaNivel3");
$comboHablaNivel3->setClass("hablaNivel");

$sql =
	"SELECT idnivdesabr id, idnivdesabr detalle
		 FROM rhpror3.idinivel
 ORDER BY 2";
$comboHablaNivel4 = new Combo($sql, "hablaNivel4");
$comboHablaNivel3->setClass("hablaNivel");

$sql =
	"SELECT ididesc id, ididesc detalle
		 FROM rhpror3.idioma
 ORDER BY 2";
$comboIdioma1 = new Combo($sql, "idioma1");
$comboIdioma1->setClass("idioma");

$sql =
	"SELECT ididesc id, ididesc detalle
		 FROM rhpror3.idioma
 ORDER BY 2";
$comboIdioma2 = new Combo($sql, "idioma2");
$comboIdioma2->setClass("idioma");

$sql =
	"SELECT ididesc id, ididesc detalle
		 FROM rhpror3.idioma
 ORDER BY 2";
$comboIdioma3 = new Combo($sql, "idioma3");
$comboIdioma3->setClass("idioma");

$sql =
	"SELECT ididesc id, ididesc detalle
		 FROM rhpror3.idioma
 ORDER BY 2";
$comboIdioma4 = new Combo($sql, "idioma4");
$comboIdioma4->setClass("idioma");

$sql =
	"SELECT instabre id, instabre detalle
		 FROM rhpror3.institucion
 ORDER BY 2";
$comboInstitucion1 = new Combo($sql, "institucion1");
$comboInstitucion1->setClass("institucion");

$sql =
	"SELECT instabre id, instabre detalle
		 FROM rhpror3.institucion
 ORDER BY 2";
$comboInstitucion2 = new Combo($sql, "institucion2");
$comboInstitucion2->setClass("institucion");

$sql =
	"SELECT instabre id, instabre detalle
		 FROM rhpror3.institucion
 ORDER BY 2";
$comboInstitucion3 = new Combo($sql, "institucion3");
$comboInstitucion3->setClass("institucion");

$sql =
	"SELECT instabre id, instabre detalle
		 FROM rhpror3.institucion
 ORDER BY 2";
$comboInstitucion4 = new Combo($sql, "institucion4");
$comboInstitucion4->setClass("institucion");

$sql =
	"SELECT instdes id, instdes detalle
		 FROM rhpror3.institucion
 ORDER BY 2";
$comboInstituto1 = new Combo($sql, "instituto1");
$comboInstituto1->setClass("instituto");

$sql =
	"SELECT instdes id, instdes detalle
		 FROM rhpror3.institucion
 ORDER BY 2";
$comboInstituto2 = new Combo($sql, "instituto2");
$comboInstituto2->setClass("instituto");

$sql =
	"SELECT instdes id, instdes detalle
		 FROM rhpror3.institucion
 ORDER BY 2";
$comboInstituto3 = new Combo($sql, "instituto3");
$comboInstituto3->setClass("instituto");

$sql =
	"SELECT instdes id, instdes detalle
		 FROM rhpror3.institucion
 ORDER BY 2";
$comboInstituto4 = new Combo($sql, "instituto4");
$comboInstituto4->setClass("instituto");

$sql =
	"SELECT idnivdesabr id, idnivdesabr detalle
		 FROM rhpror3.idinivel
 ORDER BY 2";
$comboLeeNivel1 = new Combo($sql, "leeNivel1");
$comboLeeNivel1->setClass("leeNivel");

$sql =
	"SELECT idnivdesabr id, idnivdesabr detalle
		 FROM rhpror3.idinivel
 ORDER BY 2";
$comboLeeNivel2 = new Combo($sql, "leeNivel2");
$comboLeeNivel2->setClass("leeNivel");

$sql =
	"SELECT idnivdesabr id, idnivdesabr detalle
		 FROM rhpror3.idinivel
 ORDER BY 2";
$comboLeeNivel3 = new Combo($sql, "leeNivel3");
$comboLeeNivel3->setClass("leeNivel");

$sql =
	"SELECT idnivdesabr id, idnivdesabr detalle
		 FROM rhpror3.idinivel
 ORDER BY 2";
$comboLeeNivel4 = new Combo($sql, "leeNivel4");
$comboLeeNivel4->setClass("leeNivel");

$sql =
	"SELECT locdesc id, locdesc detalle
		 FROM rhpror3.localidad
 ORDER BY 2";
$comboLocalidad = new Combo($sql, "localidad");

$sql =
	"SELECT nacionaldes id, nacionaldes detalle
		 FROM rhpror3.nacionalidad
 ORDER BY 2";
$comboNacionalidad = new Combo($sql, "nacionalidad");

$sql =
	"SELECT espnivdesabr id, espnivdesabr detalle
		 FROM rhpror3.espnivel
 ORDER BY 2";
$comboNivelEspecializacion1 = new Combo($sql, "nivelEspecializacion1");
$comboNivelEspecializacion1->setClass("nivelEspecializacion");

$sql =
	"SELECT espnivdesabr id, espnivdesabr detalle
		 FROM rhpror3.espnivel
 ORDER BY 2";
$comboNivelEspecializacion2 = new Combo($sql, "nivelEspecializacion2");
$comboNivelEspecializacion2->setClass("nivelEspecializacion");

$sql =
	"SELECT espnivdesabr id, espnivdesabr detalle
		 FROM rhpror3.espnivel
 ORDER BY 2";
$comboNivelEspecializacion3 = new Combo($sql, "nivelEspecializacion3");
$comboNivelEspecializacion3->setClass("nivelEspecializacion");

$sql =
	"SELECT espnivdesabr id, espnivdesabr detalle
		 FROM rhpror3.espnivel
 ORDER BY 2";
$comboNivelEspecializacion4 = new Combo($sql, "nivelEspecializacion4");
$comboNivelEspecializacion4->setClass("nivelEspecializacion");

$sql =
	"SELECT nivdesc id, nivdesc detalle
		 FROM rhpror3.nivest
 ORDER BY 2";
$comboNivelFormacion1 = new Combo($sql, "nivelFormacion1");
$comboNivelFormacion1->setClass("nivelFormacion");

$sql =
	"SELECT nivdesc id, nivdesc detalle
		 FROM rhpror3.nivest
 ORDER BY 2";
$comboNivelFormacion2 = new Combo($sql, "nivelFormacion2");
$comboNivelFormacion2->setClass("nivelFormacion");

$sql =
	"SELECT nivdesc id, nivdesc detalle
		 FROM rhpror3.nivest
 ORDER BY 2";
$comboNivelFormacion3 = new Combo($sql, "nivelFormacion3");
$comboNivelFormacion3->setClass("nivelFormacion");

$sql =
	"SELECT nivdesc id, nivdesc detalle
		 FROM rhpror3.nivest
 ORDER BY 2";
$comboNivelFormacion4 = new Combo($sql, "nivelFormacion4");
$comboNivelFormacion4->setClass("nivelFormacion");

$sql =
	"SELECT paisdesc id, paisdesc detalle
		 FROM rhpror3.pais
 ORDER BY 2";
$comboPais = new Combo($sql, "pais");

$sql =
	"SELECT paisdesc id, paisdesc detalle
		 FROM rhpror3.pais
 ORDER BY 2";
$comboPaisNacimiento = new Combo($sql, "paisNacimiento");

$sql =
	"SELECT partnom id, partnom detalle
		 FROM rhpror3.partido
 ORDER BY 2";
$comboPartido = new Combo($sql, "partido");

$sql =
	"SELECT provdesc id, provdesc detalle
		 FROM rhpror3.provincia
 ORDER BY 2";
$comboProvincia = new Combo($sql, "provincia");

$sql =
	"SELECT espdesabr id, espdesabr detalle
		 FROM rhpror3.especializacion
 ORDER BY 2";
$comboTipo1 = new Combo($sql, "tipo1");
$comboTipo1->setClass("tipo");

$sql =
	"SELECT espdesabr id, espdesabr detalle
		 FROM rhpror3.especializacion
 ORDER BY 2";
$comboTipo2 = new Combo($sql, "tipo2");
$comboTipo2->setClass("tipo");

$sql =
	"SELECT espdesabr id, espdesabr detalle
		 FROM rhpror3.especializacion
 ORDER BY 2";
$comboTipo3 = new Combo($sql, "tipo3");
$comboTipo3->setClass("tipo");

$sql =
	"SELECT espdesabr id, espdesabr detalle
		 FROM rhpror3.especializacion
 ORDER BY 2";
$comboTipo4 = new Combo($sql, "tipo4");
$comboTipo4->setClass("tipo");

$sql =
	"SELECT tipcurdesabr id, tipcurdesabr detalle
		 FROM rhpror3.cap_tipocurso
 ORDER BY 2";
$comboTipoCurso1 = new Combo($sql, "tipoCurso1");
$comboTipoCurso1->setClass("tipoCurso");

$sql =
	"SELECT tipcurdesabr id, tipcurdesabr detalle
		 FROM rhpror3.cap_tipocurso
 ORDER BY 2";
$comboTipoCurso2 = new Combo($sql, "tipoCurso2");
$comboTipoCurso2->setClass("tipoCurso");

$sql =
	"SELECT tipcurdesabr id, tipcurdesabr detalle
		 FROM rhpror3.cap_tipocurso
 ORDER BY 2";
$comboTipoCurso3 = new Combo($sql, "tipoCurso3");
$comboTipoCurso3->setClass("tipoCurso");

$sql =
	"SELECT tipcurdesabr id, tipcurdesabr detalle
		 FROM rhpror3.cap_tipocurso
 ORDER BY 2";
$comboTipoCurso4 = new Combo($sql, "tipoCurso4");
$comboTipoCurso4->setClass("tipoCurso");

$sql =
	"SELECT tidsigla id, tidsigla detalle
		 FROM rhpror3.tipodocu
 ORDER BY 2";
$comboTipoDocumento = new Combo($sql, "tipoDocumento");

$sql =
	"SELECT titdesabr id, titdesabr detalle
		 FROM rhpror3.titulo
 ORDER BY 2";
$comboTitulo1 = new Combo($sql, "titulo1");
$comboTitulo1->setClass("titulo");

$sql =
	"SELECT titdesabr id, titdesabr detalle
		 FROM rhpror3.titulo
 ORDER BY 2";
$comboTitulo2 = new Combo($sql, "titulo2");
$comboTitulo2->setClass("titulo");

$sql =
	"SELECT titdesabr id, titdesabr detalle
		 FROM rhpror3.titulo
 ORDER BY 2";
$comboTitulo3 = new Combo($sql, "titulo3");
$comboTitulo3->setClass("titulo");

$sql =
	"SELECT titdesabr id, titdesabr detalle
		 FROM rhpror3.titulo
 ORDER BY 2";
$comboTitulo4 = new Combo($sql, "titulo4");
$comboTitulo4->setClass("titulo");
?>