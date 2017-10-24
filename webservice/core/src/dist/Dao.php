<?php
namespace core\src\dist;
use \DateTime;

abstract class Dao extends \core\src\Jules{

	protected  function varchar($v,$len = 255){
		if(strlen($v) <= $len && $len <= 255)
			return true;
		else
			return false;
	}

	protected  function longText($v,$len = (2 ^ 32 - 1)){
		if(strlen($v) <= $len && $len <= (2 ^ 32 - 1))
			return true;
		else
			return false;
	}

	protected  function text($v,$len = 65.535){
		if(strlen($v) <= $len && $len <= 65.535)
			return true;
		else
			return false;
	}

	protected  function int($v,$len = 11){
		if(strlen($v) <= $len || $len <= 11)
			return true;
		else
			return false;
	}

	protected  function boolean($v){
		switch($v){
			case true:
			case 'on':
			case '1':
			case 'active':
				return true;
			break;
			case false:
			case 'off':
			case '0':
			case 'desactive':
				return true;
			break;
			default:
				return false;
			break;
		}
	}

	protected  function date($v){
		$d = \DateTime::createFromFormat('Y-m-d', $v);
		$date = $d->format('Y-m-d');
		if($d != false && $date != false)
			return true;
		else
			return false;
	}

	protected  function datetime($v){
		$d = \DateTime::createFromFormat('Y-m-d H:i:s', $v);
		$date = $d->format('Y-m-d H:i:s');
		if($d != false && $date != false)
			return true;
		else
			return false;
	}

	protected  function time($v){
		$d = \DateTime::createFromFormat('H:i:s', $v);
		$date = $d->format('H:i:s');
		if($d != false && $date != false)
			return true;
		else
			return false;
	}

	protected  function json($v){
		$result = json_decode($v);
		if (json_last_error() === JSON_ERROR_NONE)
			return false;

		return true;

	}


}