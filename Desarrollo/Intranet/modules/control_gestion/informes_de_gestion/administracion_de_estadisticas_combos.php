<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT it_id id, it_tema detalle
     FROM intra.cit_informetemas
   	WHERE it_fechabaja IS NULL
UNION ALL
	 SELECT -2, 'Tablero de Control'
		 FROM DUAL
 ORDER BY 2";
$comboTema = new Combo($sql, "tema", $tema);
$comboTema->setOnChange("filtrarTitulosBusqueda(this.value)");

$sql =
	"SELECT ip_id id, ip_titulo detalle
     FROM intra.cip_informepublicado
    WHERE ip_fechabaja IS NULL
 ORDER BY 2";
$comboTitulo = new Combo($sql, "titulo");

$sql =
	"SELECT DISTINCT se_id id, se_nombre detalle
           		FROM use_usuarios, intra.cie_informeestadistica, intra.cip_informepublicado
          	 WHERE se_usuario = ie_usuario
            	 AND ie_idpublicado = ip_id
            	 AND se_fechabaja IS NULL
            	 AND se_usuariogenerico = 'N'
            	 AND ip_fechabaja IS NULL
       		ORDER BY 2";
$comboUsuario = new Combo($sql, "Usuario");
?>