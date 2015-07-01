<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/export_query.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 55));


$sql =
	"SELECT tj_cuil \"CUIL\", tj_nombre \"Nombre\", tj_sexo \"Sexo\", na_descripcion \"Nacionalidad\", tj_otranacionalidad \"Otra Nacionalidad\", tj_fnacimiento \"Fecha Nacimiento\",
					tb_descripcion \"Estado Civil\", rl_fechaingreso \"Fecha Ingreso\", es_nombre \"Establecimiento\", mc_descripcion \"Tipo Contrato\", rl_tarea \"Tarea\", rl_sector \"Sector\",
					rl_ciuo \"Código CIUO\", rl_sueldo \"Remuneracion\", es_calle \"Calle\", es_numero \"Número\", es_piso \"Piso\", es_departamento \"Depto\", es_cpostal \"Código Postal\",
					es_localidad \"Localidad\", pv_descripcion \"Provincia\", rl_fecharecepcion \"Fecha Baja\"
		 FROM ctj_trabajador, crl_relacionlaboral, cre_relacionestablecimiento, aes_establecimiento, cpv_provincias, cna_nacionalidad, ctb_tablas estad, cmc_modalidadcontratacion
		WHERE tj_id = rl_idtrabajador
			AND rl_id = re_idrelacionlaboral(+)
			AND re_idestablecimiento = es_id(+)
			AND es_provincia = pv_codigo(+)
			AND tj_idnacionalidad = na_id(+)
			AND estad.tb_clave(+) = 'ESTAD'
			AND estad.tb_codigo(+) = tj_estadocivil
			AND rl_idmodalidadcontratacion = mc_id(+)
			AND rl_contrato = art.afiliacion.get_contratovigente((SELECT em_cuit
																															FROM aem_empresa
																														 WHERE em_id = ".$_SESSION["idEmpresa"]."), SYSDATE)";
$exportQuery = new ExportQuery($sql, "Trabajadores");
$exportQuery->export();
?>