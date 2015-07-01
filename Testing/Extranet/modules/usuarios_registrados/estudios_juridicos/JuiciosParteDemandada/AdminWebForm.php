<?php
if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/AdminWebForm.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatos.php");
ValidarUserSession();
$webformaction = "/AdminWebForm";

if(isset($_REQUEST['btnPericias'] )){
	echo "<script type='text/javascript'> 
				window.location.href = '/PeritajesWebForm';
		   </script>";
}

if(isset($_REQUEST['btnEventos'] )){
	echo "<script type='text/javascript'> 
				window.location.href = '/EventosWebForm';
		   </script>";
}

if(isset($_REQUEST['btnSentencia'] )){
	echo "<script type='text/javascript'> 
				window.location.href = '/SentenciaWebForm';
		   </script>";

}

?>
<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/legales.css" rel="stylesheet" type="text/css" />
<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/textos.css" rel="stylesheet" type="text/css" />		
<link href="/styles/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/js/jquery.js"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/AdminWebForm.js" type="text/javascript"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/js/EstudiosJuridicos.js" type="text/javascript"></script>
	
<form name="formAdminWebForm" method="post" action="<?php echo $webformaction ?>" id="idformAdmin">

<div align="left" id="divContentGrid" name="divContentGrid" 
	style="height:100%; margin-left:20px; margin-top:8px; overflow:auto; width:96%;">		
	<table>
		<tr>
			<td colspan="2">
				<?php 

				    if(isset($_REQUEST["NroJuicio"])) 
				    	$_SESSION["NroJuicio"] = $_REQUEST["NroJuicio"];

					//TABLA CON DATOS DE ENCABEZADO
					TablaDatosUsuario($_SESSION["usuario"]);				

					//VALORES UTILIZADOS EN EL FORMULARIO
					extract(ObtenerDatosDeJuicio($_SESSION["NroJuicio"], $_SESSION["IDESTUDIOJURIDICO"], $_SESSION["usuario"]));
						
					$_SESSION["NUMEROCARPETA"] = $JT_NUMEROCARPETA;  
				    $_SESSION["DESCRIPCARATULA"] = $DESCRIPCARATULA;  				    
				    	
				?>
			</td></tr>
		<tr>			
			<td bgcolor="#808080" align="left"colspan="2">
				<font face="Verdana" color="#FFFFFF" style="font-size: 10pt">Datos del Juicio</font>
			</td>			
		</tr>
		<tr>
		  	<td colspan="2" height="5" bgcolor="#ffffff"></td></tr>
		<tr>
			<td colspan="2" class="title_NegroFndGrisClaro" style="width:100%; height: 19px;">Datos Generales</td>		
	
		<tr>
			<td colspan="2" style="height: 8px"></td></tr>
		<tr>
			<td width="24%" class="item_grisClaro" style="height: 23px">Nro Carpeta:</td>
			<td colspan="2" style="height: 23px"><span id="txtidCarpeta" class="valor_azulOscuro" style="Z-INDEX: 1">
				<?php echo $_SESSION["NUMEROCARPETA"];  ?></span></td></tr>
		<tr>
			<td class="item_grisClaro" style="height: 21px">Tipo Juicio:</td>
			<td colspan="2" style="height: 21px"><span id="txtTipoJuicio" class="valor_azulOscuro">
				<?php echo $TIPOJUICIO;  ?>
				</span></td></tr>
		<tr>
			<td class="item_grisClaro" valign="top">Caratula:</td>
			<td colspan="2"><span id="txtCaratula" class="valor_azulOscuro">
				<?php echo $_SESSION["DESCRIPCARATULA"];  ?>
				</span></td></tr>
		<tr>
			<td class="item_grisClaro">Abogado:</td>
			<td colspan="2"><span id="txtAbogado" class="valor_azulOscuro">
				<?php echo $JT_IDABOGADO;  ?></span></td></tr>
		<tr>
			<td class="item_grisClaro" style="height: 21px">F. Asignacion:</td>
			<td width="28%" align="left" style="height: 21px">
				<span id="txtFechaAsignacion" class="valor_azulOscuro">
				<?php echo $JT_FECHAASIGN;  ?></span></td>
			<td width="48%" align="center" style="height: 21px"></td></tr>
		<tr>
			<td class="item_grisClaro">F. Notific:</td>
			<td colspan="2"><span id="txtFechaNotificacion" class="valor_azulOscuro">
				<?php echo $JT_FECHANOTIFICACIONJUICIO;  ?></span></td></tr>
		<tr>
			<td class="item_grisClaro" style="height: 21px">F. Fin:</td>
			<td colspan="2" style="height: 21px"><span id="txtFechaFin" class="valor_azulOscuro">
				<?php echo $JT_FECHAFINJUICIO;  ?></span></td></tr>
		<tr>
			<td colspan="2" valign="top" class="item_grisClaro"></td></tr>
		<tr> 
			<td colspan="2" class="title_NegroFndGrisClaro" style="height: 19px;">Detalle </td></tr>	
		<tr>
			<td width="19%" class="item_grisClaro" style="height: 23px">Estado:</td>
			<td width="81%" style="height: 23px"><span id="txtEstado" class="valor_azulOscuro">
				<?php echo $EJ_DESCRIPCION;  ?>
			</span></td></tr>
		<tr>
			<td height="150" valign="top" class="item_grisClaro">Res. Probable:</td>
			<td height="150"><textarea name="txtResProbable" id="txtResProbable" class="text_area">
				<?php echo trim($JT_RESULTADO);  ?>
			</textarea></td></tr>
		<tr>
			<td colspan="2" style="height: 19px"></td></tr>
		<tr>
			<td colspan="2" class="title_NegroFndGrisClaro" style="height: 23px">Juzgado</td></tr>			    		
		<tr>
			<td colspan="2" class="item_grisClaro" style="height: 5px"></td></tr>
		<tr>
			<td class="item_grisClaro" style="height: 28px">Jurisdiccion:</td>
			<td class="item_grisClaro" style="height: 28px">
				<select name="cmbJurisdiccion" id="idcmbJurisdiccion"  disabled="disabled" class="combo" >
					<?php 
						echo CargarJurisdiccion($JT_IDJURISDICCION); 
					?>
				</select>
				<input type="hidden" name="hiddenJurisdiccion" id="idHJuzgadoComp"/>
				</td></tr>
		<tr>
			<td class="item_grisClaro">Juzgado Nro:</td>
			<td class="item_grisClaro">
				<select name="cmbJuzgadoNro" id="idcmbJuzgadoNro"  disabled="disabled" 										
						class="combo" >
					<?php echo CargarJuzgado($JT_IDJURISDICCION, $JT_IDFUERO, $JT_IDJUZGADO); ?>
				</select>
				</td></tr>		
		<tr>
			<td class="item_grisClaro">Instancia: </td>
			<td class="item_grisClaro">
				<input name="txtInstancia" type="text" value="<?php echo $IN_DESCRIPCION; ?>" 
					readonly="readonly" id="txtInstancia" class="input_text" />
				</td></tr>		
		<tr>
			<td class="item_grisClaro"><p style="MARGIN-LEFT: 15px">Fuero:</td>
			<td align="left" class="item_grisClaro" width="84%">
				<select name="cmbFuero" id="cmbFuero" disabled="disabled"   						
						class="combo" >
					<?php 
						echo CargarFuero($JT_IDJURISDICCION, $JT_IDFUERO); 
					?>
				</select></td></tr>
		<tr>			
			<td class="item_grisClaro"><p style="MARGIN-LEFT: 15px">Secretaria: </td>
			<td align="left" class="item_grisClaro" width="84%">
				<select name="cmbSecretaria" id="cmbSecretaria"  disabled="disabled" 					
					class="combo">
                                        <?php echo CargarSecretaria($JT_IDJUZGADO, $JT_IDSECRETARIA); ?>                               
				</select></td></tr>
		<tr>
			<td class="item_grisClaro"><p style="MARGIN-LEFT: 15px" >Nro Exp: </td>
			<td align="left" class="item_grisClaro" width="84%">
			
				<input name="txtNroExp" type="text" value="<?php echo $JT_NROEXPEDIENTE; ?>"						
						maxlength="10" id="txtNroExp" class="input_text_right" />/						
				<input name="txtAnioExp" type="text" value="<?php echo $JT_ANIOEXPEDIENTE; ?>"
						maxlength="2" id="txtAnioExp" class="input_text" /></td></tr>
		<tr>
			<td class="item_grisClaro" colspan="2" height="7"></td></tr>		
		<tr>
			<td bgcolor="#ffffff"  colspan="2" align="center"><br>				
				<input type="button" name="MasDatosJuicioWebForm" value="Mas Datos Juicio" 
					onclick="this.form.action = '/MasDatosJuicioWebForm'; this.form.submit();">		
				<input type="button" name="InstanciasWebForm1" value="Instancias" 
					onClick="window.location.href = '/index.php?pageid=102&accion=nuevo';"	>									
				</td></tr>			
		
		<tr>
			<td colspan="2"></td></tr>
		<tr>
			<td colspan="2" class="title_NegroFndGrisClaro">Observaciones</td></tr>
		<tr>
			<td colspan="2" class="bordeGris_freetext">
				<span id="txtDetalle" class="valor_azulOscuro" style="Z-INDEX: 1"></span>
				<font color="#ffffff">.</font></td></tr>
		<tr>
			<td colspan="2"></td></tr>	
	
		<tr>
			<td colspan="2" class="title_NegroFndGrisClaro">Origen Demanda</td></tr>
		<tr>	
			<td colspan="2" >
				<?php 
					echo getGridDemandas($_SESSION["NUMEROCARPETA"]);
				?>
			</td>			
		</tr>		
		
		<tr>
			<td colspan="2" class="title_NegroFndGrisClaro">Reclamos</td></tr>
		<tr>	
			<td colspan="2" >
				<?php 
					echo getGridReclamos($_SESSION["NUMEROCARPETA"]);
				?>
			</td>			
		</tr>			
		<td bgcolor="#ffffff" align="center" colspan="2"><br>
			
			<input type="submit" name="btnPericias" id="idPericias" value="Pericias">									
			<input type="submit" name="btnEventos" id="idEventos" value="Eventos">									
			<input type="submit" name="btnSentencia" id="idSentencia" value="Sentencia">									
			
			</td>
		</tr>				
		<tr>
			<td colspan="2" align="right">
				<input type="submit" name="btnModificar" value="Modificar" id="btnModificar" class="submit" /></td></tr>				
		</td></tr>				
		<tr>
			<td align="center" colspan="2" height="50">				
				<input class="btnVolver" type="button" value="" onClick="goBackTime();"/>				
			</td></tr>
	</table>				
</div>			
</form>
