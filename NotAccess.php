<?php
/*  NotCMS (Nice Or Terrible CMS).
    Copyright (C) 2011  Jona "PyLinX" Lelmi

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>. */
require("config/style.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="author" content="Jona Lelmi" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link type="text/css" rel="stylesheet" media="all" href=<?php echo '"'.TEMPLATE.'"' ?> />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<title>Login</title>
</head>
<?php
require_once("lib/log.php");
if(isset($_POST["user"])&&isset($_POST["passwd"])){
        login($_POST["user"], $_POST["passwd"]);
        if($_SESSION["logged"]===true){
                 echo "<script type='text/javascript'>location.href='NotAdmin.php';</script>";
        }else{
                 echo "<script type='text/javascript'>alert('Incorrect Login!');</script>";
        } 
}
?>
<body>
<div id='corpo'>
   <form id='login' method='POST' action='NotAccess.php'>
      <table>
          <tr><td>Username: </td><td><input type='text' name='user' /></td></tr>
          <tr><td>Password: </td><td><input type='password' name='passwd' /></td></tr>
          <tr><td></td><td><input type='submit' class='but' value='Accedi' /></td></tr>
      </table>
   </form>
</div>
</body>
</html>
