<?php
class Database {
	
	protected static $connection;
	private $HOST;
	private $USERNAME;
	private $PASSWORD;
	private $DB_NAME;
	
	public function __construct($hostname, $username, $password, $database){
		$this->HOST = $hostname;
		$this->USERNAME = $username;
		$this->PASSWORD = $password;
		$this->DB_NAME = $database;
	}
	
	public function connect(){
		if(!isset(self::$connection)){
			self::$connection = mysqli_connect($this->HOST, $this->USERNAME, $this->PASSWORD, $this->DB_NAME);
		}
		
		if (!isset(self::$connection)) {
			echo "Error: Unable to connect to MySQL." . PHP_EOL;
			echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
			echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}
		
		return self::$connection;
	}
	
	public function query($SQL){
		$connection = $this->connect();
		$result = mysqli_query($connection, $SQL) or die(mysqli_error($connection));
		return $result;
	}
	
	public function insert($SQL){
		$connection = $this->connect();
		if($this->query($SQL));
		return mysqli_insert_id($connection);
	}
	
	public function select($SQL){
		$arr_rows = [];
		$sql_rows = $this->query($SQL);
		if($sql_rows === false){
			return false;
		}
		while($row = $sql_rows->fetch_assoc()){
			$arr_rows[] = $row;
		}
		return $arr_rows;
	}
	
	public function selectOne($SQL){
		$result_set = $this->select($SQL);
		if(count($result_set) > 0){
			return $result_set[0];
		}
	}
	
    public function escape($value){
    	$connection = $this->connect();
    	return $connection->real_escape_string($value);
    }
    
	public function quote($value) {
        return "'" . $this->escape($value) . "'";
    }
}
?>