<?php
namespace application\database\mysql\dao;
use application\database\mysql\dao as forekey;


class HelloWord extends \core\src\dist\Dao {

    public
    $id,
	$msg;

	public function set_id($v){
		$this->id = $v;
		return $this;
	}

	public function set_msg($msg){
		$this->msg = $msg;
		return $this;
	}


}














