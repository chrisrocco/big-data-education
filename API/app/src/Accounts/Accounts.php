<?php
class Accounts {
	
	private $database;
	
	function __construct($database){
		$this->database = $database;
	}
	
	/**
	* Returns account object if exsists
	*/
	function login($email, $password){
		$email = $this->database->quote($email);
		$password = $this->database->quote($password);
// 		$accounts = $this->database->select("SELECT * FROM accounts WHERE email=$email AND password=$password AND active='1'"); Disabling account activation requirement
		$accounts = $this->database->select("SELECT * FROM accounts WHERE email=$email AND password=$password");
		if($accounts){
			return $accounts[0];
		}
	}
	
	/**
	* Unsets session variables
	*/
	function logout(){
		$result = false;
		if(isset($_SESSION["ID"]) && isset($_SESSION["email"]) && isset($_SESSION["password"])){
			unset($_SESSION["ID"]);
			unset($_SESSION["email"]);
			unset($_SESSION["password"]);
			unset($_SESSION["name"]);
			unset($_SESSION["role"]);
			unset($_SESSION["login_time"]);
			$result = true;
		}
		return $result;
	}
	/**
	* Returns true if the given email is registered to an account
	*/
	function exists($email){
		$email = $this->database->quote($email);
		$result = $this->database->select("SELECT * FROM accounts WHERE email=$email");
		return (count($result) > 0);
	}
	
	/**
	* Creates an inactive account.
	* @return the created account's ID
	*/
	public static $ALREADY_EXISTS_ERROR = -2;
	function register($name, $email, $password, $role){
		
		// Make sure account doesn't already exsists
		if($this->exists($email)){
			return self::$ALREADY_EXISTS_ERROR;
		}
		
		// Escape input
		$email = $this->database->quote($email);
		$password = $this->database->quote($password);
		$name = $this->database->quote($name);
		$role = $this->database->quote($role);
		
		// Insert into database
		$sql = "INSERT INTO accounts";
		$sql .= " (email, password, name, roleID, active, hash, date_created)";
		$sql .= " VALUES ($email, $password, $name, $role, '0', ' ', now())";
		return ($this->database->insert($sql));
	}
	
	/**
	* Enrolls a student in a class
	*/
	function enroll($student_ID, $class_code){
		// Make sure class exsists
		$exsistSet = $this->database->select("SELECT * FROM classes WHERE class_code='$class_code'");
		if(count($exsistSet) == 0) return false;
		
		$student_ID = $this->database->quote($student_ID);
		$class_code = $this->database->quote($class_code);
		$sql = "INSERT INTO enrollments (studentID, class_code) VALUES ($student_ID, $class_code)";
		return $this->database->query($sql);
	}
	
	/**
	* Sends a validation link
	*/
	function sendValidation($email, $link){
		$hash = md5(rand(0,1000));
		$this->database->query("UPDATE accounts SET hash='$hash' WHERE email='$email'");	
		// send message
		$headers =  'MIME-Version: 1.0' . "\r\n"; 
		$headers .= 'From: Big Data <info@bigdata.com>' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 
		$subject = "BigDataEd | Validate Account";
		$body = "to validate your account, please use this link: $link?hash=$hash";
		$result = mail($email, $subject, $body, $headers);
		
	}
	
	/**
	* Validates an inactive account
	*/
	function validate($hash){
		$accounts = $this->database->select("SELECT * FROM accounts WHERE hash='$hash'");
		$account = $accounts[0];
		$accountID = $account['uniqueID'];
		return $this->database->query("UPDATE accounts SET active = '1' WHERE uniqueID='$accountID'");
	}
	
	/**
	* Resets hash and send validation email
	*/
	function sendRecovery($email){
		$email = $this->database->escape($email);
		
		$accounts = $this->database->select("SELECT uniqueID FROM accounts WHERE email='$email'");
		$accountID = $accounts[0];
		
		$hash = md5(rand(0,1000));
		$result = $this->database->query("UPDATE accounts SET hash='$hash' WHERE email='$email'");
	
		return mail($email, "BigDataEd | Reset Password", "to reset your password, please use this link: http://www.chrisrocco.net/bigdataed/resetPassword.php?hash=$hash");
	}
	
	/**
	* Updates password of account with given hash
	*/
	function resetPassword($hash, $newPassword){
		return $this->database->query("UPDATE accounts SET password = '$newPassword' WHERE hash='$hash'");
	}
}
?>