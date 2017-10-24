<?php
namespace  application\managers;
use core;
use \Exception;
use \ErrorException;




/**
 * Summary of Files
 * Usage in php
 *
		$imagens = new managers\Files();
		$imagens->setFileTag('images')
		->setFormatsAllowed(['jpg','png','jpej'])
		->setLocalSave('../')
		->execute();
		if($imagens->hasError)
		self::response(self::alert('error',$imagens->errorMsg),false);

   format use in javascript
   	  $_FILES[

           'tag:imagem:984754hdjfd=' => [

					flies parametrs ....

					]

	  ]



 */
class Files{

	//set
	protected
		$fileTag,
		$localSave,
		$formatsAllow,
		$maxFiles = 10;

	//patterns
	private
		$patternFormat = '/(tag:)([a-zA-Z]+)(:)([a-zA-Z-0-9\=]+)/', //Format name files in $_FILES
		$patternGetFormatFile = '/[^"\\\\]*\.(\w+)$/';//Get format file

	//file success save
	public
		$filesSalve = [];

	//menssage error
	public
		$errorMsg,
		$hasError = false;


	/**
	 * Summary of setFileTag
	 * @param mixed $tag
	 * @return Files
	 */
	public function setFileTag($tag){
		$this->fileTag = $tag;
		return $this;
	}
	/**
	 * Summary of setLocalSave
	 * @param mixed $localSave
	 */
	public function setLocalSave($localSave){
		$this->localSave = $localSave;
		return $this;

	}
	/**
	 * Summary of setFormatsAllowed
	 * @param mixed $formats
	 */
	public function setFormatsAllowed(array $formats){
		$this->formatsAllow = $formats;
		return $this;
	}
	/**
	 * Summary of setMaxFiles
	 * @param mixed $max
	 */
	public function setMaxFiles($max){
		$this->maxFiles = (int) $max;
		return $this;
	}
	/**
	 * Summary of execute
	 */
	public function execute(){

		try{
			if(count($_FILES) <= $this->maxFiles){
				//Tenta salvar arquivos
				foreach($_FILES as $file => $fileInfo){

					$fileInfo = (object) $fileInfo;

					//Check format name file
					if(!preg_match($this->patternFormat,$file,$match) === FALSE){
						//Get tag
						$tag = $match[2];

						//check tag is expected
						if($this->fileTag == $tag){

							//get extencion file
							if(preg_match($this->patternGetFormatFile,$fileInfo->name,$matchExtencion)){

								//Extension file
								$extension = $matchExtencion[1]; //ex: jpg,gif,exe,bat,php

								if(in_array($extension,$this->formatsAllow)){

									//Make name directory + name file + file extension
									$newFileDirectory = $this->generateNameFile($this->localSave,$extension);

									//move file to location destination
									if(move_uploaded_file($fileInfo->tmp_name,$newFileDirectory)){

										//files success
										array_push($this->filesSalve,$newFileDirectory);

									}else{

										throw new \Exception("Ocorreu um erro interno e não foi possivel salvar os arquivos!");

									}


								}else{

									throw new \Exception("Formato do arquivo ".$fileInfo->name." não é valido!");

								}

							}

						}

					}else{

						throw new \Exception("Formato de captura da TAG esta incorreto!");

					}

				}
		    	}else{

					throw new \Exception('O  numeros maximo de upload é de '.$this->maxFiles.' arquivos.');

				}

	  	     }catch(Exception $e){

			  $this->errorMsg = $e->getMessage();
			  $this->hasError = true;
			  return false;

		    }finally{

			  return true;
		 }


	}
	/**
	 * Summary of generateNameFile
	 * @param mixed $name
	 * @param mixed $extension
	 */
	private function generateNameFile($directory,$extension){
		return  $directory.bin2hex(openssl_random_pseudo_bytes(10)).'.'.$extension;
	}
	/**
	 * Summary of nameFileToString
	 * @param mixed $separator
	 * @return mixed
	 */
	public function  nameFilesToString($separator = ','){
		$fileStings = implode($separator,$this->filesSalve);
		return  str_replace($this->localSave,'',$fileStings);
	}
	/**
	 * Summary of destroyFiles
	 */
	public function destroyFiles(){
		try{
			foreach($this->filesSalve as $files){
				@unlink($files);
			}
		}catch(Exception $e){
			return false;
		}
		return true;
	}
	/**
	 * Summary of __destruct
	 */
	public function __destruct(){
		//If has error delete all files save success
		if($this->hasError){

			$this->destroyFiles();

		}

	}


}