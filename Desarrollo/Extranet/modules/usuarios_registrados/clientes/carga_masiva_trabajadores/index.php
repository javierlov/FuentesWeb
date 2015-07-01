<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");

validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 55));
?>
<style>
	#divGridEspera {background-color:#0f539c; cursor:wait; display:none; filter:alpha(opacity = 20); height:432px; left:0; opacity:.1; position:absolute; top:0; width:756px;}
	#divGridEsperaTexto {background-color:#efefef; border:1px solid black; color:#000; cursor:wait; display:none; font-family:Trebuchet MS; font-size:16px; left:200px; padding:5px;
											 position:absolute; top:144px;}
</style>
<script src="/modules/usuarios_registrados/clientes/js/carga_masiva_trabajadores.js" type="text/javascript"></script>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<div class="TituloSeccion" style="display:block;">Carga Masiva de Trabajadores</div>
<div align="right" class="ContenidoSeccion" style="margin-top:5px;"><i>>> <a href="/nomina-trabajadores/terminos-y-condiciones">T�rminos y Condiciones de uso</a></i></div>
<div class="ContenidoSeccion" style="margin-top:20px;">
	<p>Utilice la herramienta de Carga masiva de trabajadores para mantener actualizada la n�mina de su empresa en forma r�pida y sencilla.</p>
	<b>Carga masiva de trabajadores PASO a PASO</b>:<br />
	1. Descargue su �N�mina Completa� declarada a la fecha en formato Excel haciendo clic en el bot�n �Bajar n�mina completa�.<br />
	2. Rev�selo e introd�zcale las modificaciones necesarias (altas, bajas o modificaciones de los datos de los trabajadores). Recuerde no alterar el formato del archivo: mantenga la extensi�n .xls y respete la cantidad de columnas y el orden de las mismas.<br />
	3. Si necesita efectuar bajas de trabajadores, simplemente ingrese la fecha de baja en la columna �Fecha Baja� (Columna H).<br />
	4. Utilice el bot�n �Examinar� para encontrar el archivo que usted acaba de crear o modificar.<br />
	5. Presione �Subir n�mina� para enviarlo.<br />
	6. Antes de importar los registros a la Base de Datos, nuestro sistema procesar� el archivo que usted suba y le mostrar� todos los datos procesados informando los registros que no pudieron ser validados correctamente y la causa de la falla en la validaci�n.<br />
	7. Revise la pantalla y presione �Importar� para impactar los datos en la Base o �Salir� si desea cancelar la operaci�n.<br />
	<p><b>Recuerde que s�lo los registros validados podr�n ser importados a la Base de Datos.</b></p>
	<img src="/modules/usuarios_registrados/images/bajar_nomina_completa.jpg" style="cursor:pointer; margin-bottom:16px; margin-top:8px;" onClick="bajarNominaCompleta(<?= $_SESSION["idEmpresa"]?>)" />
	<form action="/importacion-masiva-trabajadores" enctype="multipart/form-data" id="formArchivo" method="post" name="formArchivo">
		<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="10485760" />
		<input id="idEmpresa" name="idEmpresa" type="hidden" value="<?= $_SESSION["idEmpresa"]?>" />
		<input class="InputText" id="archivo" name="archivo" type="file" value="" />
		<img border="0" src="/modules/usuarios_registrados/images/subir_nomina.jpg" style="cursor:pointer; margin-left:16px; vertical-align:middle;" onClick="subirNomina()" />
		<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
	</form>
</div>
<div id="divGridEspera">&nbsp;</div>
<div id="divGridEsperaTexto"><img src="/images/loading.gif" style="vertical-align:middle;" />&nbsp;&nbsp;Subiendo archivo, aguarde un instante por favor...</div>