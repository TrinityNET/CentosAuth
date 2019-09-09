<?php

	include "database.php";
	class auth extends database{
		public function __construct(){
			$this->connect();
		}
		public function Encrypt($string)
		{
           $plaintext = $string;
           $password = base64_decode($_POST['session_id']);
           $method = 'aes-256-cbc';
           $password = substr(hash('sha256', $password, true), 0, 32);
           $iv = base64_decode($_POST['session_salt']);
           $encrypted = base64_encode(openssl_encrypt($plaintext, $method, $password, OPENSSL_RAW_DATA, $iv));
           return $encrypted;
		}
		public function Decrypt($string)
		{
           $plaintext = $string;
           $password = base64_decode($_POST['session_id']);
           $method = 'aes-256-cbc';
           $password = substr(hash('sha256', $password, true), 0, 32);
           $iv = base64_decode($_POST['session_salt']);
           $decrypted = openssl_decrypt(base64_decode($plaintext), $method, $password, OPENSSL_RAW_DATA, $iv);
           return $decrypted;
		}
		public function generatestring($length) 
	    {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxwz';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return strtoupper($randomString);
        }
        public function Login($username,$password,$HWID)
        {
        	$query = $this->db->prepare("SELECT * FROM users WHERE username = :license");
			$query->execute(array("license"=>$this->Decrypt($username)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
				if(password_verify($this->Decrypt($password),$result['password']))
				{
				    $date = new DateTime($result['expiry_date']);
                    $today = new DateTime();
					if($result['hwid'] == $this->Decrypt($HWID))
					{ 
                        if($date > $today)
                        {
                        	$encrypted_var = array();
                        	$offset = $this->db->prepare("SELECT * FROM vars");
	                        $offset->execute();
	                        $offsets = $offset->fetchAll();
	                        foreach ($offsets as $row)
	                        {
	                       	    $encrypted_var[$row["name"]] = $this->Encrypt($row["value"]);
	                        }
	                        $ip = (isset($_SERVER["HTTP_CF_CONNECTING_IP"])?$_SERVER["HTTP_CF_CONNECTING_IP"]:$_SERVER['REMOTE_ADDR']);
	                        die($this->Encrypt('{
				    		"result":"success",
				    		"logged_in":"true",
				    		"id":"'.$result['id'].'",
				    		"username":"'.$result['username'].'",
				    		"password":"'.$this->Decrypt($password).'",
				    		"hwid":"'.$result['hwid'].'",
				    		"email":"'.$result['email'].'",
				    		"rank":"'.$result['rank'].'",
				    		"ip":"'.$ip.'",
				    		"expiry":"'.$result['expiry_date'].'",
				    		"vars":'.json_encode($encrypted_var, JSON_FORCE_OBJECT).',
				    		"session_id":"'.$_POST['session_id'].'",
				    		"session_salt":"'.$_POST['session_salt'].'"}'));
                        }
                        else
                        {
                            die($this->Encrypt('{
				    		"result":"time_expired",
				    		"session_id":"'.$_POST['session_id'].'",
				    		"session_salt":"'.$_POST['session_salt'].'"}'));
                        }
					}
					else if($result['hwid'] == '')
					{
					    if($date > $today)
					    {
					    	$this->update("users", array("hwid"=>$this->Decrypt($hwid)), "username", $this->Decrypt($username));
					    	die($this->Encrypt('{
				    	    "result":"hwid_updated",
				    		"session_id":"'.$_POST['session_id'].'",
				    		"session_salt":"'.$_POST['session_salt'].'"}'));
					    }
					    else
					    {
					    	die($this->Encrypt('{
				    		"result":"time_expired",
				    		"session_id":"'.$_POST['session_id'].'",
				    		"session_salt":"'.$_POST['session_salt'].'"}'));
					    }
					}
					else
					{
					    die($this->Encrypt('{
				    	"result":"invalid_hwid",
				    	"session_id":"'.$_POST['session_id'].'",
				    	"session_salt":"'.$_POST['session_salt'].'"}'));
					}
				}
				else
				{
				    die($this->Encrypt('{
				    "result":"invalid_details",
				    "session_id":"'.$_POST['session_id'].'",
				    "session_salt":"'.$_POST['session_salt'].'"}'));
				}
			}
			else
			{
				die($this->Encrypt('{
				"result":"invalid_details",
				"session_id":"'.$_POST['session_id'].'",
				"session_salt":"'.$_POST['session_salt'].'"}'));
			}
        }
        public function Register($username,$password,$rep_pass,$email,$token,$hwid)
        {
        	$query = $this->db->prepare("SELECT * FROM users WHERE username = :license");
			$query->execute(array("license"=>$this->Decrypt($username)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result)
			{
				$email_check = $this->db->prepare("SELECT * FROM users WHERE email = :license");
			    $email_check->execute(array("license"=>$this->Decrypt($email)));
			    $email_result = $email_check->fetch(PDO::FETCH_ASSOC);
				if(!$email_result)
				{
					if($this->Decrypt($password) == $this->Decrypt($rep_pass))
				    {
					    $token_check = $this->db->prepare("SELECT * FROM tokens WHERE token = :license AND used != 1");
			            $token_check->execute(array("license"=>$this->Decrypt($token)));
			            $token_result = $token_check->fetch(PDO::FETCH_ASSOC);
			            if($token_result)
			            {
			            	$today = new DateTime();
                            $newDate = $today->modify('+'.$token_result['days'].' days');
                            $rank = $token_result['rank'];
                            $date2 = $newDate;
                            $TIME = ''.$date2->format('Y-m-d H:i:s').'';
			            	$this->insert_query("users", array(
				            "username"=>$this->Decrypt($username),
				            "password"=>password_hash($this->Decrypt($password), PASSWORD_BCRYPT),
				            "email"=>$this->Decrypt($email),
				            "hwid"=>$this->Decrypt($hwid),
				            "rank"=>$rank,
				            "expiry_date"=>$TIME
				             ));
				            $this->update("tokens", array("used"=>1,"used_by"=>$this->Decrypt($username)), "id", $token_result['id']);
				            die($this->Encrypt('{
				            "result":"success",
				            "username":"'.$this->Decrypt($username).'",
				            "session_id":"'.$_POST['session_id'].'",
				            "session_salt":"'.$_POST['session_salt'].'"}'));
			            }
			            else
			            {
			            	die($this->Encrypt('{
				            "result":"invalid token",
				            "session_id":"'.$_POST['session_id'].'",
				            "session_salt":"'.$_POST['session_salt'].'"}'));
			            }
				    }
				    else
				    {
				    	die($this->Encrypt('{
				        "result":"invalid passwords",
				        "session_id":"'.$_POST['session_id'].'",
				        "session_salt":"'.$_POST['session_salt'].'"}'));
				    }
				}
				else
				{
					die($this->Encrypt('{
				    "result":"email used",
				    "session_id":"'.$_POST['session_id'].'",
				    "session_salt":"'.$_POST['session_salt'].'"}'));
				}
			}
			else
			{
				die($this->Encrypt('{
				"result":"invalid username",
				"session_id":"'.$_POST['session_id'].'",
				"session_salt":"'.$_POST['session_salt'].'"}'));
			}
        }
        public function RedeemToken($username,$password,$token)
        {
        	$query = $this->db->prepare("SELECT * FROM users WHERE username = :license");
			$query->execute(array("license"=>$this->Decrypt($username)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
				if(password_verify($this->Decrypt($password),$result['password']))
				{
					$token_check = $this->db->prepare("SELECT * FROM tokens WHERE token = :license AND used != 1");
			        $token_check->execute(array("license"=>$this->Decrypt($token)));
			        $token_result = $token_check->fetch(PDO::FETCH_ASSOC);
			        if($token_result)
			        {
			        	$date = new DateTime($result['expiry_date']);
                        $today = new DateTime();
                        if($date > $today)
                        {
                        	$newDate = $date->modify('+'.$token_result['days'].' days');
                            $date2 = $newDate;
                            $TIME = ''.$date2->format('Y-m-d H:i:s').'';
                            $this->update("users", array("rank"=>$token_result['rank'],"expiry_date"=>$TIME), "username", $result['username']);
			                $this->update("tokens", array("used"=>1,"used_by"=>$this->Decrypt($username)), "token", $token_result['token']);
			        	    die($this->Encrypt('{
				            "result":"success",
				            "session_id":"'.$_POST['session_id'].'",
				            "session_salt":"'.$_POST['session_salt'].'"}'));
                        }
                        else
                        {
                        	$newDate = $today->modify('+'.$token_result['days'].' days');
                            $date2 = $newDate;
                            $TIME = ''.$date2->format('Y-m-d H:i:s').'';
                            $this->update("users", array("rank"=>$token_result['rank'],"expiry_date"=>$TIME), "username", $result['username']);
			                $this->update("tokens", array("used"=>1,"used_by"=>$this->Decrypt($username)), "token", $token_result['token']);
			        	    die($this->Encrypt('{
				            "result":"success",
				            "session_id":"'.$_POST['session_id'].'",
				            "session_salt":"'.$_POST['session_salt'].'"}'));
                        }
			        }
			        else
			        {
			            die($this->Encrypt('{
				        "result":"invalid token",
				        "session_id":"'.$_POST['session_id'].'",
				        "session_salt":"'.$_POST['session_salt'].'"}'));
			        }
				}
				else
				{
					die($this->Encrypt('{
				    "result":"invalid details",
				    "session_id":"'.$_POST['session_id'].'",
				    "session_salt":"'.$_POST['session_salt'].'"}'));
				}
			}
			else
			{
				die($this->Encrypt('{
				"result":"invalid token",
				"session_id":"'.$_POST['session_id'].'",
			    "session_salt":"'.$_POST['session_salt'].'"}'));
			}
        }
        
	}