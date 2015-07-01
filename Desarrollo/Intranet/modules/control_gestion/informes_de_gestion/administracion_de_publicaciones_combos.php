<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT it_id id, it_tema detalle
     FROM intra.cit_informetemas
   	WHERE it_fechabaja IS NULL
 ORDER BY 2 DESC";
$comboTemaFiltro = new Combo($sql, "temaFiltro", $temaFiltro);
$comboTemaFiltro->setFocus(true);

$sql =
	"SELECT it_id id, it_tema detalle
     FROM intra.cit_informetemas
   	WHERE it_fechabaja IS NULL
 ORDER BY 2 DESC";
$comboTema = new Combo($sql, "tema");
?>