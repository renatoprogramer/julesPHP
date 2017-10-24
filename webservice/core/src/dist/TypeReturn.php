<?php
namespace core\src\dist;

trait  TypeReturn {

	static public function  alert(int $tipo,$msg,$param = []){
		$tipo = ($tipo < 0 || $tipo > 3)? 0 : $tipo;
		$type = ['error','success','warning','info'];
		return  (array) ['alert'=>['tipo'=>$type[$tipo],'msg'=>$msg,'param'=>$param]];
	}

	static public function  data($data){

		if(is_object($data) || is_array($data)){

			return (array) ['data' => $data];

		}

		return (array) ['data' => 'Error: param not is type object||array!'];


	}

	static public function  text($text){

		if(is_string($text)){

			return (array) ['text'=> $text];

		}

		return (array) ['text'=> 'Error: param not is type string!'];


	}

	static public function  custom($data){

		return (array) ['custom' => $data];

	}

	static public function  waitForReply($action,$authorization,$description=null,$parameters = []){

			return (object) ['waitForReply'=> [
								'action'	   => $action,
								'authorization'=> $authorization,
								'description'  => $description,
								'parameters'   => $parameters
							]];

	}

}