<?php
/**
* <b>Database Connection</b> class.
* @author Php Object Generator
* @version 3.0d / PHP5
* @see http://www.phpobjectgenerator.com/
* @copyright Free for personal & commercial use. (Offered under the BSD license)
*/
Class Database{
	public $connection;

	//private function Database(){
	private function __Constructor () {
		$databaseName 		= $GLOBALS['configuration']['db'];
		$serverName 		= $GLOBALS['configuration']['host'];
		$databaseUser 		= $GLOBALS['configuration']['user'];
		$databasePassword 	= $GLOBALS['configuration']['pass'];
		$databasePort 		= $GLOBALS['configuration']['port'];
		//echo 'DBNAME => '.$databaseName.'<br>SERVER NAME => '.$serverName.'<br>DB PORT => '.$databasePort.'<br>DB USER => '.$databaseUser.'<br>DB PASS => '.$databasePassword; die;
		$this->connection 	= mysqli_connect ($serverName, $databaseUser, $databasePassword);
		if ($this->connection){
			if (!mysqli_select_db ($this->connection, $databaseName)){
				throw new Exception('I cannot find the specified database "'.$databaseName.'". Please edit configuration.php.');
			}
		}else{
			throw new Exception('I cannot connect to the database. Please edit configuration.php with your database configuration.');
		}
	}

	public static function Connect(){
		static $database = null;
		if (!isset($database)){
			$database = new Database();
		}
		return $database->connection;
	}

	public static function Reader($query, $connection) {
		/*echo "\n";
		echo $query.";";
		echo "\n";*/
		$cursor = mysqli_query($connection, $query);
		return $cursor;
	}

	public static function Read($cursor){
		return mysqli_fetch_assoc($cursor);
	}

	public static function NonQuery($query, $connection){
		mysql_query($query, $connection);
		$result = mysql_affected_rows($connection);
		if ($result == -1){
			return false;
		}
		return $result;

	}

	public static function Query($query, $connection){
		echo "\n";
		echo $query.";";
		echo "\n";
		
		$result = mysql_query($query, $connection);
		return mysql_num_rows($result);
	}

	public static function InsertOrUpdate($query, $connection){
		$result = mysqli_query($connection, $query);
		return intval(mysqli_insert_id($connection));
	}
}
?>
