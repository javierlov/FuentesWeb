<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/encryptation.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");


function desencriptar($valor) {
	global $semilla;

	$valor = decrypt($valor, $semilla);

//	$valor = substr_replace($valor, "", 17, 1);
//	$valor = base64_decode(substr_replace($valor, strrev(substr($valor, 7, 7)), 7, 7));
	$valor = base64_decode($valor);

	return $valor;
}

function encriptar($valor) {
	global $semilla;

	// Inserto una pseudoencriptación ademas del base64..
	$valor = base64_encode($valor);
/*	$valor = substr_replace($valor, strrev(substr($valor, 7, 7)), 7, 7);

	$nums = date("His");
	if (($nums[0] % 2) == 0)
		$extraNum = 0;
	else
		$extraNum = 9;
	for ($i=1; $i<6; $i++)
		if (($nums[0] % 2) == 0) {
			if ($nums[$i] > $extraNum)
				$extraNum = $nums[$i];
		}
		else {
			if ($nums[$i] < $extraNum)
				$extraNum = $nums[$i];
		}
	$valor = substr_replace($valor, substr($valor, 16, 1).$extraNum, 16, 1);
*/
	return encrypt($valor, $semilla);
}


$semilla = "EVILA";
?>