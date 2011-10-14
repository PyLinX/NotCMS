<?php
require("lib/NotCMS.php");
require("lib/NotConfig.php");
require("captcha/securimage/securimage.php");
session_start();
header("Content-Type: text/html");

$img = new Securimage();
$valid = $img->check($_POST['cap']);
if($valid != true){
die("<script type=\"text/Javascript\">alert(\"Invalid Captcha!\"); $('#siimage').attr('src', 'captcha/securimage/securimage_show.php?sid=' + Math.random());</script>");
}
$nick=htmlentities($_POST["nick"]);
$password=htmlentities($_POST["pass1"]);
$site=htmlentities($_POST["site"]);
$db=htmlentities($_POST["db"]);

mkdir("config");
mkdir("Db_XML");

$menu=array();
foreach($_POST["sec"] as $key=>$value){
$menu[htmlentities($value)]=$_POST["type"][htmlentities($key)];
}
foreach($_POST["txt"] as $key=>$value){
$lu[htmlentities($value)]=$_POST["link"][htmlentities($key)];
}

$not=new NotCMS("Db_XML/".$db.".xml");
$not->createMenu($menu);
$conf=new NotConfig();
$conf->createLU($lu);
$conf->setUser($nick, $password);
$conf->setVisualConfig($site);
$conf->useDb($db.".xml");
$conf->load_template("default.css");

unlink("install.php");
unlink("NotInstall.php");
unlink("install.js");

echo "<h1>Installazione Completata, reindirizzamento al pannello di amministrazione.</h1><script type='text/Javascript'>location.href='NotAccess.php';</script>";
?>