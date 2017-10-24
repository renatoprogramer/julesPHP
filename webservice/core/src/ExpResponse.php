<?php
namespace core\src;
use core\src\dist as dist;
use Exception;

class  ExpResponse extends \Exception
{
	public  $message = '';

    public function __construct($message) {
		$this->message = $message;
    }

    public function getResponse() {
		return (object) $this->message;
    }


}





