<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
error_reporting(0);

	include "class.php";
	$auth = new auth;
	$Type = $_POST['type'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$rep_pass = $_POST['rep_pass'];
	$email = $_POST['email'];
	$hwid = $_POST['hwid'];
	$token = $_POST['token'];


	if(isset($Type)){
		switch(strip_tags($Type)){
			//case "assembly": $set_up->Load_Assemblys($_POST['version']); break;
			//case "set_up": $set_up->Validate_Version($_POST['version']); break;
			//case "user_pic": $auth->Get_User_Picture(); break;
			case "login": $auth->Login($username,$password,$hwid); break;
			case "register": $auth->Register($username,$password,$rep_pass,$email,$token,$hwid); break;
			//case "log_info": $logger->Log_History($_POST['clan'],$_POST['level'],$_POST['prestige'],$_POST['psn'],$_POST['ip'],$_POST['port'],$_POST['npid'],$_POST['xuid'],$_POST['game']); break;
			//case "changepw": $auth->Change_Password($username,$password,$hwid,$_POST['new_password']); break;
			//case "forgotpw": $auth->Forgot_Password($username,$hwid);break;
			//case "exception": $exception->Log_Exception($_POST['stack_trace'],$_POST['exception_info']);break;
			case "redeem": $auth->RedeemToken($username,$password,$token);break;
			//case "resolve": $auth->Resolve_PSN($username,$password);break;
		}
	}