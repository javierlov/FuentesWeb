<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


for ($i=1; $i<=$totalRegistros; $i++) {
	$sql =
		"SELECT se_id id, UPPER(SUBSTR(se_usuario, 1, 2)) || LOWER(SUBSTR(se_usuario, 3, 1000)) detalle
			 FROM use_usuarios
			WHERE se_fechabaja IS NULL
				AND se_usuariogenerico = 'N'
	 ORDER BY 2";
	$comboUsuario[] = new Combo($sql, "usuario".$i);

	$sql =
		"SELECT 'F' id, 'No informa' detalle
			 FROM DUAL
	UNION ALL
		 SELECT 'T', 'Informa'
			 FROM DUAL
	 ORDER BY 2";
	$comboAcciones[] = new Combo($sql, "acciones".$i);
}
?>