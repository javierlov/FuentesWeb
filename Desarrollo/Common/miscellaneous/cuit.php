<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


function ponerGuiones($cuit) {
	$result = trim($cuit);
	if (strlen($result) < 11)
		$result = str_pad($result, 11, "0", STR_PAD_LEFT);

	$result = substr($result, 0, 2)."-".substr($result, 2, 8)."-".substr($result, 10, 1);

	return $result;
}

function sacarGuiones($cuit) {
	return trim(str_replace("-", "", $cuit));
}

function validarCuit($cuit) {
	try {
		$cuit = trim($cuit);
		if (strlen($cuit) != 11)
			return false;
		else {
			for ($i=0; $i<=10; $i++)
				if (!validarNumero($cuit[$i]))
					return false;

			$suma = 0;
			for ($i=0; $i<=9; $i++)
				$suma = $suma + $cuit[$i] * substr('5432765432', $i, 1);
			$suma = 11 - ($suma % 11);

			if ($suma == 11)
				$suma = 0;

			if ($suma == 10)
				return false;
			else {
				if (($cuit[strlen($cuit) - 1] >= "0") and ($cuit[strlen($cuit) - 1] <= "9"))
					return ($suma == $cuit[strlen($cuit) - 1]);
				else
					return false;
			}
		}
	}
	catch (Exception $e) {
		return false;
	}
}
?>