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

session_start();
session_regenerate_id();
require_once("config/style.php");
if($_SESSION["logged"]!==true) die("<head><link type=\"text/css\" rel=\"stylesheet\" media=\"all\" href='".TEMPLATE."' /></head><body><div id='notif'><h1>Sorry, you are not logged!</h1><script>setTimeout('location.href=\'NotAccess.php\'', 3000);</script></div></body>");

require_once("config/visual_config.php");
require_once("config/admin_config.php");
require_once("config/db_config.php");

require_once("lib/NotCMS.php");
require_once("captcha/securimage/securimage.php");
require_once("lib/NotConfig.php");
require_once("lib/BBcode.php");
require_once("lib/br2nl.php");

$not=new NotCMS(DB, array("b","em","url","u","url=","code","img","youtube"));
$conf=new NotConfig();
header('Content-Type: text/html');
switch($_POST['action']){
	case 'delMen':
		if(!isset($_POST['id'])) die('<strong>Errore Lettura Id!</strong>');
		$not->deleteMenu($_POST['id']);
		foreach($not->makeMenu() as $value){echo '<tr><td><strong>'.$value["name"].'</strong></td><td><a href="#" onclick="admin(\'admin.php\',\'id='.$value["id"].'&action=delMen\', \'sec_list\')">Elimina Sezione!</a></td></li>';}
		break;
		
	case 'addSec':
                $menu=array();
                foreach($_POST["sec"] as $key=>$value){
	               $menu[htmlentities($value)]=$_POST["type"][htmlentities($key)];
                }

                $not->createMenu($menu);
                foreach($not->makeMenu() as $value){echo '<tr><td><strong>'.$value["name"].'</strong></td><td><a href="#" onclick="admin(\'admin.php\',\'id='.$value["id"].'&action=delMen\', \'sec_list\'); refreshSections(\'admin.php\');">Elimina Sezione!</a></td></li>';}
                break;
	case 'delLU':
		if(!isset($_POST['id'])) die('<strong>Errore Lettura Id!</strong>');
		$conf->delLU($_POST['id']);
		foreach($conf->readLink() as $value){echo '<tr><td><strong>'.$value["nome"].'</strong></td><td><a href="#" onclick="admin(\'admin.php\', \'id='.$value["id"].'&action=delLU\', \'link_list\')">Elimina Link!</a></td></tr>';}
		break;
        case 'addLU':
                $lu=array();
                foreach($_POST["txt"] as $key=>$value){
	               $lu[htmlentities($value)]=$_POST["link"][htmlentities($key)];
                }
                $conf->createLU($lu);
                foreach($conf->readLink() as $value){echo '<tr><td><strong>'.$value["nome"].'</strong></td><td><a href="#" onclick="admin(\'admin.php\', \'id='.$value["id"].'&action=delLU\', \'link_list\')">Elimina Link!</a></td></tr>';}
                break;
        case 'addPag':
                if(!isset($_POST["title"])||!isset($_POST['cont'])||!isset($_POST["section"])) die('<strong>Errore Lettura campi!</strong>');
                $page=$not->writePage($_POST["title"], $_POST["cont"], $_POST["section"]);
                echo "<table> 
                            <h3>Pagina Inserita!</h3>
                            <tr><td><strong>Titolo: </strong></td><td>".$page->title."</td></tr>
                            <tr><td><strong>Contenuto: </strong></td><td>".$page->text."</td></tr>
                      </table>";
                break;
        case 'pageList':
              if(!isset($_POST["sec_id_li"])) die('<strong>Errore Lettura campi!</strong>'); 
              $pages=array();
              list($pages, $type)=$not->readSection($_POST['sec_id_li']);
              echo "<table>";
              $sec_id=htmlentities($_POST['sec_id_li']);
              if($type=='pages'){
                    echo "<h4>Sezione Pages</h4>";
                    foreach($pages as $pag){
                           echo "<tr><td>".$pag['id']."</td><td>".$pag->title."</td><td><a href='#' onclick='admin(\"admin.php\", \"id_p=".$pag['id']."&id_s=".$sec_id."&action=modPage\", \"pages_list\"); return false;'>Modifica!</a></td><td><a href='#' onclick='admin(\"admin.php\", \"id_p=".$pag['id']."&id_s=".$sec_id."&action=comCor\", \"pages_list\"); return false;'>Commenti Correlati!</a></td><td><a href='#' onclick='admin(\"admin.php\", \"id_p=".$pag['id']."&id_s=".$sec_id."&action=delPage\", \"pages_list\"); return false;'>Elimina Pagina!!</a></td></tr>";
                    }
              }elseif($type=='guest'){
                    echo "<h4>Sezione Guestbook</h4>";
                    foreach($pages as $pag){
                           if($pag->fe=="") continue;
                           echo "<tr><td>".$pag['id']."</td><td>".$pag->name."</td><td>".$pag->fe."</td><td><td><a href='#' onclick='admin(\"admin.php\", \"id_p=".$pag['id']."&id_s=".$sec_id."&action=delFeed\", \"pages_list\"); return false;'>Elimina Commento!</a></td></tr>";
                    }
              }
              echo "</table>";
              break;
        case "delPage":
               if(!isset($_POST["id_s"])||!isset($_POST["id_p"])) die('<strong>Errore Lettura campi!</strong>'); 
               $not->removePage($_POST['id_s'], $_POST["id_p"]);
               $pages=array();
               list($pages, $type)=$not->readSection($_POST['id_s']);
               $id_s=htmlentities($_POST['id_s']);
               echo "<table>";
               echo "<h4>Sezione Pages</h4>";
               foreach($pages as $pag){
                     echo "<tr><td>".$pag['id']."</td><td>".$pag->title."</td><td><a href='#' onclick='admin(\"admin.php\", \"id_p=".$pag['id']."&id_s=".$id_s."&action=modPage\", \"pages_list\"); return false;'>Modifica!</a></td><td><a href='#' onclick='admin(\"admin.php\", \"id_p=".$pag['id']."&id_s=".$id_s."&action=comCor\", \"pages_list\"); return false;'>Commenti Correlati!</a></td><td><a href='#' onclick='admin(\"admin.php\", \"id_p=".$pag['id']."&id_s=".$id_s."&action=delPage\", \"pages_list\"); return false;'>Elimina Pagina!!</a></td></tr>";
               } 
               break;
        case "modPage":
               if(!isset($_POST["id_s"])||!isset($_POST["id_p"])) die('<strong>Errore Lettura campi!</strong>');
               $rep=array("strong", "em", "u", "url", "img", "code", "youtube"); 
               $page=$not->getAPage($_POST['id_s'], $_POST['id_p']);
               echo "<form id='mod_pag_form' onsubmit='tit=this.title_mod.value;con=this.cont_mod.value;if(tit==\"\"||con==\"\"){ $(\"#mod_pag_form .campi\").animate({height: \"show\"}, 1000); }else{admin(\"admin.php\", \"title_mod=\"+encodeURIComponent(tit)+\"&cont_mod=\"+encodeURIComponent(con)+\"&id_s=".htmlentities($_POST['id_s'])."&id_p=".htmlentities($_POST['id_p'])."&action=ModDefPag\", \"pages_list\");} return false;'>
                                	<table> 
                                		<tr><td><strong>Titolo</strong></td><td><input type='text' name='title_mod' value='".$page->title."' /></td></tr><tr><td></td><td><button type='button' onclick='addBB(\"bold\", 1)'><strong>b</strong></button><button type='button' onclick='addBB(\"em\", 1)'><em>i</em></button><button type='button' onclick='addBB(\"und\", 1)'><u>u</u></button><button type='button' onclick='addBB(\"cod\", 1)'>code</button><button type='button' onclick='addBB(\"url\", 1)'>url</button><button type='button' onclick='addBB(\"img\",1)'>img</button><button type='button' onclick='addBB(\"lk\", 1)'>link</button><button type='button' onclick='addBB(\"color\", 1)'>color</button><button type='button' onclick='addBB(\"youtube\",1)'>youtube</button></td></tr><tr><td><strong>Contenuto:</strong></td><td><textarea id='t1' name='cont_mod'>".UnBB(br2nl($page->text), $rep)."</textarea></td></tr>
                                 <tr><td colspan='2'><div class='campi'>Compilare tutti i campi!</div></td></tr>
                                 <tr><td></td><td><input type='submit' class=\"but\" value='Aggiungi Pagina' /></td></tr>
                                 </table>
				</form>";
              unset($rep);
              unset($page);
              break;
        case "ModDefPag":
                            if(!isset($_POST["id_s"])||!isset($_POST["id_p"])||!isset($_POST["title_mod"])||!isset($_POST['cont_mod'])) die('<strong>Errore Lettura campi!</strong>');
              $pag=$not->modPage($_POST["title_mod"], $_POST['cont_mod'], $_POST['id_s'], $_POST['id_p']);
              echo "<div id='modified'><table> 
                            <h3>Pagina Inserita!</h3>
                            <tr><td><strong>Titolo: </strong></td><td>".$pag->title."</td></tr>
                            <tr><td><strong>Contenuto: </strong></td><td>".$pag->text."</td></tr>
                      </table></div>";
              break;
        case 'comCor':
              if(!isset($_POST["id_s"])||!isset($_POST["id_p"])) die('<strong>Errore Lettura campi!</strong>');
              $commenti=$not->readComments($_POST["id_s"], $_POST["id_p"]);
              echo "<table>";
              foreach($commenti->commento as $cmm){
                      echo "<tr><td>".$cmm['id']."</td><td>".$cmm->name."</td><td>".$cmm->comment."</td><td><a href='#' onclick='admin(\"admin.php\", \"id_p=".htmlentities($_POST['id_p'])."&id_s=".htmlentities($_POST['id_s'])."&id_c=".$cmm['id']."&action=delCom\", \"pages_list\"); return false;'>Elimina Commento!</a></td></tr>";
              }
              echo "</table>";
              break;
        case 'delCom':
              if(!isset($_POST["id_s"])||!isset($_POST["id_p"])||!isset($_POST["id_c"])) die('<strong>Errore Lettura campi!</strong>');
              $not->delComment($_POST["id_s"], $_POST["id_p"], $_POST["id_c"]);
              $commenti=$not->readComments($_POST["id_s"], $_POST["id_p"]);
              echo "<table>";
              foreach($commenti->commento as $cmm){
                      echo "<tr><td>".$cmm['id']."</td><td>".$cmm->name."</td><td>".$cmm->comment."</td><td><a href='#' onclick='admin(\"admin.php\", \"id_p=".htmlentities($_POST['id_p'])."&id_s=".htmlentities($_POST['id_s'])."&id_c=".$cmm['id']."&action=delCom\", \"pages_list\"); return false;'>Elimina Commento!</a></td></tr>";
              }
              echo "</table>";
              break;
        case "delFeed":
              if(!isset($_POST["id_s"])||!isset($_POST["id_p"])) die('<strong>Errore Lettura campi!</strong>');
              $not->delFeed($_POST["id_s"], $_POST["id_p"]);
              list($pages, $type)=$not->readSection($_POST["id_s"]);
              if($type!="guest"){ die("Errore imprevisto!");}
              echo "<table>";
              echo "<h4>Sezione Guestbook</h4>";
              foreach($pages as $pag){
                     if($pag->fe=="") continue;
                           echo "<tr><td>".$pag['id']."</td><td>".$pag->name."</td><td>".$pag->fe."</td><td><td><a href='#' onclick='admin(\"admin.php\", \"id_p=".$pag['id']."&id_s=".htmlentities($_POST['id_s'])."&action=delFeed\", \"pages_list\"); return false;'>Elimina Commento!</a></td></tr>";
                    }
             
              echo "</table>";
              break; 
        case 'addNews':
              if(!isset($_POST["news"])) die('<strong>Errore Lettura campi!</strong>');
              $conf->addNews($_POST["news"]);
              foreach($conf->readNews() as $new){
                      echo $new->testo."<br />";
              }
              break;
        case "Configure":
              if(!isset($_POST["title"])&&!isset($_POST["footer"])&&!isset($_POST["db"])&&!isset($_POST["template"])&&!isset($_POST["limit"])&&!isset($_POST["geshi"])) die('<strong>Errore Lettura campi!</strong>');
              $title=SITE_NAME;
              $footer=FOOTER;
              $limit=LIMIT;
              $geshi=GESHI_STYLE;
              if(isset($_POST["title"])&&$_POST["title"]!='') $title=$_POST["title"];
              if(isset($_POST["footer"])&&$_POST["footer"]!='')$footer=$_POST["footer"];
              if(isset($_POST["db"])&&$_POST["db"]!=''){ $conf->useDb($_POST["db"]);}
              if(isset($_POST["limit"])&&$_POST["limit"]!=''){$limit=$_POST["limit"]; settype($limit, int);}
              if(isset($_POST["geshi"])&&$_POST["geshi"]!='') $geshi=$_POST["geshi"];
              if(isset($_POST["template"])&&$_POST["template"]!=''){ $temp=$_POST["template"]; $conf->load_template($temp);} 
              $conf->setVisualConfig($title, $footer, $geshi, $limit);
              echo "<strong>Impostazioni Cambiate</strong> <a href='#' onclick='admin(\"admin.php\", \"action=showImp\", \"n_resp\"); return false;'>Visualizza Lista!</a>";
              break;
        case "showImp":
              echo "<table><tr><td>Header: </td><td>".SITE_NAME."</td></tr><tr><td>Footer: </td><td>".FOOTER."</td></tr><tr><td>DB Name: </td><td>".DB."</td></tr><tr><td>Limit: </td><td>".LIMIT."</td></tr><tr><td>Template: </td><td>".TEMPLATE."</td></tr><tr><td>GESHI Style </td><td>".GESHI_STYLE."</td></tr><table>";
              break;
        case "refreshS":
              header("Content-type: xml");
              echo '<?xml version="1.0" ?><sezioni>';
              foreach($not->makeMenu() as $value){
                         echo '<sezione id="'.$value["id"].'" type="'.$value["type"].'">'.$value["name"].'</sezione>';
              }
              echo '</sezioni>';
              break;
	default:
		break;
}

?>
