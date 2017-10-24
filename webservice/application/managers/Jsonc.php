<?php
namespace  application\managers;
/**
		$compress =  converter\Jsonc::Compress()
		->setColumns('id,name,lastname,email')
		->setClass(__CLASS__)
		->setCallBack(['name' => 'mask_mail']) ou ->setCallBack(['name' => function($value){return $value}])
		->setData($data)
		->execute();
 */
class Jsonc{

	private $columns;
	private $data;
	private $callbacks;
	private $class;
	private $dynamic;
	private $result;

	public static function Compress(){
		return new self;
	}

	public static function UnCompress(){

		//not implement
		return 'fail: not implement';

	}

	public function setColumns($columns){
		$this->columns = $columns;
		$this->makeColumnsObj();
		return $this;
	}

	public function setData($data){
		$this->data = $data;
		return $this;
	}

	public function setCallBack($callback){
		$this->callbacks = $callback;
		return $this;
	}

	public function setClass($class){
		$this->class = $class;
		return $this;
	}

	public function setColumnsDynamic($data){
		$this->dynamic = $data;
		return $this;
	}

	public function execute(){
		$countR = 0;
		$countD = 0;
		if(is_object($this->data) || is_array($this->data)){

			//SET COLUMNS
			foreach($this->result as $column => $value){

				foreach($this->data as $key => $obj){

					foreach($obj as $columnD => $valueD){

						if($countD == $countR){

							$this->data[$key] = (array) $this->data[$key];

							//callback set value
							$this->data[$key][$columnD] = $this->executeCallback($column,$valueD);

							//final
							array_push($this->result->$column,$this->data[$key][$columnD]);

						}

						$countD++;

					}

					$countD = 0;

				}

				$countR++;

			}

			//SET COLUMNS DINAMICS

			if(is_array($this->dynamic) || is_object($this->dynamic)){

				foreach($this->dynamic as $name => $value){
					//create column
					if(!isset($this->result->$name))
						$this->result->$name = [];
					//create row
					for($i = 0; $i < count((array)$this->data); $i++){
						if(is_callable($value)){
							//row function return
							array_push($this->result->$name,call_user_func_array($value,[(object)$this->data[$i]]));
						}else{
							//row simple value
							array_push($this->result->$name,$value);
						}
					}
				}

			}

			return $this->result;

		}

	}

    /**
	 * Summary of makeColumnsObj
	 * Nesta função  é feito a conversão das colunas separadas por virgula em um objeto
	 * ["coluna" => []]
	 */
    private function makeColumnsObj(){
	  $this->result = (object) array_map(function($value){return [];},array_flip(explode(',',$this->columns)));
	}

	/**
	 * Summary of executeCallback
	 * @param mixed $column
	 * @param mixed $value
	 * @return mixed
	 */
	private function executeCallback($column,$value){

		if(is_array($this->callbacks) || is_object($this->callbacks)){

			if(isset($this->callbacks[$column])){

				if(is_callable($this->callbacks[$column])){
					return $this->callbacks[$column]($value);
				}else{
					return call_user_func($this->makeClassCallback().$this->callbacks[$column],$value);
				}

			}

		}

		return $value;

	}

	/**
	 * Summary of makeClassCallback
	 * @return mixed
	 */
	private function makeClassCallback(){

		if(!empty($this->class)){

			return $this->class.'::';

		}

		return '';

	}


}
