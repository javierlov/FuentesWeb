<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Common/Clases/Tabla.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

function Get_Formulario($usuario, $CodCaratula, $NroExpediente, $NroCarpeta, $tipoJuicio){
	
	$result = TablaDatosUsuario($usuario);
	$result .= TablaGrupoPrimerGrupo();
	$result .= TablaGrupoJuzgado();
	echo $result;
}

function TablaGrupoPrimerGrupo(){
	//colspan='3' bgcolor='#808080' align='left'
	$result = "
	<table cellspacing='0' cellpadding='2' width='98%' border='0' align='center'  >
	<tr>
		<td colspan='3' bgcolor='#808080' align='left' > <b><font face='Verdana' style='font-size: 8pt' color='#FFFFFF'>Datos del Juicio</font></b> </td>			
	<tr>
		<td height='5' bgcolor='#ffffff'></td>
		<td rowspan='4' width='5'></td>
		<td height='5' bgcolor='#ffffff'></td>		</tr>
	<tr>
		<td class='title_NegroFndGrisClaro'>Datos Generales</td>
		<td class='title_NegroFndGrisClaro'>Detalle</td>	</tr>
	<tr>
		<td width='45%' valign='top' class='item_grisClaro'> ";
	
	$carpeta = '999';
	$tipoJuicio = 'final';
	$caratula = 'AGUIRRE LEOPOLDOC/ADM.PARQUES Y ZOO ENFERMEDAD-ACCIDENTE';
	$abogado = 'LIVELLARA';
	$fAsiganacion = '30/11/1999';
	$fNotific = '30/11/1999';
	$fFin = '';
	
	$result .= TablaDatosGenerales($carpeta, $tipoJuicio,$caratula,$abogado,$fAsiganacion,$fNotific,$fFin);
	
	$result .= "</td> <td width='54%' class='item_grisClaro' valign='top'>";
			
	$estado = 'SENTENCIA DE CAMARA FIRME';
	$resProbable = "El actor de 67 a&#241;os reclama por viejas dolencias enfermedades inculpables, seg&#250;n se ha  sostenido por nuestra parte, no obstante, que sostiene que el &#250;ltimo accidente, se produjo durante la cobertura de ART, el cual no fue denunciado oportunamente, ni por el empleado ni por el empleador (Gobierno de Mza.) seg&#250;n antecedentes de la C&#225;mara puede esperarse un fallo favorable al demandante pero reduciendo montos peticionados.";
	
	$result .= TablaDetalle($estado, $resProbable);
	
	$result .= "</td> 	</tr> 	</table>";
			
	return $result;		
}

function TablaDatosGenerales($carpeta, $tipoJuicio,$caratula,$abogado,$fAsiganacion,$fNotific,$fFin){
	
	$result = "<table width='100%' cellspacing='0'>
	<tr>
			<td height='8' colspan='3'></td>		</tr>
	<tr>
			<td width='24%' class='item_grisClaro'>Nro Carpeta:</td>
			<td colspan='2'><span id='txtidCarpeta' class='valor_azulOscuro' style='Z-INDEX: 1'>$carpeta</span></td>		</tr>
	<tr>
			<td class='item_grisClaro'>Tipo Juicio:</td>
			<td colspan='2'><span id='txtTipoJuicio' class='valor_azulOscuro'>$tipoJuicio</span>		</td>
		</tr>
	<tr>
			<td class='item_grisClaro' valign='top'>Carátula:</td>
			<td colspan='2'><span id='txtCaratula' class='valor_azulOscuro'>$caratula</span>		</td>		</tr>
	<tr>
			<td class='item_grisClaro'>Abogado:</td>
			<td colspan='2'><span id='txtAbogado' class='valor_azulOscuro'>$abogado</span>		</td>		</tr>
	<tr>
			<td class='item_grisClaro'>F. Asignación:</td>
			<td width='28%' align='left'><span id='txtFechaAsignacion' class='valor_azulOscuro'>$fAsiganacion</span>		</td>
			<td width='48%' align='center'>		</td>		</tr>
	<tr> 
			<td class='item_grisClaro'>F. Notific:</td>
			<td colspan='2'><span id='txtFechaNotificacion' class='valor_azulOscuro'>$fNotific</span>		</td>		</tr>
	<tr>
			<td class='item_grisClaro'>F. Fin:</td>
			<td colspan='2'><span id='txtFechaFin' class='valor_azulOscuro'>$fFin</span>		</td>		</tr>
	<tr>
			<td colspan='3' valign='top' class='item_grisClaro'></td>		</tr>
	</table>";
	
	return $result;
		
}

function TablaDetalle($estado, $resProbable){	
	$result = "
		<table cellspacing='0' cellpadding='2' width='100%' height='100%' border='0'>
		<tr>
			<td width='19%' class='item_grisClaro'>Estado:</td>
			<td width='81%'><span id='txtEstado' class='valor_azulOscuro'>$estado</span>	</td>	</tr>
		<tr>
			<td height='150' valign='top' class='item_grisClaro'>Res. Probable:</td>
			<td height='150'><textarea name='txtResProbable' id='txtResProbable' disabled='disabled' class='text_area'>$resProbable</textarea>	</td>	</tr>
		</table>";
	return $result;
}

function TablaGrupoJuzgado(){
	$result = "	
	<table cellspacing='0' cellpadding='2' width='98%' border='0' align='center'  >
	<tr> <td colspan='2'>	</td>	</tr>
	<tr> <td colspan='2' class='title_NegroFndGrisClaro'>Juzgado</td>	</tr>
	<tr> <td colspan='2' height='5' class='item_grisClaro'></td>	</tr>
	<tr> <td width='100%'>";			
	$result .= TablaDetalleJuzgado();	
	$result .= "</td> </td>	</tr> <td width='100%' valign='top'>";	
	$result .= TablaDetalleJuzgado2();
	
	$result .= "</td>	</tr>
		<tr> <td colspan='2'></td>	</tr>
		<tr> <td colspan='2' class='title_NegroFndGrisClaro'>Observaciones</td>	</tr>
		<tr>
			<td colspan='2' class='bordeGris_freetext'>
			<span id='txtDetalle' class='valor_azulOscuro' style='Z-INDEX: 1'></span>
			<font color='#ffffff'>.</font>	</td>	</tr>
		<tr> <td colspan='2'></td>	</tr>	
		<tr> <td colspan='2' class='title_NegroFndGrisClaro'>Origen Demanda</td>	</tr>
		<tr> <td bgcolor='#ffffff' HEIGHT='2' colspan='2'></td>	</tr>
		<tr> <td colspan='2'>";
			
	$result .= TablaOrigenDemanda();
	
	$result .= "	</td>	</tr>
		<tr> <td colspan='2'></td>	</tr>
		<tr> <td colspan='2' class='title_NegroFndGrisClaro'>Reclamos</td>	</tr>
		<tr> <td bgcolor='#ffffff' HEIGHT='2' colspan='2'></td>	</tr>
		<tr> <td colspan='2'>";
				
	$result .= TablaReclamos();
	
	$result .= "</td>	</tr>
		<tr> <td height='19' colspan='2'></td>	</tr>
		<tr> <td colspan='2'>";
			
	$result .= TablaBotones();
	
	$result .= "</td>	</tr>
		<tr> <td colspan='2' align='right'>
			<input type='submit' name='btnModificar' value='Modificar' id='btnModificar' class='submit' />	</td>	</tr>
		</table>";
	
	return $result;
}

function TablaDetalleJuzgado(){
	$result = "<table cellspacing='0' cellpadding='0' width='100%' border='0'>
	<tr>
			<td class='item_grisClaro'>Jurisdicción:</td>
			<td width='78%' class='item_grisClaro'>
	<select name='cmbJurisdiccion' onchange='__doPostBack('cmbJurisdiccion','')' language='javascript' id='cmbJurisdiccion' disabled='disabled' class='combo'>
	<option value=''></option>
	<option value='235'>BUENOS AIRES - AVELLANEDA</option>
	<option value='1'>BUENOS AIRES - AZUL</option>
	<option value='2'>BUENOS AIRES - AZUL / OLAVARR&#205;A</option>
	<option value='4'>BUENOS AIRES - BAH&#205;A BLANCA</option>
	<option value='5'>BUENOS AIRES - BAH&#205;A BLANCA / TRES ARROYOS</option>
	<option value='341'>BUENOS AIRES - BRAGADO</option>
	<option value='6'>BUENOS AIRES - DOLORES</option>
	<option value='7'>BUENOS AIRES - JUN&#205;N</option>
	<option value='8'>BUENOS AIRES - LA MATANZA</option>
	<option value='9'>BUENOS AIRES - LA PLATA</option>
	<option value='205'>BUENOS AIRES - LANUS</option>
	<option value='10'>BUENOS AIRES - LOMAS DE ZAMORA</option>
	<option value='11'>BUENOS AIRES - MAR DEL PLATA</option>
	<option value='12'>BUENOS AIRES - MERCEDES</option>
	<option value='13'>BUENOS AIRES - MOR&#211;N</option>
	<option value='14'>BUENOS AIRES - NECOCHEA</option>
	<option value='421'>BUENOS AIRES - OLAVARRIA</option>
	<option value='15'>BUENOS AIRES - PERGAMINO</option>
	<option value='16'>BUENOS AIRES - QUILMES</option>
	<option value='17'>BUENOS AIRES - SAN ISIDRO</option>
	<option value='119'>BUENOS AIRES - SAN ISIDRO / TIGRE</option>
	<option value='321'>BUENOS AIRES - SAN JUSTO</option>
	<option value='18'>BUENOS AIRES - SAN MART&#205;N</option>
	<option value='342'>BUENOS AIRES - SAN MIGUEL</option>
	<option value='19'>BUENOS AIRES - SAN NICOL&#193;S</option>
	<option value='381'>BUENOS AIRES - TANDIL</option>
	<option value='20'>BUENOS AIRES - TRENQUE LAUQUEN</option>
	<option value='361'>BUENOS AIRES - TRES ARROYOS</option>
	<option value='21'>BUENOS AIRES - Z&#193;RATE / CAMPANA</option>
	<option value='741'>BUENOS AIRES-MONTEGRANDE</option>
	<option value='701'>CAMARA CIVIL COMERCIA, DE FAMILIA Y TRABAJO</option>
	<option value='22'>CAPITAL FEDERAL (CASACI&#211;N PENAL)</option>
	<option value='23'>CAPITAL FEDERAL (CIVIL)</option>
	<option value='24'>CAPITAL FEDERAL (COMERCIAL)</option>
	<option value='25'>CAPITAL FEDERAL (CONTENCIOSO ADMINISTRATIVO Y TRIBUTARIO)</option>
	<option value='26'>CAPITAL FEDERAL (CONTRAVENCIONAL)</option>
	<option value='27'>CAPITAL FEDERAL (CORTE)</option>
	<option value='28'>CAPITAL FEDERAL (CRIMINAL Y CORRECCIONAL)</option>
	<option value='29'>CAPITAL FEDERAL (ELECTORAL)</option>
	<option value='30'>CAPITAL FEDERAL (FEDERAL CIVIL Y COMERCIAL)</option>
	<option value='31'>CAPITAL FEDERAL (FEDERAL CONTENCIOSO ADMINISTRATIVO)</option>
	<option value='32'>CAPITAL FEDERAL (FEDERAL CRIMINAL Y CORRECCIONAL)</option>
	<option value='33'>CAPITAL FEDERAL (FEDERAL SEGURIDAD SOCIAL)</option>
	<option value='34'>CAPITAL FEDERAL (PENAL ECON&#211;MICO)</option>
	<option value='35'>CAPITAL FEDERAL (TRABAJO)</option>
	<option value='362'>CATAMARCA </option>
	<option value='36'>CHACO - RESISTENCIA</option>
	<option value='641'>CHACO - ROQUE SAENZ PE&#209;A</option>
	<option value='37'>CHUBUT - COMODORO RIVADAVIA</option>
	<option value='38'>CHUBUT - ESQUEL</option>
	<option value='39'>CHUBUT - PUERTO MADRYN</option>
	<option value='40'>CHUBUT - SARMIENTO</option>
	<option value='41'>CHUBUT -TRELEW</option>
	<option value='1041'>CHUBUT-RAWSON</option>
	<option value='301'>COLON - ENTRE RIOS</option>
	<option value='43'>C&#211;RDOBA - ALTA GRACIA</option>
	<option value='44'>C&#211;RDOBA - ARROYITO</option>
	<option value='45'>C&#211;RDOBA - BELL VILLE</option>
	<option value='46'>C&#211;RDOBA - CARLOS PAZ</option>
	<option value='51'>C&#211;RDOBA - C&#211;RDOBA</option>
	<option value='52'>C&#211;RDOBA - C&#211;RDOBA  (JURISDICCI&#211;N FEDERAL)</option>
	<option value='47'>C&#211;RDOBA - CORRAL DE BUSTOS</option>
	<option value='48'>C&#211;RDOBA - COSQU&#205;N</option>
	<option value='49'>C&#211;RDOBA - CRUZ DEL EJE</option>
	<option value='50'>C&#211;RDOBA - CURA BROCHERO</option>
	<option value='53'>C&#211;RDOBA - DE&#193;N FUNES</option>
	<option value='54'>C&#211;RDOBA - HUINCA RENANC&#211;</option>
	<option value='55'>C&#211;RDOBA - JES&#218;S MAR&#205;A</option>
	<option value='56'>C&#211;RDOBA - LA CARLOTA</option>
	<option value='57'>C&#211;RDOBA - LABOULAYE</option>
	<option value='58'>C&#211;RDOBA - LAS VARILLAS</option>
	<option value='59'>C&#211;RDOBA - MORTEROS</option>
	<option value='60'>C&#211;RDOBA - OLIVA</option>
	<option value='61'>C&#211;RDOBA - R&#205;O CUARTO</option>
	<option value='402'>CORDOBA - RIO IV</option>
	<option value='62'>C&#211;RDOBA - R&#205;O SEGUNDO</option>
	<option value='63'>C&#211;RDOBA - R&#205;O TERCERO</option>
	<option value='64'>C&#211;RDOBA - SAN FRANCISCO</option>
	<option value='65'>C&#211;RDOBA - VILLA DOLORES</option>
	<option value='66'>C&#211;RDOBA - VILLA MAR&#205;A</option>
	<option value='702'>CORDOBA- MARCOS JUAREZ</option>
	<option value='42'>CORRIENTES - CORRIENTES</option>
	<option value='821'>CORRIENTES-CURUZU CUATIA</option>
	<option value='501'>DISTRITO JUDICIAL NORTE</option>
	<option value='67'>ENTRE R&#205;OS - CONCEPCI&#211;N DEL URUGUAY</option>
	<option value='68'>ENTRE R&#205;OS - CONCORDIA</option>
	<option value='69'>ENTRE R&#205;OS - GUALEGUAYCHU</option>
	<option value='70'>ENTRE R&#205;OS - PARAN&#193;</option>
	<option value='302'>ENTRE RIOS- COLON</option>
	<option value='661'>ENTRE RIOS- CONCORDIA</option>
	<option value='541'>FORMOSA</option>
	<option value='581'>JUJUY - SAN PEDRO DE JUJUY</option>
	<option value='461'>JUZGADO CIVIL Y COMERCIAL</option>
	<option value='781'>JUZGADO CIVIL Y COMERCIAL 1</option>
	<option value='71'>LA PAMPA - 1&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='72'>LA PAMPA - 2&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='73'>LA PAMPA - 3&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='74'>LA PAMPA - 4&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='75'>LA RIOJA - 1&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='76'>LA RIOJA - 2&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='77'>LA RIOJA - 3&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='78'>LA RIOJA - 4&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='79'>LA RIOJA - 5&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='80'>MENDOZA - GENERAL ALVEAR</option>
	<option value='81'>MENDOZA - MALARGUE</option>
	<option selected='selected' value='82'>MENDOZA - MENDOZA</option>
	<option value='83'>MENDOZA - RIVADAVIA</option>
	<option value='84'>MENDOZA - SAN MART&#205;N</option>
	<option value='85'>MENDOZA - SAN RAFAEL</option>
	<option value='86'>MENDOZA - TUNUY&#193;N</option>
	<option value='941'>MENDOZA- MENDOZA</option>
	<option value='259'>MISIONES</option>
	<option value='1021'>MISIONES</option>
	<option value='681'>MISIONES - EL DORADO</option>
	<option value='87'>MISIONES - 1&#186; CIRCUNS. - POSADAS</option>
	<option value='88'>MISIONES - 2&#186; CIRCUNS. - OBER&#193;</option>
	<option value='89'>MISIONES - 3&#186; CIRCUNS. - EL DORADO</option>
	<option value='90'>MISIONES - 4&#186; CIRCUNS. - PUERTO RICO</option>
	<option value='91'>NEUQU&#201;N - 1&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='92'>NEUQU&#201;N - 2&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='93'>NEUQU&#201;N - 3&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='94'>NEUQU&#201;N - 4&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='95'>NEUQU&#201;N - 5&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='861'>NEUQUEN-ZAPALA</option>
	<option value='901'>RIO NEGRO - BARILOCHE</option>
	<option value='281'>R&#205;O NEGRO - CIPOLLETTI</option>
	<option value='96'>R&#205;O NEGRO - GENERAL ROCA</option>
	<option value='97'>R&#205;O NEGRO - SAN CARLOS DE BARILOCHE</option>
	<option value='98'>R&#205;O NEGRO - VIEDMA</option>
	<option value='99'>SALTA - MET&#193;N</option>
	<option value='100'>SALTA - OR&#193;N</option>
	<option value='101'>SALTA - SALTA</option>
	<option value='102'>SALTA - TARTAGAL</option>
	<option value='103'>SAN JUAN - 1&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='104'>SAN JUAN - 2&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='212'>SAN LUIS - SAN LUIS</option>
	<option value='561'>SAN LUIS - VILLA MERCEDES</option>
	<option value='213'>SAN LUIS - VILLA MERCEDES</option>
	<option value='981'>SAN LUIS-CONCAR&#193;N</option>
	<option value='248'>SANTA CRUZ - PTO.SAN JULIAN</option>
	<option value='257'>SANTA CRUZ - RIO GALLEGOS</option>
	<option value='120'>SANTA CRUZ - 1&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='121'>SANTA CRUZ - 2&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='401'>SANTA CRUZ-PUERTO DESEADO</option>
	<option value='841'>SANTA FE - CASILDA</option>
	<option value='801'>SANTA FE - MELINCU&#201;</option>
	<option value='1001'>SANTA FE - SAN JORGE</option>
	<option value='207'>SANTA FE - SANTA FE</option>
	<option value='921'>SANTA FE - VENADO TUERTO</option>
	<option value='105'>SANTA F&#201; - 1&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='106'>SANTA F&#201; - 2&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='107'>SANTA F&#201; - 3&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='108'>SANTA F&#201; - 4&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='109'>SANTA F&#201; - 5&#186; CIRCUNSCRIPCI&#211;N JUDICIAL</option>
	<option value='961'>SANTA FE- SAN LORENZO</option>
	<option value='601'>SANTA FE-CA&#209;ADA DE GOMEZ</option>
	<option value='441'>SANTA FE-FIRMAT</option>
	<option value='881'>SANTA FE-RAFAELA</option>
	<option value='217'>SANTA FE-ROSARIO</option>
	<option value='283'>SANTA FE-VENADO TUERTO</option>
	<option value='110'>SANTIAGO DEL ESTERO - A&#209;ATUYAoption>
	<option value='111'>SANTIAGO DEL ESTERO - FRIAS</option>
	<option value='112'>SANTIAGO DEL ESTERO - LA BANDA</option>
	<option value='113'>SANTIAGO DEL ESTERO - SANTIAGO DEL ESTERO</option>
	<option value='114'>SANTIAGO DEL ESTERO - TERMAS DE R&#205;O HONDO</option>
	<option value='115'>TIERRA DEL FUEGO - DISTRITO JUDICIAL NORTE</option>
	<option value='116'>TIERRA DEL FUEGO - DISTRITO JUDICIAL SUR</option>
	<option value='721'>TIERRA DEL FUEGO-RIO GRANDE</option>
	<option value='117'>TUCUM&#193;N - CAPITAL</option>
	<option value='118'>TUCUM&#193;N - CONCEPCI&#211;N</option>
	<option value='521'>VILLA CONSTITUCION</option>
	<option value='481'>VILLA CONSTITUCION</option>
	</select>	</td>	</tr>
	<tr>
			<td class='item_grisClaro'>Juzgado Nro:</td>
			<td class='item_grisClaro'>
				<select name='cmbJuzgadoNro' onchange='__doPostBack('cmbJuzgadoNro','')' language='javascript' id='cmbJuzgadoNro' disabled='disabled' class='combo'>
				<option value=''></option>
				<option value='185'>SALA 1</option>
				<option value='178'>SALA 2</option>
				<option value='177'>SALA 3</option>
				<option value='179'>SALA 4</option>
				<option value='174'>SALA 5</option>
				<option selected='selected' value='189'>SALA 6</option>
				<option value='10001'>SALA 7</option>
	</select>	</td>	</tr>
	<tr>
			<td class='item_grisClaro'>Instancia:</td>
			<td class='item_grisClaro'>
				<input name='txtInstancia' type='text' value='Cámara de Apelaciones' readonly='readonly' id='txtInstancia' disabled='disabled' class='input_text' />	</td>	</tr>
	<tr>
			<td height='7' class='item_grisClaro' colspan='2'></td>	</tr>
	<tr>
			<td bgcolor='#ffffff' height='7' colspan='2'></td>	</tr>
	<tr>
			<td bgcolor='#ffffff' align='center' colspan='2'>
				<input type='image' name='btnMasDatos' 
				onclick='if (typeof(Page_ClientValidate) == 'function') Page_ClientValidate(); ' 
				language='javascript' id='btnMasDatos' 
				title='Más Datos' 
				src='Imagenes/MasDatos_off.gif' 
				alt='Más Datos' 
				align='Top' border='0' /><br>
				<a id='lnkMasDatos' 
					class='linksBar' 
					href='javascript:{if (typeof(Page_ClientValidate) != 'function' ||  Page_ClientValidate()) __doPostBack('lnkMasDatos','')} '>MÁS DATOS</a></td>	</tr>
	</table>";
	
	return $result;
}

function TablaDetalleJuzgado2(){
	$result = "
			<table cellspacing='0' cellpadding='2' width='100%' align='left' border='0' id='table11'>
			<tr>
					<td width='20%' class='item_grisClaro'><p style='MARGIN-LEFT: 15px'>Fuero:</td>
					<td align='left' class='item_grisClaro' width='84%'>
			<select name='cmbFuero' onchange='__doPostBack('cmbFuero','')' language='javascript' id='cmbFuero' disabled='disabled' class='combo'>
			<option value=''></option>
			<option selected='selected' value='15'>C&#193;MARA DE APELACIONES DEL TRABAJO</option>
			<option value='22'>C&#193;MARA DE APELACIONES EN LO CIVIL, COMERCIAL, MINAS, DE PAZ Y TRIBUTARIA</option>
			<option value='28'>C&#193;MARA DE APELACIONES EN LO CRIMINAL</option>
			<option value='100'>JUZGADO DE EJECUCI&#211;N</option>
			<option value='103'>JUZGADO DE FAMILIA</option>
			<option value='107'>JUZGADO DE INSTRUCCI&#211;N</option>
			<option value='113'>JUZGADO DE MENORES</option>
			<option value='121'>JUZGADO DE PROCESOS CONCURSALES Y REGISTRO</option>
			<option value='87'>JUZGADO DE 1&#186; INSTANCIA EN LO CIVIL, COMERCIAL Y DE MINER&#205;A</option>
			<option value='123'>JUZGADO DEL TRABAJO</option>
			<option value='127'>JUZGADO EN LO CORRECCIONAL</option>
			<option value='132'>JUZGADO EN LO PENAL DE MENORES</option>
			<option value='42'>JUZGADO FEDERAL DE 1&#186; INSTANCIA</option>
			<option value='134'>SUPREMA CORTE DE JUSTICIA</option>
			<option value='141'>TRIBUNAL PENAL DE MENORES</option>
			<option value='142'>TRIBUNAL TRIBUTARIO</option>
			</select>			</td>			</tr>
			<tr>
					<td class='item_grisClaro'><p style='MARGIN-LEFT: 15px'>Secretaría:</td>
					<td align='left' class='item_grisClaro' width='84%'>
				<select name='cmbSecretaria' onchange='__doPostBack('cmbSecretaria','')' language='javascript' id='cmbSecretaria' disabled='disabled' class='combo'>
				<option value=''></option>
				<option selected='selected' value='733'>-</option>
			</select>			</td>			</tr>
			<tr>
					<td class='item_grisClaro'><p style='MARGIN-LEFT: 15px'>Nro Exp:</td>
					<td align='left' class='item_grisClaro' width='84%'>
					<input name='txtNroExp' type='text' value='9058' maxlength='10' id='txtNroExp' disabled='disabled' class='input_text_right' />
				/
				<input name='txtAnioExp' type='text' value='99' maxlength='2' id='txtAnioExp' disabled='disabled' class='input_text' />			</td>			</tr>
			<tr>
				<td class='item_grisClaro' colspan='2' height='7'></td>			</tr>
			<tr>
				<td bgcolor='#ffffff' height='7' colspan='2'></td>			</tr>
			<tr>
				<td bgcolor='#ffffff' colspan='2' align='center'>
				<input type='image' name='btnInstancias' onclick='if (typeof(Page_ClientValidate) == 'function') Page_ClientValidate(); ' language='javascript' id='btnInstancias' title='Instancias' onmouseover='javascript:this.src=&quot;Imagenes/Instancias_on.gif&quot;;' onmouseout='javascript:this.src=&quot;Imagenes/Instancias_off.gif&quot;;' src='Imagenes/Instancias_off.gif' alt='Instancias' align='Top' border='0' /><br>
				<a id='lnkInstancias' class='linksBar' href='javascript:{if (typeof(Page_ClientValidate) != 'function' ||  Page_ClientValidate()) __doPostBack('lnkInstancias','')} '>INSTANCIAS</a></td>			</tr>
			</table>";
			
	return $result;
}

function TablaOrigenDemanda(){
	$result = "<table cellspacing='0' rules='all' border='1' id='dbgDemanda' width='100%'>
		<tr class='tableHeader'>
			<td width='180'>Origen</td>		<td>Descripción</td>		<td align='Center' width='60'>Siniestros</td>
	</tr>	<tr class='innerTable'>
			<td>TRABAJADOR</td>		<td>Nombre: AGUIRRE LEOPOLDO O  - CUIL: 20068586748</td>		<td align='Center'>
	<a id='dbgDemanda__ctl3_btnSelect' href='SiniestrosWebForm.aspx?OrigenDemanda=10922&amp;Nro_Juicio=498'><img src='Imagenes/ic_fled_mr.gif' alt='' border='0' /></a>
	</td>
	</tr>
	</table>";
	
	return $result;
}

function TablaReclamos(){
	$result = "<table cellspacing='0' rules='all' border='1' id='dbgReclamos' width='100%'>
				<tr class='tableHeader'>
					<td>Descripción</td>		<td align='Center'>M. Demandado</td>		<td align='Center'>% Inc. Demanda</td>		<td align='Center'>M. Sentencia</td>		<td align='Center'>Porc. Sentencia</td>
			</tr>	<tr class='innerTable'>
					<td>RECLAMO POR ARTICULO 1113 C.C. CON PLANTEO DE INCONSTITUCIONALIDAD</td>		<td align='Right'>$ 13.080,49</td>		<td align='Right'>30,00%</td>		<td align='Right'>$ 0,00</td>		<td align='Right'></td>
			</tr>
			</table>";
	
	return $result;			

}

function TablaBotones(){
	$result = "<table cellspacing='0' cellpadding='2' width='100%' border='0'>
			<tr>
			<td align='center'>
				<input type='image' name='btnPericias' 
					onclick='if (typeof(Page_ClientValidate) == 'function') Page_ClientValidate(); ' language='javascript' id='btnPericias' title='Pericias' 
				
				src='Imagenes/Pericias_off.gif' alt='Pericias' align='Top' border='0' /><br>
				<a id='lnkPericias' class='linksBar' 
					href='javascript:{if (typeof(Page_ClientValidate) != 'function' ||  Page_ClientValidate()) __doPostBack('lnkPericias','')} '>PERICIAS</a></td>
			<td align='center'>
				<input type='image' name='btnEvento' onclick='if (typeof(Page_ClientValidate) == 'function') Page_ClientValidate(); ' language='javascript' id='btnEvento' title='Eventos' onmouseover='javascript:this.src=&quot;Imagenes/Eventos_on.gif&quot;;' onmouseout='javascript:this.src=&quot;Imagenes/Eventos_off.gif&quot;;' src='Imagenes/Eventos_off.gif' alt='Eventos' align='Top' border='0' /><br>
				<a id='lnkEventos' class='linksBar' href='javascript:{if (typeof(Page_ClientValidate) != 'function' ||  Page_ClientValidate()) __doPostBack('lnkEventos','')} '>EVENTOS</a></td>
			<td align='center'>
				<input type='image' name='btnSentencia' onclick='if (typeof(Page_ClientValidate) == 'function') Page_ClientValidate(); ' language='javascript' id='btnSentencia' title='Sentencia' onmouseover='javascript:this.src=&quot;Imagenes/Sentencia_on.gif&quot;;' onmouseout='javascript:this.src=&quot;Imagenes/Sentencia_off.gif&quot;;' src='Imagenes/Sentencia_off.gif' alt='Sentencia' align='Top' border='0' /><br>
				<a id='lnkSentencia' class='linksBar' href='javascript:{if (typeof(Page_ClientValidate) != 'function' ||  Page_ClientValidate()) __doPostBack('lnkSentencia','')} '>SENTENCIA</a></td>
			</tr>
			<tr>
					<td colspan='3'></td>
			</tr>
			</table>";
	
	return $result;			


}
