<?php
final class MySQL {
	private $link;
	
	public function __construct($hostname, $username, $password, $database) {
		if (!$this->link = mysqli_connect($hostname, $username, $password)) {
      		trigger_error('Error: Could not make a database link using ' . $username . '@' . $hostname);
    	}

    	if (!mysqli_select_db($this->link, $database)) {
      		trigger_error('Error: Could not connect to database ' . $database);
    	}
		
		mysqli_query($this->link, "SET NAMES 'utf8'");
		mysqli_query($this->link, "SET CHARACTER SET utf8");
		mysqli_query($this->link, "SET CHARACTER_SET_CONNECTION=utf8");
		mysqli_query($this->link, "SET SQL_MODE = ''");
  	}
		
  	public function query($sql) {
		$resource = mysqli_query($this->link, $sql);

		if ($resource) {
			if (is_resource($resource)) {
				$i = 0;
    	
				$data = array();
		
				while ($result = mysqli_fetch_assoc($resource)) {
					$data[$i] = $result;
    	
					$i++;
				}
				
				mysql_free_result($resource);
				
				$query = new stdClass();
				$query->row = isset($data[0]) ? $data[0] : array();
				$query->rows = $data;
				$query->num_rows = $i;
				
				unset($data);
				
				return $query;	
    		} else {
				return true;
			}
		} else {
			trigger_error('Error: ' . mysqli_error($this->link) . '<br />Error No: ' . mysqli_errno($this->link) . '<br />' . $sql);
			exit();
    	}
  	}
	
	public function escape($value) {
		return mysqli_real_escape_string($this->link, $value);
	}
	
  	public function countAffected() {
    	return mysqli_affected_rows($this->link);
  	}

  	public function getLastId() {
    	return mysqli_insert_id($this->link);
  	}	
	
	public function __destruct() {
		if( gettype($this->link) == "resource") 
		{
			mysqli_close($this->link);
		}

		//mysql_close($this->link);
	}
	
	public function Reader($query)
	{
		$cursor = mysqli_query($this->link, $query);
		return $cursor;
	}

	public function Read($cursor)
	{
		return mysqli_fetch_assoc($cursor);
	}

	public function NonQuery($query)
	{
		mysqli_query($this->link, $query);
		$result = mysqli_affected_rows($this->link);
		if ($result == -1)
		{
			return false;
		}
		return $result;

	}
	
	public function InsertOrUpdate($query)
	{
		$result = mysqli_query($this->link, $query);
		return intval(mysqli_insert_id($this->link));
	}
}
?>