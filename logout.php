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
require("lib/log.php");

$dominio=preg_replace("§\.§", "\.", $_SERVER["SERVER_NAME"]);
if(preg_match("§\Ahttp\:\/\/".$dominio."§", $_SERVER["HTTP_REFERER"])==0&&preg_match("§\Ahttps\:\/\/".$dominio."§", $_SERVER["HTTP_REFERER"])==0&&preg_match("§\Aftp\:\/\/".$dominio."§", $_SERVER["HTTP_REFERER"])==0){
	die("Non puoi sloggarti non proveniendo da:".$_SERVER['SERVER_NAME']."");
}
session_start();
session_regenerate_id();
if($_SESSION["logged"]!=true){
	die("Non puoi sloggarti se non hai effettuato l'accesso!");
}
logout();
header("Location: view_section.php");
?>
