<script type="text/javascript">
	function inicial() {
		if (document.getElementById('capas').value == '76711059') {
			mostrar('capa1');
			mostrar('capa2');
			mostrar('capa3');
			mostrar('capa4');
			mostrar('capa5');
			mostrar('capa6');
			mostrar('capa7');
			mostrar('capa8');

		}
		else {
			ocultar('capa1');
			ocultar('capa2');
			ocultar('capa3');
			ocultar('capa4');
			ocultar('capa5');
			ocultar('capa6');
			ocultar('capa7');
			ocultar('capa8');

		}
	}
	function mostrar(nombreCapa) {
		document.getElementById(nombreCapa).style.display = 'block';
		document.getElementById('img_' + nombreCapa).src = 'modules/servicio/images/' + nombreCapa.charAt(nombreCapa.length - 1) + '_seleccionado.jpg';
	}

	function ocultar(nombreCapa) {
		document.getElementById(nombreCapa).style.display = 'none';
		document.getElementById('img_' + nombreCapa).src = 'modules/servicio/images/' + nombreCapa.charAt(nombreCapa.length - 1) + '.jpg';
	}
</script>
<table cellspacing="0" cellpadding="0">
	<tr>
		<td class="TituloSeccion">Preguntas frecuentes</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="ContenidoSeccion">
		<table border="0" cellspacing="2" cellpadding="2">
			<tr>
				<td colspan="2"><p>Encuentre la respuesta a sus consultas acerca de su cobertura de Riesgos del Trabajo, el proceso de afiliación, las obligaciones de prevención y cómo actuar en caso de accidente, entre otros temas de interés para su empresa y sus trabajadores.</p><p></p></td>
			</tr>
			<tr>
				<td><a target="_self" href="javascript:mostrar('capa1')" onclick="mostrar('capa1');ocultar('capa2');ocultar('capa3');ocultar('capa4');ocultar('capa5');ocultar('capa6');ocultar('capa7');ocultar('capa8')" ondblclick="ocultar('capa1')" id="capas" onChange="inicial()"><img border="0" id="img_capa1" src="/modules/servicio/images/1.jpg"></a></td>
				<td><a target="_self" href="javascript:mostrar('capa5')" onclick="mostrar('capa5');ocultar('capa1');ocultar('capa2');ocultar('capa3');ocultar('capa4');ocultar('capa6');ocultar('capa7');ocultar('capa8')" ondblclick="ocultar('capa5')" id="capas" onChange="inicial()"><img border="0" id="img_capa5" src="/modules/servicio/images/5.jpg"></a></td>
			</tr>	
			<tr>	
				<td><a target="_self" href="javascript:mostrar('capa2')" onclick="mostrar('capa2');ocultar('capa1');ocultar('capa3');ocultar('capa4');ocultar('capa5');ocultar('capa6');ocultar('capa7');ocultar('capa8')" ondblclick="ocultar('capa2')" id="capas" onChange="inicial()"><img border="0" id="img_capa2" src="/modules/servicio/images/2.jpg"></a></td>
				<td><a target="_self" href="javascript:mostrar('capa6')" onclick="mostrar('capa6');ocultar('capa1');ocultar('capa2');ocultar('capa3');ocultar('capa4');ocultar('capa5');ocultar('capa7');ocultar('capa8')" ondblclick="ocultar('capa6')" id="capas" onChange="inicial()"><img border="0" id="img_capa6" src="/modules/servicio/images/6.jpg"></a></td>
			</tr>
			<tr>
				<td><a target="_self" href="javascript:mostrar('capa3')" onclick="mostrar('capa3');ocultar('capa1');ocultar('capa2');ocultar('capa4');ocultar('capa5');ocultar('capa6');ocultar('capa7');ocultar('capa8')" ondblclick="ocultar('capa3')" id="capas" onChange="inicial()"><img border="0" id="img_capa3" src="/modules/servicio/images/3.jpg"></a></td>
				<td><a target="_self" href="javascript:mostrar('capa7')" onclick="mostrar('capa7');ocultar('capa1');ocultar('capa2');ocultar('capa3');ocultar('capa4');ocultar('capa5');ocultar('capa6');ocultar('capa8')" ondblclick="ocultar('capa7')" id="capas" onChange="inicial()"><img border="0" id="img_capa7" src="/modules/servicio/images/7.jpg"></a></td>
			</tr>
			<tr>
				<td><a target="_self" href="javascript:mostrar('capa4')" onclick="mostrar('capa4');ocultar('capa1');ocultar('capa2');ocultar('capa3');ocultar('capa5');ocultar('capa6');ocultar('capa7');ocultar('capa8')" ondblclick="ocultar('capa4')" id="capas" onChange="inicial()"><img border="0" id="img_capa4" src="/modules/servicio/images/4.jpg"></a></td>
				<td><a target="_self" href="javascript:mostrar('capa8')" onclick="mostrar('capa8');ocultar('capa1');ocultar('capa2');ocultar('capa3');ocultar('capa4');ocultar('capa5');ocultar('capa6');ocultar('capa7')" ondblclick="ocultar('capa8')" id="capas" onChange="inicial()"><img border="0" id="img_capa8" src="/modules/servicio/images/8.jpg"></a></td>
			</tr>			
			<tr>
				<td height="10" colspan="2"></td>
			</tr>
		</table>
		<div id='capa1'>
		<table>
			<tr>
				<td>
					<p><b>1.1. ¿Qué es una ART?</b></p>
					<p>Una Aseguradora de Riesgos del Trabajo o ART es una empresa de seguros que se especializa en la prestación del seguro de riesgos del trabajo creado por la Ley 24.557. Las Superintendencias de Riesgos del Trabajo y de Seguros de la Nación son las que autorizan el funcionamiento de las ART y las controlan.</p>
					<p><b>1.2. ¿Cuáles son las obligaciones de la ART?</b></p>
					<p>Las principales obligaciones son:</p>
					<p>- Brindar asesoramiento y ofrecer asistencia técnica en materia de prevención a las empresas afiliadas.</p>
					<p>- Realizar visitas de control y prevención de riesgos a los establecimientos de las empresas afiliados.</p>
					<p>- Brindar capacitación a los trabajadores cubiertos.</p>
					<p>- Realizar exámenes periódicos a partir de los relevamientos de expuestos provistos por las empresas afiliadas.</p>
					<p>- Brindar atención médica integral en caso de accidente.</p>
					<p>- Proveer los servicios complementarios previstos en la ley para que el trabajador accidentado pueda restablecerse (traslados, elementos de ortopedia, recalificación laboral, etc).</p>
					<p>- Abonar las indemnizaciones contempladas en la ley a las empresas afiliadas, los trabajadores asegurados o sus derechohabientes, según corresponda.</p>
					<p>- Cumplir con la normativa general y cooperar con el organismo de control (Superintendencia de Riesgos del Trabajo) aportando la información que le sea requerida.</p>
					<p><b>1.3. ¿Quiénes tienen que contratar una ART?</b></p>
					<p>Todas las empresas / instituciones con personal en relación de dependencia deben contar con un seguro de riesgos del trabajo.</p>
					<p><b>1.4. ¿Todos los trabajadores están cubiertos por la ART?</b></p>
					<p>El seguro de riesgos del trabajo cubre a los empleados privados en relación de dependencia (a tiempo indeterminado o a plazo fijo), pasantes y diversas modalidades de empleo público (nacional, provincial y municipal). No están incluidos en la cobertura los trabajadores autónomos / monotributistas, trabajadores domésticos, trabajadores vinculados por relaciones no laborales y bomberos voluntarios.</p>
					<p><b>1.5. ¿En qué consiste la cobertura de riesgos del trabajo?</b></p>
					<p>El servicio básico es prevenir la ocurrencia de accidentes de trabajo, accidentes in itínere y enfermedades profesionales. Como la función más importante de una ART es ser agente de prevención, el equipo de especialistas en higiene, seguridad y medicina laboral de Provincia ART trabaja junto a las empresas afiliadas para establecer planes de reducción del riesgo y prevención de accidentes. En caso de accidente, Provincia ART brinda al trabajador atención médica inmediata e integral más un seguimiento constante de su evolución para su pronto restablecimiento. Además, Provincia ART se hace cargo de la indemnización compensatoria de acuerdo con los parámetros de la ley. De esta forma, las empresas afiliadas garantizan la cobertura de sus trabajadores contra accidentes y enfermedades laborales, pueden recuperar los salarios pagados en concepto de días caídos, acceden a un amplio servicio de asesoramiento y capacitación en materia de seguridad, higiene y medicina laboral, y están protegidas de ulteriores consecuencias legales o económicas. Contáctese con el Centro de Atención al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas) para saber más acerca de nuestros <a class="linkSubrayado" href="/servicio">servicios</a>.</p>
					<p><b>1.6. ¿Qué es un certificado de cobertura y cómo lo obtengo?</b></p>
					<p>El certificado de cobertura es un documento que acredita que la empresa que lo presenta cuenta con la cobertura de una ART. Puede ser un certificado simple, con nómina (total o parcial) o con cláusula de no repetición. Éste último lo utilizan aquellas empresas que prestan servicios dentro de otras empresas a pedido de sus clientes (ejemplo: servicios de logística, servicios de seguridad, contratistas de la construcción, etc). A través de la Central de Servicios en Línea, las empresas afiliadas pueden emitir directamente los certificados. También pueden solicitarlos vía e-mail (<a class="linkSubrayado" href="mailto:certificados@provart.com.ar">certificados@provart.com.ar</a>) o telefónicamente al Centro de Atención al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas).</p>
					<p><b>1.7. ¿Cómo se puede obtener credenciales que acrediten la cobertura para mi personal?</b></p>
					<p>Es importante que todo el personal cubierto tenga credencial. Si ya se le agotaron las que le entregamos al momento de afiliarse, puede solicitar una reposición de credenciales a través del Centro de Atención al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas), por e-mail a <a class="linkSubrayado" href="mailto:info@provart.com.ar">info@provart.com.ar</a> o obtenerlas haciendo <a class="linkSubrayado" href="/descarga-formularios">clic aquí</a>.</p>
				</td>
			</tr>
		</table>
		</div>

		<div id='capa2'>
		<table>
			<tr>
				<td>
					<p><b>2.1. ¿Cómo se puede obtener una cotización?</b></p> 
					<p>Para solicitar una cotización, puede contactarse con nuestro Centro de Atención al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas), escribirnos a <a class="linkSubrayado" href="mailto:info@provart.com.ar">info@provart.com.ar</a>, acercarse a cualquiera de <a class="linkSubrayado" href="/sucursales">nuestras oficinas</a> o a la sucursal más cercana del Banco Nación, Banco Provincia o Banco Ciudad. También puede averiguar acerca de nuestros servicios y afiliarse a través de su productor o broker de seguros.</p>
					<p><b>2.2. ¿Qué datos son necesarios para la cotización?</b></p>
					<p>Al momento de solicitar una cotización es conveniente contar con los siguientes datos:</p>
					<p>- Razón social.</p>
					<p>- C.U.I.T.</p>
					<p>- Código de actividad según formulario A.F.I.P. Nº150 (C.I.I.U. y Descripción de actividad).</p>
					<p>- Cantidad de trabajadores.</p>
					<p>- Masa salarial.</p>
					<p><b>2.3. ¿Qué documentación se debe presentar al momento de la afiliación / traspaso?</b></p>
					<p>Al concurrir a afiliarse a cualquiera de las <a class="linkSubrayado" href="/sucursales">oficinas de Provincia ART</a>, las sucursales del Banco Provincia, Banco Nación o Banco Ciudad, o a su productor o broker de seguros se deberá completar la siguiente documentación:</p>
					<p>- Formulario de Solicitud de Afiliación firmada por una persona responsable (titular o apoderado).</p>
					<p>- Formulario de Ubicación de Riesgo.</p>
					<p>- Formulario de Relevamiento General de Riesgos Laborales (uno por cada establecimiento).</p>
					<p>- Poder del firmante: original y fotocopia.</p>
					<p>- DNI del firmante: original y fotocopia de 1º y 2º hoja.</p>
					<p>- Fotocopia de Estatuto o Contrato constitutivo para empresas con personería jurídica.</p>
					<p>- Fotocopia del formulario 460 de constancia de inscripción en la A.F.I.P.</p>
					<p>- Fotocopia de las constancias de alta de los trabajadores en “Mi Simplificación” (AFIP). Si la empresa tiene más de 35 empleados puede presentar la nómina en Excel enviándola a <a class="linkSubrayado" href="mailto:nominas@provart.com.ar">nominas@provart.com.ar</a> y adjuntar el listado que surge de la página web de A.F.I.P. con los movimientos realizados por Mi Simplificación.</p>
					<p><b>2.4. Una vez producida la afiliación, ¿cuándo comienza la vigencia de la cobertura?</b>
					<p>En el caso de las empresas que se afilian por primera vez a una ART, la cobertura entra en vigencia desde la 0 hora del día siguiente a la suscripción de la solicitud de afiliación. </p>
					<p>En caso de un traspaso, éstos se solicitan a la aseguradora actual del 1 al 10 de cada mes. Si la empresa registra deuda con la ART anterior o no presentó el Relevamiento General de Riesgos Laborales –en caso de corresponder-, primero deberá regularizar su situación. La cobertura entrará en vigencia a partir del 1º día del mes siguiente al del mes en el que se aprobó el traspaso. Mientras transcurre la aprobación, la ART anterior sigue cubriendo a la empresa.</p>
					<p><b>2.5. ¿Qué sucede cuando el traspaso es rechazado?</b></p>
					<p>Para que una empresa pueda traspasarse de ART deben haber transcurrido 6 (empresa nueva) o 12 meses (empresa ya inscripta en el sistema) desde la firma del contrato con una ART. La ART actual puede negar el traspaso en caso de que la empresa registre deuda o no haya presentado el Formulario de Relevamiento General de Riesgos Laborales. La SRT informa la negativa de traspaso a la ART solicitante para que ésta dé aviso a la empresa.</p>				
				</td>
			</tr>
		</table>
		</div>

		<div id='capa3'>	
			<table>
				<tr>
					<td>
						<p><b>3.1. ¿Cómo deben efectuarse los movimientos en la nómina de personal?</b></p>
						<p>Todas las Altas y Bajas de personal, así como las modificaciones de los datos informados con error u omisión, los vínculos familiares de cada uno y los domicilios de desempeño entre otros datos, deben ser incorporadas mediante Internet a través del aplicativo “Mi Simplificación”, al cual se accede con clave fiscal.</p>
						<p>Mediante Mi Simplificación -On Line- el empleador podrá:</p>
						<p style="margin-left: 10px">- Informar las altas de las nuevas relaciones laborales.<br>
						- Confirmar las altas anticipadas por teléfono (Anexo IV)<br>
						- Modificar los datos ingresados oportunamente.<br>
						- Informar las bajas de las relaciones laborales.<br>
						- Anular las altas y bajas anticipadas comunicadas.<br>
						- Consultar todas las relaciones laborales activas existentes, todas las gestiones de claves realizadas en un día, etc.</p>
						<p>Además, este registro exhibe en forma automática:</p>
						<p style="margin-left: 10px">- La ART contratada con indicación de la fecha de vigencia<br>
						- Apellido y Nombre del trabajador<br>
						- Obra Social elegida por el trabajador si existiera opción<br>
						- Régimen previsional del trabajador<br>
						<p>Para cargas masivas de datos, el empleador podrá acceder el programa “Mi Simplificación”, integrado dentro del  Sistema Integrado de Aplicaciones (S.I.Ap.) para luego transmitir la declaración jurada a través del servicio “Presentación de DDJJ y Pagos”, al cual también se accede con clave fiscal.</p>
						<p>Normativa Aplicable: RG 2016.</p>
						<p>Si desea mayor información, usted puede ingresar en <a class="linkSubrayado" target="_blank" href="http://www.afip.gov.ar/genericos/miSimplificacion/">www.afip.gov.ar/genericos/miSimplificacion</a>, comunicarse con el Centro de Información Telefónica al 0810-999-2347 de 8 a 20 horas, o enviar un <a class="linkSubrayado" href="mailto:mayuda@afip.gov.ar">correo electrónico a la Mesa de Ayuda de AFIP</a>.</p>
						<p><b>3.2. ¿Cómo es posible registrar la baja de trabajadores?</b></p>
						<p>Es posible efectuar la baja de los trabajadores dentro del registro administrado por AFIP, utilizando alguna de las siguientes vías:</p>
						<p style="margin-left: 10px">- Ingresar con clave fiscal, a través del servicio "Mi Simplificación".<br>
						- Presentar el formulario 885, por duplicado, en la dependencia donde se encuentra inscripto.<br>
						- Off Line, mediante la generación del archivo F. 935 a través del aplicativo Mi Simplificación ejecutado dentro del S.I.Ap.</p>
						<p>Es importante tener en cuenta lo que establece la RG 1891 en su artículo 4º: “La comunicación de la baja en el "Registro" deberá realizarse dentro del plazo de CINCO (5) días corridos, contados a partir de la fecha, inclusive, en que se produjo la extinción del contrato de trabajo, por cualquier causa. Si la baja se produce por renuncia, y se recibe el telegrama luego de transcurridos 5 días corridos, tenga en cuenta lo establecido en el Evento 1218.”</p>
						<p><b>3.3. ¿Qué sucede si el empleador no tiene acceso a Internet?</b></p>
						<p>En caso de no contar con acceso a Internet, el empleador podrá concurrir a la Agencia AFIP en la que se encuentre inscripto, y realizar las altas, bajas, modificaciones y anulaciones por medio del F.885 nuevo modelo, el ingreso de las novedades necesarias, de los datos del empleador y las relaciones laborales.</p>
						<p>Cuando se trate de relaciones de familiares debe concurrir a la UDAI de ANSES más cercana a su domicilio para informar las novedades, siempre que el empleador tenga en su poder la información respaldatoria de los vínculos de familia a informar.</p>
						<p>Para garantizar la <b>adecuada cobertura</b> de su nómina y mantener ordenado el estado de cuentas, <b>la nómina de trabajadores</b> declarados por el empleador, <b>debe ser reflejada en su totalidad en la Declaración Mensual de Personal</b> presentada a través de la AFIP.</p>
					</td>
				</tr>
			</table>
		</div>
	
		<div id='capa4'>	
		<table>
			<tr>
				<td>
					<p><b>4.1. ¿Cómo calcula la ART el valor de la tarifa?</b></p>
					<p>La tarifa varía de acuerdo con distintos factores: el rubro de actividad de la empresa, la historia siniestral (accidentes ocurridos entre 1996 y la fecha), la masa salarial de los empleados, entre otros.</p>
					<p>La tarifa mensual se compone de una parte fija y otra variable (acompaña las variaciones de la masa salarial) más $0,60 por trabajador destinados al Fondo Especial de Enfermedades Profesionales.</p>
					<p><b>4.2. ¿Dónde puedo consultar mi tarifa?</b></p>
					<p>Si su empresa ya está afiliada con Provincia ART, puede consultar la alícuota vigente a través de la Central de Servicios en Línea o comunicándose con el Centro de Atención al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas).</p>
					<p><b>4.3. ¿Cómo se paga el seguro de riesgos del trabajo?</b></p>
					<p>Las empresas privadas y los organismos públicos adheridos al Sistema Único de la Seguridad Social (SUSS) abonan la tarifa mensual junto con las cargas sociales a través del Formulario 931 de AFIP. El servicio se paga a mes adelantado en función de la nómina salarial del mes anterior (Art. 23 Ley de Riesgos de Trabajo 24.557 y art. 9 del Decreto 334/96).</p>
					<p>En los casos de inicio de actividad, o cuando por otras razones no exista nómina salarial en el mes anterior al pago de la cuota, la cuota de afiliación se calculará en función de la nómina salarial prevista para el mes en curso. En el supuesto previsto para el inicio de actividad, la cuota será ingresada en forma directa a la Aseguradora correspondiente.</p>
					<p><b>4.4. ¿Dónde puedo verificar el estado de mi cuenta?</b></p>
					<p>Para consultar el estado de su cuenta, puede hacerlo a través de la web ingresando en la Central de Servicios en Línea, comunicándose con nuestro Centro de Atención al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas) o escribiendo a <a class="linkSubrayado" href="mailto:cobranzas@provart.com.ar">cobranzas@provart.com.ar</a>.</p>
					<p><b>4.5. Si figura que mi empresa tiene deuda, ¿cómo lo soluciono?</b></p>
					<p>En principio, al pagar sobre la nómina declarada no debería generarse deuda. Sin embargo, puede haber errores de cálculo, problemas con la información o atrasos en los pagos que den lugar a desajustes al abonar la tarifa. En caso de registrar deuda, ésta puede pagarse utilizando los formularios de AFIP 817 (para deuda nominal) y 801/C (para intereses), recordando utilizar un formulario por cada período en el que se registre la mora.</p>
					<p>Recuerde que, de acuerdo con la Ley 24.557, la existencia de deuda puede bloquear un traspaso o, en caso de sumar el equivalente a 2 períodos de cobertura, puede implicar la rescisión del contrato con la ART.</p>
				</td>
			</tr>
		</table>					
		</div>
	
		<div id='capa5'>
		<table>
			<tr>
				<td>
					<p><b>5.1. Recomendaciones para confeccionar las DDJJ de personal</b></p>
					<p>- Si el empleador se encuentra obligado a utilizará el servicio de AFIP “Su Declaración”, al cual se accede con clave fiscal y que permite obtener la declaración jurada determinativa de aportes y contribuciones con destino a los distintos subsistemas de la seguridad social, confeccionada sobre la base de los datos del período inmediato anterior a aquel que se declara, si existiera, más las novedades registradas en el sistema "Mi Simplificación".</p>
					<p>Más información: <a class="linkSubrayado" href="http://www.afip.gob.ar/sudeclaracion">www.afip.gob.ar/sudeclaracion</a></p>
					<p>- De no encontrarse contenido en la opción “Su declaración”, al momento de crear una nueva declaración jurada con el <b>Sistema de Cálculo de Obligaciones de la Seguridad Social</b> (aplicativo SICOSS), el empleador debe tener en cuenta colocar los datos de la tarifa vigente con la aseguradora en el apartado “Ley de riegos del Trabajo” <a class="linkSubrayado" href="#" onClick="javascript:window.open('/modules/servicio/img1.php',null,'height=429,width=575,status=no,toolbar=no ,menubar=no,location=no,top=5, left=20');">[ver imagen]</a>. Para poder ingresar las alícuotas, es necesario tildar previamente “Corresponde LRT”, paso que el empleador deberá efectuar cada vez confeccione una Declaración Jurada, para poder calcular correctamente la cuota de LRT.</p>
					<p>- Cuando el empleador ingrese la información por trabajador, deberá reflejar correctamente los datos del cuadro “Datos Referenciales del Empleado” <a class="linkSubrayado" href="#" onClick="javascript:window.open('/modules/servicio/img2.php',null,'height=425,width=578,status=no,toolbar=no ,menubar=no,location=no,top=5, left=20');">[ver imagen]</a>. La <b>modalidad de contratación</b> informada debe ser igual que la indicada en el momento de efectuar el alta temprana.</p>
					<p>- Al ingresar los “Datos Complementarios” <a class="linkSubrayado" href="#" onClick="javascript:window.open('/modules/servicio/img3.php',null,'height=332,width=567,status=no,toolbar=no ,menubar=no,location=no,top=5, left=20');">[ver imagen]</a>, se deberá poner especial cuidado en <b>reflejar la correcta cantidad de días trabajados</b>. La cantidad de días trabajados debe ser la cantidad real, guardando relación con la remuneración informada. Por ejemplo si un trabajador percibió $600 por 2 días trabajados, debe desprenderse esta información de la Declaración Jurada presentada ante la AFIP.</p>
					<p>- Cuando se ingresan las remuneraciones, es recomendable que el empleador <b>revise el campo “Remuneración 9”</b> <a class="linkSubrayado" href="#" onClick="javascript:window.open('/modules/servicio/img4.php',null,'height=253,width=582,status=no,toolbar=no ,menubar=no,location=no,top=5, left=20');">[ver imagen]</a> 
					ya que allí deben reflejarse la sumatoria de:</p>
					<p>a) los montos de conceptos remunerativos brutos, 
					<a href="http://www.provinciart.com.ar/novedades/23">aplicando el correspondiente tope vigente</a>, 
					o</p>
					<p class="MsoNormal">b) de conceptos remunerativos brutos y no remunerativos en ambos casos percibidos por el trabajador en el mes.<o:p></o:p></p>
					<p class="MsoNormal">El manual que indica como confeccionar el F931 bajo la nueva ley, es decir el link: Más información: <a target="_blank" href="http://www.provinciart.com.ar/descargas/PART_DDJJ_Base_imponible.pdf">www.provinciart.com.ar/descargas/PART_DDJJ_Base_imponible.pdf</a></p>
					<p>Para conocer cuál de las opciones debe colocar en función de su contrato, la comunicación A10 – Ley 26773 remitida a través de la ventanilla electrónica de la Superintendencia de Riesgos del Trabajo.</p>					
					<p>- Una vez completados los datos de todos los trabajadores, y al momento de imprimir el <b>Formulario 931</b>, es importante <b>corroborar que los datos del apartado VI</b> – Ley de Riesgos del Trabajo <a class="linkSubrayado" href="#" onClick="javascript:window.open('/modules/servicio/img5.php',null,'height=521,width=468,status=no,toolbar=no ,menubar=no,location=no,top=5, left=20');">[ver imagen]</a> se encuentren completos. Por otra parte se recomienda al empleador conservar una copia del formulario, junto al Acuse de Recibo de DJ emitido por la AFIP al momento de efectuar la presentación de la DDJJ a través de Internet.</p>
					<p>- Deberá seguir los mismos pasos al momento de confeccionar un archivo TXT para importar en el aplicativo.</p>
					<p><i><b>- De este modo el Formulario 931 le proveerá el monto correcto a abonar en concepto de ART, lo que le permitirá garantizar la adecuada cobertura de su nómina y mantener ordenado el estado de cuentas.</b></i></p>
					<p><b>Organismos Públicos no adheridos al SUSS:</b></p>
					<p>- Los Organismos Públicos podrán presentar sus DDJJ a través de esta Web, desde el <a class="linkSubrayado" target="_blank" href="/acceso-exclusivo-usuarios-registrados">Acceso Exclusivo</a> por medio de una clave y contraseña. Con esta herramienta los OOPP pueden remitir el detalle de sus trabajadores y confeccionar automáticamente el Resumen No Suss (RNS).  A través de tres sencillos pasos,  es posible controlar la correcta presentación de la información de los trabajadores y posteriormente enviar el RNS con los datos coincidentes al detalle. </p>
					<p>Aquí se encuentra disponible el  <a class="linkSubrayado" target="_blank" href="http://www.provinciart.com.ar/download/P_ART_Instructivo_extranet.pdf">Manual de uso</a> con las instrucciones para la utilización de este sistema</p>										
					<p><b>5.2. ¿Por qué se factura un trabajador con Código de Situación: “Licencia”?</b></p>
					<p>En la Declaración Jurada se incluyen a todos los empleados en relación de dependencia, mas allá de que se le efectúe o no una liquidación.</p> 
					<p>Lo que preveé el aplicativo de la AFIP (Sicoss) es la especificación de la condición del trabajador por medio del Código de situación, para que sea consistente el hecho de que tenga o no salario declarado. Por ello es que existen diferentes números de Códigos entre los cuales además de 01-Activo, se encuentran las licencias.</p>
					<p>Una de las razones por las cuales existe esta forma de declarar, además de la relación de dependencia con la empresa, es que el trabajador en caso de tener un siniestro en el período de licencia (por ejemplo si va a la empresa hacer algun tramite laboral, un initinere) el accidente tiene cobertura de Ley de Riesgo de Trabajo (LRT). A su vez, como está declarado ante Mi Simplificación debe ser incluído en la Declaración Jurada.</p>
					<p>El aplicativo de la AFIP calcula el Apartado de LRT en base a las alícuotas que posea la empresa. En caso de tener sólo de suma fija los $0.60, al no haber masa salarial, solo calculará esos $0.60 (Si tiene una fija distinta, calcula la fija).</p>
					<p><b>5.3. ¿Cómo se ingresan los Socios, Gerentes, Directores de S.A., etc.?</b></p>
					<p>Se deberán tener en cuenta las siguientes situaciones:</p>
					<p>A) Por ser Directores de S.A. están obligados a ser AUTÓNOMOS. Cuando cumplen con tareas administrativas, (como por ejemplo, además son los contadores internos de las organizaciones), y sólo se informa la ART en el aplicativo (porque no están adheridos al Régimen de Jubilaciones y Pensiones) puesto que ejercen la opción de no ingresar aportes y contribuciones por el sueldo que reciben (situación comprendida en la Ley 24.241), deben ser ingresados en el aplicativo con el código de Actividad 15, de Modalidad 99 y Obra Social Ninguna.</p>
					<p>En el caso de querer tener una cobertura de salud, deben realizarla directamente en la entidad y no a través del aplicativo. </p>
					<p>Se debe tener en cuenta que desde la obligatoriedad de utilizar el aplicativo SICOSS versión 32, la indicación del código de Actividad 15 especificada en el párrafo anterior, no comprende a los directores de sociedades que tienen por objeto desarrollar las actividades alcanzadas por la Ley 25.922 ( Ley de Promoción de la Industria del Software). A partir de las DDJJ presentadas con el aplicativo mencionado, debe registrar a estos directores con el código de Actividad 40, de Modalidad 99 y Obra Social Ninguna.</p>
					<p>B) Por ser Directores de S.A. están obligados a ser AUTÓNOMOS. Cuando cumplen con tareas administrativas, (como por ejemplo, además son los contadores internos de las organizaciones), y se informan en el aplicativo (porque están adheridos al Régimen de Jubilaciones y Pensiones), puesto que no ejercen la opción e ingresan aportes y contribuciones, se debe cargar en el aplicativo con el código de Actividad 49 y de Modalidad 08, como un trabajador más.</p>
					<p>Ambas situaciones descriptas anteriormente se consideran en Relación de dependencia, por lo que debe informarse en el aplicativo y en el servicio Mi Simplificación.</p>
					<p><i>Aclaración: Existe otra posibilidad (diferentes a las indicadas en el inciso A) y B)), ser Director de S.A., obligados a ser AUTÓNOMOS, que no cumplen tareas administrativas, por lo tanto, no están en relación de dependencia.</i></p>
					<p><i>Estos casos no se cargan en el aplicativo, ni se informan en el servicio Mi simplificación.</i></p>
					<p>Fuente: CIT AFIP <a class="linkSubrayado" target="_blank" href="http://www.afip.gob.ar/genericos/guiavirtual/consultas_detalle.aspx?id=1453600">www.afip.gob.ar/genericos/guiavirtual/consultas_detalle.aspx?id=1453600</a></p>
				</td>
			</tr>
		</table>
		</div>
	
		<div id='capa6'>
		<table>
			<tr>
				<td>
					<p><b>6.1. ¿En qué consiste el servicio de prevención de la ART?</b></p>
					<p>El servicio de prevención de Provincia ART comprende:</p>
					<p>- Visitas de fiscalización para relevar condiciones, verificar el cumplimiento de la normativa y realizar recomendaciones.</p>
					<p>- Asesoramiento para la mejora de las condiciones de trabajo, adaptado al riesgo, la actividad y las posibilidades de la empresa.</p>
					<p>- Desarrollo de planes y programas de prevención.</p>
					<p>- Capacitación a los trabajadores en el puesto de trabajo, uso correcto de elementos de protección personal, actuación en caso de accidente, etc.</p>
					<p>- Organización de simulacros de evacuación y rol de incendio.</p>
					<p>- Realización de relevamientos de expuestos y exámenes periódicos.</p>
					<p>- Asesoramiento en higiene industrial y mediciones ambientales.</p>
					<p>- Asesoramiento en ergonomía y evaluación de puestos de trabajo.</p>
					<p>Con la asistencia de nuestro equipo de especialistas en higiene, seguridad, medicina laboral y ergonomía, las empresas pueden cumplir con la normativa y garantizar un ambiente laboral sano y seguro que promueva la eficacia y la eficiencia en sus resultados. Hacer prevención no es un gasto, sino una inversión al alcance de todas las empresas.</p>
					<p><b>6.2. ¿Todas las empresas están obligadas a contar con un profesional propio de higiene y seguridad y/o con un médico laboral?</b></p>
					<p>De acuerdo con el Decreto 1338/96, las empresas que están excluidas de poseer servicio propio de higiene y seguridad son:</p>
					<p>- Los establecimientos dedicados exclusivamente a tareas administrativas de hasta DOSCIENTOS (200) trabajadores.</p>
					<p>- Los establecimientos donde se desarrollen tareas comerciales o de servicios de hasta CIEN (100) trabajadores, siempre que no se manipulen, almacenen o fraccionen productos tóxicos, inflamables, radioactivos o peligrosos para el trabajador.</p>
					<p>- Los servicios médicos sin internación.</p>
					<p>- Los establecimientos educativos que no tengan talleres.</p>
					<p>- Los talleres de reparación de automotores que empleen hasta CINCO (5) trabajadores equivalentes.</p>
					<p>- Los lugares de esparcimiento público que no cuenten con áreas destinadas al mantenimiento, de menos de TRES (3) trabajadores.</p>
					<p>- Los establecimientos dedicados a la agricultura, caza, silvicultura y pesca, que tengan hasta QUINCE (15) trabajadores permanentes.</p>
					<p>- Las explotaciones agrícolas por temporada.</p>
					<p><b>6.3. ¿Cómo se puede solicitar la visita de un preventor?</b></p>
					<p>Para solicitar una visita, puede comunicarse con el Centro de Atención al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas) o escribir un e-mail a <a class="linkSubrayado" href="mailto:prevencion@provart.com.ar">prevencion@provart.com.ar</a>.</p>
					<p><b>6.4. ¿Qué es el Formulario de Relevamiento General de Riesgos Laborales?</b></p>
					<p>Es un formulario creado por la Resolución SRT 463/09 por medio del cual el empleador declara, con carácter de declaración jurada, su nivel de cumplimiento de obligaciones de higiene y seguridad para cada uno de sus establecimientos. Su presentación es obligatoria al momento de la afiliación a una ART (por primera vez o por traspaso) y luego una vez al año antes de que opere la renovación automática del contrato. Cada formulario debe estar firmado por quien confecciona el formulario y por su Servicio de higiene y seguridad. En caso de no presentarlo en tiempo y forma, la Res. SRT 529/09 establece penalidades para la empresa. La presentación de los formularios se realiza en cualquiera de las <a class="linkSubrayado" href="/sucursales">oficinas de Provincia ART</a> o por correo a Provincia ART - Casa Central - Gerencia de Prevención - Carlos Pellegrini 91 2º (C1009ABA) - Ciudad Autónoma de Buenos Aires.</p>
					<p>Descargar instructivo para completar el RGRL (PDF)</p>
				</td>
			</tr>
		</table>	
		</div>

		<div id='capa7'>
		<table>
			<tr>
				<td>
					<p><b>7.1. ¿Qué hay que hacer en caso de accidente?</b></p>
					<p>El empleador debe completar el <a class="linkSubrayado" href="/descarga-formularios">Formulario de Solicitud de Asistencia Médica</a> y entregárselo al trabajador accidentado para que pueda concurrir al prestador y recibir atención médica. Si el accidente es grave, alguna persona de la empresa debería acompañar al accidentado al médico.</p>
					<p>En caso de urgencia, comuníquense inmediatamente con el C.E.M. (0800-333-1333 las 24 horas, los 365 días del año) para el envío de una ambulancia.</p>
					<p>Además, en todos los casos debe enviar a Provincia ART el <a class="linkSubrayado" href="/descarga-formularios">Formulario de Denuncia de Accidente</a> firmado en original. Puede adelantarlo vía fax (011-4819-2888) o cargarlo directamente en la Web a través de la Central de Servicios en Línea.</p>
					<p>El trabajador debe portar siempre la credencial con los números de teléfono de emergencia de Provincia ART. Si el accidente sucede dentro de la empresa, debe pedirle a su empleador la <a class="linkSubrayado" href="/descarga-formularios">Solicitud de Asistencia Médica</a> para concurrir al sanatorio a atenderse. En caso de que se trate de un accidente in itínere, necesitará además una denuncia policial o exposición civil (en la provincia de Buenos Aires se realizan en los municipios, no en las comisarías).</p>
					<p>Ante cualquier accidente grave, en el lugar de trabajo o in itínere, comuníquese inmediatamente con el C.E.M. (0800-333-1333 las 24 horas, los 365 días del año).</p>
					<p>Descargar el instructivo “<a class="linkSubrayado" target="_blank" href="<?= getFile(STORAGE_EXTRANET."descargables_web/Provincia_ART_instructivo_accidentes.pdf")?>">Qué hacer en caso de accidente</a>”</p>
					<p><b>7.2. ¿Cómo se completa el Formulario de Denuncia de Accidente?</b></p>
					<p>El empleador puede completar este formulario en forma manual o a través de la Central de Servicios en Línea pero, en cualquier caso, deberá enviarlo firmado en original a Provincia ART dentro de las 48 horas de sucedido el accidente. El formulario es importante porque permite registrar el siniestro para darle tratamiento, entender cómo sucedió el accidente para planificar la prevención, obtener datos para contactar a la empresa o al trabajador accidentado, derivar al trabajador a un prestador idóneo, etc. Es fundamental completar todos los campos referidos a datos de la empresa, del establecimiento en el que sucedió el accidente, del trabajador accidentado y del lugar donde éste estuviera recibiendo atención médica (prestador).</p>
					<p><b>7.3. ¿Dónde puedo consultar la cartilla de prestadores?</b></p>
					<p>Provincia ART cuenta con más de 3.000 prestadores de distintas especialidades. Los trabajadores que se accidentes pueden concurrir libremente a algunos de ellos, mientras que para otros se necesita derivación de un especialista (como en cualquier servicio médico). Para conocer los prestadores cercanos a su empresa a los que puede dirigirse sin derivación, consulte la cartilla disponible en la Central de Servicios en Línea, comuníquese con el Centro de Atención al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas) o escriba a <a class="linkSubrayado" href="mailto:info@provart.com.ar">info@provart.com.ar</a>.</p>
					<p><b>7.4. ¿Cuáles son los servicios a los que puede acceder un trabajador accidentado?</b></p>
					<p>Desde el momento del accidente, Provincia ART pone a disposición del trabajador los siguientes servicios y prestaciones:</p>
					<p>- Atención médica integral: servicio de ambulancia, atención primaria, atención de alta complejidad, estudios, diagnóstico por imágenes, intervenciones quirúrgicas, internación, medicamentos, materiales de osteosíntesis y ortopedia, oftalmología, odontología, atención psiquiátrica.</p>
					<p>- Atención en el exterior, traslados aéreos y terrestres, y hospedaje.</p>
					<p>- Rehabilitación y recalificación profesional.</p>
					<p>- Contención social al accidentado y la familia.</p>
					<p>- Prestaciones dinerarias: indemnizaciones al trabajador accidentado o a su familia.</p>
					<p>La atención se brinda hasta la curación completa o mientras subsista la incapacidad.</p>
					<p><b>7.5. ¿Cómo se gestionan los turnos y traslados?</b></p>
					<p>Las gestiones relativas a turnos y traslados hacia y desde los prestadores médicos se realizan a través del Centro de Atención al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas).</p>
					<p><b>7.6. ¿Cuándo se reintegra un empleado a sus labores después de haber sufrido un accidente laboral?</b></p>
					<p>El empleado puede volver a trabajar cuando cuenta con el alta laboral. A través de la Central de Servicios en Línea o comunicándose con el Centro de Atención al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas), las empresas afiliadas pueden obtener información sobre el estado de sus trabajadores accidentados de acuerdo con las normas que regulan la confidencialidad para temas médicos.</p>
					<p><b>7.7. ¿Qué son las prestaciones dinerarias?</b></p>
					<p>Existen básicamente 3 tipos de prestaciones dinerarias: la ILT (Incapacidad Laboral Temporaria) que se paga a las empresas en calidad de reintegro, las Incapacidades Laborales Permanentes (provisoria, definitiva, parcial o total) y la Indemnización por fallecimiento. Las dos últimas se abonan a los trabajadores accidentados o a sus derecho habientes.</p>
					<p><b>7.8. ¿Qué documentación debe presentar la empresa para obtener el reintegro de ILT?</b></p>
					<p>La ILT es el pago que la ART le hace a las empresas reintegrándoles los sueldos de sus trabajadores accidentados durante los días que éstos están de baja (excepto los 10 primeros días de franquicia). La empresa debe pagarles el sueldo por cuenta y orden de Provincia ART –liquidando los días de baja con un concepto especial que se llama ‘Prestación dineraria a cargo de la empresa’ y ‘Prestación dineraria a cargo de la ART’- y a mes vencido la ART les reintegra el importe correspondiente. Para ello, las empresas deben presentar: el Formulario de Liquidación de Prestaciones Dinerarias (firmado en original y sellado por el responsable de la empresa), fotocopia de los comprobantes de pago de los aportes y contribuciones a la Seguridad Social de los meses a reintegrar, informar los porcentajes de las contribuciones patronales de los meses a reintegrar (detallando conceptos y porcentajes netos de reducción), fotocopia de los recibos de sueldo correspondientes a los meses a reintegrar (firmados y sellados en original por el responsable de la empresa con la leyenda “es copia fiel”).</p>
					<p><b>7.9. ¿Qué documentación deben presentar los derecho-habientes para percibir la prestación dineraria por fallecimiento?</b></p>
					<p>La documentación varía de acuerdo con quiénes son los derecho-habientes. Se considera derecho-habientes a:</p>
					<p>- Cónyuge, conviviente, hijos (menores de 21 o menores de 25 si son estudiantes a cargo exclusivo del fallecido)</p>
					<p>- Padres (sólo si no existen los anteriores beneficiarios)</p>
					<p>- Otros beneficiarios a cargo del fallecido (parientes consanguíneos en ausencia de los dos grupos de beneficiarios anteriores).</p>
				</td>
			</tr>
		</table>
		</div>

		<div id='capa8'>
		<table>
			<tr>
				<td>
					<p><b>8.1. ¿Cómo se canalizan las consultas, reclamos y quejas?</b></p>
					<p>Para solicitar información, realizar consultas, presentar quejas y reclamos, puede comunicarse con el Centro de Atención al Cliente (0800-333-1278 de Lunes a Viernes de 9 a 18 Hs.) o escribir un e-mail a info@provart.com.ar.</p>
					<p><b>8.2. ¿Cómo es posible contactarse con el organismo de control?</b></p>
					<p>La Superintendencia de Riesgos del Trabajo (SRT) recibe denuncias, quejas y reclamos personalmente y por correo en Bartolomé Mitre 751 3º Ciudad de Buenos Aires; por teléfono al 0800-666-6778; por correo electrónico a <a class="linkSubrayado" href="mailto:denuncias@srt.gov.ar">denuncias@srt.gov.ar</a> o a través de su página web <a class="linkSubrayado" target="_blank" href="http://www.srt.gov.ar/">www.srt.gov.ar</a></p>
					<p><b>8.3. ¿Qué es la ventanilla electrónica para empleadores?</b></p>
					<p>Se trata de un sistema electrónico que permite a las empresas acceder a la información del Sistema de Riesgos del Trabajo y a las comunicaciones remitidas por la SRT y por la ART con la que tengan contrato. El acceso a la ventanilla se realiza desde la página web <a class="linkSubrayado" target="_blank" href="http://www.srt.gov.ar/">www.srt.gov.ar</a></p>
				</td>
			</tr>
		</table>
		</div>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td width="2%"><input class="btnVolver" type="button" value="" onClick="history.back(-1);" /></td>
	</tr>
</table>
<script type="text/javascript">
	inicial();
</script>