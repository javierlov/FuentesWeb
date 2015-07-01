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
/* Orientaci�n a los resultados */
	wrap2('Es la capacidad de encaminar todos los actos al logro de lo esperado, actuando con responsabilidad y<br>' +
				'eficiencia para cumplir con las necesidades del sector/ cliente y para mejorar los resultados de<br>' +
				'la organizaci�n.'),
	wrap2('�	Incorpora como propias las directivas de sus superiores y muestra un alto nivel de compromiso con lo<br>' +
				'pactado. Se propone objetivos superiores a los establecidos, de manera realista y ambiciosa.<br>' +
				'�	Trabaja excediendo los objetivos fijados para su puesto y el grupo al que pertenece. Brinda apoyo y<br>' +
				'da el ejemplo en t�rminos de preocuparse por mejorar la calidad y la eficiencia de los procesos y<br>' +
				'servicios brindados.<br>' +
				'�	Da respuesta a situaciones complejas en contextos cambiantes, consiguiendo soluciones de alto impacto<br>' +
				'para la organizaci�n. Es un referente dentro de la organizaci�n en lo que respecta a la mejora continua<br>' +
				'de la eficiencia.'),
	wrap2('�	Modifica el sistema o sus propios m�todos de trabajo para conseguir mejoras en el rendimiento;<br>' +
				'encuentra formas mejores, m�s r�pidas, menos costosas y m�s eficientes en su desempe�o cotidiano.<br>' +
				'�	Analiza peri�dicamente los resultados y establece planes de mejora para la calidad y la satisfacci�n<br>' +
				'del cliente interno y externo.<br>' +
				'�	Posee una buena tolerancia a la presi�n y a la frustraci�n. Supera apropiadamente los obst�culos<br>' +
				'proponiendo alternativas para alcanzar sus metas.'),
	wrap2('�	Cumple con el nivel de desempe�o que se le pide y se organiza adecuadamente para llevar a cabo sus<br>' +
				'tareas, cumpliendo con los procesos establecidos.<br>' +
				'�	Realiza bien su trabajo y facilita el de los dem�s para el logro de los objetivos y acciones esperadas.<br>' +
				'�	Est� atento a los tiempos de realizaci�n de los trabajos y colabora constantemente en el cumplimiento<br>' +
				'de objetivos.'),
	wrap2('�	Act�a bas�ndose en las normas y procedimientos estandarizados sin estar atento a los resultados.<br>' +
				'�	Tiene dificultades para cumplir con objetivos de gesti�n exigentes o para responder a los tiempos<br>' +
				'fuera de su rutina.<br>' +
				'�	Se resiste a los cambios en procesos o servicios, aunque comprenda que est�n orientados a la mejora<br>' +
				'de los resultados.'),
	wrap2('�	Conf�a en que otros planeen y organicen su propio trabajo.<br>' +
				'�	Se atiene exclusivamente al cumplimiento de un horario desentendi�ndose de los resultados esperados.<br>' +
				'�	No asume la responsabilidad que se desprende de sus procesos de trabajo y su nivel de compromiso con<br>' +
				'la organizaci�n y sus objetivos es bajo.'),
/* Adaptabilidad al cambio */
	wrap2('Es la capacidad de modificar la propia conducta para alcanzar determinados objetivos cuando surgen<br>' +
				'dificultades, nuevos datos o cambios en el medio. Se asocia con la habilidad de adaptarse a distintos<br>' +
				'contextos, situaciones, medios y personas, r�pida y adecuadamente, trabajando con efectividad.<br>' +
				'La flexibilidad est� asociada a la capacidad de adaptar m�todos, juicios y formas de interpretar<br>' +
				'la realidad, manteniendo siempre claro el objetivo. Tambi�n est� vinculada a la capacidad para<br>' +
				'la revisi�n cr�tica.'),
	wrap2('�	Lidera el cambio antes que otros en su propio sector y se mueve con comodidad en todo tipo de<br>' +
				'ambiente y entre toda clase de gente.<br>' +
				'�	Est� atento a las necesidades cambiantes del contexto y analiza las situaciones y las caracter�sticas<br>' +
				'de las personas o grupos con el fin de adaptarse o adaptarlos de acuerdo a las circunstancias.<br>' +
				'�	Promueve la adaptabilidad al cambio entre sus pares y/o colaboradores y comprende y valora puntos de<br>' +
				'vista y criterios diversos, integrando el nuevo conocimiento con facilidad.'),
	wrap2('�	Visualiza en forma r�pida la necesidad de un cambio y propone acciones atinadas para enfrentar<br>' +
				'nuevas situaciones.' +
				'�	Se integra r�pidamente a diversos equipos de trabajo y adapta sus comportamientos a las situaciones<br>' +
				'o personas en funci�n de la calidad y eficiencia buscados.<br>' +
				'�	Promueve cambios de procesos, servicios o relaciones, de acuerdo con las situaciones planteadas y<br>' +
				'aprende de sus errores, aplicando su capacidad para revisar cr�ticamente su accionar.'),
	wrap2('�	Respeta y adhiere a las nuevas disposiciones y directivas de la empresa.<br>' +
				'�	Implementa en forma r�pida las propuestas que plantea la organizaci�n ante situaciones complejas.<br>' +
				'�	Muestra inter�s por modificar su accionar para mejorar la calidad de su trabajo y es eficiente en su<br>' +
				'integraci�n con interlocutores de diversos estilos.'),
	wrap2('�	Tiene dificultad para comprender los cambios de contexto y suele resistirse a incorporar ideas o<br>' +
				'criterios distintos de los propios.<br>' +
				'�	Tiene escasa capacidad para tomar decisiones adecuadas y oportunas en situaciones variadas o cambiantes.<br>' +
				'�	Trabaja c�modo en ambientes conocidos, pero tiene dificultades para integrarse a otros �mbitos o equipos.'),
	wrap2('�	Cuestiona las decisiones tomadas por la organizaci�n para hacer frente a crisis o situaciones<br>' +
				'delicadas, sin aportar otras ideas.<br>' +
				'�	Obstaculiza la implementaci�n de nuevos procesos y no le encuentra valor agregado a lo nuevo.<br>' +
				'�	No eval�a sus acciones pasadas; carece de actitud cr�tica.'),
/* Trabajo en equipo */
	wrap2('Es la capacidad de colaborar y cooperar con los dem�s, de formar parte de un grupo y trabajar juntos;<br>' +
				'lo opuesto a hacerlo individual y competitivamente. Es la capacidad de participar activamente en<br>' +
				'la prosecuci�n de una meta com�n, subordinando los intereses personales a los objetivos del equipo.<br>' +
				'Su visi�n del equipo incluye adem�s de sus jefes, pares y colaboradores, a sus clientes y proveedores<br>' +
				'internos como socios.'),
	wrap2('�	Alienta y fomenta el trabajo en equipo en toda la organizaci�n; y el intercambio de informaci�n y<br>' +
				'experiencias.<br>' +
				'�	Idea e implementa nuevas modalidades de trabajo en equipo a fin de a�adir valor a los resultados grupales.<br>' +
				'�	Prioriza los objetivos organizacionales de largo plazo sobre los propios o los de su grupo y es un<br>' +
				'referente en el manejo de equipos de trabajo.'),
	wrap2('�	Act�a para generar un ambiente de trabajo amistoso, de buen clima y cooperaci�n.<br>' +
				'�	Mantiene una actitud abierta para aprender de los otros.<br>' +
				'�	Valora las contribuciones ajenas, a�n cuando se le planteen diferentes puntos de vista, y resuelve<br>' +
				'los conflictos que plantea su equipo.'),
	wrap2('�	Coopera en las actividades comunes manteniendo una adecuada comunicaci�n y buen clima con quienes<br>' +
				'interact�a.<br>' +
				'�	Realiza la parte del trabajo que le corresponde respetando el espacio del resto del equipo,<br>' +
				'comprometi�ndose con la tarea.<br>' +
				'�	Comparte informaci�n y mantiene informados a los otros miembros del equipo de los temas que los afectan.'),
	wrap2('�	Tiene dificultades para colaborar y cooperar con los dem�s.<br>' +
				'�	Trabaja mejor en forma individual.<br>' +
				'�	Le cuesta compartir informaci�n o mantener a sus pares al tanto de sus avances.'),
	wrap2('�	Es receloso de los logros de los dem�s; es competitivo.<br>' +
				'�	Genera conflictos dentro del equipo de trabajo.<br>' +
				'�	Prioriza sus objetivos personales por sobre los del equipo o la organizaci�n.'),
/* Orientaci�n al cliente interno y externo */
	wrap2('Es la vocaci�n y el deseo de satisfacer a los clientes con el compromiso personal para cumplir con<br>' +
				'sus pedidos, deseos y expectativas. Implica esforzarse por conocer y resolver los problemas del cliente;<br>' +
				'comprender y satisfacer sus necesidades, de manera tal que se genere una relaci�n de<br>' +
				'colaboraci�n permanente. Es demostrar compromiso con la calidad, esforz�ndose por una mejora continua.'),
	wrap2('�	El cuidado del cliente forma parte siempre de su estrategia; es una convicci�n que promueve a trav�s<br>' +
				'de su constante ejemplo.<br>' +
				'�	Realiza, en forma proactiva, acciones orientadas a mejorar los �ndices de satisfacci�n del cliente y,<br>' +
				'frecuentemente, supera las expectativas. Indaga y se informa sobre necesidades actuales y potenciales de<br>' +
				'clientes internos, externos y proveedores.<br>' +
				'�	Sus acciones superan su propia responsabilidad, impulsando con su ejemplo a actuar en la misma<br>' +
				'direcci�n. Es un referente interno y externo cuando se busca aportar soluciones o satisfacer necesidades<br>' +
				'de clientes.'),
	wrap2('�	Responde a las demandas de los clientes brind�ndoles satisfacci�n m�s all� de lo esperado.<br>' +
				'�	Se preocupa por la calidad de cada trabajo que emprende, dando respuesta inmediata a los problemas de<br>' +
				'sus clientes con soluciones adecuadas.<br>' +
				'�	Atiende a cada cliente con dedicaci�n y voluntad de satisfacer las demandas que se le planteen, y es<br>' +
				'paciente y tolerante con sus clientes internos y externos, aun en situaciones complejas.'),
	wrap2('�	Responde adecuadamente frente a las demandas de los clientes, y ofrece respuestas est�ndar a sus<br>' +
				'necesidades.<br>' +
				'�	Atiende cordialmente a todos los clientes y escucha atentamente sus necesidades.<br>' +
				'�	Est� disponible para consultas y reclamos, y se interesa por el grado de satisfacci�n de los mismos.'),
	wrap2('�	No es flexible antes los distintos tipos de demandas.<br>' +
				'�	Se desentiende de las necesidades del cliente interno o externo; no atiende quejas ni reclamos.<br>' +
				'�	Sus respuestas a las demandas de los clientes no son adecuadas en tiempo y forma.'),
	wrap2('�	Nunca est� disponible para los clientes; atiende de mala gana y discute.<br>' +
				'�	Siempre tiene una respuesta negativa para los requerimientos de los clientes y no soluciona los<br>' +
				'problemas que �stos presentan, brindando una mala imagen de la organizaci�n.<br>' +
				'�	Piensa que la satisfacci�n de los clientes est� fuera de sus responsabilidades.'),
/* Liderazgo */
	wrap2('Es la habilidad necesaria para orientar la acci�n de los grupos humanos a su cargo. Tiene relaci�n con<br>' +
				'la capacidad para establecer claramente objetivos, prioridades, y de comunicarlos integrando<br>' +
				'las opiniones de los otros, realizando un seguimiento de los mismos. Capacidad de crear un clima<br>' +
				'de compromiso, motivando e inspirando confianza, desarrollando la cooperaci�n y el trabajo en equipo.<br>' +
				'Los l�deres construyen y comparten una visi�n, utilizando apropiados estilos interpersonales para<br>' +
				'ganar aceptaci�n de una idea. Toman decisiones considerando necesidades e impacto sobre los dem�s.'),
	wrap2('�	Se lo considera un referente interno y externo en materia de liderazgo, tanto formal como informal,<br>' +
				'porque promueve un clima que facilita la circulaci�n fluida de informaci�n y la mutua confianza,<br>' +
				'alentando la presentaci�n de sugerencias por parte del equipo.<br>' +
				'�	Fija objetivos, los transmite claramente, realiza su seguimiento y da coaching y feedback sobre<br>' +
				'el avance registrado, integrando las opiniones de los diferentes miembros del equipo.<br>' +
				'�	Realiza reuniones para el seguimiento de la evoluci�n de los proyectos, creando compromiso entres<br>' +
				'sus colaboradores y con la tarea, motiva y recompensa las mejoras aportadas al trabajo en l�nea con<br>' +
				'los objetivos de la empresa.'),
	wrap2('�	Se preocupa por el desarrollo de sus colaboradores y toma decisiones concretas al respecto, planeando<br>' +
				'y proponiendo acciones de desarrollo y capacitaci�n adecuadas.<br>' +
				'�	Se asegura que los colaboradores est�n informados sobre la marcha de la empresa y los resultados del �rea.<br>' +
				'�	Adopta herramientas y procedimientos para mejorar el rendimiento del equipo y se preocupa por<br>' +
				'efectuar evaluaciones constantes y dar feedback sobre la manera como trabaja el equipo.'),
	wrap2('�	Fija con claridad los lineamientos de la tarea que los re�ne y orienta al equipo hacia el logro de<br>' +
				'los objetivos propuestos.<br>' +
				'�	Encomienda a cada miembro del grupo una responsabilidad respecto del proyecto al que se encuentran<br>' +
				'asignados, reserv�ndose los puntos importantes y el manejo final para s�.<br>' +
				'�	Realiza un adecuado seguimiento del desempe�o del equipo para el cumplimiento de los objetivos y<br>' +
				'siempre que se le solicita provee de asesor�a y de la retroalimentaci�n necesaria para apoyar<br>' +
				'las actividades de desarrollo de sus colaboradores.'),
	wrap2('�	Produce demoras en la organizaci�n del trabajo por identificar err�neamente las competencias de<br>' +
				'sus integrantes y se conduce con formalidad o distancia frente al equipo que lidera impidiendo<br>' +
				'un acercamiento genuino a sus integrantes.<br>' +
				'�	Se muestra molesto siempre que recibe sugerencias o propuestas de sus colaboradores, comport�ndose<br>' +
				'con cierto grado de autoritarismo, impl�cito en sus comentarios.<br>' +
				'�	Tiene dificultades para fijar objetivos y act�a frente a posibilidades de capacitaci�n y mejora para<br>' +
				'los miembros de su equipo de trabajo, s�lo si se lo solicitan.'),
	wrap2('�	Obstaculiza la comunicaci�n entre sus colaboradores, generando conflictos por conducirse con<br>' +
				'evidentes preferencias por algunos colaboradores respecto de los dem�s.<br>' +
				'�	Sus colaboradores no saben qu� se espera de ellos y no reciben feedback porque realiza una<br>' +
				'inadecuada asignaci�n de roles, disminuyendo la potencialidad del equipo.<br>' +
				'�	Retiene informaci�n, dejando a las personas afectadas sin capacidad de decisi�n. El grupo no lo<br>' +
				'percibe como l�der.'),
/* Capacidad de Planificaci�n y organizaci�n */
	wrap2('Es la capacidad de determinar eficazmente las metas y las prioridades de su tarea/ �rea/ proyecto,<br>' +
				'estipulando la acci�n, los plazos y los recursos requeridos. Incluye la instrumentaci�n de mecanismos<br>' +
				'de seguimiento y verificaci�n de la informaci�n.'),
	wrap2('�	Se preocupa por comunicar claramente los objetivos, y verifica su comprensi�n por parte de todos<br>' +
				'los participantes del equipo. Documenta lo acordado para hacer el correspondiente seguimiento y los<br>' +
				'ajustes necesarios durante la marcha del proyecto.<br>' +
				'�	Es referente en la organizaci�n por pautar efectivos cursos de acci�n para s� y para los dem�s, con<br>' +
				'el fin de lograr un objetivo espec�fico.<br>' +
				'�	Idea y/o implementa con anticipaci�n herramientas que contribuyen a la organizaci�n y transparencia<br>' +
				'de los procedimientos que involucran a mucha gente y que requieren el manejo de grandes cantidades de<br>' +
				'informaci�n.'),
	wrap2('�	Negocia metas y recursos con sus compa�eros y colaboradores a fin de lograr acuerdos claros y<br>' +
				'compromisos genuinos.<br>' +
				'�	Toma tiempo para explicar a su grupo de trabajo los pasos a seguir, los tiempos estipulados,<br>' +
				'los recursos disponibles y las funciones asignadas.<br>' +
				'�	Lidera proyectos importantes asignados a su sector, por ser considerado conocedor de herramientas<br>' +
				'efectivas que hacen a la claridad de la tarea y aseguran la calidad de los resultados.'),
	wrap2('�	Est� atento por cumplir y hacer que se cumplan las pautas de trabajo establecidas al comienzo de<br>' +
				'cada proyecto.<br>' +
				'�	Aplica las herramientas disponibles para mantener organizada y utilizable la informaci�n referida a<br>' +
				'los trabajos de su sector.<br>' +
				'�	Toma en cuenta sugerencias para fijar prioridades y mantener la calidad de los resultados esperados<br>' +
				'y genera informaci�n �til a la hora de evaluar la gesti�n del �rea.'),
	wrap2('�	Necesita informaci�n por escrito que le fije pautas de trabajo a cumplir.<br>' +
				'�	Desconoce recursos disponibles haciendo m�s dif�ciles las tareas sencillas.<br>' +
				'�	Es desorganizado en sus presentaciones, transmitiendo dicha desorganizaci�n a su equipo de trabajo.'),
	wrap2('�	Act�a fuera de las normas y los procedimientos establecidos.<br>' +
				'�	Desatiende las pautas de trabajo que se le establecen al inicio de cada proyecto.<br>' +
				'�	No utiliza instrumentos de seguimiento o control de la gesti�n en su �rea y genera informaci�n<br>' +
				'inexacta o poco confiable a la hora de controlar las tareas por �l realizadas.'),
/* Pensamiento anal�tico */
	wrap2('Es la capacidad de entender y resolver un problema a partir de desagregar sistem�ticamente sus partes;<br>' +
				'realizando comparaciones, estableciendo prioridades, identificando secuencias temporales y<br>' +
				'relaciones causales entre los componentes.'),
	wrap2('�	Recopila informaci�n compleja de manera ordenada y sistem�tica. Establece diferentes relaciones entre<br>' +
				'los datos obtenidos, logrando descubrir problemas que no hab�an sido detectados.<br>' +
				'�	Permanece atento a todos los cambios del contexto, observa y examina cada aspecto. Establece<br>' +
				'relaciones entre los datos obtenidos, que le dan una base para planificar su estrategia de trabajo.<br>' +
				'�	Elabora informes precisos y f�ciles de comprender, interpretando y simplificando la complejidad de<br>' +
				'la informaci�n que maneja para que pueda ser utilizada por las diversas �reas de la organizaci�n.'),
	wrap2('�	Re�ne, revisa y comprende informaci�n compleja, necesaria para identificar o solucionar problemas.<br>' +
				'�	Organiza las partes de un problema, realiza comparaciones entre ellas y establece prioridades.<br>' +
				'�	Realiza propuestas para prevenir los problemas y aprovechar las oportunidades.'),
	wrap2('�	Comprende sistemas de mediana complejidad.<br>' +
				'�	Identifica las causas que llevaron a un problema y propone acciones para resolverlo.<br>' +
				'�	Efect�a acciones preventivas en funci�n del an�lisis de la informaci�n que posee.'),
	wrap2('�	No se preocupa por recopilar y examinar informaci�n importante para detectar problemas u oportunidades.<br>' +
				'�	Cuando se presenta un problema, no puede establecer sus causas y sus relaciones.<br>' +
				'�	Le cuesta anticiparse a los problemas.'),
	wrap2('�	Tiene dificultad para interpretar informaci�n compleja.<br>' +
				'�	No muestra inter�s por mejorar sus procesos de trabajo.<br>' +
				'�	Genera informes de escasa profundidad o dif�cil comprensi�n por lo confuso de la informaci�n contenida.')
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