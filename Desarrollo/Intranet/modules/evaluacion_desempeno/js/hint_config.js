<!-- at the beginning of the document's body configure and initialize the hint object -->

// configuration variable for the hint object, these setting will be shared among all hints created by this object
var HINTS_CFG = {
	'wise'       : true, // don't go off screen, don't overlap the object in the document
	'margin'     : 10, // minimum allowed distance between the hint and the window edge (negative values accepted)
	'gap'        : -7, // minimum allowed distance between the hint and the origin (negative values accepted)
	'align'      : 'brtl', // align of the hint and the origin (by first letters origin's top|middle|bottom left|center|right to hint's top|middle|bottom left|center|right)
	'css'        : 'wrapped', // a style class name for all hints, applied to DIV element (see style section in the header of the document)
	'show_delay' : 300, // a delay between initiating event (mouseover for example) and hint appearing
	'hide_delay' : 600, // a delay between closing event (mouseout for example) and hint disappearing
	'follow'     : false, // hint follows the mouse as it moves
	'z-index'    : 100, // a z-index for all hint layers
	'IEfix'      : false, // fix IE problem with windowed controls visible through hints (activate if select boxes are visible through the hints)
	'IEtrans'    : ['blendTrans(DURATION=.3)'], // [show transition, hide transition] - transition effects, only work in IE5+
	'opacity'    : 95 // opacity of the hint in %%
};

// text/HTML of the hints
var HINTS_ITEMS = [
/* COMPROMISO CON EL TRABAJO */
	wrap2('� Se identifica plenamente con la visi�n y los objetivos que plantea la organizaci�n, apoya y pone en marcha<br />las directivas de la empresa para alcanzar los objetivos e incluso superarlos.<br />' +
				'� Se preocupa por mantener una cooperaci�n activa con las otras �reas de la organizaci�n que requieren de<br />su conocimiento y experiencia para trabajar en pos de los objetivos comunes.<br />' +
				'� Siempre tiene presente los resultados que persigue la organizaci�n y el mismo, y es reconocido en la<br/>compa��a por alcanzar las metas que se propone.'),
	wrap2('� Tiene un alto nivel de compromiso con los objetivos que plantea la organizaci�n/ su sector; se ajusta con<br/>facilidad a las directivas de la empresa, las apoya y las pone en marcha.<br/>' +
				'� Coopera abiertamente con otras �reas/compa�eros que requieran de su conocimiento y/o experiencia.<br/>' +
				'� Cumple con sus metas individuales y con las que le propone la organizaci�n/sector.'),
	wrap2('� Se compromete con el cumplimiento de los objetivos del puesto que ocupa.<br/>' +
				'� Coopera con otras �reas/sectores cuando as� se lo solicitan.<br/>' +
				'� Cumple con las metas de la organizaci�n cuando entiende que tienen relaci�n con su puesto y sus tareas.'),
	wrap2('� Prioriza el cumplimiento de la tarea rutinaria por sobre los objetivos de su sector.<br/>' +
				'� Suele prometer cosas que despu�s no puede cumplir por falta de seguimiento o capacidad de gesti�n.<br/>' +
				'� Demuestra poco inter�s en cooperar con otras �reas /compa�eros que requieran de sus conocimientos y experiencia.<br/>' +
				'� Necesita seguimiento continuo para organizarse, planificar sus tareas y alcanzar los objetivos de su puesto.'),
	wrap2('� No demuestra inter�s por los objetivos de la organizaci�n / su sector / su puesto.<br/>' +
				'� Se limita a realizar lo que se le solicita, sin analizar la situaci�n o pensar en posible mejoras.<br/>' +
				'� No suele cooperar con otras �reas/compa�eros, incluso cuando se lo piden.<br/>' +
				'� Sistem�ticamente no logra alcanzar los objetivos establecidos para su puesto.'),
/* ADAPTABILIDAD AL CAMBIO Y FLEXIBILIDAD */
	wrap2('� Lidera el cambio antes que otros y se mueve con comodidad en todo tipo de ambiente y entre toda clase de gente.<br/>' +
				'� Est� atento a las necesidades cambiantes del contexto y analiza las situaciones y las caracter�sticas<br/>de las personas o grupos con el fin de adaptarse o adaptarlos de acuerdo a las circunstancias.<br/>' +
				'� Promueve el cambio entre sus pares y/o colaboradores, comprendiendo y valorando puntos de vista y<br/>criterios diversos, e integrando el nuevo conocimiento con facilidad.'),
	wrap2('� Visualiza r�pidamente la necesidad de un cambio y propone acciones concretas para enfrentar nuevas situaciones.<br/>' +
				'� Se integra r�pidamente a diversos equipos de trabajo y adapta sus comportamientos a las situaciones<br/>o personas para lograr los mejores resultados.<br/>' +
				'� Promueve cambios de procesos, servicios o relaciones, de acuerdo con las situaciones planteadas y<br/>aprende de sus errores, aplicando su capacidad para revisar cr�ticamente su accionar.'),
	wrap2('� Respeta y adhiere a las nuevas disposiciones y directivas de la organizaci�n.<br/>' +
				'� Implementa r�pidamente las propuestas que plantea la organizaci�n ante situaciones complejas.<br/>' +
				'� Muestra inter�s por modificar su accionar para mejorar la calidad de su trabajo y es eficiente en su<br/>integraci�n con interlocutores de diversos estilos.'),
	wrap2('� Tiene dificultad para comprender los cambios de contexto y suele resistirse a incorporar ideas o criterios<br/>distintos de los propios.<br/>' +
				'� Tiene escasa capacidad para tomar decisiones oportunas en situaciones variadas o cambiantes.<br/>' +
				'� Trabaja c�modo en ambientes conocidos, pero tiene dificultades para integrarse a otros �mbitos o equipos.'),
	wrap2('� Cuestiona las decisiones tomadas por la organizaci�n para hacer frente a crisis o situaciones delicadas, sin aportar otras ideas.<br/>' +
				'� Obstaculiza la implementaci�n de nuevos procesos y no le encuentra valor agregado a lo nuevo.<br/>' +
				'� No eval�a sus acciones pasadas; carece de actitud cr�tica.'),
/* COLABORACI�N Y TRABAJO EN EQUIPO */
	wrap2('� Alienta y fomenta el trabajo en equipo y el intercambio de informaci�n en toda la organizaci�n.<br/>' +
				'� Idea e implementa nuevas modalidades de trabajo en equipo a fin de a�adir valor a los resultados grupales.<br/>' +
				'� Siempre prioriza los objetivos organizacionales de largo plazo y es un referente en el manejo de equipos de trabajo.<br/>' +
				'� Su colaboraci�n es extensiva a actores externos a la organizaci�n (como clientes, proveedores, organismos<br/>de control, socios, etc) con los que debe interactuar.'),
	wrap2('� Act�a para generar un ambiente de cooperaci�n en su equipo y con las otras �reas.<br/>' +
				'� Mantiene una actitud abierta para aprender de los dem�s.<br/>' +
				'� Genera confianza en los dem�s por su actitud generosa a la hora de responsabilizarse con los objetivos comunes.<br/>' +
				'� Realiza acciones que contribuyen al cumplimiento de los objetivos de otras personas o �reas, sin<br/>descuidar los propios.'),
	wrap2('� Coopera con las actividades comunes manteniendo una adecuada comunicaci�n con quienes interact�a.<br/>' +
				'� Realiza en tiempo y forma la parte del trabajo que le corresponde respetando el espacio del resto del equipo.<br/>' +
				'� Comparte informaci�n y mantiene informados a los otros miembros del equipo de los temas que los afectan.'),
	wrap2('� Se dedica m�s al trabajo individual que al trabajo en contacto con otros.<br/>' +
				'� Muestra poca inclinaci�n a contribuir con los dem�s si eso no es parte de sus responsabilidades.<br/>' +
				'� Tiende a ser reservado con la informaci�n, dificultando el trabajo de otros.<br/>' +
				'� S�lo muestra inter�s por los propios resultados.'),
	wrap2('� Se reserva informaci�n para s� mismo y no proporciona informaci�n indispensable para trabajar.<br/>' +
				'� No muestra inter�s por colaborar con los dem�s, a�n si esto es necesario para la consecuci�n de los objetivos del sector.<br/>' +
				'� No atiende a los pedidos de los dem�s.'),
/* SERVICIO AL CLIENTE INTERNO Y EXTERNO */
	wrap2('� El cuidado del cliente siempre forma parte de su estrategia; es una convicci�n que promueve a trav�s<br/>de su constante ejemplo.<br/>' +
				'� Realiza, en forma proactiva, acciones orientadas a mejorar los �ndices de satisfacci�n del cliente y,<br/>frecuentemente, supera las expectativas. Indaga y se informa sobre necesidades actuales y potenciales de<br/>clientes internos, externos y proveedores.<br/>' +
				'� Sus acciones superan su propia responsabilidad, impulsando con su ejemplo a actuar en la misma<br/>direcci�n. Es un referente interno y externo cuando se busca aportar soluciones o satisfacer necesidades<br/>de clientes.'),
	wrap2('� Responde a las demandas de los clientes brind�ndoles satisfacci�n m�s all� de lo esperado.<br/>' +
				'� Se preocupa por la calidad de cada trabajo que emprende, dando respuesta inmediata a los<br/>problemas de sus clientes con soluciones adecuadas.<br/>' +
				'� Atiende a cada cliente con dedicaci�n y voluntad de satisfacer las demandas que se le planteen, y<br/>es paciente y tolerante con sus clientes internos y externos, aun en situaciones complejas.'),
	wrap2('� Responde adecuadamente frente a las demandas de los clientes, ofreciendo respuestas est�ndar a sus necesidades.<br/>' +
				'� Atiende cordialmente a todos los clientes y escucha atentamente sus necesidades.<br/>' +
				'� Est� disponible para consultas y reclamos, y se interesa por el grado de satisfacci�n de los mismos.'),
	wrap2('� No es flexible ante los distintos tipos de demandas de los clientes.<br/>' +
				'� Se desentiende de las necesidades del cliente interno o externo; no atiende quejas ni reclamos.<br/>' +
				'� Sus respuestas a las demandas de los clientes no son adecuadas en tiempo y forma.'),
	wrap2('� Su actitud frente a las demandas de los clientes no cumple con los niveles m�nimos de eficiencia y cordialidad.<br/>' +
				'� Siempre tiene una respuesta negativa para los requerimientos de los clientes y no soluciona los<br/>problemas que �stos presentan, brindando una mala imagen de la organizaci�n.<br/>' +
				'� Con su comportamiento demuestra poco inter�s por la satisfacci�n de los clientes.'),
/* HABILIDADES DE COMUNICACI�N */
	wrap2('� Comunica sus ideas a todos los niveles de la organizaci�n en forma clara, eficiente y fluida, logrando que<br/>entiendan sus mensajes (escritos y orales) y que comprendan el sentido que desea darles.<br/>' +
				'� Es un referente en lo que respecta a promover y coordinar reuniones o encuentros para llegar a acuerdos.<br/>En estos espacios ofrece lo que est� dispuesto a brindar y asume compromisos que est� dispuesto a cumplir.<br/>' +
				'� Alienta a otros a compartir informaci�n y valora las contribuciones de los dem�s.<br/>' +
				'� Establece una relaci�n emp�tica con las dem�s personas logrando influir sobre ellas para alcanzar los<br/>objetivos organizacionales y del sector.'),
	wrap2('� Utiliza con precisi�n y buenos resultados la comunicaci�n verbal y escrita, expresando sus ideas en forma<br/>clara y precisa, logrando que los dem�s entiendan su mensaje.<br/>' +
				'� Comparte con los dem�s la informaci�n que busca y obtiene.<br/>' +
				'� Ajusta su manera de expresarse teniendo en cuenta las particularidades de sus interlocutores y verifica<br/>que el mensaje que desea transmitir haya sido comprendido correctamente.<br/>' +
				'� Demuestra una actitud abierta a escuchar al otro para comprender sus necesidades en pos de generar<br/>acuerdos efectivos.'),
	wrap2('� Transmite adecuadamente sus ideas tanto de manera escrita como oral.<br/>' +
				'� Realiza preguntas y expresa con sus propias palabras lo que entiende sobre lo que los otros est�n expresando.<br/>' +
				'� Trata con respeto a sus pares y manifiesta su disposici�n para escuchar a los dem�s.'),
	wrap2('� Transmite con dificultad ideas y mensajes, expres�ndose con ambig�edad o vaguedad; sin adaptar su<br/>lenguaje a las caracter�sticas particulares de su interlocutor.<br/>' +
				'� Raramente comparte informaci�n que puede ser relevante para los dem�s.<br/>' +
				'� Muestra escaso inter�s por escuchar ideas o puntos de vista de los dem�s.'),
	wrap2('� No demuestra inter�s en transmitir sus ideas. Cuando lo hace, es confuso y contradictorio.<br/>' +
				'� Considera que s�lo sus opiniones son relevantes, por lo que se le dificulta mucho escuchar a los dem�s.<br/>' +
				'� Act�a de forma impulsiva y no demuestra ver valor en el di�logo como forma para lograr acuerdos.')
];

// this custom function receives what's unique about individual hint and wraps it in the HTML template
function wrap (s_text, s_icon) {
	return '<table><tr><td rowspan="2"><img src="' + s_icon + '"></td><td colspan="2"><img src="/js/hint/img/pixel.gif" width="1" height="15" border="0"></td></tr><tr><td background="/js/hint/img/2.gif" height="28" nowrap>' + s_text + '</td><td><img src="/js/hint/img/4.gif"></td></tr></table>';
}

// multiple templates/functions can be used in the same page
function wrap2 (s_text) {
	return [
		'<table border="0" cellpadding="0" cellspacing="0">',
			'<tr>',
				'<td><img src="/js/hint/img/corner_tl.gif" width="10" height="10" /></td>',
				'<td style="background-image:url(/js/hint/img/side_t.gif);"></td>',
				'<td><img src="/js/hint/img/corner_tr.gif" width="10" height="10" /></td>',
			'</tr>',
			'<tr>',
				'<td style="background-image:url(/js/hint/img/side_l.gif);"></td>',
				'<td class="hintText">', s_text ,'</td>',
				'<td style="background-image:url(/js/hint/img/side_r.gif);"></td>',
			'</tr>',
			'<tr>',
				'<td><img src="/js/hint/img/corner_bl.gif" width="10" height="10" /></td>',
				'<td style="background-image:url(/js/hint/img/side_b.gif);"></td>',
				'<td><img src="/js/hint/img/corner_br.gif" width="10" height="10" /></td>',
			'</tr>',
		'</table>'
	].join('');
}

var myHint = new THints (HINTS_ITEMS, HINTS_CFG);