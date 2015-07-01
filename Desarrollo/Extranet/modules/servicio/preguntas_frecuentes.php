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
				<td colspan="2"><p>Encuentre la respuesta a sus consultas acerca de su cobertura de Riesgos del Trabajo, el proceso de afiliaci�n, las obligaciones de prevenci�n y c�mo actuar en caso de accidente, entre otros temas de inter�s para su empresa y sus trabajadores.</p><p></p></td>
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
					<p><b>1.1. �Qu� es una ART?</b></p>
					<p>Una Aseguradora de Riesgos del Trabajo o ART es una empresa de seguros que se especializa en la prestaci�n del seguro de riesgos del trabajo creado por la Ley 24.557. Las Superintendencias de Riesgos del Trabajo y de Seguros de la Naci�n son las que autorizan el funcionamiento de las ART y las controlan.</p>
					<p><b>1.2. �Cu�les son las obligaciones de la ART?</b></p>
					<p>Las principales obligaciones son:</p>
					<p>- Brindar asesoramiento y ofrecer asistencia t�cnica en materia de prevenci�n a las empresas afiliadas.</p>
					<p>- Realizar visitas de control y prevenci�n de riesgos a los establecimientos de las empresas afiliados.</p>
					<p>- Brindar capacitaci�n a los trabajadores cubiertos.</p>
					<p>- Realizar ex�menes peri�dicos a partir de los relevamientos de expuestos provistos por las empresas afiliadas.</p>
					<p>- Brindar atenci�n m�dica integral en caso de accidente.</p>
					<p>- Proveer los servicios complementarios previstos en la ley para que el trabajador accidentado pueda restablecerse (traslados, elementos de ortopedia, recalificaci�n laboral, etc).</p>
					<p>- Abonar las indemnizaciones contempladas en la ley a las empresas afiliadas, los trabajadores asegurados o sus derechohabientes, seg�n corresponda.</p>
					<p>- Cumplir con la normativa general y cooperar con el organismo de control (Superintendencia de Riesgos del Trabajo) aportando la informaci�n que le sea requerida.</p>
					<p><b>1.3. �Qui�nes tienen que contratar una ART?</b></p>
					<p>Todas las empresas / instituciones con personal en relaci�n de dependencia deben contar con un seguro de riesgos del trabajo.</p>
					<p><b>1.4. �Todos los trabajadores est�n cubiertos por la ART?</b></p>
					<p>El seguro de riesgos del trabajo cubre a los empleados privados en relaci�n de dependencia (a tiempo indeterminado o a plazo fijo), pasantes y diversas modalidades de empleo p�blico (nacional, provincial y municipal). No est�n incluidos en la cobertura los trabajadores aut�nomos / monotributistas, trabajadores dom�sticos, trabajadores vinculados por relaciones no laborales y bomberos voluntarios.</p>
					<p><b>1.5. �En qu� consiste la cobertura de riesgos del trabajo?</b></p>
					<p>El servicio b�sico es prevenir la ocurrencia de accidentes de trabajo, accidentes in it�nere y enfermedades profesionales. Como la funci�n m�s importante de una ART es ser agente de prevenci�n, el equipo de especialistas en higiene, seguridad y medicina laboral de Provincia ART trabaja junto a las empresas afiliadas para establecer planes de reducci�n del riesgo y prevenci�n de accidentes. En caso de accidente, Provincia ART brinda al trabajador atenci�n m�dica inmediata e integral m�s un seguimiento constante de su evoluci�n para su pronto restablecimiento. Adem�s, Provincia ART se hace cargo de la indemnizaci�n compensatoria de acuerdo con los par�metros de la ley. De esta forma, las empresas afiliadas garantizan la cobertura de sus trabajadores contra accidentes y enfermedades laborales, pueden recuperar los salarios pagados en concepto de d�as ca�dos, acceden a un amplio servicio de asesoramiento y capacitaci�n en materia de seguridad, higiene y medicina laboral, y est�n protegidas de ulteriores consecuencias legales o econ�micas. Cont�ctese con el Centro de Atenci�n al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas) para saber m�s acerca de nuestros <a class="linkSubrayado" href="/servicio">servicios</a>.</p>
					<p><b>1.6. �Qu� es un certificado de cobertura y c�mo lo obtengo?</b></p>
					<p>El certificado de cobertura es un documento que acredita que la empresa que lo presenta cuenta con la cobertura de una ART. Puede ser un certificado simple, con n�mina (total o parcial) o con cl�usula de no repetici�n. �ste �ltimo lo utilizan aquellas empresas que prestan servicios dentro de otras empresas a pedido de sus clientes (ejemplo: servicios de log�stica, servicios de seguridad, contratistas de la construcci�n, etc). A trav�s de la Central de Servicios en L�nea, las empresas afiliadas pueden emitir directamente los certificados. Tambi�n pueden solicitarlos v�a e-mail (<a class="linkSubrayado" href="mailto:certificados@provart.com.ar">certificados@provart.com.ar</a>) o telef�nicamente al Centro de Atenci�n al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas).</p>
					<p><b>1.7. �C�mo se puede obtener credenciales que acrediten la cobertura para mi personal?</b></p>
					<p>Es importante que todo el personal cubierto tenga credencial. Si ya se le agotaron las que le entregamos al momento de afiliarse, puede solicitar una reposici�n de credenciales a trav�s del Centro de Atenci�n al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas), por e-mail a <a class="linkSubrayado" href="mailto:info@provart.com.ar">info@provart.com.ar</a> o obtenerlas haciendo <a class="linkSubrayado" href="/descarga-formularios">clic aqu�</a>.</p>
				</td>
			</tr>
		</table>
		</div>

		<div id='capa2'>
		<table>
			<tr>
				<td>
					<p><b>2.1. �C�mo se puede obtener una cotizaci�n?</b></p> 
					<p>Para solicitar una cotizaci�n, puede contactarse con nuestro Centro de Atenci�n al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas), escribirnos a <a class="linkSubrayado" href="mailto:info@provart.com.ar">info@provart.com.ar</a>, acercarse a cualquiera de <a class="linkSubrayado" href="/sucursales">nuestras oficinas</a> o a la sucursal m�s cercana del Banco Naci�n, Banco Provincia o Banco Ciudad. Tambi�n puede averiguar acerca de nuestros servicios y afiliarse a trav�s de su productor o broker de seguros.</p>
					<p><b>2.2. �Qu� datos son necesarios para la cotizaci�n?</b></p>
					<p>Al momento de solicitar una cotizaci�n es conveniente contar con los siguientes datos:</p>
					<p>- Raz�n social.</p>
					<p>- C.U.I.T.</p>
					<p>- C�digo de actividad seg�n formulario A.F.I.P. N�150 (C.I.I.U. y Descripci�n de actividad).</p>
					<p>- Cantidad de trabajadores.</p>
					<p>- Masa salarial.</p>
					<p><b>2.3. �Qu� documentaci�n se debe presentar al momento de la afiliaci�n / traspaso?</b></p>
					<p>Al concurrir a afiliarse a cualquiera de las <a class="linkSubrayado" href="/sucursales">oficinas de Provincia ART</a>, las sucursales del Banco Provincia, Banco Naci�n o Banco Ciudad, o a su productor o broker de seguros se deber� completar la siguiente documentaci�n:</p>
					<p>- Formulario de Solicitud de Afiliaci�n firmada por una persona responsable (titular o apoderado).</p>
					<p>- Formulario de Ubicaci�n de Riesgo.</p>
					<p>- Formulario de Relevamiento General de Riesgos Laborales (uno por cada establecimiento).</p>
					<p>- Poder del firmante: original y fotocopia.</p>
					<p>- DNI del firmante: original y fotocopia de 1� y 2� hoja.</p>
					<p>- Fotocopia de Estatuto o Contrato constitutivo para empresas con personer�a jur�dica.</p>
					<p>- Fotocopia del formulario 460 de constancia de inscripci�n en la A.F.I.P.</p>
					<p>- Fotocopia de las constancias de alta de los trabajadores en �Mi Simplificaci�n� (AFIP). Si la empresa tiene m�s de 35 empleados puede presentar la n�mina en Excel envi�ndola a <a class="linkSubrayado" href="mailto:nominas@provart.com.ar">nominas@provart.com.ar</a> y adjuntar el listado que surge de la p�gina web de A.F.I.P. con los movimientos realizados por Mi Simplificaci�n.</p>
					<p><b>2.4. Una vez producida la afiliaci�n, �cu�ndo comienza la vigencia de la cobertura?</b>
					<p>En el caso de las empresas que se afilian por primera vez a una ART, la cobertura entra en vigencia desde la 0 hora del d�a siguiente a la suscripci�n de la solicitud de afiliaci�n. </p>
					<p>En caso de un traspaso, �stos se solicitan a la aseguradora actual del 1 al 10 de cada mes. Si la empresa registra deuda con la ART anterior o no present� el Relevamiento General de Riesgos Laborales �en caso de corresponder-, primero deber� regularizar su situaci�n. La cobertura entrar� en vigencia a partir del 1� d�a del mes siguiente al del mes en el que se aprob� el traspaso. Mientras transcurre la aprobaci�n, la ART anterior sigue cubriendo a la empresa.</p>
					<p><b>2.5. �Qu� sucede cuando el traspaso es rechazado?</b></p>
					<p>Para que una empresa pueda traspasarse de ART deben haber transcurrido 6 (empresa nueva) o 12 meses (empresa ya inscripta en el sistema) desde la firma del contrato con una ART. La ART actual puede negar el traspaso en caso de que la empresa registre deuda o no haya presentado el Formulario de Relevamiento General de Riesgos Laborales. La SRT informa la negativa de traspaso a la ART solicitante para que �sta d� aviso a la empresa.</p>				
				</td>
			</tr>
		</table>
		</div>

		<div id='capa3'>	
			<table>
				<tr>
					<td>
						<p><b>3.1. �C�mo deben efectuarse los movimientos en la n�mina de personal?</b></p>
						<p>Todas las Altas y Bajas de personal, as� como las modificaciones de los datos informados con error u omisi�n, los v�nculos familiares de cada uno y los domicilios de desempe�o entre otros datos, deben ser incorporadas mediante Internet a trav�s del aplicativo �Mi Simplificaci�n�, al cual se accede con clave fiscal.</p>
						<p>Mediante Mi Simplificaci�n -On Line- el empleador podr�:</p>
						<p style="margin-left: 10px">- Informar las altas de las nuevas relaciones laborales.<br>
						- Confirmar las altas anticipadas por tel�fono (Anexo IV)<br>
						- Modificar los datos ingresados oportunamente.<br>
						- Informar las bajas de las relaciones laborales.<br>
						- Anular las altas y bajas anticipadas comunicadas.<br>
						- Consultar todas las relaciones laborales activas existentes, todas las gestiones de claves realizadas en un d�a, etc.</p>
						<p>Adem�s, este registro exhibe en forma autom�tica:</p>
						<p style="margin-left: 10px">- La ART contratada con indicaci�n de la fecha de vigencia<br>
						- Apellido y Nombre del trabajador<br>
						- Obra Social elegida por el trabajador si existiera opci�n<br>
						- R�gimen previsional del trabajador<br>
						<p>Para cargas masivas de datos, el empleador podr� acceder el programa �Mi Simplificaci�n�, integrado dentro del  Sistema Integrado de Aplicaciones (S.I.Ap.) para luego transmitir la declaraci�n jurada a trav�s del servicio �Presentaci�n de DDJJ y Pagos�, al cual tambi�n se accede con clave fiscal.</p>
						<p>Normativa Aplicable: RG 2016.</p>
						<p>Si desea mayor informaci�n, usted puede ingresar en <a class="linkSubrayado" target="_blank" href="http://www.afip.gov.ar/genericos/miSimplificacion/">www.afip.gov.ar/genericos/miSimplificacion</a>, comunicarse con el Centro de Informaci�n Telef�nica al 0810-999-2347 de 8 a 20 horas, o enviar un <a class="linkSubrayado" href="mailto:mayuda@afip.gov.ar">correo electr�nico a la Mesa de Ayuda de AFIP</a>.</p>
						<p><b>3.2. �C�mo es posible registrar la baja de trabajadores?</b></p>
						<p>Es posible efectuar la baja de los trabajadores dentro del registro administrado por AFIP, utilizando alguna de las siguientes v�as:</p>
						<p style="margin-left: 10px">- Ingresar con clave fiscal, a trav�s del servicio "Mi Simplificaci�n".<br>
						- Presentar el formulario 885, por duplicado, en la dependencia donde se encuentra inscripto.<br>
						- Off Line, mediante la generaci�n del archivo F. 935 a trav�s del aplicativo Mi Simplificaci�n ejecutado dentro del S.I.Ap.</p>
						<p>Es importante tener en cuenta lo que establece la RG 1891 en su art�culo 4�: �La comunicaci�n de la baja en el "Registro" deber� realizarse dentro del plazo de CINCO (5) d�as corridos, contados a partir de la fecha, inclusive, en que se produjo la extinci�n del contrato de trabajo, por cualquier causa. Si la baja se produce por renuncia, y se recibe el telegrama luego de transcurridos 5 d�as corridos, tenga en cuenta lo establecido en el Evento 1218.�</p>
						<p><b>3.3. �Qu� sucede si el empleador no tiene acceso a Internet?</b></p>
						<p>En caso de no contar con acceso a Internet, el empleador podr� concurrir a la Agencia AFIP en la que se encuentre inscripto, y realizar las altas, bajas, modificaciones y anulaciones por medio del F.885 nuevo modelo, el ingreso de las novedades necesarias, de los datos del empleador y las relaciones laborales.</p>
						<p>Cuando se trate de relaciones de familiares debe concurrir a la UDAI de ANSES m�s cercana a su domicilio para informar las novedades, siempre que el empleador tenga en su poder la informaci�n respaldatoria de los v�nculos de familia a informar.</p>
						<p>Para garantizar la <b>adecuada cobertura</b> de su n�mina y mantener ordenado el estado de cuentas, <b>la n�mina de trabajadores</b> declarados por el empleador, <b>debe ser reflejada en su totalidad en la Declaraci�n Mensual de Personal</b> presentada a trav�s de la AFIP.</p>
					</td>
				</tr>
			</table>
		</div>
	
		<div id='capa4'>	
		<table>
			<tr>
				<td>
					<p><b>4.1. �C�mo calcula la ART el valor de la tarifa?</b></p>
					<p>La tarifa var�a de acuerdo con distintos factores: el rubro de actividad de la empresa, la historia siniestral (accidentes ocurridos entre 1996 y la fecha), la masa salarial de los empleados, entre otros.</p>
					<p>La tarifa mensual se compone de una parte fija y otra variable (acompa�a las variaciones de la masa salarial) m�s $0,60 por trabajador destinados al Fondo Especial de Enfermedades Profesionales.</p>
					<p><b>4.2. �D�nde puedo consultar mi tarifa?</b></p>
					<p>Si su empresa ya est� afiliada con Provincia ART, puede consultar la al�cuota vigente a trav�s de la Central de Servicios en L�nea o comunic�ndose con el Centro de Atenci�n al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas).</p>
					<p><b>4.3. �C�mo se paga el seguro de riesgos del trabajo?</b></p>
					<p>Las empresas privadas y los organismos p�blicos adheridos al Sistema �nico de la Seguridad Social (SUSS) abonan la tarifa mensual junto con las cargas sociales a trav�s del Formulario 931 de AFIP. El servicio se paga a mes adelantado en funci�n de la n�mina salarial del mes anterior (Art. 23 Ley de Riesgos de Trabajo 24.557 y art. 9 del Decreto 334/96).</p>
					<p>En los casos de inicio de actividad, o cuando por otras razones no exista n�mina salarial en el mes anterior al pago de la cuota, la cuota de afiliaci�n se calcular� en funci�n de la n�mina salarial prevista para el mes en curso. En el supuesto previsto para el inicio de actividad, la cuota ser� ingresada en forma directa a la Aseguradora correspondiente.</p>
					<p><b>4.4. �D�nde puedo verificar el estado de mi cuenta?</b></p>
					<p>Para consultar el estado de su cuenta, puede hacerlo a trav�s de la web ingresando en la Central de Servicios en L�nea, comunic�ndose con nuestro Centro de Atenci�n al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas) o escribiendo a <a class="linkSubrayado" href="mailto:cobranzas@provart.com.ar">cobranzas@provart.com.ar</a>.</p>
					<p><b>4.5. Si figura que mi empresa tiene deuda, �c�mo lo soluciono?</b></p>
					<p>En principio, al pagar sobre la n�mina declarada no deber�a generarse deuda. Sin embargo, puede haber errores de c�lculo, problemas con la informaci�n o atrasos en los pagos que den lugar a desajustes al abonar la tarifa. En caso de registrar deuda, �sta puede pagarse utilizando los formularios de AFIP 817 (para deuda nominal) y 801/C (para intereses), recordando utilizar un formulario por cada per�odo en el que se registre la mora.</p>
					<p>Recuerde que, de acuerdo con la Ley 24.557, la existencia de deuda puede bloquear un traspaso o, en caso de sumar el equivalente a 2 per�odos de cobertura, puede implicar la rescisi�n del contrato con la ART.</p>
				</td>
			</tr>
		</table>					
		</div>
	
		<div id='capa5'>
		<table>
			<tr>
				<td>
					<p><b>5.1. Recomendaciones para confeccionar las DDJJ de personal</b></p>
					<p>- Si el empleador se encuentra obligado a utilizar� el servicio de AFIP �Su Declaraci�n�, al cual se accede con clave fiscal y que permite obtener la declaraci�n jurada determinativa de aportes y contribuciones con destino a los distintos subsistemas de la seguridad social, confeccionada sobre la base de los datos del per�odo inmediato anterior a aquel que se declara, si existiera, m�s las novedades registradas en el sistema "Mi Simplificaci�n".</p>
					<p>M�s informaci�n: <a class="linkSubrayado" href="http://www.afip.gob.ar/sudeclaracion">www.afip.gob.ar/sudeclaracion</a></p>
					<p>- De no encontrarse contenido en la opci�n �Su declaraci�n�, al momento de crear una nueva declaraci�n jurada con el <b>Sistema de C�lculo de Obligaciones de la Seguridad Social</b> (aplicativo SICOSS), el empleador debe tener en cuenta colocar los datos de la tarifa vigente con la aseguradora en el apartado �Ley de riegos del Trabajo� <a class="linkSubrayado" href="#" onClick="javascript:window.open('/modules/servicio/img1.php',null,'height=429,width=575,status=no,toolbar=no ,menubar=no,location=no,top=5, left=20');">[ver imagen]</a>. Para poder ingresar las al�cuotas, es necesario tildar previamente �Corresponde LRT�, paso que el empleador deber� efectuar cada vez confeccione una Declaraci�n Jurada, para poder calcular correctamente la cuota de LRT.</p>
					<p>- Cuando el empleador ingrese la informaci�n por trabajador, deber� reflejar correctamente los datos del cuadro �Datos Referenciales del Empleado� <a class="linkSubrayado" href="#" onClick="javascript:window.open('/modules/servicio/img2.php',null,'height=425,width=578,status=no,toolbar=no ,menubar=no,location=no,top=5, left=20');">[ver imagen]</a>. La <b>modalidad de contrataci�n</b> informada debe ser igual que la indicada en el momento de efectuar el alta temprana.</p>
					<p>- Al ingresar los �Datos Complementarios� <a class="linkSubrayado" href="#" onClick="javascript:window.open('/modules/servicio/img3.php',null,'height=332,width=567,status=no,toolbar=no ,menubar=no,location=no,top=5, left=20');">[ver imagen]</a>, se deber� poner especial cuidado en <b>reflejar la correcta cantidad de d�as trabajados</b>. La cantidad de d�as trabajados debe ser la cantidad real, guardando relaci�n con la remuneraci�n informada. Por ejemplo si un trabajador percibi� $600 por 2 d�as trabajados, debe desprenderse esta informaci�n de la Declaraci�n Jurada presentada ante la AFIP.</p>
					<p>- Cuando se ingresan las remuneraciones, es recomendable que el empleador <b>revise el campo �Remuneraci�n 9�</b> <a class="linkSubrayado" href="#" onClick="javascript:window.open('/modules/servicio/img4.php',null,'height=253,width=582,status=no,toolbar=no ,menubar=no,location=no,top=5, left=20');">[ver imagen]</a> 
					ya que all� deben reflejarse la sumatoria de:</p>
					<p>a) los montos de conceptos remunerativos brutos, 
					<a href="http://www.provinciart.com.ar/novedades/23">aplicando el correspondiente tope vigente</a>, 
					o</p>
					<p class="MsoNormal">b) de conceptos remunerativos brutos y no remunerativos en ambos casos percibidos por el trabajador en el mes.<o:p></o:p></p>
					<p class="MsoNormal">El manual que indica como confeccionar el F931 bajo la nueva ley, es decir el link: M�s informaci�n: <a target="_blank" href="http://www.provinciart.com.ar/descargas/PART_DDJJ_Base_imponible.pdf">www.provinciart.com.ar/descargas/PART_DDJJ_Base_imponible.pdf</a></p>
					<p>Para conocer cu�l de las opciones debe colocar en funci�n de su contrato, la comunicaci�n A10 � Ley 26773 remitida a trav�s de la ventanilla electr�nica de la Superintendencia de Riesgos del Trabajo.</p>					
					<p>- Una vez completados los datos de todos los trabajadores, y al momento de imprimir el <b>Formulario 931</b>, es importante <b>corroborar que los datos del apartado VI</b> � Ley de Riesgos del Trabajo <a class="linkSubrayado" href="#" onClick="javascript:window.open('/modules/servicio/img5.php',null,'height=521,width=468,status=no,toolbar=no ,menubar=no,location=no,top=5, left=20');">[ver imagen]</a> se encuentren completos. Por otra parte se recomienda al empleador conservar una copia del formulario, junto al Acuse de Recibo de DJ emitido por la AFIP al momento de efectuar la presentaci�n de la DDJJ a trav�s de Internet.</p>
					<p>- Deber� seguir los mismos pasos al momento de confeccionar un archivo TXT para importar en el aplicativo.</p>
					<p><i><b>- De este modo el Formulario 931 le proveer� el monto correcto a abonar en concepto de ART, lo que le permitir� garantizar la adecuada cobertura de su n�mina y mantener ordenado el estado de cuentas.</b></i></p>
					<p><b>Organismos P�blicos no adheridos al SUSS:</b></p>
					<p>- Los Organismos P�blicos podr�n presentar sus DDJJ a trav�s de esta Web, desde el <a class="linkSubrayado" target="_blank" href="/acceso-exclusivo-usuarios-registrados">Acceso Exclusivo</a> por medio de una clave y contrase�a. Con esta herramienta los OOPP pueden remitir el detalle de sus trabajadores y confeccionar autom�ticamente el Resumen No Suss (RNS).  A trav�s de tres sencillos pasos,  es posible controlar la correcta presentaci�n de la informaci�n de los trabajadores y posteriormente enviar el RNS con los datos coincidentes al detalle. </p>
					<p>Aqu� se encuentra disponible el  <a class="linkSubrayado" target="_blank" href="http://www.provinciart.com.ar/download/P_ART_Instructivo_extranet.pdf">Manual de uso</a> con las instrucciones para la utilizaci�n de este sistema</p>										
					<p><b>5.2. �Por qu� se factura un trabajador con C�digo de Situaci�n: �Licencia�?</b></p>
					<p>En la Declaraci�n Jurada se incluyen a todos los empleados en relaci�n de dependencia, mas all� de que se le efect�e o no una liquidaci�n.</p> 
					<p>Lo que preve� el aplicativo de la AFIP (Sicoss) es la especificaci�n de la condici�n del trabajador por medio del C�digo de situaci�n, para que sea consistente el hecho de que tenga o no salario declarado. Por ello es que existen diferentes n�meros de C�digos entre los cuales adem�s de 01-Activo, se encuentran las licencias.</p>
					<p>Una de las razones por las cuales existe esta forma de declarar, adem�s de la relaci�n de dependencia con la empresa, es que el trabajador en caso de tener un siniestro en el per�odo de licencia (por ejemplo si va a la empresa hacer algun tramite laboral, un initinere) el accidente tiene cobertura de Ley de Riesgo de Trabajo (LRT). A su vez, como est� declarado ante Mi Simplificaci�n debe ser inclu�do en la Declaraci�n Jurada.</p>
					<p>El aplicativo de la AFIP calcula el Apartado de LRT en base a las al�cuotas que posea la empresa. En caso de tener s�lo de suma fija los $0.60, al no haber masa salarial, solo calcular� esos $0.60 (Si tiene una fija distinta, calcula la fija).</p>
					<p><b>5.3. �C�mo se ingresan los Socios, Gerentes, Directores de S.A., etc.?</b></p>
					<p>Se deber�n tener en cuenta las siguientes situaciones:</p>
					<p>A) Por ser Directores de S.A. est�n obligados a ser AUT�NOMOS. Cuando cumplen con tareas administrativas, (como por ejemplo, adem�s son los contadores internos de las organizaciones), y s�lo se informa la ART en el aplicativo (porque no est�n adheridos al R�gimen de Jubilaciones y Pensiones) puesto que ejercen la opci�n de no ingresar aportes y contribuciones por el sueldo que reciben (situaci�n comprendida en la Ley 24.241), deben ser ingresados en el aplicativo con el c�digo de Actividad 15, de Modalidad 99 y Obra Social Ninguna.</p>
					<p>En el caso de querer tener una cobertura de salud, deben realizarla directamente en la entidad y no a trav�s del aplicativo. </p>
					<p>Se debe tener en cuenta que desde la obligatoriedad de utilizar el aplicativo SICOSS versi�n 32, la indicaci�n del c�digo de Actividad 15 especificada en el p�rrafo anterior, no comprende a los directores de sociedades que tienen por objeto desarrollar las actividades alcanzadas por la Ley 25.922 ( Ley de Promoci�n de la Industria del Software). A partir de las DDJJ presentadas con el aplicativo mencionado, debe registrar a estos directores con el c�digo de Actividad 40, de Modalidad 99 y Obra Social Ninguna.</p>
					<p>B) Por ser Directores de S.A. est�n obligados a ser AUT�NOMOS. Cuando cumplen con tareas administrativas, (como por ejemplo, adem�s son los contadores internos de las organizaciones), y se informan en el aplicativo (porque est�n adheridos al R�gimen de Jubilaciones y Pensiones), puesto que no ejercen la opci�n e ingresan aportes y contribuciones, se debe cargar en el aplicativo con el c�digo de Actividad 49 y de Modalidad 08, como un trabajador m�s.</p>
					<p>Ambas situaciones descriptas anteriormente se consideran en Relaci�n de dependencia, por lo que debe informarse en el aplicativo y en el servicio Mi Simplificaci�n.</p>
					<p><i>Aclaraci�n: Existe otra posibilidad (diferentes a las indicadas en el inciso A) y B)), ser Director de S.A., obligados a ser AUT�NOMOS, que no cumplen tareas administrativas, por lo tanto, no est�n en relaci�n de dependencia.</i></p>
					<p><i>Estos casos no se cargan en el aplicativo, ni se informan en el servicio Mi simplificaci�n.</i></p>
					<p>Fuente: CIT AFIP <a class="linkSubrayado" target="_blank" href="http://www.afip.gob.ar/genericos/guiavirtual/consultas_detalle.aspx?id=1453600">www.afip.gob.ar/genericos/guiavirtual/consultas_detalle.aspx?id=1453600</a></p>
				</td>
			</tr>
		</table>
		</div>
	
		<div id='capa6'>
		<table>
			<tr>
				<td>
					<p><b>6.1. �En qu� consiste el servicio de prevenci�n de la ART?</b></p>
					<p>El servicio de prevenci�n de Provincia ART comprende:</p>
					<p>- Visitas de fiscalizaci�n para relevar condiciones, verificar el cumplimiento de la normativa y realizar recomendaciones.</p>
					<p>- Asesoramiento para la mejora de las condiciones de trabajo, adaptado al riesgo, la actividad y las posibilidades de la empresa.</p>
					<p>- Desarrollo de planes y programas de prevenci�n.</p>
					<p>- Capacitaci�n a los trabajadores en el puesto de trabajo, uso correcto de elementos de protecci�n personal, actuaci�n en caso de accidente, etc.</p>
					<p>- Organizaci�n de simulacros de evacuaci�n y rol de incendio.</p>
					<p>- Realizaci�n de relevamientos de expuestos y ex�menes peri�dicos.</p>
					<p>- Asesoramiento en higiene industrial y mediciones ambientales.</p>
					<p>- Asesoramiento en ergonom�a y evaluaci�n de puestos de trabajo.</p>
					<p>Con la asistencia de nuestro equipo de especialistas en higiene, seguridad, medicina laboral y ergonom�a, las empresas pueden cumplir con la normativa y garantizar un ambiente laboral sano y seguro que promueva la eficacia y la eficiencia en sus resultados. Hacer prevenci�n no es un gasto, sino una inversi�n al alcance de todas las empresas.</p>
					<p><b>6.2. �Todas las empresas est�n obligadas a contar con un profesional propio de higiene y seguridad y/o con un m�dico laboral?</b></p>
					<p>De acuerdo con el Decreto 1338/96, las empresas que est�n excluidas de poseer servicio propio de higiene y seguridad son:</p>
					<p>- Los establecimientos dedicados exclusivamente a tareas administrativas de hasta DOSCIENTOS (200) trabajadores.</p>
					<p>- Los establecimientos donde se desarrollen tareas comerciales o de servicios de hasta CIEN (100) trabajadores, siempre que no se manipulen, almacenen o fraccionen productos t�xicos, inflamables, radioactivos o peligrosos para el trabajador.</p>
					<p>- Los servicios m�dicos sin internaci�n.</p>
					<p>- Los establecimientos educativos que no tengan talleres.</p>
					<p>- Los talleres de reparaci�n de automotores que empleen hasta CINCO (5) trabajadores equivalentes.</p>
					<p>- Los lugares de esparcimiento p�blico que no cuenten con �reas destinadas al mantenimiento, de menos de TRES (3) trabajadores.</p>
					<p>- Los establecimientos dedicados a la agricultura, caza, silvicultura y pesca, que tengan hasta QUINCE (15) trabajadores permanentes.</p>
					<p>- Las explotaciones agr�colas por temporada.</p>
					<p><b>6.3. �C�mo se puede solicitar la visita de un preventor?</b></p>
					<p>Para solicitar una visita, puede comunicarse con el Centro de Atenci�n al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas) o escribir un e-mail a <a class="linkSubrayado" href="mailto:prevencion@provart.com.ar">prevencion@provart.com.ar</a>.</p>
					<p><b>6.4. �Qu� es el Formulario de Relevamiento General de Riesgos Laborales?</b></p>
					<p>Es un formulario creado por la Resoluci�n SRT 463/09 por medio del cual el empleador declara, con car�cter de declaraci�n jurada, su nivel de cumplimiento de obligaciones de higiene y seguridad para cada uno de sus establecimientos. Su presentaci�n es obligatoria al momento de la afiliaci�n a una ART (por primera vez o por traspaso) y luego una vez al a�o antes de que opere la renovaci�n autom�tica del contrato. Cada formulario debe estar firmado por quien confecciona el formulario y por su Servicio de higiene y seguridad. En caso de no presentarlo en tiempo y forma, la Res. SRT 529/09 establece penalidades para la empresa. La presentaci�n de los formularios se realiza en cualquiera de las <a class="linkSubrayado" href="/sucursales">oficinas de Provincia ART</a> o por correo a Provincia ART - Casa Central - Gerencia de Prevenci�n - Carlos Pellegrini 91 2� (C1009ABA) - Ciudad Aut�noma de Buenos Aires.</p>
					<p>Descargar instructivo para completar el RGRL (PDF)</p>
				</td>
			</tr>
		</table>	
		</div>

		<div id='capa7'>
		<table>
			<tr>
				<td>
					<p><b>7.1. �Qu� hay que hacer en caso de accidente?</b></p>
					<p>El empleador debe completar el <a class="linkSubrayado" href="/descarga-formularios">Formulario de Solicitud de Asistencia M�dica</a> y entreg�rselo al trabajador accidentado para que pueda concurrir al prestador y recibir atenci�n m�dica. Si el accidente es grave, alguna persona de la empresa deber�a acompa�ar al accidentado al m�dico.</p>
					<p>En caso de urgencia, comun�quense inmediatamente con el C.E.M. (0800-333-1333 las 24 horas, los 365 d�as del a�o) para el env�o de una ambulancia.</p>
					<p>Adem�s, en todos los casos debe enviar a Provincia ART el <a class="linkSubrayado" href="/descarga-formularios">Formulario de Denuncia de Accidente</a> firmado en original. Puede adelantarlo v�a fax (011-4819-2888) o cargarlo directamente en la Web a trav�s de la Central de Servicios en L�nea.</p>
					<p>El trabajador debe portar siempre la credencial con los n�meros de tel�fono de emergencia de Provincia ART. Si el accidente sucede dentro de la empresa, debe pedirle a su empleador la <a class="linkSubrayado" href="/descarga-formularios">Solicitud de Asistencia M�dica</a> para concurrir al sanatorio a atenderse. En caso de que se trate de un accidente in it�nere, necesitar� adem�s una denuncia policial o exposici�n civil (en la provincia de Buenos Aires se realizan en los municipios, no en las comisar�as).</p>
					<p>Ante cualquier accidente grave, en el lugar de trabajo o in it�nere, comun�quese inmediatamente con el C.E.M. (0800-333-1333 las 24 horas, los 365 d�as del a�o).</p>
					<p>Descargar el instructivo �<a class="linkSubrayado" target="_blank" href="<?= getFile(STORAGE_EXTRANET."descargables_web/Provincia_ART_instructivo_accidentes.pdf")?>">Qu� hacer en caso de accidente</a>�</p>
					<p><b>7.2. �C�mo se completa el Formulario de Denuncia de Accidente?</b></p>
					<p>El empleador puede completar este formulario en forma manual o a trav�s de la Central de Servicios en L�nea pero, en cualquier caso, deber� enviarlo firmado en original a Provincia ART dentro de las 48 horas de sucedido el accidente. El formulario es importante porque permite registrar el siniestro para darle tratamiento, entender c�mo sucedi� el accidente para planificar la prevenci�n, obtener datos para contactar a la empresa o al trabajador accidentado, derivar al trabajador a un prestador id�neo, etc. Es fundamental completar todos los campos referidos a datos de la empresa, del establecimiento en el que sucedi� el accidente, del trabajador accidentado y del lugar donde �ste estuviera recibiendo atenci�n m�dica (prestador).</p>
					<p><b>7.3. �D�nde puedo consultar la cartilla de prestadores?</b></p>
					<p>Provincia ART cuenta con m�s de 3.000 prestadores de distintas especialidades. Los trabajadores que se accidentes pueden concurrir libremente a algunos de ellos, mientras que para otros se necesita derivaci�n de un especialista (como en cualquier servicio m�dico). Para conocer los prestadores cercanos a su empresa a los que puede dirigirse sin derivaci�n, consulte la cartilla disponible en la Central de Servicios en L�nea, comun�quese con el Centro de Atenci�n al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas) o escriba a <a class="linkSubrayado" href="mailto:info@provart.com.ar">info@provart.com.ar</a>.</p>
					<p><b>7.4. �Cu�les son los servicios a los que puede acceder un trabajador accidentado?</b></p>
					<p>Desde el momento del accidente, Provincia ART pone a disposici�n del trabajador los siguientes servicios y prestaciones:</p>
					<p>- Atenci�n m�dica integral: servicio de ambulancia, atenci�n primaria, atenci�n de alta complejidad, estudios, diagn�stico por im�genes, intervenciones quir�rgicas, internaci�n, medicamentos, materiales de osteos�ntesis y ortopedia, oftalmolog�a, odontolog�a, atenci�n psiqui�trica.</p>
					<p>- Atenci�n en el exterior, traslados a�reos y terrestres, y hospedaje.</p>
					<p>- Rehabilitaci�n y recalificaci�n profesional.</p>
					<p>- Contenci�n social al accidentado y la familia.</p>
					<p>- Prestaciones dinerarias: indemnizaciones al trabajador accidentado o a su familia.</p>
					<p>La atenci�n se brinda hasta la curaci�n completa o mientras subsista la incapacidad.</p>
					<p><b>7.5. �C�mo se gestionan los turnos y traslados?</b></p>
					<p>Las gestiones relativas a turnos y traslados hacia y desde los prestadores m�dicos se realizan a trav�s del Centro de Atenci�n al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas).</p>
					<p><b>7.6. �Cu�ndo se reintegra un empleado a sus labores despu�s de haber sufrido un accidente laboral?</b></p>
					<p>El empleado puede volver a trabajar cuando cuenta con el alta laboral. A trav�s de la Central de Servicios en L�nea o comunic�ndose con el Centro de Atenci�n al Cliente (0800-333-1278 de lunes a viernes de 9 a 18 horas), las empresas afiliadas pueden obtener informaci�n sobre el estado de sus trabajadores accidentados de acuerdo con las normas que regulan la confidencialidad para temas m�dicos.</p>
					<p><b>7.7. �Qu� son las prestaciones dinerarias?</b></p>
					<p>Existen b�sicamente 3 tipos de prestaciones dinerarias: la ILT (Incapacidad Laboral Temporaria) que se paga a las empresas en calidad de reintegro, las Incapacidades Laborales Permanentes (provisoria, definitiva, parcial o total) y la Indemnizaci�n por fallecimiento. Las dos �ltimas se abonan a los trabajadores accidentados o a sus derecho habientes.</p>
					<p><b>7.8. �Qu� documentaci�n debe presentar la empresa para obtener el reintegro de ILT?</b></p>
					<p>La ILT es el pago que la ART le hace a las empresas reintegr�ndoles los sueldos de sus trabajadores accidentados durante los d�as que �stos est�n de baja (excepto los 10 primeros d�as de franquicia). La empresa debe pagarles el sueldo por cuenta y orden de Provincia ART �liquidando los d�as de baja con un concepto especial que se llama �Prestaci�n dineraria a cargo de la empresa� y �Prestaci�n dineraria a cargo de la ART�- y a mes vencido la ART les reintegra el importe correspondiente. Para ello, las empresas deben presentar: el Formulario de Liquidaci�n de Prestaciones Dinerarias (firmado en original y sellado por el responsable de la empresa), fotocopia de los comprobantes de pago de los aportes y contribuciones a la Seguridad Social de los meses a reintegrar, informar los porcentajes de las contribuciones patronales de los meses a reintegrar (detallando conceptos y porcentajes netos de reducci�n), fotocopia de los recibos de sueldo correspondientes a los meses a reintegrar (firmados y sellados en original por el responsable de la empresa con la leyenda �es copia fiel�).</p>
					<p><b>7.9. �Qu� documentaci�n deben presentar los derecho-habientes para percibir la prestaci�n dineraria por fallecimiento?</b></p>
					<p>La documentaci�n var�a de acuerdo con qui�nes son los derecho-habientes. Se considera derecho-habientes a:</p>
					<p>- C�nyuge, conviviente, hijos (menores de 21 o menores de 25 si son estudiantes a cargo exclusivo del fallecido)</p>
					<p>- Padres (s�lo si no existen los anteriores beneficiarios)</p>
					<p>- Otros beneficiarios a cargo del fallecido (parientes consangu�neos en ausencia de los dos grupos de beneficiarios anteriores).</p>
				</td>
			</tr>
		</table>
		</div>

		<div id='capa8'>
		<table>
			<tr>
				<td>
					<p><b>8.1. �C�mo se canalizan las consultas, reclamos y quejas?</b></p>
					<p>Para solicitar informaci�n, realizar consultas, presentar quejas y reclamos, puede comunicarse con el Centro de Atenci�n al Cliente (0800-333-1278 de Lunes a Viernes de 9 a 18 Hs.) o escribir un e-mail a info@provart.com.ar.</p>
					<p><b>8.2. �C�mo es posible contactarse con el organismo de control?</b></p>
					<p>La Superintendencia de Riesgos del Trabajo (SRT) recibe denuncias, quejas y reclamos personalmente y por correo en Bartolom� Mitre 751 3� Ciudad de Buenos Aires; por tel�fono al 0800-666-6778; por correo electr�nico a <a class="linkSubrayado" href="mailto:denuncias@srt.gov.ar">denuncias@srt.gov.ar</a> o a trav�s de su p�gina web <a class="linkSubrayado" target="_blank" href="http://www.srt.gov.ar/">www.srt.gov.ar</a></p>
					<p><b>8.3. �Qu� es la ventanilla electr�nica para empleadores?</b></p>
					<p>Se trata de un sistema electr�nico que permite a las empresas acceder a la informaci�n del Sistema de Riesgos del Trabajo y a las comunicaciones remitidas por la SRT y por la ART con la que tengan contrato. El acceso a la ventanilla se realiza desde la p�gina web <a class="linkSubrayado" target="_blank" href="http://www.srt.gov.ar/">www.srt.gov.ar</a></p>
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