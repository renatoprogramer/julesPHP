<?php
namespace core\src\dist;
use core\src\dist as dist;
use \Exception;

abstract class ReceptorPost extends \core\src\Jules{

	public static
	$RECEPTOR_METHOD = null,
	$RECEPTOR_ARGS   = null;

	/**
	 * Summary of __construct
	 * @throws Exception
	 */
	public function __construct(){

		try{
			
			//Define header return
			header('Content-Type: application/json');
			
			//Get and check post
			$post = $this->postRequet();
			if(!is_object($post))
				throw new Exception('This post is not valid. Post: '.(string)$post);

			//Runner ...
			$response = [];
			foreach($post as $class => $methods){
				if(method_exists($this,$class)) {
					foreach($methods as $method => $args){
						try{
							self::$RECEPTOR_METHOD = $method;
							self::$RECEPTOR_ARGS   = (object) $args;
							$response[$class][$method] = null;
							$this->$class($method,$args);
						}catch(\core\src\ExpResponse $e){
							$response[$class][$method] = $e->getResponse();
						}
					}
				}
			}

			//finaly
			die(json_encode($response));

	 	}catch(\Exception $e){

			die($e->getMessage());

		}
	}

	/**
	 * Summary of getPostJson
	 */
	private function postRequet(){

		//Header
		$header  = (array) getallheaders();

		//headers method post receive
		if(!isset($header['input-application']))
			throw new \Exception('Header "input-application" not found!');

		//Switch type
		switch($header['input-application']){

			//Format mobile json for JAVA programmers or angularApp
			case 'contents':
				$request =	file_get_contents('php://input');
				$result  =  (object) json_decode($request);
				if (json_last_error() === JSON_ERROR_NONE)
					return (object) $result;
			break;
			default:
				//Default format urlencode
				return (object) $_POST;
			break;

		}

	}

}




