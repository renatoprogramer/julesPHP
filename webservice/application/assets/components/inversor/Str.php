<?php
namespace application\assets\components\inversor;

class Str{
	
	/**
	 * If $value is empty return $mixed and not empty return $value 
	 * @param mixed $value 
	 * @param mixed $mixed 
	 * @return mixed
	 */
	static public function emptyToMixed($value,$mixed){
		if(empty($value))
			return $mixed;
		return $value;
	}


}