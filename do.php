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
require("config/db_config.php");
require("lib/NotCMS.php");
require("captcha/securimage/securimage.php");
$not=new NotCMS(DB, array('b', 'em', 'u', 'url', 'url=', 'color', 'code'));
header("Content-Type: xml");
switch($_POST["what"]){
	case "r_comments":
		if(!isset($_POST["id_s"])||!isset($_POST["id_p"])){
			echo "<?xml version='1.0' encoding='UTF-8' ?>
				<advice>
					<adv>Errore, id_s e/o id_p non passati</adv>
				</advice>";
		}else{
			echo "<?xml version='1.0' encoding='UTF-8' ?>";
			$comm=$not->readComments($_POST["id_s"], $_POST["id_p"]);
                        echo $comm->asXML();
                }
                break;
        case "add_comment":
        	session_start();
                session_regenerate_id();
if(!isset($_POST["id_s"])||!isset($_POST["id_p"])||!isset($_POST["nick"])||!isset($_POST["com"])||!isset($_POST["cap"])){
			echo "<?xml version='1.0' encoding='UTF-8' ?>
				<advice>
					<adv cod='0'>Errore, specificare i campi obblicatori, id_s e id_p</adv>
				</advice>";
		}
		$img = new Securimage();
		$valid = $img->check($_POST['cap']);
		if($valid != true){
			echo "<?xml version='1.0' encoding='UTF-8' ?>
				<advice>
					<adv cod='1'>Invalid Captcha</adv>
				</advice>";
			die();
		}
		else{
			$web=$_POST['web'];
			if(!isset($_POST['web'])||$_POST['web']=='') $web='#';
			echo "<?xml version='1.0' encoding='UTF-8' ?>";
			echo $not->putComment($_POST["nick"], $web, $_POST["com"], $_SERVER['REMOTE_ADDR'], $_POST["id_s"], $_POST["id_p"]);
			
		}
        	break;
        case "more_pages":
                
        	if(!isset($_POST['id_s'])||!isset($_POST['from'])||!isset($_POST['limit'])){
                	echo "<?xml version='1.0' encoding='UTF-8' ?>
                		<advice>
                			<adv cod='0'>Immettere tutti i campi obbligatori!</adv>
                		</advice>";    
		}else{
                        $pages=array();
			list($pages, $type)=$not->readSection($_POST['id_s'], $_POST['limit'], $_POST['from']);
                	$cpt_img='captcha/securimage/securimage_show.php?sid='.md5(time()).'';
                        echo "<?xml version='1.0' encoding='UTF-8' ?><pagine lmt='".htmlentities($_POST['limit'])."'>";
		   	foreach($pages as $page){
                                $page->addAttribute('id_s', htmlentities($_POST['id_s']));
		   		echo $page->asXML();  
			}echo "</pagine>";
		
		}
        	break;
        case "add_feed":
        	session_start();
                session_regenerate_id();
if(!isset($_POST["id_s"])||!isset($_POST["nick"])||!isset($_POST["com"])||!isset($_POST["cap"])){
			echo "<?xml version='1.0' encoding='UTF-8' ?>
				<advice>
					<adv cod='0'>Errore, specificare i campi obblicatori, id_s e id_p</adv>
				</advice>";
		}
		$img = new Securimage();
		$valid = $img->check($_POST['cap']);
		if($valid != true){
			echo "<?xml version='1.0' encoding='UTF-8' ?>
				<advice>
					<adv cod='1'>Invalid Captcha</adv>
				</advice>";
			die();
		}
		else{
			$web=$_POST['web'];
			if(!isset($_POST['web'])||$_POST['web']=='') $web='#';
			echo "<?xml version='1.0' encoding='UTF-8' ?>";
			echo $not->addFeed($_POST["nick"], $web, $_POST["com"], $_SERVER['REMOTE_ADDR'], $_POST["id_s"]);
			
		}
        	break;
        default:
                break;	
}
?>