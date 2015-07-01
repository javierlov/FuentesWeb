<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php");

@session_start(); 

ValidarUserSession();
$menues = ObtenerPermisosMenu($_SESSION["idUsuario"]);

?>
<table class="table_General" align="left">
	<tr>
		<td class="TituloSeccion" colspan="2" height="22">Administración para Estudios Jurídico</td>
	</tr>
	<tr>
		<td height="5" colspan="2"></td>
	</tr>
	<tr>
		<td width="2%"></td>
		<td height="5" width="95%">
			<div align="left">
				<table class="table_General" align="left">
					<tr>
						<td class="ContenidoSeccion" colspan="3">
							<p style="margin-top: 0; margin-bottom: 0">
							<p style="margin-top: 0; margin-bottom: 0">
							<p style="margin-top: 0; margin-bottom: 0">
							<p style="margin-top: 0; margin-bottom: 0">
						</td>
					</tr>
					<tr>
						<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" colspan="3"></td>
					</tr>
<!-- 
Menues en pantalla principal	

					<tr>
						<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="22"></td>
						<td width="284" valign="top">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<?php 
								// if(in_array("1", $menues)){ 
							?>
								<tr>
									<td style="border-bottom: 1px dotted #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="284">
										<a href="/JuiciosParteDemandada"><img border="0" 
										src="/modules/usuarios_registrados/estudios_juridicos/images/juiciospartedemandada.jpg"></a>										
									</td>
								</tr>								
							<?php 
								//}	if(in_array("2", $menues)){ 
							?>
								<tr>
									<td style="border-bottom: 1px dotted #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="284">
										<a href="/modules/usuarios_registrados/estudios_juridicos/redirect.php?ConcursoyQuiebrasGrid">
										<img border="0" src="/modules/usuarios_registrados/estudios_juridicos/images/ConcursosQuiebras.jpg"></a>
									</td>
								</tr>
							<?php 
								//}	if(in_array("3", $menues)){ 
								//CAMBIO PAG 94=101 -->
							?>
								<tr>
									<td style="border-bottom: 1px dotted #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; 
									padding-bottom: 1px" width="284">
										<a href="/index.php?pageid=101"><img border="0" 
										src="/modules/usuarios_registrados/estudios_juridicos/images/JuiciosParteActora.jpg"></a>
									</td>
								</tr>
							<?php 
								//} if(in_array("4", $menues)){ 
							?>
								<tr>
									<td style="border-bottom: 1px dotted #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="284">
										<a href="/ChequesDisponibles"><img border="0" 
										src="/modules/usuarios_registrados/estudios_juridicos/images/ChequesDisponibles.jpg"></a>
									</td>
								</tr>
							<?php 
								//}  
							?>
								<tr>
									<td height="80">
										<noscript>
											<span style="color:#f00; font-size:11px;">
												Usted tiene JavaScript desactivado.<br />
												Para navegar correctamente por el sitio web debe tener activado JavaScript.<br />
												Haga <a class="linkSubrayado" href="/javascript" target="_blank">clic aquÃ­</a> para conocer mas.</span>
										</noscript>
									</td>
								</tr>
							</table>
						</td>
						-->						
						<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="335">
						
						</td>
					</tr>
					<tr>
						<td class="ContenidoSeccion" width="644" colspan="3"></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
