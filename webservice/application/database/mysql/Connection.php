<?php
namespace application\database\mysql;
use \PDO;
use \PDOException;

//Conection db
$OPENConnection = null;


class Connection{

	/**
	 * Summary of DB_NAME_MAIN_MYSQL
	 * db main name
	 */

	const DB_HOST = "localhost";
	const DB_NAME = "";
	const DB_USER = "";
	const DB_PASS = "";

    /**
     * Summary of run
     * @return PDO
     */
    public static function run(){

		try {

			//use global
			global $OPENConnection;

			//Check has conection open
			if($OPENConnection != null)
				return $OPENConnection;

            $conn = new \PDO('mysql:host='+self::DB_HOST+';dbname='.self::DB_NAME, self::DB_USER, self::DB_PASS);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			//Set conection
			$OPENConnection = $conn;

            return $conn;

		}catch(\PDOException $e) {

            die('@error: mysql connection fail.');

        }

    }


}
