<link rel="stylesheet" href="/modules/biblioteca/styles/style.css" type="text/css" />
<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


function getGrid($autor, $estado, $isbn, $tema, $titulo) {
	$ob = "6";
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];
	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];
	$sb = false;
	if (isset($_REQUEST["sb"]))
		if ($_REQUEST["sb"] == "T")
			$sb = true;
	$showProcessMsg = false;

	$params = array();
	$where = "";

	if ($autor != "") {
		$params[":autor"] = "%".$autor."%";
		$where.= " AND UPPER(li_autor) LIKE UPPER(:autor)";
	}
	if ($estado != "") {
		$params[":estado"] = "%".$estado."%";
		$where.= " AND UPPER(li_estado) LIKE UPPER(:estado)";
	}
	if ($isbn != "") {
		$params[":isbn"] = "%".$isbn."%";
		$where.= " AND UPPER(li_ibsn) LIKE UPPER(:isbn)";
	}
	if ($tema != "") {
		$params[":tema"] = "%".$tema."%";
		$where.= " AND UPPER(li_tema) LIKE UPPER(:tema)";
	}
	if ($titulo != "") {
		$params[":titulo"] = "%".$titulo."%";
		$where.= " AND UPPER(li_titulo) LIKE UPPER(:titulo)";
	}

	$sql =
		"SELECT li_id ¿id?,
						pr_id ¿id2?,
						pr_id ¿id3?,
						li_id ¿id4?,
						li_autor ¿autor?,
						li_titulo ¿titulo?,
						li_tema ¿tema?,
						li_ibsn ¿isbn?,
					  pr_idusuario ¿usuario?,
						li_estado ¿estado?,
						pr_fechavencimiento ¿fechavencimiento?,
						li_fechabaja ¿baja?
						DECODE(li_estado, 'LIBRE', 'F', 'T') ¿hidecol1?,
						DECODE(li_estado, 'RESERVA', DECODE(".((HasPermiso(60))?1:0).", 1, 'F', 'T'), 'T') ¿hidecol2?,
						DECODE(li_estado, 'PRESTADO', DECODE(".((HasPermiso(60))?1:0).", 1, 'F', 'T'), 'T') ¿hidecol3?,
						DECODE(".((HasPermiso(60))?1:0).", 1, 'F', 'T') ¿hidecol4?,
						'Reservar' ¿reservar?,
						'Entregar' ¿entregar?,
						'Devolver' ¿devolver?,
						'Modificar' ¿modificar?
			 FROM rrhh.bli_libro, rrhh.bpr_prestamo
			WHERE li_id = pr_idlibro(+)
				AND (pr_id IN(SELECT maximo
												FROM (SELECT pr_idlibro, MAX(pr_id) maximo
																FROM rrhh.bpr_prestamo
														GROUP BY pr_idlibro))
						OR pr_id IS NULL)".$where;
	$grilla = new Grid(15, 5);
	$grilla->addColumn(new Column("R", 8, true, false, 17, "btnReservar", "/modules/biblioteca/accion_grilla.php?accion=R", "", -1, true, -1, "Reservar"));
	$grilla->addColumn(new Column("E", 8, true, false, 18, "btnEntregar", "/modules/biblioteca/accion_grilla.php?accion=E", "", -1, true, -1, "Entregar"));
	$grilla->addColumn(new Column("D", 8, true, false, 19, "btnDevolver", "/modules/biblioteca/accion_grilla.php?accion=D", "", -1, true, -1, "Devolver"));
	$grilla->addColumn(new Column("M", 8, true, false, 20, "btnModificar", "/modules/biblioteca/accion_grilla.php?accion=M", "", -1, true, -1, "Modificar"));
	$grilla->addColumn(new Column("Autor"));
	$grilla->addColumn(new Column("Título"));
	$grilla->addColumn(new Column("Tema"));
	$grilla->addColumn(new Column("I.S.B.N."));
	$grilla->addColumn(new Column("Usuario Préstamo"));
	$grilla->addColumn(new Column("Estado"));
	$grilla->addColumn(new Column("F. Vencimiento"));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 1));
	$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 2));
	$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 3));
	$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 4));
	$grilla->addColumn(new Column("", 0, false));
	$grilla->addColumn(new Column("", 0, false));
	$grilla->addColumn(new Column("", 0, false));
	$grilla->addColumn(new Column("", 0, false));
	$grilla->setBaja(12, $sb, false);
	$grilla->setColsSeparator(true);
	$grilla->setFieldBaja("li_fechabaja");
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRowsSeparator(true);
	$grilla->setRowsSeparatorColor("#c0c0c0");
	$grilla->setShowTotalRegistros(true);
	$grilla->setSql($sql);
	$grilla->setUseTmpIframe(true);

	return $grilla->Draw(true);
}
?>