<?php
namespace application\rules;
use application\database\mysql\dao as dao;
use application\database\mysql     as database;
use application\managers		   as managers;
use core\src\dist\Responses		   as Response;
use application\dictionary		   as dictionary;
use application\assets\components  as components;

class HelloWord{

	/**
	 * Summary of create
	 * @param dao\HelloWord $helloword
	 */
	public static function create(dao\HelloWord $helloword){

		//Save in DB
		if(database\Helloword::create($helloword)){
			
			//Ok
			Response::resolved("Saved successfully!");

		}

		//Error
		Response::resolved("Unable to save!");

	}

}