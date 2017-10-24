<?php
namespace application\database\mysql;
use application\database\mysql     as mysql;
use application\database\mysql\dao as dao;
use \PDO;
use \PDOException;


class Helloword{

	/**
	 * Summary of create
	 * @param mysql\dao\HelloWord $helloWord 
	 * @return boolean
	 */
	static public function create(dao\HelloWord $helloWord)
	{

		try{

			$sql = "INSERT INTO
						helloword (msg)
						VALUES   (:msg)";
			$conn =  mysql\Conection::run();
			$x = $conn->prepare($sql);
			$x->bindParam(':msg',$helloWord->msg, PDO::PARAM_STR);
			$x->execute();
			if($x->rowCount() > 0){

				//Set id insert 
				$helloWord->set_id($conn->lastInsertId());

				return  true;

			}

			return false;

		}catch(PDOException $e){

			return false;

		}


	}


}



