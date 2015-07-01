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
/* Orientación a los resultados */
	wrap2('Es la capacidad de encaminar todos los actos al logro de lo esperado, actuando con responsabilidad y<br>' +
				'eficiencia para cumplir con las necesidades del sector/ cliente y para mejorar los resultados de<br>' +
				'la organización.'),
	wrap2('·	Incorpora como propias las directivas de sus superiores y muestra un alto nivel de compromiso con lo<br>' +
				'pactado. Se propone objetivos superiores a los establecidos, de manera realista y ambiciosa.<br>' +
				'·	Trabaja excediendo los objetivos fijados para su puesto y el grupo al que pertenece. Brinda apoyo y<br>' +
				'da el ejemplo en términos de preocuparse por mejorar la calidad y la eficiencia de los procesos y<br>' +
				'servicios brindados.<br>' +
				'·	Da respuesta a situaciones complejas en contextos cambiantes, consiguiendo soluciones de alto impacto<br>' +
				'para la organización. Es un referente dentro de la organización en lo que respecta a la mejora continua<br>' +
				'de la eficiencia.'),
	wrap2('·	Modifica el sistema o sus propios métodos de trabajo para conseguir mejoras en el rendimiento;<br>' +
				'encuentra formas mejores, más rápidas, menos costosas y más eficientes en su desempeño cotidiano.<br>' +
				'·	Analiza periódicamente los resultados y establece planes de mejora para la calidad y la satisfacción<br>' +
				'del cliente interno y externo.<br>' +
				'·	Posee una buena tolerancia a la presión y a la frustración. Supera apropiadamente los obstáculos<br>' +
				'proponiendo alternativas para alcanzar sus metas.'),
	wrap2('·	Cumple con el nivel de desempeño que se le pide y se organiza adecuadamente para llevar a cabo sus<br>' +
				'tareas, cumpliendo con los procesos establecidos.<br>' +
				'·	Realiza bien su trabajo y facilita el de los demás para el logro de los objetivos y acciones esperadas.<br>' +
				'·	Está atento a los tiempos de realización de los trabajos y colabora constantemente en el cumplimiento<br>' +
				'de objetivos.'),
	wrap2('·	Actúa basándose en las normas y procedimientos estandarizados sin estar atento a los resultados.<br>' +
				'·	Tiene dificultades para cumplir con objetivos de gestión exigentes o para responder a los tiempos<br>' +
				'fuera de su rutina.<br>' +
				'·	Se resiste a los cambios en procesos o servicios, aunque comprenda que están orientados a la mejora<br>' +
				'de los resultados.'),
	wrap2('·	Confía en que otros planeen y organicen su propio trabajo.<br>' +
				'·	Se atiene exclusivamente al cumplimiento de un horario desentendiéndose de los resultados esperados.<br>' +
				'·	No asume la responsabilidad que se desprende de sus procesos de trabajo y su nivel de compromiso con<br>' +
				'la organización y sus objetivos es bajo.'),
/* Adaptabilidad al cambio */
	wrap2('Es la capacidad de modificar la propia conducta para alcanzar determinados objetivos cuando surgen<br>' +
				'dificultades, nuevos datos o cambios en el medio. Se asocia con la habilidad de adaptarse a distintos<br>' +
				'contextos, situaciones, medios y personas, rápida y adecuadamente, trabajando con efectividad.<br>' +
				'La flexibilidad está asociada a la capacidad de adaptar métodos, juicios y formas de interpretar<br>' +
				'la realidad, manteniendo siempre claro el objetivo. También está vinculada a la capacidad para<br>' +
				'la revisión crítica.'),
	wrap2('·	Lidera el cambio antes que otros en su propio sector y se mueve con comodidad en todo tipo de<br>' +
				'ambiente y entre toda clase de gente.<br>' +
				'·	Está atento a las necesidades cambiantes del contexto y analiza las situaciones y las características<br>' +
				'de las personas o grupos con el fin de adaptarse o adaptarlos de acuerdo a las circunstancias.<br>' +
				'·	Promueve la adaptabilidad al cambio entre sus pares y/o colaboradores y comprende y valora puntos de<br>' +
				'vista y criterios diversos, integrando el nuevo conocimiento con facilidad.'),
	wrap2('·	Visualiza en forma rápida la necesidad de un cambio y propone acciones atinadas para enfrentar<br>' +
				'nuevas situaciones.' +
				'·	Se integra rápidamente a diversos equipos de trabajo y adapta sus comportamientos a las situaciones<br>' +
				'o personas en función de la calidad y eficiencia buscados.<br>' +
				'·	Promueve cambios de procesos, servicios o relaciones, de acuerdo con las situaciones planteadas y<br>' +
				'aprende de sus errores, aplicando su capacidad para revisar críticamente su accionar.'),
	wrap2('·	Respeta y adhiere a las nuevas disposiciones y directivas de la empresa.<br>' +
				'·	Implementa en forma rápida las propuestas que plantea la organización ante situaciones complejas.<br>' +
				'·	Muestra interés por modificar su accionar para mejorar la calidad de su trabajo y es eficiente en su<br>' +
				'integración con interlocutores de diversos estilos.'),
	wrap2('·	Tiene dificultad para comprender los cambios de contexto y suele resistirse a incorporar ideas o<br>' +
				'criterios distintos de los propios.<br>' +
				'·	Tiene escasa capacidad para tomar decisiones adecuadas y oportunas en situaciones variadas o cambiantes.<br>' +
				'·	Trabaja cómodo en ambientes conocidos, pero tiene dificultades para integrarse a otros ámbitos o equipos.'),
	wrap2('·	Cuestiona las decisiones tomadas por la organización para hacer frente a crisis o situaciones<br>' +
				'delicadas, sin aportar otras ideas.<br>' +
				'·	Obstaculiza la implementación de nuevos procesos y no le encuentra valor agregado a lo nuevo.<br>' +
				'·	No evalúa sus acciones pasadas; carece de actitud crítica.'),
/* Trabajo en equipo */
	wrap2('Es la capacidad de colaborar y cooperar con los demás, de formar parte de un grupo y trabajar juntos;<br>' +
				'lo opuesto a hacerlo individual y competitivamente. Es la capacidad de participar activamente en<br>' +
				'la prosecución de una meta común, subordinando los intereses personales a los objetivos del equipo.<br>' +
				'Su visión del equipo incluye además de sus jefes, pares y colaboradores, a sus clientes y proveedores<br>' +
				'internos como socios.'),
	wrap2('·	Alienta y fomenta el trabajo en equipo en toda la organización; y el intercambio de información y<br>' +
				'experiencias.<br>' +
				'·	Idea e implementa nuevas modalidades de trabajo en equipo a fin de añadir valor a los resultados grupales.<br>' +
				'·	Prioriza los objetivos organizacionales de largo plazo sobre los propios o los de su grupo y es un<br>' +
				'referente en el manejo de equipos de trabajo.'),
	wrap2('·	Actúa para generar un ambiente de trabajo amistoso, de buen clima y cooperación.<br>' +
				'·	Mantiene una actitud abierta para aprender de los otros.<br>' +
				'·	Valora las contribuciones ajenas, aún cuando se le planteen diferentes puntos de vista, y resuelve<br>' +
				'los conflictos que plantea su equipo.'),
	wrap2('·	Coopera en las actividades comunes manteniendo una adecuada comunicación y buen clima con quienes<br>' +
				'interactúa.<br>' +
				'·	Realiza la parte del trabajo que le corresponde respetando el espacio del resto del equipo,<br>' +
				'comprometiéndose con la tarea.<br>' +
				'·	Comparte información y mantiene informados a los otros miembros del equipo de los temas que los afectan.'),
	wrap2('·	Tiene dificultades para colaborar y cooperar con los demás.<br>' +
				'·	Trabaja mejor en forma individual.<br>' +
				'·	Le cuesta compartir información o mantener a sus pares al tanto de sus avances.'),
	wrap2('·	Es receloso de los logros de los demás; es competitivo.<br>' +
				'·	Genera conflictos dentro del equipo de trabajo.<br>' +
				'·	Prioriza sus objetivos personales por sobre los del equipo o la organización.'),
/* Orientación al cliente interno y externo */
	wrap2('Es la vocación y el deseo de satisfacer a los clientes con el compromiso personal para cumplir con<br>' +
				'sus pedidos, deseos y expectativas. Implica esforzarse por conocer y resolver los problemas del cliente;<br>' +
				'comprender y satisfacer sus necesidades, de manera tal que se genere una relación de<br>' +
				'colaboración permanente. Es demostrar compromiso con la calidad, esforzándose por una mejora continua.'),
	wrap2('·	El cuidado del cliente forma parte siempre de su estrategia; es una convicción que promueve a través<br>' +
				'de su constante ejemplo.<br>' +
				'·	Realiza, en forma proactiva, acciones orientadas a mejorar los índices de satisfacción del cliente y,<br>' +
				'frecuentemente, supera las expectativas. Indaga y se informa sobre necesidades actuales y potenciales de<br>' +
				'clientes internos, externos y proveedores.<br>' +
				'·	Sus acciones superan su propia responsabilidad, impulsando con su ejemplo a actuar en la misma<br>' +
				'dirección. Es un referente interno y externo cuando se busca aportar soluciones o satisfacer necesidades<br>' +
				'de clientes.'),
	wrap2('·	Responde a las demandas de los clientes brindándoles satisfacción más allá de lo esperado.<br>' +
				'·	Se preocupa por la calidad de cada trabajo que emprende, dando respuesta inmediata a los problemas de<br>' +
				'sus clientes con soluciones adecuadas.<br>' +
				'·	Atiende a cada cliente con dedicación y voluntad de satisfacer las demandas que se le planteen, y es<br>' +
				'paciente y tolerante con sus clientes internos y externos, aun en situaciones complejas.'),
	wrap2('·	Responde adecuadamente frente a las demandas de los clientes, y ofrece respuestas estándar a sus<br>' +
				'necesidades.<br>' +
				'·	Atiende cordialmente a todos los clientes y escucha atentamente sus necesidades.<br>' +
				'·	Está disponible para consultas y reclamos, y se interesa por el grado de satisfacción de los mismos.'),
	wrap2('·	No es flexible antes los distintos tipos de demandas.<br>' +
				'·	Se desentiende de las necesidades del cliente interno o externo; no atiende quejas ni reclamos.<br>' +
				'·	Sus respuestas a las demandas de los clientes no son adecuadas en tiempo y forma.'),
	wrap2('·	Nunca está disponible para los clientes; atiende de mala gana y discute.<br>' +
				'·	Siempre tiene una respuesta negativa para los requerimientos de los clientes y no soluciona los<br>' +
				'problemas que éstos presentan, brindando una mala imagen de la organización.<br>' +
				'·	Piensa que la satisfacción de los clientes está fuera de sus responsabilidades.'),
/* Liderazgo */
	wrap2('Es la habilidad necesaria para orientar la acción de los grupos humanos a su cargo. Tiene relación con<br>' +
				'la capacidad para establecer claramente objetivos, prioridades, y de comunicarlos integrando<br>' +
				'las opiniones de los otros, realizando un seguimiento de los mismos. Capacidad de crear un clima<br>' +
				'de compromiso, motivando e inspirando confianza, desarrollando la cooperación y el trabajo en equipo.<br>' +
				'Los líderes construyen y comparten una visión, utilizando apropiados estilos interpersonales para<br>' +
				'ganar aceptación de una idea. Toman decisiones considerando necesidades e impacto sobre los demás.'),
	wrap2('·	Se lo considera un referente interno y externo en materia de liderazgo, tanto formal como informal,<br>' +
				'porque promueve un clima que facilita la circulación fluida de información y la mutua confianza,<br>' +
				'alentando la presentación de sugerencias por parte del equipo.<br>' +
				'·	Fija objetivos, los transmite claramente, realiza su seguimiento y da coaching y feedback sobre<br>' +
				'el avance registrado, integrando las opiniones de los diferentes miembros del equipo.<br>' +
				'·	Realiza reuniones para el seguimiento de la evolución de los proyectos, creando compromiso entres<br>' +
				'sus colaboradores y con la tarea, motiva y recompensa las mejoras aportadas al trabajo en línea con<br>' +
				'los objetivos de la empresa.'),
	wrap2('·	Se preocupa por el desarrollo de sus colaboradores y toma decisiones concretas al respecto, planeando<br>' +
				'y proponiendo acciones de desarrollo y capacitación adecuadas.<br>' +
				'·	Se asegura que los colaboradores estén informados sobre la marcha de la empresa y los resultados del área.<br>' +
				'·	Adopta herramientas y procedimientos para mejorar el rendimiento del equipo y se preocupa por<br>' +
				'efectuar evaluaciones constantes y dar feedback sobre la manera como trabaja el equipo.'),
	wrap2('·	Fija con claridad los lineamientos de la tarea que los reúne y orienta al equipo hacia el logro de<br>' +
				'los objetivos propuestos.<br>' +
				'·	Encomienda a cada miembro del grupo una responsabilidad respecto del proyecto al que se encuentran<br>' +
				'asignados, reservándose los puntos importantes y el manejo final para sí.<br>' +
				'·	Realiza un adecuado seguimiento del desempeño del equipo para el cumplimiento de los objetivos y<br>' +
				'siempre que se le solicita provee de asesoría y de la retroalimentación necesaria para apoyar<br>' +
				'las actividades de desarrollo de sus colaboradores.'),
	wrap2('·	Produce demoras en la organización del trabajo por identificar erróneamente las competencias de<br>' +
				'sus integrantes y se conduce con formalidad o distancia frente al equipo que lidera impidiendo<br>' +
				'un acercamiento genuino a sus integrantes.<br>' +
				'·	Se muestra molesto siempre que recibe sugerencias o propuestas de sus colaboradores, comportándose<br>' +
				'con cierto grado de autoritarismo, implícito en sus comentarios.<br>' +
				'·	Tiene dificultades para fijar objetivos y actúa frente a posibilidades de capacitación y mejora para<br>' +
				'los miembros de su equipo de trabajo, sólo si se lo solicitan.'),
	wrap2('·	Obstaculiza la comunicación entre sus colaboradores, generando conflictos por conducirse con<br>' +
				'evidentes preferencias por algunos colaboradores respecto de los demás.<br>' +
				'·	Sus colaboradores no saben qué se espera de ellos y no reciben feedback porque realiza una<br>' +
				'inadecuada asignación de roles, disminuyendo la potencialidad del equipo.<br>' +
				'·	Retiene información, dejando a las personas afectadas sin capacidad de decisión. El grupo no lo<br>' +
				'percibe como líder.'),
/* Capacidad de Planificación y organización */
	wrap2('Es la capacidad de determinar eficazmente las metas y las prioridades de su tarea/ área/ proyecto,<br>' +
				'estipulando la acción, los plazos y los recursos requeridos. Incluye la instrumentación de mecanismos<br>' +
				'de seguimiento y verificación de la información.'),
	wrap2('·	Se preocupa por comunicar claramente los objetivos, y verifica su comprensión por parte de todos<br>' +
				'los participantes del equipo. Documenta lo acordado para hacer el correspondiente seguimiento y los<br>' +
				'ajustes necesarios durante la marcha del proyecto.<br>' +
				'·	Es referente en la organización por pautar efectivos cursos de acción para sí y para los demás, con<br>' +
				'el fin de lograr un objetivo específico.<br>' +
				'·	Idea y/o implementa con anticipación herramientas que contribuyen a la organización y transparencia<br>' +
				'de los procedimientos que involucran a mucha gente y que requieren el manejo de grandes cantidades de<br>' +
				'información.'),
	wrap2('·	Negocia metas y recursos con sus compañeros y colaboradores a fin de lograr acuerdos claros y<br>' +
				'compromisos genuinos.<br>' +
				'·	Toma tiempo para explicar a su grupo de trabajo los pasos a seguir, los tiempos estipulados,<br>' +
				'los recursos disponibles y las funciones asignadas.<br>' +
				'·	Lidera proyectos importantes asignados a su sector, por ser considerado conocedor de herramientas<br>' +
				'efectivas que hacen a la claridad de la tarea y aseguran la calidad de los resultados.'),
	wrap2('·	Está atento por cumplir y hacer que se cumplan las pautas de trabajo establecidas al comienzo de<br>' +
				'cada proyecto.<br>' +
				'·	Aplica las herramientas disponibles para mantener organizada y utilizable la información referida a<br>' +
				'los trabajos de su sector.<br>' +
				'·	Toma en cuenta sugerencias para fijar prioridades y mantener la calidad de los resultados esperados<br>' +
				'y genera información útil a la hora de evaluar la gestión del área.'),
	wrap2('·	Necesita información por escrito que le fije pautas de trabajo a cumplir.<br>' +
				'·	Desconoce recursos disponibles haciendo más difíciles las tareas sencillas.<br>' +
				'·	Es desorganizado en sus presentaciones, transmitiendo dicha desorganización a su equipo de trabajo.'),
	wrap2('·	Actúa fuera de las normas y los procedimientos establecidos.<br>' +
				'·	Desatiende las pautas de trabajo que se le establecen al inicio de cada proyecto.<br>' +
				'·	No utiliza instrumentos de seguimiento o control de la gestión en su área y genera información<br>' +
				'inexacta o poco confiable a la hora de controlar las tareas por él realizadas.'),
/* Pensamiento analítico */
	wrap2('Es la capacidad de entender y resolver un problema a partir de desagregar sistemáticamente sus partes;<br>' +
				'realizando comparaciones, estableciendo prioridades, identificando secuencias temporales y<br>' +
				'relaciones causales entre los componentes.'),
	wrap2('·	Recopila información compleja de manera ordenada y sistemática. Establece diferentes relaciones entre<br>' +
				'los datos obtenidos, logrando descubrir problemas que no habían sido detectados.<br>' +
				'·	Permanece atento a todos los cambios del contexto, observa y examina cada aspecto. Establece<br>' +
				'relaciones entre los datos obtenidos, que le dan una base para planificar su estrategia de trabajo.<br>' +
				'·	Elabora informes precisos y fáciles de comprender, interpretando y simplificando la complejidad de<br>' +
				'la información que maneja para que pueda ser utilizada por las diversas áreas de la organización.'),
	wrap2('·	Reúne, revisa y comprende información compleja, necesaria para identificar o solucionar problemas.<br>' +
				'·	Organiza las partes de un problema, realiza comparaciones entre ellas y establece prioridades.<br>' +
				'·	Realiza propuestas para prevenir los problemas y aprovechar las oportunidades.'),
	wrap2('·	Comprende sistemas de mediana complejidad.<br>' +
				'·	Identifica las causas que llevaron a un problema y propone acciones para resolverlo.<br>' +
				'·	Efectúa acciones preventivas en función del análisis de la información que posee.'),
	wrap2('·	No se preocupa por recopilar y examinar información importante para detectar problemas u oportunidades.<br>' +
				'·	Cuando se presenta un problema, no puede establecer sus causas y sus relaciones.<br>' +
				'·	Le cuesta anticiparse a los problemas.'),
	wrap2('·	Tiene dificultad para interpretar información compleja.<br>' +
				'·	No muestra interés por mejorar sus procesos de trabajo.<br>' +
				'·	Genera informes de escasa profundidad o difícil comprensión por lo confuso de la información contenida.')
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