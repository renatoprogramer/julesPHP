<?php
require 'core/autoload.php';
use core\src\dist\RequireArgs	    as RequireArgs;
use application\treatments	    as treatment;
use application\managers	    as managers;
use application\database\mysql\dao  as dao;
use application\rules	     	    as rules;


class Registration extends \core\src\dist\ReceptorPost{

	public function helloWordLet(){

		//Isset to objects
		$Args = new RequireArgs(self::$RECEPTOR_ARGS);

		switch(self::$RECEPTOR_METHOD){

			case 'setHelloWord':

				//Require post ARGS
				$str = $Args->fetch('msg');

				$hello	= new dao\HelloWord();
				$hello->set_msg($str->msg);


				//Rule..
				rules\HelloWord::create($hello);

			break;

		}

	}

}
new Registration();
