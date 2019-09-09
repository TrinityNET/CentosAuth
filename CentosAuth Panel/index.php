<html>
<?php
error_reporting(0);
include '../include/settings.php';
session_start();
if (isset($_SESSION['authed'])) {
header("Location: $yoursiteurl/Panel");
exit();
}
?>
    <head>
		<title>CentosAuth - Login</title>
		<link rel="icon" href="favicon.ico" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="css/semantic.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    </head>
    <body style="background-color: #232323">
        <div class="ui inverted segment">
            <div class="ui inverted secondary menu">
                <div class="header item"><i class="material-icons">cloud</i><b>Centos<b style="color: #21ba45">Auth</b></b></div>              
            </div>
        </div>
        <div class="ui container">
            <div style="width: 500px; margin: auto" class="ui inverted segment">
                <h3 class="ui inverted dividing header">Access Panel</h3>
                <form class="ui form" method="POST" action="">
					<div class="field">
                        <input id="username" name="accesskey" placeholder="Access key..." required type="text" value="">
                    </div>
                    <div class="ui divider"></div>
                    <button name="login" class="ui fluid green button" type="submit">Access</button>
                </form>
            </div>
            <?php

if(isset($_POST["login"])) {
if (!isset($_SESSION)) 
{ session_start(); 
}

include 'include/settings.php';

$accesskey = xss_clean(mysqli_real_escape_string($con, $_POST['accesskey']));

$result = mysqli_query($con, "SELECT * FROM `panel` WHERE `accesskey` = '$accesskey'") or die(mysqli_error($con));

if(mysqli_num_rows($result) == 1){
    $_SESSION['authed'] = "true";
    
    echo "<div style=\"margin:auto;margin-top:10px;width:500px;\" class=\"ui positive message\">
                    <p>Access granted! Reloading...</p>
                </div>";
                header("refresh:3; url=$yoursiteurl/Panel");
                die();
}
else{
    echo "<div style=\"margin:auto;margin-top:10px;width:500px;\" class=\"ui negative message\">
                    <p>Invalid access key!</p>
                </div>";
}
}
?>

<?php
function xss_clean($data)
  {
    // Fix &entity\n;
    $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

    do
    {
      // Remove really unwanted tags
      $old_data = $data;
      $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    }
    while ($old_data !== $data);

    // we are done...
    return $data;
  }
?>
            <div>
            </div>
        </div>
    </body>
</html>