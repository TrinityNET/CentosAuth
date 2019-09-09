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
    <title>CentosAuth - Variables</title>
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
                    <a class="green item" href="index.php">Dashboard</a>
                    <a href="tokens.php" class="green item">Tokens</a>
                    <a href="users.php" class="green item">Users</a>
                     <a href="#" class="green active item">Variables</a>
                    <div class="right menu">
                        <a href="/logout" class="green item">Logout</a>
                    </div>
            </div>
        </div>

        <div class="ui container">
            <div style="margin:auto; width:1000px; margin-bottom: 15px" class="ui inverted segment">
                <h2 class="ui inverted dividing header">Create Variable</h2>
<?php
if(isset($_POST['createvar'])) {
$varname = xss_clean(mysqli_real_escape_string($con, $_POST['varname']));
$varvalue = xss_clean(mysqli_real_escape_string($con, $_POST['varvalue']));

if(empty(trim($varname)) || empty(trim($varvalue))){
     echo "<div style=\"margin:auto;margin-top:10px;width:970px;\" class=\"ui negative message\">
                    <p>You cannot input empty values!</p>
                </div><br>";
}
else {


if(strlen($varname) > 30){
    echo "<div style=\"margin:auto;margin-top:10px;width:970px;\" class=\"ui negative message\">
                    <p>Variable name too long! Max length is 30 characters</p>
                </div><br>";
}
else if(strlen($varvalue) > 2000){
    echo "<div style=\"margin:auto;margin-top:10px;width:970px;\" class=\"ui negative message\">
                    <p>Variable value too long! Max length is 2000 characters</p>
                </div><br>";
}
else {

$namecheckboii = mysqli_query($con, "SELECT * FROM `vars` WHERE `name` = '$varname'") or die(mysqli_error($con));
if(mysqli_num_rows($namecheckboii) > 0){
    echo "<div style=\"margin:auto;margin-top:10px;width:970px;\" class=\"ui negative message\">
                    <p>Variable name already exists!</p>
                </div><br>";
}
else {

$insertlol = mysqli_query($con, "INSERT INTO `vars` (id, name, value) 
  VALUES ('', '$varname', '$varvalue')") or die(mysqli_error($con));
      if($insertlol){
          echo "<div style=\"margin:auto;margin-top:10px;width:970px;\" class=\"ui positive message\">
                    <p>Successfully created variable!</p>
                </div><br>";
      }
}
}
}
}


?>
                    <form class="ui form" method="POST" action="">
                    <div class="col-md-3"><strong><font color="white">Variable name (e.g. MySecretVariable):</font></strong></div>
                    <div class="ui action fluid input">
                        <input id="text" name="varname" placeholder="Variable name" type="text" value="">
                      </select>
                    </div>
                    <br>
                    <div class="col-md-3"><strong><font color="white">Variable value (e.g. My string value):</font></strong></div>
                    <div class="ui action fluid input">
                        <input id="text" name="varvalue" placeholder="Variable value" type="text" value="">
                    </div>
                    <br>
                    <input id="submit" name="createvar" type="submit" class="ui fluid green button" value="Create Variable">
                </form>
            </div>

                

        <div class="ui container">
          <?php
if (isset($_POST['deletevar'])){
$varname = xss_clean(mysqli_real_escape_string($con, $_POST['secret']));

$bruhmoment = mysqli_query($con, "DELETE FROM `vars` WHERE `name` = '$varname'") or die(mysqli_error($con));
      if ($bruhmoment){
          echo "<div style=\"margin:auto;margin-top:10px;width:970px;\" class=\"ui positive message\">
                    <p>Successfully deleted variable!</p>
                </div><br>";
      }
      else{
          echo "<div style=\"margin:auto;margin-top:10px;width:970px;\" class=\"ui negative message\">
                    <p>Failed to delete variable!</p>
                </div><br>";
      }

}
          ?>
            <div style="margin:auto; width:1000px; margin-bottom: 15px" class="ui inverted segment">
                <h2 class="ui inverted dividing header">Manage Variables</h2>
                    <form class="ui form" method="POST" action="">
                    <div class="ui action fluid input">
                      <table class="w3-table w3-striped w3-bordered">
          <thead>
            <tr class="w3-green">
              <th>Name</th>
              <th>Value</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <?php
              $grabvars = mysqli_query($con, "SELECT * FROM `vars`") or die(mysqli_error($con));
              while($row = mysqli_fetch_array($grabvars)){
              {
                echo '
              <form class="ui form" method="POST" action="">
                <tr>
                <td class="gtext">'.$row['name'].'</td>
                <td class="gtext">'.$row['value'].'</td>
                <input class="w3-input" type="hidden" name="secret" value="'.$row['name'].'">
                <td class="gtext"><input id="submit" style="float: right" name="deletevar" type="submit" class="ui red button" value="Delete"></td>
              </form>
                '
                ;
              }
              }

              
              ?>
            </tr>
          </tbody>
        </table>
                    </div>
                    <br>
                </form>
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