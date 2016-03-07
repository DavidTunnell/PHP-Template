<?php
define('dbax', true); 
include 'app/database.php';
class auth {
	private $_siteKey;

	public function __construct()
  	{	
  		if (!defined('aux')) { exit; }
  		//randomly generated salt key - function below
  		$config = parse_ini_file('config.ini'); 
		$this->_siteKey = $config['salt'];
	}

	public function createUser($email, $password, $is_admin = 0)
	{			
		//Generate users salt
		$user_salt = $this->genSalt();
				
		//Salt and Hash the password
		$password = $user_salt . $password;
		$password = $this->hashData($password);
				
		//Create verification code
		$verificationCode = $this->genSalt();

		//Commit values to database
		$db = new Db(); 
		//to bypass active/verified make 1,1 in INSERT @ is_verified, is_active - added for extensibility
		$createdUser = $db->db_query('INSERT INTO users (email, password, user_salt, is_verified, is_active, is_admin, verification_code) VALUES (' . $db->db_quote($email) . ', ' . $db->db_quote($password) . ', ' . $db->db_quote($user_salt) . ', 1, 1, ' . $is_admin . ', '. $db->db_quote($verificationCode) . ');');

		if($createdUser != null){
			return true;
		}	
		return false;
	}

	public function login($email, $password)
	{
	    //Select users row from database base on $email
		$db = new Db(); 
		$selection = $db->db_select('SELECT * FROM users WHERE email = ' . $db->db_quote($email));
		require 'app/FirePHPCore/fb.php';
		ob_start();
		$firephp = FirePHP::getInstance(true);
		//$firephp->log($selection[0]);
		if($selection == null){
			//no email in database
			return 5;
		}
		//Salt and hash password for checking
		$password = $selection[0]['user_salt'] . $password;
		$password = $this->hashData($password);
			
		//Check email and password hash match database row
		$match = $db->db_select('SELECT * FROM users WHERE email = ' . $db->db_quote($email) . ' AND password = ' . $db->db_quote($password));

		//Convert to boolean
		$is_active = (boolean) $selection[0]['is_active'];
		$verified = (boolean) $selection[0]['is_verified'];
		if($match != null) {
			if($is_active == true) {
				if($verified == true) {
					//Email/Password combination exists, set sessions
					//First, generate a random string.
					$random = $this->genSalt();
					//Build the token
					$token = $_SERVER['HTTP_USER_AGENT'] . $random;
					$token = $this->hashData($token);
						
					//Setup sessions vars
					session_start();
					$_SESSION['token'] = $token;
					$_SESSION['user_id'] = $selection[0]['id'];
						
					//Delete old logged_in_member records for user - also use this on logout function
					$deleted = $db->db_query('DELETE FROM logged_in_member WHERE user_id = ' . $selection[0]['id']);

					//Insert new logged_in_member record for user
					$inserted = $db->db_query('INSERT INTO logged_in_member (user_id, session_id, token) VALUES (' . $db->db_quote($selection[0]['id']) . ', ' . $db->db_quote(session_id()) . ', ' . $db->db_quote($token) . ')');

					//Logged in
					if($inserted != null) {
						return 0;
					}
					//insert of logged_in_member failed - DB issue?
					return 3;
				} else {
					//Not verified
					return 1;
				}
			} else {
				//Not active
				return 2;
			}
		}
		//No match, reject
		return 4;
	}

	//checks if users should be able to access page
	public function checkSession()
	{
		$db = new Db();
		//Select the row
		$selection = $db->db_select('SELECT * FROM logged_in_member WHERE user_id = ' . $db->db_quote($_SESSION['user_id']));
			
		if($selection) {
			//Check ID and Token
			if(session_id() == $selection['session_id'] && $_SESSION['token'] == $selection['token']) {
				//Id and token match, refresh the session for the next request
				$this->refreshSession();
				return true;
			}
		}		
		return false;
	}

	private function refreshSession()
	{		
		//Regenerate token
		$random = $this->genSalt();
		//Build the token
		$token = $_SERVER['HTTP_USER_AGENT'] . $random;
		$token = $this->hashData($token); 
			
		//Store in session
		$_SESSION['token'] = $token;

		//update database table
		$updateToken = $db->db_query('UPDATE logged_in_member SET token = ' . $db->db_quote($token) . ' WHERE user_id = ' . $_SESSION['user_id'] . ';');
	}


	protected function hashData($data)
    {
		return hash_hmac('sha512', $data, $this->_siteKey);
	}

	public function isAdmin()
	{		
		//$selection being array of the row returned from database
		if($selection['is_admin'] == 1) {
			return true;
		}		
		return false;
	}

	//salt generation function 1
	//use:
	//$length = x; //or defaults to 60
	//$result = genSalt($length);
	protected function genSalt($length = 60) {
	  if($length > 0) { 
		  $random_id="";
			for($i=1; $i <= $length; $i++) {
			    mt_srand((double)microtime() * 1000000);
			    $number = mt_rand(1,72);
			    $random_id .= $this->assign_random_value($number);
			}
	  }
		return $random_id;
	}

	//salt generation function 2
	protected function assign_random_value($number) {
	  switch($number) {
	    case "1":
	        $random_value = "a";
	    break;
	    case "2":
	        $random_value = "b";
	    break;
	    case "3":
	        $random_value = "c";
	    break;
	    case "4":
	        $random_value = "d";
	    break;
	    case "5":
	        $random_value = "e";
	    break;
	    case "6":
	        $random_value = "f";
	    break;
	    case "7":
	        $random_value = "g";
	    break;
	    case "8":
	        $random_value = "h";
	    break;
	    case "9":
	        $random_value = "i";
	    break;
	    case "10":
	        $random_value = "j";
	    break;
	    case "11":
	        $random_value = "k";
	    break;
	    case "12":
	        $random_value = "l";
	    break;
	    case "13":
	        $random_value = "m";
	    break;
	    case "14":
	        $random_value = "n";
	    break;
	    case "15":
	        $random_value = "o";
	    break;
	    case "16":
	        $random_value = "p";
	    break;
	    case "17":
	        $random_value = "q";
	    break;
	    case "18":
	        $random_value = "r";
	    break;
	    case "19":
	        $random_value = "s";
	    break;
	    case "20":
	        $random_value = "t";
	    break;
	    case "21":
	        $random_value = "u";
	    break;
	    case "22":
	        $random_value = "v";
	    break;
	    case "23":
	        $random_value = "w";
	    break;
	    case "24":
	        $random_value = "x";
	    break;
	    case "25":
	        $random_value = "y";
	    break;
	    case "26":
	        $random_value = "z";
	    break;
	    case "27":
	        $random_value = "0";
	    break;
	    case "28":
	        $random_value = "1";
	    break;
	    case "29":
	        $random_value = "2";
	    break;
	    case "30":
	        $random_value = "3";
	    break;
	    case "31":
	        $random_value = "4";
	    break;
	    case "32":
	        $random_value = "5";
	    break;
	    case "33":
	        $random_value = "6";
	    break;
	    case "34":
	        $random_value = "7";
	    break;
	    case "35":
	        $random_value = "8";
	    break;
	    case "36":
	        $random_value = "9";
	    break;
	    case "37":
	        $random_value = "*";
	    break;
	    case "38":
	        $random_value = "~";
	    break;
	    case "39":
	        $random_value = "-";
	    break;
	    case "40":
	        $random_value = "|";
	    break;
	    case "41":
	        $random_value = "^";
	    break;
	    case "42":
	        $random_value = "%";
	    break;
	    case "43":
	        $random_value = " ";
	    break;
	    case "44":
	        $random_value = "_";
	    break;
	    case "45":
	        $random_value = "+";
	    break;
	    case "46":
	        $random_value = "=";
	    break;
	    case "47":
	        $random_value = "A";
	    break;
	    case "48":
	        $random_value = "B";
	    break;
	    case "49":
	        $random_value = "C";
	    break;
	    case "50":
	        $random_value = "D";
	    break;
	    case "51":
	        $random_value = "E";
	    break;
	    case "52":
	        $random_value = "F";
	    break;
	    case "53":
	        $random_value = "G";
	    break;
	    case "54":
	        $random_value = "H";
	    break;
	    case "55":
	        $random_value = "I";
	    break;
	    case "56":
	        $random_value = "J";
	    break;
	    case "57":
	        $random_value = "K";
	    break;
	    case "58":
	        $random_value = "L";
	    break;
	    case "59":
	        $random_value = "M";
	    break;
	    case "60":
	        $random_value = "N";
	    break;
	    case "61":
	        $random_value = "O";
	    break;
	    case "62":
	        $random_value = "P";
	    break;
	    case "63":
	        $random_value = "Q";
	    break;
	    case "64":
	        $random_value = "R";
	    break;
	    case "65":
	        $random_value = "S";
	    break;
	    case "66":
	        $random_value = "T";
	    break;
	    case "67":
	        $random_value = "U";
	    break;
	    case "68":
	        $random_value = "V";
	    break;
	    case "69":
	        $random_value = "W";
	    break;
	    case "70":
	        $random_value = "X";
	    break;
	    case "71":
	        $random_value = "Y";
	    break;
	    case "72":
	        $random_value = "Z";
	    break;
	  }
	return $random_value;
	}
}
?>