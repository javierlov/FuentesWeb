<?
function verPermisos() {
	// Solo se habilita si el usuario es del sector "An�lisis y Control de Gesti�n" o es alguno de nosotros de prueba..
	$idSector = getUserIdSectorIntranet();
	$sistemas = ((getWindowsLoginName(true) == "ALAPACO") or
							 (getWindowsLoginName(true) == "EVILA") or
							 (getWindowsLoginName(true) == "FPEREZ") or
							 (getWindowsLoginName(true) == "SGABRIELLI"));

	return (($idSector == 5014) or ($idSector == 19028) or ($sistemas));
}
?>