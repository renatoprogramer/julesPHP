<?php
namespace core\src\dist;

/**
 * Usage string:
 * $require = 'name,lastname,age';
 * $params	= ['name'=>'Julesca','lastname'=>'junior','age'=>25]
 * $args	= new RequireArgs($require,$params);
 *
 * Usage array:
 * $require = 'peoples[{name,lastname}]';
 * $params	= ['peoples'=>[['name'=>'Julesca','lastname'=>'junior'],['name'=>'Champola','lastname'=>'junior']]]
 * $args	= new RequireArgs($require,$params);
 *
 * Usage object:
 * $require = 'peoples{name,lastname}';
 * $params	= ['peoples'=>['name'=>'Julesca','lastname'=>'junior']]
 * $args	= new RequireArgs($require,$params);
 *
 * Flags conversion
 * Use flags global to conversion values
 *
 *	Flags:
 *
 *	 FLAG_STRING		=  0;
 *	 FLAG_INT		=  1;
 *	 FLAG_BOOLEAN	=  2;
 *	 FLAG_FLOAT		=  3;
 *	 FLAG_DECIMAL	=  4;
 *	 FLAG_OBJECT	    =  5;
 *	 FLAG_ARRAY		=  6;
 *	 FLAG_SCP_STRING =  7;//Scape string
 *	 FLAG_AUTO		=  null;
 *
 * Usage flag in string|object|array:
 *
 * $require = 'name,lastname,age';
 * $params	= ['name'=>'Julesca','lastname'=>'junior','age'=>25]  <------- observe
 * $args	= new RequireArgs($require,$params,\RequireArgs::FLAG_INT);<----  flag INT
 * @output:
 *	['name'=> 0 ,'lastname'=> 0,'age'=>25]
 *
 */
class RequireArgs{

	//Set
    private
    $require,
    $parameters,
	$flag,
    $matchArray  = [],
    $matchObject = [],
    $matchString = [];

	//Flags
	const FLAG_STRING	  =  0;
	const FLAG_INT		  =  1;
	const FLAG_BOOLEAN	  =  2;
	const FLAG_FLOAT	  =  3;
	const FLAG_DECIMAL	  =  4;
	const FLAG_OBJECT	  =  5;
	const FLAG_ARRAY	  =  6;
	const FLAG_SCP_STRING =  7;//Scape string
	const FLAG_AUTO		  =	 null;

    public function __construct($parameters){

		//Set
		if(is_object($parameters) || is_array($parameters))
			$this->parameters = $parameters;
		else
			die('Require args not working! Parameters not is valid.');

    }

	//Fetch

	/**
	 * Busca nos argumentos o $require e verifica/converte as informações caso não for satifeito é emitido um
	 * exception
	 * @param mixed $require ex: id,people[{name,lastname}],address{street,city,country}
	 * @param mixed $flagConversion
	 * @return RequireArgs
	 */
	public function  fetch($require,$flagConversion = self::FLAG_AUTO){

		$this->require	  = $require;
		$this->flag		  = $flagConversion;

		//Match	explode require	 and proccess

		//step1
	    if($this->matchArray())
			$this->processArray();

		//step2
		if($this->matchObject())
			$this->processObject();

		//step3
		if($this->matchString())
			$this->processString();


		//ok! use get parameters ;-)
		return $this->parameters;

	}

	/**
	 * Summary of fetchStr
	 * @param mixed $requires ex: name,lastname,age
	 * @param mixed $flagConversion
	 * @return RequireArgs
	 * @expected name,lastname,age
	 */
	public function fetchStr($requires,$flagConversion = self::FLAG_AUTO){

		$this->require	  = $requires;
		$this->flag		  = $flagConversion;

		//Match	explode require	 and proccess

		//clear ...
		//step1
		$this->matchArray();
		//step2
		$this->matchObject();

		//proccess
		//step3
		if($this->matchString())
			$this->processString();


		//ok! use get parameters ;-)
		return $this->parameters;

	}

	/**
	 * Summary of fetchObj
	 * @param mixed $name ex: people
	 * @param mixed $require ex: name,lastname,age
	 * @param mixed $flagConversion
	 * @return RequireArgs
	 * @expected people{name,lastname,age}
	 */
	public function fetchObj($name,$require,$flagConversion = self::FLAG_AUTO){

		$this->require	  = "$name{$require}";
		$this->flag		  = $flagConversion;

		//Match	explode require	 and proccess

		//clear ...
		//step1
		$this->matchArray();

		//proccess

		//step2
		if($this->matchObject())
			$this->processObject();


		//ok! use get parameters ;-)
		return $this->parameters;


	}

	/**
	 * Summary of fetchArr
	 * @param mixed $name ex: people
	 * @param mixed $require ex: name,lastname,age
	 * @param mixed $flagConversion
	 * @return RequireArgs
	 * @expected  people[{name,lastname,age}]
	 */
	public function fetchArr($name,$require,$flagConversion = self::FLAG_AUTO){

		$this->require	  = "$name\r[{$require}\r]";
		$this->flag		  = $flagConversion;

		//Match	explode require	 and proccess

		//clear ...
		//step1
		if($this->matchArray())
			$this->matchArray();

		//ok! use get parameters ;-)
		return $this->parameters;


	}


	//Process

	private function  processArray(){

		try{

			foreach($this->matchArray as $mtcName => $mtcParam){

				//Check exist param
				if(!isset($this->parameters->$mtcName))
					throw new \Exception("Array parameter not found: ['$mtcName']");

				foreach($this->parameters->$mtcName as $parKey => $parParam){

					//computa as diferença
					$diff = array_diff_key($mtcParam,$parParam);
					if(count($diff) > 0)
						throw new \Exception("Array parameter not found: $mtcName\r[".implode(',',array_flip($diff))."]");

					//Apply conversion of parameters
					foreach($parParam as $parParamKey => $parParamValue)
						$this->parameters->$mtcName[$parKey][$parParamKey] =  $this->applyFlag($parParamValue);

				}

			}

		}
		catch(\Exception $e){
			die($e->getMessage());
		}

	}

	private function  processObject(){
		try{

			foreach($this->matchObject as $mtcName => $mtcParam){

				//Check exist param
				if(!isset($this->parameters->$mtcName))
					throw new \Exception("Object not found: $mtcName\r{...}");


				//computa as diferença
				$diff = array_diff_key($mtcParam,(array)$this->parameters->$mtcName);
				if(count($diff) > 0)
					throw new \Exception("Object parameter not found: $mtcName\r[".implode(',',array_flip($diff))."]");


				//Apply conversion of parameters
				foreach($this->parameters->$mtcName as $parParamKey => $parParamValue)
					$this->parameters->$mtcName->$parParamKey =  $this->applyFlag($parParamValue);


			}

		}
		catch(\Exception $e){
			die($e->getMessage());
		}
	}

	private function  processString(){

		try{

			foreach($this->matchString as $mtcName => $mtcValue){

				//Check exist param
				if(!isset($this->parameters->$mtcName))
					throw new \Exception("String parameter not found: ['$mtcName']");

				//Apply conversion of parameters
				$this->parameters->$mtcName =  $this->applyFlag($this->parameters->$mtcName);

			}

		}
		catch(\Exception $e){
			die($e->getMessage());
		}

	}

	//Matchs

    private function matchArray(){

        //Regex Match
        if(preg_match_all('/([a-zA-Z0-9]+)(\[\{([a-zA-Z0-9,]+)\}\])/',trim($this->require),$match)){

			//Match result
			$matchResult = [];

            //Extractor nameObject + atributes  " ex: object[ name => [att1,attr2] ];	"
            for($i=0; $i < count($match[0]);$i++){

				//remove require
				$this->require = str_replace($match[0][$i].',','',$this->require);

                //Set values
                $name  = (string) $match[1][$i]; // name
                $attrs = $match[3][$i]; //attr

				//remove require
				$this->require = preg_replace("/($name)(\[\{($attrs)\}\])\,?/",'',$this->require);

				//Set in match
                $matchResult[$name]= array_flip(explode(',',$attrs));

            }

			//Set match
			$this->matchArray = $matchResult;

			//Match result
			return true;

        }

        return false;

    }

    private function matchObject(){

        //Regex Match
        if(preg_match_all('/([a-zA-Z0-9]+)(\{([a-zA-Z0-9,]+)\})/',trim($this->require),$match)){

			//Match result
			$matchResult = [];

            //Extractor nameObject + atributes  " ex: object[ name => [att1,attr2] ];	"
            for($i=0; $i < count($match[0]);$i++){

                //Set values
                $name  = (string) $match[1][$i]; // name
                $attrs = $match[3][$i]; //attr

				//remove require
				$this->require = preg_replace("/($name)(\{($attrs)\},?)/",'',$this->require);

				//Set in match
                $matchResult[$name] = array_flip(explode(',',$attrs));

            }

			//Set match
			$this->matchObject = $matchResult;

			//Match result
			return true;

        }

        return false;

    }

    private function matchString(){

        //Regex Match
        if(preg_match_all('/([a-bA-z0-9]+)(,[a-bA-z0-9]+)*/',trim($this->require),$match)){

			//Match result
			$matchResult = [];

			//Extractor nameObject + atributes  " ex: object[ name => [att1,attr2] ];	"
			for($i=0; $i < count($match[0]);$i++){

				//Set values
				$name  = (string) $match[1][$i]; // name

				//remove require
				$this->require = preg_replace("/($name)\,?/",'',$this->require);

				//Set in match
				$matchResult = array_flip(explode(',',$name));

			}

			//Set match
			$this->matchString = $matchResult;

			//Match result
			return true;

        }

        return false;

    }


	//Flag conversion

	private function applyFlag($mixed){

		switch($this->flag){

			case self::FLAG_STRING:		return	$this->ftcString($mixed);		break;
			case self::FLAG_INT:		return	$this->ftcInt($mixed);			break;
			case self::FLAG_BOOLEAN:	return	$this->ftcBoolean($mixed);		break;
			case self::FLAG_FLOAT:		return	$this->ftcFloat($mixed);		break;
			case self::FLAG_DECIMAL:	return	$this->ftcDecimal($mixed);		break;
			case self::FLAG_OBJECT:		return	$this->ftcObject($mixed);		break;
			case self::FLAG_ARRAY:		return	$this->ftcArray($mixed);		break;
			case self::FLAG_SCP_STRING:	return	$this->ftcScpString($mixed);	break;
			case self::FLAG_AUTO:		return	$this->ftcAuto($mixed);			break;

		}

	}

	private function ftcString($v){
		return (string) strip_tags(trim($v));
	}

	private function ftcInt($v){
		return (int) $v;
	}

	private function ftcBoolean($v){
		switch($v){
			case 'on':
			case 'true':
			case '1':
			case 'active':
			 return true;
			break;
			default:
				return false;
			break;

		}
	}

	private function ftcFloat($v){
		return (float) $v;
	}

	private function ftcDecimal($v){
		return (double) $v;
	}

	private function ftcObject($v){
		return (object) $v;
	}

	private function ftcArray($v){
		return (object) $v;
	}

	private function ftcScpString($v){
		return (string) $v;
	}

	private function ftcAuto($v){

		if(is_string($v))
			return $this->ftcString($v);
		elseif(is_int($v))
			return $this->ftcInt($v);
		elseif(is_bool($v))
			return $this->ftcBoolean($v);
		elseif(is_array($v))
			return $this->ftcArray($v);
		elseif(is_float($v))
			return $this->ftcFloat($v);
		elseif(is_double($v))
			return $this->ftcDecimal($v);
		elseif(is_object($v))
			return $this->ftcObject($v);

		//?? type not found
		die("Type not found: ".@json_encode($v));

	}


	//Exception

	//private  function exception(\Exception $e){

	//    if($this->fetchException){

	//        die($e->getMessage());

	//    }

	//}



}