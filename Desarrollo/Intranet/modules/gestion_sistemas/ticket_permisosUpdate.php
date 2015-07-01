<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/modules/gestion_sistemas/ticket_funciones.php");

// foreach($_REQUEST as $k=>$v)	echo "  _REQUEST  ".$k." = ".$v." <p>";

$permisos = GetParametroDecode("grupoids");
$motivoid = GetParametroDecode("motivoid");

$iddescpadre = GetParametroDecode("idpadre");
$iddescmotivo = GetParametroDecode("idmotivo");

$descpadre = GetDescripComputo($iddescpadre);
$descmotivo = GetDescripComputo($iddescmotivo);

$sistema = GetParametro("sistema", 1);


if (!isset($sistema))
    $sistema = GetParametroDecode("sistema");

$UsuarioSolicitud = GetUsuarioAplicacion();

if ($permisos == '')
    $grilla = DatosUsuarioGrid($UsuarioSolicitud, $sistema, '', $motivoid, 1, '');
else
    $grilla = DatosUsuarioGrid($UsuarioSolicitud, $sistema, $permisos, $motivoid, 1, '');

echo "<script type='text/javascript' src='/modules/gestion_sistemas/js/ticket_permisosUpdate.js?rnd=".RandomNumber()."'></script> ";
// echo "<br>" . GetUsuarioAplicacion();
?>

<link href="/modules/gestion_sistemas/styles/responsive-nav.css" rel="stylesheet" type="text/css"></link>
<link href="/modules/gestion_sistemas/styles/styles_responsive.php" rel="stylesheet" type="text/css"></link>    
<link href="/Styles/style_sistemas.css?sid=<?=  date('YmdHis'); ?>" rel="stylesheet" type="text/css" />
<link href="/Styles/gridAjax.css" rel="stylesheet" type="text/css" />

<link href="/styles/ticket/jquery-ui-custom.css<?php echo RandonNumberParameter(); ?>" rel="stylesheet">	
<link href="/styles/ticket/ticket_style.css<?php echo RandonNumberParameter(); ?>" rel="stylesheet">		

<body>
	<form id="FormPermisosUpdate" >
	<input type="hidden" id="PaginaActual" >	

	<div class="GridTable12Permisos"  >
	<label class="TextoInfo" style="text-align:center;" ><? echo trim($descpadre . ' | ' . $descmotivo); ?></label>	
	</div>

	<div class="GridTable12Permisos" >

		<div id="grillaColaboradores"  class="divContenido">			
			
		</div>

		<div id="UsuariosActivos" class="contenedor-principal" 
			style="font-size:12px; height:auto; width:100%; float:left; text-align:center;" ></div>
		
		<hr>
		
		<div style="text-align:right; float:left; width:96%; margin:10px;" id="divBotones" >
		
			<button class="GIBtnAction disable" id="btnGuardar" type="button" onclick="GuardarPermisos();"  style="margin:2px 2px;" disabled="disabled" >Guardar</button>	
			
			<button class="GIBtnAction" id="btnCancelar" type="button" 				
				onclick="window.location.href='/modules/gestion_sistemas/index.php?sistema=<?= $sistema ?>&MNU=6&subsistema=1';" 
				style="margin:2px 2px;" >Cancelar</button>
				
		</div>

		<div id='DivAreaMensajes' ></div>
		
	</div>	

	</form>
</body>


<div id="dialogEliminaUsuario" title="Permiso Colaborador">
	<b class="txt-msj-Aviso" id='tituloEliminaUsuario' >Colaborador Bloqueado:</b>		
	<p>
	<div id="motivoEliminaUsuario" style='padding:3px 0 0 0; text-align:left; font-style:italic;' >"Quitar el usuario de la lista de usuarios bloqueados.". </div>
	<p>	
</div>

<div id="dialogProcesoOK" title="Proceso OK">
	<b class="txt-msj-Aviso" id='tituloProcesoOK' >Colaborador - Permisos:</b>		
	<p>
	<div id="motivoProcesoOK" style='padding:3px 0 0 0; text-align:left; font-style:italic;' >"Permisos Actualizados." </div>
	<p>	
</div>

<?php


echo $grilla->GetArrayChecksJS();

echo "<script type='text/javascript'> ";
echo " var sistema = " . $sistema . "; ";
// echo " var ArraysUsuariosPermisos = []; ";

if ($permisos == '') {
    echo " var motivos = ''; ";

    $params = array(":usuario" => $UsuarioSolicitud);
    $valoresarray = GetDatosUsuarioGrid($params, $motivoid);

    if (is_array($valoresarray)) {
        if (count($valoresarray) > 0) {
            foreach ($valoresarray as $valores) {
                if ($valores[2] > 0)
                    echo " ArraysUsuariosPermisos.push('" . $valores[3] . "'); ";
            }
        }
    }

} else {
    echo " var motivos = '" . $permisos . "'; ";

    $arraypermisos = explode(",", $permisos);
    foreach ($arraypermisos as $permisoitem) {
        echo " ArraysUsuariosPermisos.push('" . $permisoitem . "'); ";
    }
}
echo " var motivoid = '".$motivoid."'; ";
echo " var idsolicitud = '".$motivoid."'; ";
echo " var UsuarioNombre = '".$UsuarioSolicitud."'; ";
echo " var ArraysUsuariosPermisosDelete = []; ";

echo " window.onload=LoadGrillaPermisos('LOAD'); ";
echo " </script> ";

?>