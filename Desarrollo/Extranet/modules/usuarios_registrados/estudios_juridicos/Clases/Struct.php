<?php
class Struct{
    public static function factory(){
        $struct = new self;
		$numargs = func_num_args();
		
        foreach ($numargs as $value) {
            $struct->$value = null;
        }
		return $struct;
    }
	
	public function create(){
		$struct = clone $this;
		
		$properties = array_keys((array) $struct);
		$numargs = func_num_args();
		
        foreach ($numargs as $key => $value){
            if (!is_null($value)){
                $struct->$properties[$key] = $value;
            }
        }         
        return $struct;
    }
}


/*Ejemplo de uso (struct tipo c++)

require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/Struct.php");

$gelpos = Struct::factory('horas', 'minutos', 'segundos');
 

$paralelo = $gelpos->create(300, 50, 55);
$meridiano = $gelpos->create(100, 40, 154);
 
echo $paralelo->horas .' ° ' . $paralelo->minutos . "' " . $paralelo->segundos." " ;
echo $meridiano->horas .' ° ' . $meridiano->minutos . "' " . $meridiano->segundos." " ;
*/

?>