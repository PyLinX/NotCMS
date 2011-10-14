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
require("config/style.php");
require("lib/NotCMS.php");
require("lib/NotConfig.php");
require("config/visual_config.php");
require("config/db_config.php");
$not=new NotCMS(DB);
$conf=new NotConfig();
$pages=array();
$limit=$_GET["limit"];
if($limit=="") $limit=LIMIT;

if(!isset($_GET["id"])||$_GET["id"]==""){
	list($pages, $type)=$not->makeHome($limit);
}else{
        $id=htmlentities($_GET["id"]);
        $from=$_GET["from"];
	if($from=="") $from=0;
	list($pages, $type)=$not->readSection($id, $limit, $from);        	
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta name="author" content="Jona Lelmi" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<link type="text/css" rel="stylesheet" media="all" href=<?php echo '"'.TEMPLATE.'"' ?> />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/Javascript" src="scorri.php"></script>
<script type="text/Javascript" src="javascript/oro.js"></script>
<script type="text/Javascript" src="javascript/client.js"></script>
<title><?php echo $type." | ".SITE_NAME; ?></title>
</head>
<body onload="goo();stampa_ora();">
<div id="tit">
        <a href="index.php"><h2><?php echo SITE_NAME; ?></h2></a>
</div>
<div id="corpo">
	<div id="content">
		<div id="navigation">
                   <ul>
                     <?php
                         foreach($not->makeMenu() as $value){
                               if($value["id"]==$id){
                                      echo '<li class="the"><a href="view_section.php?id='.$value["id"].'"><strong>'.$value["name"].'</strong></a></li>';
                                      continue;
                               }
                               echo '<li><a href="view_section.php?id='.$value["id"].'"><strong>'.$value["name"].'</strong></a></li>';
                         }
                      ?>
		     </ul>
			<div id="link">
					<h4>Link Utili</h4>
					<?php
                                            foreach($conf->readLink() as $value){echo '<a href="'.$value["link"].'" target="_blank"><strong>'.$value["nome"].'</strong></a><br />';}
                                        ?>
			</div><br /><hr />
			<a href='NotAccess.php' target='_blank'>Amministra!</a>
			<hr />
		</div>
		
		<div id="text">
		<?php
                        $cpt_img='captcha/securimage/securimage_show.php?sid='.md5(time()).'';
			switch($type){
				case "pages":
		   			foreach($pages as $page){
		 				echo "<div id='p".$page['id']."s".$id."' class='page'><a href='getapage.php?id_s=".$id."&amp;id_p=".$page['id']."'><h3>".$page->title."</h3></a>";
						echo "<div class='date'><strong><em>".$page['date']."</em></strong></div>";
						echo "<br />".$page->text."<hr /><div class=\"comment\"><a href=\"#".$page['id']."\" onclick=\"show_comment(".$id.",".$page['id'].");\">Commenti{".count($page->commenti->commento)."}</a></div><div class='comments' id='".$page['id']."_".$id."' style='display: none;'>
<div class='list'>
</div>
<div class='form_commenti'>
	<form class='add_cm' onsubmit='add_comment(".$id.",".$page['id']."); return false;'>
		<table>
			<tr><td>Nick:</td><td><input type='text' name='nick' /></td></tr>
			<tr><td>Website:</td><td><input type='text' name='web' /></td></tr>
                        <tr><td></td><td><button type='button' onclick='addBB(\"bold\", ".$page['id'].", ".$id.")'><strong>b</strong></button><button type='button' onclick='addBB(\"em\", ".$page['id'].", ".$id.")'><em>i</em></button><button type='button' onclick='addBB(\"und\", ".$page['id'].", ".$id.")'><u>u</u></button><button type='button' onclick='addBB(\"cod\", ".$page['id'].", ".$id.")'>code</button><button type='button' onclick='addBB(\"url\", ".$page['id'].", ".$id.")'>url</button><button type='button' onclick='addBB(\"lk\", ".$page['id'].", ".$id.")'>link</button><button type='button' onclick='addBB(\"color\", ".$page['id'].", ".$id.")'>color</button></td></tr>
			<tr><td>Commento:</td><td><textarea name='com'></textarea></td></tr>
                        <tr><td></td><td><img class='siimage' src='".$cpt_img."' alt='' style='border: 0;'></td></tr>
			<tr><td>Captcha:</td><td><input type='text' name='cap' /></td></tr>
                        <tr><td colspan='2'><div class='campi'>Compilare tutti i campi, l'unico opzionale è web!</div></td></tr>
			<tr><td></td><td><input type='submit' class='but' value='Commenta!' /></td></tr>
		</table>
	</form>
</div>

</div><br /></div>";
					}
                                        echo "<div id='more'><a id='mp' href='' onclick='more_pages(".$id.", ".$page['tstp'].", ".$limit."); return false;'>----- More Pages! -----</a></div>";
					break;
				case "guest":
                                        echo '<div id="p-1s-1">';
					foreach($pages as $page){
                                                if($page->fe=="") continue;
						echo "<div class=\"feed\">
                		              		<h4><a href=\"".$page->web."\">".$page->name."</a></h4>
                		              		<div class=\"date\"><em>".$page->date."</em></div>
                		              		<div class=\"cont\">
                			       		       ".$page->fe."
                		             		 </div>
                	                     		 </div>
"; 
                	                     		 }
                                        echo "<div class='form_commenti'>
	<form class='add_cm' onsubmit='add_feed(".$id."); return false;'>
		<table>
			<tr><td>Nick:</td><td><input type='text' name='nick' /></td></tr>
			<tr><td>Website:</td><td><input type='text' name='web' /></td></tr>
                        <tr><td></td><td><button type='button' onclick='addBB(\"bold\", -1,-1)'><strong>b</strong></button><button type='button' onclick='addBB(\"em\", -1,-1)'><em>i</em></button><button type='button' onclick='addBB(\"und\", -1,-1)'><u>u</u></button><button type='button' onclick='addBB(\"cod\", -1,-1)'>code</button><button type='button' onclick='addBB(\"url\", -1,-1)'>url</button><button type='button' onclick='addBB(\"lk\", -1,-1)'>link</button><button type='button' onclick='addBB(\"color\", -1,-1)'>color</button></td></tr>
			<tr><td>Commento:</td><td><textarea name='com'></textarea></td></tr>
                        <tr><td></td><td><img class='siimage' src='".$cpt_img."' alt='' style='border: 0;'></td></tr>
			<tr><td>Captcha:</td><td><input type='text' name='cap' /></td></tr>
                        <tr><td colspan='2'><div class='campi'>Compilare tutti i campi, l'unico opzionale è web!</div></td></tr>
			<tr><td></td><td><input type='submit' class='but' value='Commenta!' /></td></tr>
		</table>
	</form>
</div></div>";
					break;
				case 'home':
					
		   			foreach($pages as $page){
		 				echo "<div id='p".$page['id']."s".$page['id_s']."' class='page'><a href='getapage.php?id_s=".$page['id_s']."&amp;id_p=".$page['id']."'><h3>".$page->title."</h3></a>";
						echo "<div class='date'><strong><em>".$page['date']."</em></strong></div>";
						echo "<br />".$page->text."<br style=\"clear: both;\" /><hr /><div class=\"comment\"><a href=\"#".$page['id']."\" onclick=\"show_comment(".$page['id_s'].",".$page['id'].");\">Commenti{".count($page->commenti->commento)."}</a></div><div class='comments' id='".$page['id']."_".$page['id_s']."' style='display: none;'> 
<div class='list'>
</div>
<div class='form_commenti'>
	<form class='add_cm' onsubmit='add_comment(".$page['id_s'].",".$page['id']."); return false;'>
		<table>
			<tr><td>Nick:</td><td><input type='text' name='nick' /></td></tr>
			<tr><td>Website:</td><td><input type='text' name='web' /></td></tr>
                        <tr><td></td><td><button type='button' onclick='addBB(\"bold\", ".$page['id'].", ".$page['id_s'].")'><strong>b</strong></button><button type='button' onclick='addBB(\"em\", ".$page['id'].", ".$page['id_s'].")'><em>i</em></button><button type='button' onclick='addBB(\"und\", ".$page['id'].", ".$page['id_s'].")'><u>u</u></button><button type='button' onclick='addBB(\"cod\", ".$page['id'].", ".$page['id_s'].")'>code</button><button type='button' onclick='addBB(\"url\", ".$page['id'].", ".$page['id_s'].")'>url</button><button type='button' onclick='addBB(\"lk\", ".$page['id'].", ".$page['id_s'].")'>link</button><button type='button' onclick='addBB(\"color\", ".$page['id'].", ".$page['id_s'].")'>color</button></td></tr>
			<tr><td>Commento:</td><td><textarea name='com'></textarea></td></tr>
                        <tr><td></td><td><img class='siimage' src='".$cpt_img."' alt='' style='border: 0;'></td></tr>
			<tr><td>Captcha:</td><td><input type='text' name='cap' /></td></tr>
                        <tr><td colspan='2'><div class='campi'>Compilare tutti i campi, l'unico opzionale è web!</div></td></tr>
			<tr><td></td><td><input type='submit' class='but' value='Commenta!' /></td></tr>
		</table>
	</form>
</div>

</div><br /></div>";}
					break;
				default:
					die("Errore Lettura Pagina.");
					break;
					       	
        	
			}
                        unset($type);
                        unset($cpt_img);
                        unset($limit);
                        unset($from);
                        unset($id);
                        unset($page);
		?>
               
		</div>
			
	<br style="clear: both;" />
	<hr />
	<div id="recent">
		<span class="choise">Sfoglia le News -> <a href="#" onclick="ind(0);return false;">1</a>&nbsp;<a onclick="ind(1);return false;" href="#">2</a>&nbsp;<a onclick="ind(2);return false;" href="#">3</a>&nbsp;<a onclick="ind(3);return false;" href="#">4</a>&nbsp;<a onclick="ind(4);return false;" href="#">5</a></span>
		<div id="change">
		</div>
	</div>
	
	
	<div id="orologio">
	</div>	
	<br style="clear: both;" />
	</div>
    
</div>
<div id="footer">
<?php echo FOOTER; ?>
</div>

<br style="clear: both;" />

</body>