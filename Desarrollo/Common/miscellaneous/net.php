<?
function ipToNumber($ip) {
	$ip = explode(".", $ip);
	for ($i = 0; $i < count($ip); $i++)
		while (strlen($ip[$i]) < 3)
			$ip[$i] = '0'.$ip[$i];
	return implode($ip);
}

function ipEnRango($ip, $ipRangoDesde, $ipRangoHasta) {
	$ip = ipToNumber($ip);
	$ipRangoDesde = ipToNumber($ipRangoDesde);
	$ipRangoHasta = ipToNumber($ipRangoHasta);

	return (($ip >= $ipRangoDesde) and ($ip <= $ipRangoHasta));
}
?>