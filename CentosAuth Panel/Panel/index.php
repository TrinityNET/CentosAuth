<?php
include '../include/settings.php';
error_reporting(0);
if (!isset($_SESSION)) { session_start(); }

if (!isset($_SESSION['authed'])) {
header("Location: $yoursiteurl");
exit();
}
?>
<html>
    <head>
		<title>CentosAuth - Hub</title>
		<link rel="icon" href="favicon.ico" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="semantic.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="epic.css">
        <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-green.css">
    <style> 
    .gtext {
    color: #CECECE;
    font-size: 100%;
    }
    /* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #232323; 
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #16AB39; 
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #4CAF50; 
}
    </style>
    </head>
   <body style="background-color: #232323">

        <div class="ui inverted segment">
            <div class="ui inverted secondary menu">
                <div class="header item"><i class="material-icons">cloud</i><b>Centos<b style="color: #21ba45">Auth</b></b></div>
                    <a class="green active item" href="#">Dashboard</a>
                    <a href="tokens.php" class="green item">Tokens</a>
                    <a href="users.php" class="green item">Users</a>
                    <a href="variables.php" class="green item">Variables</a>
                    <div class="right menu">
                        <a href="logout.php" class="green item">Logout</a>
                    </div>
            </div>
        </div>
        <div class="ui container">
            <div style="margin:auto; width:1000px; margin-bottom: 15px" class="ui inverted segment">
                <h2 class="ui inverted dividing header">Hub</h2>
                 <div class="col-md-3"><font color="white">Thanks for purchasing.<br>
                 If you need any help with the setup, you can message me on Discord (Centos#3363)</font></div><br>
                 <br><br>
            <center><img src="centos.gif" alt="<3"></center>
            </div>


        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>
    </body>
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
</html>