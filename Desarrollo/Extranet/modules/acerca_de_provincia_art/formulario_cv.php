<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/encryptation.php");
require_once("formulario_cv_combos.php");


$conn = oci_connect(DB_USER_RHPRO, decrypt(DB_PASS_RHPRO, "PROVART"), DB_SERV_RHPRO, "WE8ISO8859P1");
if (!$conn) {
//	$e = oci_error();
//	echo $e["message"];
	echo "Servidor congestionado, intente mas tarde.";
	exit;
}
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/design.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<link rel="stylesheet" href="css/acerca_de.css" type="text/css" />
		<script src="/js/functions.js" type="text/javascript"></script>
		<script type="text/javascript" src="/modules/acerca_de_provincia_art/js/formulario.js"></script>

		<!-- INICIO CALENDARIO.. -->
		<style type="text/css">@import url(/js/calendario/calendar-system.css);</style>
		<script type="text/javascript" src="/js/calendario/calendar.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-es.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-setup.js"></script>
		<!-- FIN CALENDARIO.. -->
	</head>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/acerca_de_provincia_art/procesar_formulario.php" enctype="multipart/form-data" id="formEnviarCV" method="post" name="formEnviarCV" target="iframeProcesando">
			<input id="p" name="p" type="hidden" value="<?= $_REQUEST["p"]?>" />
			<div>
				<img border="0" id="imgHeader" src="/modules/acerca_de_provincia_art/images/header.jpg" />
			</div>
			<div align="center" class="ContenidoSeccion" id="divPrincipal">
				<div class="divTitulo">DATOS PERSONALES</div>
				<div id="divLinea">
					<label>Nombre 1</label>
					<input autofocus class="nombre" id="nombre1" maxlength="25" name="nombre1" type="text" /> *
				</div>
				<div id="divLinea">
					<label>Nombre 2</label>
					<input class="nombre" id="nombre2" maxlength="25" name="nombre2" type="text" />
				</div>
				<div id="divLinea">
					<label>Apellido 1</label>
					<input class="nombre" id="apellido1" maxlength="25" name="apellido1" type="text" /> *
				</div>
				<div id="divLinea">
					<label>Apellido 2</label>
					<input class="nombre" id="apellido2" maxlength="25" name="apellido2" type="text" />
				</div>
				<div id="divLinea">
					<label>Fecha de Nacimiento dd/mm/aaaa</label>
					<input class="fecha" id="fechaNacimiento" maxlength="10" name="fechaNacimiento" type="text">
					<input class="botonFecha" id="btnFechaNacimiento" name="btnFechaNacimiento" type="button" value=""> *
				</div>
				<div id="divLinea">
					<label>Sexo</label>
					<input id="sexo" name="sexo" type="radio" value="0" />
					<label>Femenino</label>
					<input id="sexo" name="sexo" type="radio" value="-1" />
					<label>Masculino</label> *
				</div>
				<div id="divLinea">
					<label>Estado Civil</label>
					<?= $comboEstadoCivil->draw();?> *
				</div>
				<div id="divLinea">
					<label>País de Nacimiento</label>
					<?= $comboPaisNacimiento->draw();?> *
				</div>
				<div id="divLinea">
					<label>Nacionalidad</label>
					<?= $comboNacionalidad->draw();?> *
				</div>
				<div id="divLinea">
					<label>Tipo de Documento</label>
					<?= $comboTipoDocumento->draw();?> *
					<label>Nro</label>
					<input id="numeroDocumento" maxlength="30" name="numeroDocumento" type="text" /> *
				</div>
				<div id="divLinea">
					<label>Teléfono Fijo</label>
					<input id="telefonoFijo" maxlength="20" name="telefonoFijo" type="text" /> *
				</div>
				<div id="divLinea">
					<label>Teléfono Celular</label>
					<input id="telefonoCelular" maxlength="20" name="telefonoCelular" type="text" />
				</div>
				<div id="divLinea">
					<label>e-Mail</label>
					<input id="email" maxlength="100" name="email" type="text" />
				</div>
				<div id="divLinea">
					<label>Calle</label>
					<input id="calle" maxlength="30" name="calle" type="text" /> *
				</div>
				<div id="divLinea">
					<label>Número</label>
					<input id="numeroCalle" maxlength="8" name="numeroCalle" type="text" /> *
					<label id="pisoTitulo">Piso</label>
					<input id="piso" maxlength="8" name="piso" type="text" />
					<label id="departamentoTitulo">Departamento</label>
					<input id="departamento" maxlength="8" name="departamento" type="text" />
				</div>
				<div id="divLinea">
					<label>Torre</label>
					<input id="torre" maxlength="8" name="torre" type="text" />
					<label>Manzana</label>
					<input id="manzana" maxlength="8" name="manzana" type="text" />
					<label>Sector</label>
					<input id="sector" maxlength="8" name="sector" type="text" />
					<label>CP</label>
					<input id="cp" maxlength="12" name="cp" type="text" /> *
				</div>
				<div id="divLinea">
					<label>Localidad</label>
					<?= $comboLocalidad->draw();?> *
				</div>
				<div id="divLinea">
					<label>Provincia</label>
					<?= $comboProvincia->draw();?> *
				</div>
				<div id="divLinea">
					<label>Partido</label>
					<?= $comboPartido->draw();?> *
				</div>
				<div id="divLinea">
					<label>País</label>
					<?= $comboPais->draw();?> *
				</div>
				<div class="divTitulo">
					<label>FOTO</label>
				</div>
				<div id="divLinea">
					<label>Adjuntar foto en formato jpg y peso max de 70 KB</label>
					<br />
					<input class="InputText" id="foto" name="foto" type="file" />
				</div>

				<div class="divTitulo">
					<label>FORMACIÓN</label>
				</div>
				<div id="formacion1">
					<div id="divLinea">
						<label>Nivel</label>
						<?= $comboNivelFormacion1->draw();?> *
					</div>
					<div id="divLinea">
						<label>Completo</label>
						<input id="completo1" name="completo1" type="radio" value="-1" />
						<label>Sí</label>
						<input id="completo1" name="completo1" type="radio" value="0" />
						<label>No</label> *
					</div>
					<div id="divLinea">
						<label>Título</label>
						<?= $comboTitulo1->draw();?> *
					</div>
					<div id="divLinea">
						<label>Institución</label>
						<?= $comboInstitucion1->draw();?> *
					</div>
					<div id="divLinea">
						<label>Carrera</label>
						<?= $comboCarrera1->draw();?> *
					</div>
				</div>

				<div class="elementoOculto" id="formacion2">
					<input id="formacion2visible" name="formacion2visible" type="hidden" value="f">
					<hr />
					<div id="divLinea">
						<label>Nivel</label>
						<?= $comboNivelFormacion2->draw();?> *
					</div>
					<div id="divLinea">
						<label>Completo</label>
						<input id="completo2" name="completo2" type="radio" value="-1" />
						<label>Sí</label>
						<input id="completo2" name="completo2" type="radio" value="0" />
						<label>No</label> *
					</div>
					<div id="divLinea">
						<label>Título</label>
						<?= $comboTitulo2->draw();?> *
					</div>
					<div id="divLinea">
						<label>Institución</label>
						<?= $comboInstitucion2->draw();?> *
					</div>
					<div id="divLinea">
						<label>Carrera</label>
						<?= $comboCarrera2->draw();?> *
					</div>
				</div>

				<div class="elementoOculto" id="formacion3">
					<input id="formacion3visible" name="formacion3visible" type="hidden" value="f">
					<hr />
					<div id="divLinea">
						<label>Nivel</label>
						<?= $comboNivelFormacion3->draw();?> *
					</div>
					<div id="divLinea">
						<label>Completo</label>
						<input id="completo3" name="completo3" type="radio" value="-1" />
						<label>Sí</label>
						<input id="completo3" name="completo3" type="radio" value="0" />
						<label>No</label> *
					</div>
					<div id="divLinea">
						<label>Título</label>
						<?= $comboTitulo3->draw();?> *
					</div>
					<div id="divLinea">
						<label>Institución</label>
						<?= $comboInstitucion3->draw();?> *
					</div>
					<div id="divLinea">
						<label>Carrera</label>
						<?= $comboCarrera3->draw();?> *
					</div>
				</div>

				<div class="elementoOculto" id="formacion4">
					<input id="formacion4visible" name="formacion4visible" type="hidden" value="f">
					<hr />
					<div id="divLinea">
						<label>Nivel</label>
						<?= $comboNivelFormacion4->draw();?> *
					</div>
					<div id="divLinea">
						<label>Completo</label>
						<input id="completo4" name="completo4" type="radio" value="-1" />
						<label>Sí</label>
						<input id="completo4" name="completo4" type="radio" value="0" />
						<label>No</label> *
					</div>
					<div id="divLinea">
						<label>Título</label>
						<?= $comboTitulo4->draw();?> *
					</div>
					<div id="divLinea">
						<label>Institución</label>
						<?= $comboInstitucion4->draw();?> *
					</div>
					<div id="divLinea">
						<label>Carrera</label>
						<?= $comboCarrera4->draw();?> *
					</div>
				</div>
				<div id="divLineaLink">
					<a href="javascript:agregarEstudio()" id="agregarEstudio">[+] Agregar otro estudio</a>
					<a href="javascript:quitarEstudio()" id="quitarEstudio">[-] Quitar</a>
				</div>

				<div class="divTitulo">
					<label>EXPERIENCIA LABORAL</label>
				</div>
				<div id="experienciaLaboral1">
					<div id="divLinea">
						<label>Fecha desde</label>
						<input class="fecha" id="fechaDesde1" maxlength="10" name="fechaDesde1" type="text" />
						<input class="botonFecha" id="btnFechaDesde1" name="btnFechaDesde1" type="button" value=""> *
						<label class="labelHasta">hasta</label>
						<input class="fecha" id="fechaHasta1" maxlength="10" name="fechaHasta1" type="text" />
						<input class="botonFecha" id="btnFechaHasta1" name="btnFechaHasta1" type="button" value="">
					</div>
					<div id="divLinea">
						<label>Empresa</label>
						<input class="empresa" id="empresa1" maxlength="60" name="empresa1" type="text" /> *
					</div>
					<div id="divLinea">
						<label>Cargo anterior</label>
						<input class="cargoAnterior" id="cargoAnterior1" maxlength="50" name="cargoAnterior1" type="text" />
					</div>
					<div id="divLinea">
						<label>Breve descripción de tareas *</label>
						<br />
						<textarea class="InputText tareas" id="tareas1" maxlength="200" name="tareas1"></textarea>
					</div>
				</div>

				<div class="elementoOculto" id="experienciaLaboral2">
					<input id="experienciaLaboral2visible" name="experienciaLaboral2visible" type="hidden" value="f">
					<hr />
					<div id="divLinea">
						<label>Fecha desde</label>
						<input class="fecha" id="fechaDesde2" maxlength="10" name="fechaDesde2" type="text" />
						<input class="botonFecha" id="btnFechaDesde2" name="btnFechaDesde2" type="button" value=""> *
						<label class="labelHasta">hasta</label>
						<input class="fecha" id="fechaHasta2" maxlength="10" name="fechaHasta2" type="text" />
						<input class="botonFecha" id="btnFechaHasta2" name="btnFechaHasta2" type="button" value="">
					</div>
					<div id="divLinea">
						<label>Empresa</label>
						<input class="empresa" id="empresa2" maxlength="60" name="empresa2" type="text" /> *
					</div>
					<div id="divLinea">
						<label>Cargo anterior</label>
						<input class="cargoAnterior" id="cargoAnterior2" maxlength="50" name="cargoAnterior2" type="text" />
					</div>
					<div id="divLinea">
						<label>Breve descripción de tareas *</label>
						<br />
						<textarea class="InputText tareas" id="tareas2" maxlength="200" name="tareas2"></textarea>
					</div>
				</div>

				<div class="elementoOculto" id="experienciaLaboral3">
					<input id="experienciaLaboral3visible" name="experienciaLaboral3visible" type="hidden" value="f">
					<hr />
					<div id="divLinea">
						<label>Fecha desde</label>
						<input class="fecha" id="fechaDesde3" maxlength="10" name="fechaDesde3" type="text" />
						<input class="botonFecha" id="btnFechaDesde3" name="btnFechaDesde3" type="button" value=""> *
						<label class="labelHasta">hasta</label>
						<input class="fecha" id="fechaHasta3" maxlength="10" name="fechaHasta3" type="text" />
						<input class="botonFecha" id="btnFechaHasta3" name="btnFechaHasta3" type="button" value="">
					</div>
					<div id="divLinea">
						<label>Empresa</label>
						<input class="empresa" id="empresa3" maxlength="60" name="empresa3" type="text" /> *
					</div>
					<div id="divLinea">
						<label>Cargo anterior</label>
						<input class="cargoAnterior" id="cargoAnterior3" maxlength="50" name="cargoAnterior3" type="text" />
					</div>
					<div id="divLinea">
						<label>Breve descripción de tareas *</label>
						<br />
						<textarea class="InputText tareas" id="tareas3" maxlength="200" name="tareas3"></textarea>
					</div>
				</div>

				<div class="elementoOculto" id="experienciaLaboral4">
					<input id="experienciaLaboral4visible" name="experienciaLaboral4visible" type="hidden" value="f">
					<hr />
					<div id="divLinea">
						<label>Fecha desde</label>
						<input class="fecha" id="fechaDesde4" maxlength="10" name="fechaDesde4" type="text" />
						<input class="botonFecha" id="btnFechaDesde4" name="btnFechaDesde4" type="button" value=""> *
						<label class="labelHasta">hasta</label>
						<input class="fecha" id="fechaHasta4" maxlength="10" name="fechaHasta4" type="text" />
						<input class="botonFecha" id="btnFechaHasta4" name="btnFechaHasta4" type="button" value="">
					</div>
					<div id="divLinea">
						<label>Empresa</label>
						<input class="empresa" id="empresa4" maxlength="60" name="empresa4" type="text" /> *
					</div>
					<div id="divLinea">
						<label>Cargo anterior</label>
						<input class="cargoAnterior" id="cargoAnterior4" maxlength="50" name="cargoAnterior4" type="text" />
					</div>
					<div id="divLinea">
						<label>Breve descripción de tareas *</label>
						<br />
						<textarea class="InputText tareas" id="tareas4" maxlength="200" name="tareas4"></textarea>
					</div>
				</div>
				<div id="divLineaLink">
					<a href="javascript:agregarExperienciaLaboral()" id="agregarExperienciaLaboral">[+] Agregar otra experiencia laboral</a>
					<a href="javascript:quitarExperienciaLaboral()" id="quitarExperienciaLaboral">[-] Quitar</a>
				</div>

				<div class="divTitulo">
					<label>IDIOMAS</label>
				</div>
				<div id="divIdioma1">
					<div id="divLinea">
						<label>Idioma</label>
						<?= $comboIdioma1->draw();?> *
					</div>
					<div id="divLinea">
						<label>Habla - Nivel</label>
						<?= $comboHablaNivel1->draw();?> *
					</div>
					<div id="divLinea">
						<label>Lee - Nivel</label>
						<?= $comboLeeNivel1->draw();?> *
					</div>
					<div id="divLinea">
						<label>Escribe - Nivel</label>
						<?= $comboEscribeNivel1->draw();?> *
					</div>
				</div>

				<div class="elementoOculto" id="divIdioma2">
					<input id="idioma2visible" name="idioma2visible" type="hidden" value="f">
					<hr />
					<div id="divLinea">
						<label>Idioma</label>
						<?= $comboIdioma2->draw();?> *
					</div>
					<div id="divLinea">
						<label>Habla - Nivel</label>
						<?= $comboHablaNivel2->draw();?> *
					</div>
					<div id="divLinea">
						<label>Lee - Nivel</label>
						<?= $comboLeeNivel2->draw();?> *
					</div>
					<div id="divLinea">
						<label>Escribe - Nivel</label>
						<?= $comboEscribeNivel2->draw();?> *
					</div>
				</div>

				<div class="elementoOculto" id="divIdioma3">
					<input id="idioma3visible" name="idioma3visible" type="hidden" value="f">
					<hr />
					<div id="divLinea">
						<label>Idioma</label>
						<?= $comboIdioma3->draw();?> *
					</div>
					<div id="divLinea">
						<label>Habla - Nivel</label>
						<?= $comboHablaNivel3->draw();?> *
					</div>
					<div id="divLinea">
						<label>Lee - Nivel</label>
						<?= $comboLeeNivel3->draw();?> *
					</div>
					<div id="divLinea">
						<label>Escribe - Nivel</label>
						<?= $comboEscribeNivel3->draw();?> *
					</div>
				</div>

				<div class="elementoOculto" id="divIdioma4">
					<input id="idioma4visible" name="idioma4visible" type="hidden" value="f">
					<hr />
					<div id="divLinea">
						<label>Idioma</label>
						<?= $comboIdioma4->draw();?> *
					</div>
					<div id="divLinea">
						<label>Habla - Nivel</label>
						<?= $comboHablaNivel4->draw();?> *
					</div>
					<div id="divLinea">
						<label>Lee - Nivel</label>
						<?= $comboLeeNivel4->draw();?> *
					</div>
					<div id="divLinea">
						<label>Escribe - Nivel</label>
						<?= $comboEscribeNivel4->draw();?> *
					</div>
				</div>
				<div id="divLineaLink">
					<a href="javascript:agregarIdioma()" id="agregarIdioma">[+] Agregar otro idioma</a>
					<a href="javascript:quitarIdioma()" id="quitarIdioma">[-] Quitar</a>
				</div>

				<div class="divTitulo">
					<label>CURSOS Y OTROS CONOCIMIENTOS</label>
				</div>
				<div id="curso1">
					<div id="divLinea">
						<label>Tipo de Curso</label>
						<?= $comboTipoCurso1->draw();?> *
					</div>
					<div id="divLinea">
						<label>Nombre del Curso</label>
						<input class="nombreCurso" id="nombreCurso1" maxlength="50" name="nombreCurso1" type="text" />
					</div>
					<div id="divLinea">
						<label>Fecha de Curso dd/mm/aaaa</label>
						<input class="fecha" id="fechaCurso1" maxlength="10" name="fechaCurso1" type="text" />
						<input class="botonFecha" id="btnFechaCurso1" name="btnFechaCurso1" type="button" value="">
					</div>
					<div id="divLinea">
						<label>Instituto</label>
						<?= $comboInstituto1->draw();?> *
					</div>
				</div>

				<div class="elementoOculto" id="curso2">
					<input id="curso2visible" name="curso2visible" type="hidden" value="f">
					<hr />
					<div id="divLinea">
						<label>Tipo de Curso</label>
						<?= $comboTipoCurso2->draw();?> *
					</div>
					<div id="divLinea">
						<label>Nombre del Curso</label>
						<input class="nombreCurso" id="nombreCurso2" maxlength="50" name="nombreCurso2" type="text" />
					</div>
					<div id="divLinea">
						<label>Fecha de Curso dd/mm/aaaa</label>
						<input class="fecha" id="fechaCurso2" maxlength="10" name="fechaCurso2" type="text" />
						<input class="botonFecha" id="btnFechaCurso2" name="btnFechaCurso2" type="button" value="">
					</div>
					<div id="divLinea">
						<label>Instituto</label>
						<?= $comboInstituto2->draw();?> *
					</div>
				</div>

				<div class="elementoOculto" id="curso3">
					<input id="curso3visible" name="curso3visible" type="hidden" value="f">
					<hr />
					<div id="divLinea">
						<label>Tipo de Curso</label>
						<?= $comboTipoCurso3->draw();?> *
					</div>
					<div id="divLinea">
						<label>Nombre del Curso</label>
						<input class="nombreCurso" id="nombreCurso3" maxlength="50" name="nombreCurso3" type="text" />
					</div>
					<div id="divLinea">
						<label>Fecha de Curso dd/mm/aaaa</label>
						<input class="fecha" id="fechaCurso3" maxlength="10" name="fechaCurso3" type="text" />
						<input class="botonFecha" id="btnFechaCurso3" name="btnFechaCurso3" type="button" value="">
					</div>
					<div id="divLinea">
						<label>Instituto</label>
						<?= $comboInstituto3->draw();?> *
					</div>
				</div>

				<div class="elementoOculto" id="curso4">
					<input id="curso4visible" name="curso4visible" type="hidden" value="f">
					<hr />
					<div id="divLinea">
						<label>Tipo de Curso</label>
						<?= $comboTipoCurso4->draw();?> *
					</div>
					<div id="divLinea">
						<label>Nombre del Curso</label>
						<input class="nombreCurso" id="nombreCurso4" maxlength="50" name="nombreCurso4" type="text" />
					</div>
					<div id="divLinea">
						<label>Fecha de Curso dd/mm/aaaa</label>
						<input class="fecha" id="fechaCurso4" maxlength="10" name="fechaCurso4" type="text" />
						<input class="botonFecha" id="btnFechaCurso4" name="btnFechaCurso4" type="button" value="">
					</div>
					<div id="divLinea">
						<label>Instituto</label>
						<?= $comboInstituto4->draw();?> *
					</div>
				</div>
				<div id="divLineaLink">
					<a href="javascript:agregarCurso()" id="agregarCurso">[+] Agregar otro curso</a>
					<a href="javascript:quitarCurso()" id="quitarCurso">[-] Quitar</a>
				</div>

				<div class="divTitulo">
					<label>ESPECIALIZACIONES</label>
				</div>
				<div id="especializacion1">
					<div id="divLinea">
						<label>Tipo</label>
						<?= $comboTipo1->draw();?> *
					</div>
					<div id="divLinea">
						<label>Elemento</label>
						<?= $comboElemento1->draw();?> *
					</div>
					<div id="divLinea">
						<label>Nivel</label>
						<?= $comboNivelEspecializacion1->draw();?> *
					</div>
				</div>

				<div class="elementoOculto" id="especializacion2">
					<input id="especializacion2visible" name="especializacion2visible" type="hidden" value="f">
					<hr />
					<div id="divLinea">
						<label>Tipo</label>
						<?= $comboTipo2->draw();?> *
					</div>
					<div id="divLinea">
						<label>Elemento</label>
						<?= $comboElemento2->draw();?> *
					</div>
					<div id="divLinea">
						<label>Nivel</label>
						<?= $comboNivelEspecializacion2->draw();?> *
					</div>
				</div>

				<div class="elementoOculto" id="especializacion3">
					<input id="especializacion3visible" name="especializacion3visible" type="hidden" value="f">
					<hr />
					<div id="divLinea">
						<label>Tipo</label>
						<?= $comboTipo3->draw();?> *
					</div>
					<div id="divLinea">
						<label>Elemento</label>
						<?= $comboElemento3->draw();?> *
					</div>
					<div id="divLinea">
						<label>Nivel</label>
						<?= $comboNivelEspecializacion3->draw();?> *
					</div>
				</div>

				<div class="elementoOculto" id="especializacion4">
					<input id="especializacion4visible" name="especializacion4visible" type="hidden" value="f">
					<hr />
					<div id="divLinea">
						<label>Tipo</label>
						<?= $comboTipo4->draw();?> *
					</div>
					<div id="divLinea">
						<label>Elemento</label>
						<?= $comboElemento4->draw();?> *
					</div>
					<div id="divLinea">
						<label>Nivel</label>
						<?= $comboNivelEspecializacion4->draw();?> *
					</div>
				</div>
				<div id="divLineaLink">
					<a href="javascript:agregarEspecializacion()" id="agregarEspecializacion">[+] Agregar otra especialización</a>
					<a href="javascript:quitarEspecializacion()" id="quitarEspecializacion">[-] Quitar</a>
				</div>

				<div id="divLinea">
					<label>Remuneración pretendida</label>
					<input id="remuneracion" maxlength="15" name="remuneracion" type="text" />
				</div>
				<div id="divLinea">
					<label>Adjuntar CV en formato .doc o .pdf</label>
					<br />
					<input class="InputText" id="cv" name="cv" type="file" />
				</div>
				<div id="divLinea">
					<label>Ingrese el captcha</label>
					<input id="captcha" name="captcha" type="text" />
					<img border="0" id="imgCaptcha" src="/functions/captcha.php" />
					<img border="0" id="imgReloadCaptcha" src="/images/reload.png" title="Recargar captcha" onClick="recargarCaptcha(document.getElementById('imgCaptcha'))" />
				</div>
				<div id="divEnviar">
					<input class="btnEnviar" id="imgEnviar" type="button" value="" onClick="enviar()" />
					<img border="0" class="elementoOculto" id="imgProcesando" src="/images/loading.gif" title="Procesando, aguarde unos segundos por favor..." />
				</div>
				<div id="divDatosObligatorios"><i>* Datos obligatorios.</i><br /></div>
				<p id="guardadoOk">Los datos fueron guardados exitosamente.</p>
				<div id="divErrores">
					<table border="1" bordercolor="#ff0000" align="center" cellpadding="6" cellspacing="0">
						<tr>
							<td>
								<table cellpadding="4" cellspacing="0">
									<tr>
										<td><img border="0" src="/images/atencion.jpg"></td>
										<td class="ContenidoSeccion">
											<font color="#000000">
												No es posible continuar mientras no se corrijan los siguientes errores:
												<br />
												<br />
												<span id="errores"></span>
											</font>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<input id="foco" name="foco" readonly type="checkbox" />
				</div>
			</div>
		</form>
		<script type="text/javascript">
			w = 520;
			h = screen.height * 0.80;
			window.resizeTo(w, h);
			window.moveTo((screen.width - w) / 2, (screen.height - h) / 2);

			Calendar.setup ({
				inputField: "fechaNacimiento",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaNacimiento"
			});
			Calendar.setup ({
				inputField: "fechaDesde1",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaDesde1"
			});
			Calendar.setup ({
				inputField: "fechaDesde2",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaDesde2"
			});
			Calendar.setup ({
				inputField: "fechaDesde3",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaDesde3"
			});
			Calendar.setup ({
				inputField: "fechaDesde4",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaDesde4"
			});
			Calendar.setup ({
				inputField: "fechaHasta1",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaHasta1"
			});
			Calendar.setup ({
				inputField: "fechaHasta2",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaHasta2"
			});
			Calendar.setup ({
				inputField: "fechaHasta3",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaHasta3"
			});
			Calendar.setup ({
				inputField: "fechaHasta4",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaHasta4"
			});
			Calendar.setup ({
				inputField: "fechaCurso1",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaCurso1"
			});
			Calendar.setup ({
				inputField: "fechaCurso2",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaCurso2"
			});
			Calendar.setup ({
				inputField: "fechaCurso3",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaCurso3"
			});
			Calendar.setup ({
				inputField: "fechaCurso4",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaCurso4"
			});
		</script>
	</body>
</html>