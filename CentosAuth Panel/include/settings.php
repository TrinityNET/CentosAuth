<?php
$myhost = "localhost";
$myuser = "root";
$mypass = "";
$mydb = "centauth";
$key = "2147828743"; //Don't touch this !
$yoursiteurl = "http://localhost";

$con = mysqli_connect($myhost, $myuser, $mypass, $mydb);
mysqli_query($con, "SET NAMES UTF8") or die(mysqli_error($con));
setlocale(LC_TIME, 'fr_FR'); // modify this line if you are not in Europe. fr/FR mean France.
date_default_timezone_set('Europe/Paris'); // this too
error_reporting(E_ALL);


if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>
