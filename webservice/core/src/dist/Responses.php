<?php
namespace core\src\dist;
use core\src\dist as dist;

abstract class Responses extends \core\src\Jules{

	use dist\TypeReturn{
		dist\TypeReturn::data   as private _data;
		dist\TypeReturn::text   as private _text;
		dist\TypeReturn::alert  as private _alert;
		dist\TypeReturn::waitForReply  as private _waitForReply;
	}

   /**
    * Summary of rejected
	 * Use this method to say that some validation has been rejected
    */
   static public function rejected($msg){
		throw new \core\src\ExpResponse(self::_alert(self::CODE_MSG_ERR,$msg));
   }
   /**
    * Summary of resolv
    * @param mixed $msg
    * @throws core\src\ExpResponse
    */
   static public function resolved($msg){
		throw new \core\src\ExpResponse(self::_alert(self::CODE_MSG_SUC,$msg));
   }

   /**
    * Summary of resolvedParam
    * @param mixed $msg 
    * @param mixed $param 
    * @throws \core\src\ExpResponse 
    */
   static public function resolvedParam($msg,$param){
	   throw new \core\src\ExpResponse(self::_alert(self::CODE_MSG_SUC,$msg,$param));
   }


   /**
    * Summary of object
    * @param core\src\dist\object $obj
    * @throws core\src\ExpResponse
    */
   static public function data($obj){
	   throw new \core\src\ExpResponse(self::_data($obj));
   }
   /**
    * Summary of text
    * @param mixed $text
    */
   static public function text($text){
	   throw new \core\src\ExpResponse(self::_text($text));
   }
   /**
    * Summary of waitForReply
    * @param mixed $action
    * @param mixed $description
    * @param mixed $parameters
    * @return object
    */
   static public function waitForReply($action,$authorization,$description = null,$parameters = []){
	   throw new \core\src\ExpResponse(self::_waitForReply($action,$authorization,$description,$parameters));
   }

}