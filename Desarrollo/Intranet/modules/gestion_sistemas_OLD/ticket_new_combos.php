<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT se_id id, InitCap(se_nombre) detalle                             /* El propio usuario */
		 FROM art.use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuariogenerico = 'N'
			AND se_id = :id
UNION ALL
	 SELECT se_id ID, InitCap(se_nombre) DETALLE                             /* El jefe */
		 FROM art.use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuariogenerico = 'N'
			AND se_id = NVL(:idjefe, -1)
		UNION
	 SELECT se_id ID, InitCap(se_nombre) DETALLE                             /* Los compañeros de trabajo */
		 FROM art.use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuariogenerico = 'N'
			AND se_sector = NVL(:sector, '')
		UNION
	 SELECT se_id ID, InitCap(se_nombre) DETALLE                             /* Los otros compañeros de trabajo */
		 FROM art.use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuariogenerico = 'N'
			AND se_idsector = NVL(:idsector, -1)
		UNION
	 SELECT se_id ID, InitCap(se_nombre) DETALLE                             /* Los otros empleados a cargo */
		 FROM art.use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuariogenerico = 'N'
			AND se_respondea = NVL(UPPER(:respondea), '')
		UNION
	 SELECT se_id ID, InitCap(se_nombre) DETALLE                             /* Los empleados de los empleados a cargo */
		 FROM art.use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuariogenerico = 'N'
			AND se_respondea IN (SELECT se_usuario
														 FROM art.use_usuarios
														WHERE se_fechabaja IS NULL
															AND se_usuariogenerico = 'N'
															AND se_respondea = NVL(UPPER(:respondea), ''))
 ORDER BY 2";
$comboUsuarioSolicitud = new Combo($sql, "UsuarioSolicitud", getUserId());
$comboUsuarioSolicitud->addParam(":id", getUserId());
$comboUsuarioSolicitud->addParam(":idjefe", getUserIdJefe(NULL));
$comboUsuarioSolicitud->addParam(":idsector", getUserIdSectorIntranet());
$comboUsuarioSolicitud->addParam(":respondea", getWindowsLoginName());
$comboUsuarioSolicitud->addParam(":sector", getUserSector());
$comboUsuarioSolicitud->setClass("Combo");
$comboUsuarioSolicitud->setOnChange("ValidarPermisoUsuario();");

$sql =
	"SELECT ms_id id, ms_descripcion detalle
		 FROM computos.cms_motivosolicitud
		WHERE ms_idpadre = -1
			AND ms_visible = 'S'
			AND ms_fechabaja IS NULL
			AND ms_id IN (SELECT ms_idpadre
											FROM computos.cms_motivosolicitud, computos.cts_ticketsector
										 WHERE art.agenda_pkg.is_sectordependiente(ts_idsector, ms_idsectordefault) = 'S'
											 AND ts_idsistematicket = ".$sistema.")
          ORDER BY 2";
$comboTipoPedido = new Combo($sql, "TipoPedido");
$comboTipoPedido->setClass("Combo");
$comboTipoPedido->setOnChange("AjaxRequest('DivDetallePedido', 'ajax_detalle_motivos.php', document.formSolicitud.TipoPedido.options[document.formSolicitud.TipoPedido.selectedIndex].value);");

$sql =
	"SELECT id, detalle
		 FROM (SELECT 1 ID, 'Alta' DETALLE
						 FROM DUAL
				UNION ALL
					 SELECT 2 ID, 'Media' DETALLE
						 FROM DUAL
				UNION ALL
					 SELECT 3 ID, 'Baja' DETALLE
						 FROM DUAL) PRIORIDADES
		WHERE 1 = 1 ";
$comboPrioridad = new Combo($sql, "Prioridad");
$comboPrioridad->setClass("Combo");
?>