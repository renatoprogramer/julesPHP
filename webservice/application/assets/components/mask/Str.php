<?php
namespace application\assets\components\mask;



class Str{



	/**
	 * Summary of dataTimeExtenso
	 * @param mixed $value 
	 * @param mixed $date 
	 * @param mixed $time 
	 * @return string
	 */
	static public function dataTimeExtenso($value,$date = true,$time = true){
		setlocale(LC_ALL, 'pt_BR.UTF8', 'pt_BR.UTF8','portuguese');
		$date = ($date)? '%d de %B de %Y' : '';
		$time = ($time)? 'às %H:%Mh':'';
		return  strftime("$date $time", strtotime($value));
	}

}