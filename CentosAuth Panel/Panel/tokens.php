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
		<title>CentosAuth - Tokens</title>
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
                   <a href="#" class="green active item">Tokens</a>
                   <a href="users.php" class="green item">Users</a>
                    <a href="variables.php" class="green item">Variables</a>
                    <div class="right menu">
                        <a href="logout.php" class="green item">Logout</a>
                    </div>
            </div>
        </div>
        <div class="ui container">
            <div style="margin:auto; width:1000px; margin-bottom: 15px" class="ui inverted segment">
                <h2 class="ui inverted dividing header">Generate Token</h2>
                    <form class="ui form" method="POST" action="">
<?php 
if (isset($_POST['generatetoken'])) {
$loopcount =  xss_clean(mysqli_real_escape_string($con, $_POST['loopcount']));
$level =  xss_clean(mysqli_real_escape_string($con, $_POST['level']));
$type = xss_clean(mysqli_real_escape_string($con, $_POST['type']));

if ($type == "1"){
  $type = 1;
}
else if ($type == "2"){
  $type = 3;
}
else if ($type == "3"){
  $type = 7;
}
else if ($type == "4"){
  $type = 21;
}
else if ($type == "5"){
  $type = 31;
}
else if ($type == "6"){
  $type = 93;
}
else if ($type == "7"){
  $type = 99999;
}
else{
  echo "<div style=\"margin:auto;margin-top:10px;width:970px;\" class=\"ui negative message\">
                    <p>Invalid token type!</p>
                </div>";
  die();
}

for($i = 0; $i < $loopcount; $i++){
$tokennn = GenerateToken();
$insertlol = mysqli_query($con, "INSERT INTO `tokens` (id, token, rank, days, used, used_by) 
  VALUES ('', '$tokennn', '$level', '$type', 0, '')") or die(mysqli_error($con));
    }
if ($insertlol){
  echo "<div style=\"margin:auto;margin-top:10px;width:970px;\" class=\"ui positive message\">
                    <p>Success! Please wait...</p>
                </div>";
                echo "<meta http-equiv='Refresh' Content='3'; url='".$_SERVER."'>";
                die();
}
else{
  echo "<div style=\"margin:auto;margin-top:10px;width:700px;\" class=\"ui negative message\">
                    <p>Error!</p>
                </div>";
}

}




function GenerateToken() {
    for($i = 0; $i < 1; $i++) {
      $randomString = "";
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      for ($i = 0; $i < 10; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    }
  }
?>
                    <div class="ui action fluid input">
                      <div class="col-md-9">
                      <div class="col-md-3"><strong><font color="white">Time:</font></strong></div>
                      <select style="margin:auto; width:970px; margin-bottom: 15px" class="form-control" name="type">
                        <option value="1">1 Day</option>
                        <option value="2">3 Days</option>
                        <option value="3">1 Week</option>
                        <option value="4">3 Weeks</option>
                        <option value="5">1 Month</option>
                        <option value="6">3 Months</option>
                        <option value="7">Lifetime</option>
                      </select>
                    </div>
                    </div>
                    <div class="col-md-3"><strong><font color="white">Level:</font></strong></div>
                    <div class="ui action fluid input">
                        <input id="number" name="level" placeholder="Minimum 1, maximum 10.." required type="number" value="", min="1", max="10">
                    </div>
                    <br>
                    <div class="col-md-3"><strong><font color="white">Amount to generate:</font></strong></div>
                    <div class="ui action fluid input">
                        <input id="number" name="loopcount" placeholder="Minimum 1, maximum 20.." required type="number" value="", min="1", max="20">
                    </div>
                    <br>
                    <input id="submit" name="generatetoken" type="submit" class="ui fluid green button" value="Generate">
                </form>
            </div>

            <div class="ui container">
            <div style="margin:auto; width:1000px; margin-bottom: 15px" class="ui inverted segment">
                <h2 class="ui inverted dividing header">Manage Tokens</h2>
                <?php
if (isset($_POST['deletetoken'])){
                $tokenvalue = xss_clean(mysqli_real_escape_string($con, $_POST['secret']));
                $deltoken = mysqli_query($con, "DELETE FROM `tokens` WHERE `token` = '$tokenvalue'") or die(mysqli_error($con));
                if ($deltoken){
                  echo "<div style=\"margin:auto;margin-top:10px;width:970px;\" class=\"ui positive message\">
                    <p>Successfully deleted token!</p>
                </div><br>";
                echo "<meta http-equiv='Refresh' Content='1'; url='".$_SERVER."'>";
                }
                else{
                  echo "<div style=\"margin:auto;margin-top:10px;width:700px;\" class=\"ui negative message\">
                    <p>Error!</p>
                </div><br>";
                }
              }
                ?>
                    <table class="w3-table w3-striped w3-bordered">
          <thead>
            <tr class="w3-green">
              <th>Token</th>
              <th>Days</th>
              <th>Used?</th>
              <th>Used by?</th>
              <th>Manage</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <?php
              $grabprograms = mysqli_query($con, "SELECT * FROM `tokens`") or die(mysqli_error($con));
              while($row = mysqli_fetch_array($grabprograms)){
              {
                if ($row['used_by'] == ""){
                  $kekloll = "N/A";
                }
                else{
                  $kekloll = $row['used_by'];
                }
                echo '
                <form class="ui form" method="POST" action="">
                <tr>
                <td class="gtext">'.$row['token'].'</td>
                <td class="gtext">'.$row['days'].'</td>
                '.($row['used'] == "1" ? "<td class = 'w3-text-red'>Used</td>" : "<td class = 'w3-text-green'>Not Used</td>") .'
                
                <td class="gtext">'.$kekloll.'</td>
                <input class="w3-input" type="hidden" name="secret" value="'.$row['token'].'">
                <td class="gtext"><input id="submit" name="deletetoken" type="submit" class="ui red button" value="Delete"></td>
                <td>
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