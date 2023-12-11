<?php 
class DB {

	protected static $instance;

	protected function __construct() {}
	protected function __clone() {}
	protected function __wakeup() {}

	public static function getInstance() {

		if(empty(self::$instance)) {

			try{
                self::$instance = new PDO('mysql:host=localhost;dbname=auction','','');
            }
            catch(PDOException $e){
                echo 'Error : '.$e->getMessage();
            }

		}

		return self::$instance;
	}
}
?>