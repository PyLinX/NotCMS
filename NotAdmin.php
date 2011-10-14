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
session_start();
session_regenerate_id();
if($_SESSION["logged"]!==true) die("<head><link type=\"text/css\" rel=\"stylesheet\" media=\"all\" href='".TEMPLATE."' /></head><body><div id='notif'><h1>Sorry, you are not logged!</h1><script>setTimeout('location.href=\'NotAccess.php\'', 3000);</script></div></body>");
require("lib/NotCMS.php");
require("lib/NotConfig.php");
require("config/db_config.php");
require("config/visual_config.php");
$not=new NotCMS(DB);
$conf=new NotConfig();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="author" content="" />
<meta name="copyright" content="" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link type="text/css" rel="stylesheet" media="all" href=<?php echo '"'.TEMPLATE.'"' ?> />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>
<script type="text/Javascript" src="javascript/admin.js"></script>
<title>Admin Panel</title>
<style type="text/css">
#navigation ul li{
font-size: 10px;
}
table{
width: 100%;
}
div.act{
width: 100%;
display: none;
}
</style>
</head>
<body>
<div id="corpo">
	<div id="content">
		<div id="navigation">
			<ul>
				<li><a class='act' href="#men_prin"><strong>Modifica menu principale</strong></a></li>
				<li><a class='act' href="#lu"><strong>Modifica link utili</strong></a></li>
				<li><a class='act' href="#new_pag_div"><strong>Pubblica nuovo articolo</strong></a></li>

				<li><a class='act' href="#list_pag"><strong>Lista articoli</strong></a></li>
                                <li><a class='act' href="#news_div"><strong>Aggiungi News</strong></a></li>
                                <li><a class='act' href="#n_configuration"><strong>NotCMS config</strong></a></li>
                                <li><a class='act' href="logout.php"><strong>Logout</strong></a></li>
			 </ul>
		 
		</div>
		
		<div id="text">
                        <div class="form_commenti">
			<div id='men_prin' class='act'>
				<h3>Menu Principale</h3>
				
					<table id='sec_list'>
						<?php foreach($not->makeMenu() as $value){echo '<tr><td><strong>'.$value["name"].'</strong></td><td><a href="#" onclick="admin(\'admin.php\',\'id='.$value["id"].'&action=delMen\', \'sec_list\'); refreshSections(\'admin.php\');">Elimina Sezione!</a></td></li>';} ?>
					</table>
					<hr />
                                <form id='mod_men' onsubmit='se=""; ty=""; $("input[name=\"sec[]\"]").each(function(){se=se+"&sec[]="+encodeURIComponent($(this).val());});$("select[name=\"type[]\"]").each(function(){ty=ty+"&type[]="+encodeURIComponent($(this).val());});admin("admin.php", ""+se+"&"+ty+"&action=addSec", "sec_list"); refreshSections("admin.php"); return false;'>
                                	<table> 
                                                <h4>Add one or more sections</h4>
                                		<tr><td><strong>Sezione:</strong>&nbsp<input type="text" name="sec[]" /></td><td><strong>Type:</strong>&nbsp<select name="type[]">
                                                                                    <option value="pages">Page</option>
                                                                                    <option value="guest">Guest</option>

                                 </select></td></tr><tr id="t_more" class='m'><td colspan='2'><a href="#" id="more">-----More!-----</a></td></tr>
                                 <tr><td></td><td><input type='submit' class="but" value='Aggiungi sezione/i' /></td></tr>
                                 </table>
				</form>
			</div>
			
			
			<div id='lu' class='act'>
				<h3>Link Utili</h3>
                                <table id='link_list'>
                                <?php
                                       foreach($conf->readLink() as $value){echo '<tr><td><strong>'.$value["nome"].'</strong></td><td><a href="#" onclick="admin(\'admin.php\', \'id='.$value["id"].'&action=delLU\', \'link_list\')">Elimina Link!</a></td></tr>';}
                                ?>
                                </table>
                                <hr />
                                <form id='mod_lu' onsubmit='tx=""; lk=""; $("input[name=\"txt[]\"]").each(function(){tx=tx+"&txt[]="+encodeURIComponent($(this).val());});$("input[name=\"link[]\"]").each(function(){lk=lk+"&link[]="+encodeURIComponent($(this).val());});admin("admin.php", ""+tx+"&"+lk+"&action=addLU", "link_list"); return false;'>
                                <table>
                                      <tr><td><strong>Testo:</strong>&nbsp<input type="text" name="txt[]" /></td><td><strong>Link:</strong>&nbsp<input type="text" name="link[]" /></td></tr><tr id="l_more" class='m'><td colspan='2'><a href="#" class="more">-----More!-----</a></td></tr>
                                      <tr><td></td><td><input type='submit' class="but" value='Aggiungi Link!' /></td></tr>
                                </table>
                                </form>
			</div>
			
			
			<div id='new_pag_div' class='act'>
                                <div id='resp'></div>
				<h3>Nuova Pagina</h3>
				
                                <form id='new_pag' onsubmit='tit=this.title.value;sectio=this.section.value;con=this.cont.value;$("#resp").fadeOut("slow");if(tit==""||sectio==""||con==""){ $("#new_pag .campi").animate({height: "show"}, 1000); }else{admin("admin.php", "title="+encodeURIComponent(tit)+"&cont="+encodeURIComponent(con)+"&section="+sectio+"&action=addPag", "resp");$("#resp").fadeIn("slow");this.reset();} return false;'>
                                	<table> 
                                		<tr><td><strong>Titolo</strong></td><td><input type='text' name='title' /></td></tr><tr><td><strong>Sezione: </strong></td><td><select name="section" id="sec_id_pag"><?php
                                                                                   foreach($not->makeMenu() as $value){
if($value['type']=='guest') continue;
echo '<option value="'.$value["id"].'">'.$value["name"].'</option>';}

                                 ?></select></td></tr><tr><td></td><td><button type='button' onclick='addBB("bold", 0)'><strong>b</strong></button><button type='button' onclick='addBB("em", 0)'><em>i</em></button><button type='button' onclick='addBB("und", 0)'><u>u</u></button><button type='button' onclick='addBB("cod", 0)'>code</button><button type='button' onclick='addBB("url", 0)'>url</button><button type='button' onclick='addBB("img", 0)'>img</button><button type='button' onclick='addBB("lk", 0)'>link</button><button type='button' onclick='addBB("color", 0)'>color</button><button type='button' onclick='addBB("youtube", 0)'>youtube</button></td></tr><tr><td><strong>Contenuto:</strong></td><td><textarea id='t0' name='cont'></textarea></td></tr>
                                 <tr><td colspan='2'><div class='campi'>Compilare tutti i campi!</div></td></tr>
                                 <tr><td></td><td><input type='submit' class="but" value='Aggiungi Pagina' /></td></tr>
                                 </table>
				</form>
			</div>
			
			
			<div id='list_pag' class='act'>
				<h3>Lista Pagine</h3>
				<table>
                                      <tr><td>Sezione Pagine:</td><td><select name="sec_id_li" id='sec_id_li' onChange='$("#pages_list").slideUp("slow");s=this.value;admin("admin.php", "sec_id_li="+s+"&action=pageList", "pages_list");$("#pages_list").slideDown("slow");'><option value=""></option><?php
                                                                                   foreach($not->makeMenu() as $value){
echo '<option value="'.$value["id"].'">'.$value["name"].'</option>';}

                                 ?></select></td></tr>
                                </table>
                                <div id='pages_list'>
                                </div>
			</div>	
                      

                        <div id='news_div' class='act'>
				<h3>Aggiungi News</h3>
                                <form id='add_news' onsubmit='$("#news_agg").fadeOut("slow");n=this.news.value;if(n==""){$("#news_div .campi").animate({height: "show"}, 1000);}else{admin("admin.php", "news="+encodeURIComponent(n)+"&action=addNews", "news_agg");$("#news_agg").fadeIn("slow");} return false;'>
				<table>
                                      <tr><td></td><td><button type='button' onclick='addBB("lk", 2)'>link</button></td></tr>
                                      <tr><td><strong>News: </strong></td><td><textarea name='news' id='t2'></textarea></td></td>
                                      <tr><td colspan='2'><div class="campi" style="display: none;">Compilare tutti i campi!</div></td></tr>
                                      <tr><td></td><td><input type='submit' class="but" value='Invia!' /></td></tr>
                                </table>
                                </form>
                                <div id='news_agg'>
                                </div>
			</div>


                        <div id='n_configuration' class='act'>
                                <div id='n_resp'></div>
				<h3>Configurazione NotCMS</h3>
                                <form id='n_config' onsubmit="t=this.titolo.value; db=this.n_db.value; fo=this.footer.value; temp=this.template.value; limi=this.limit.value; gesh=this.geshi.value; if(t=='' && db=='' && fo=='' && temp=='' &&limi=='' &&gesh==''){$('#n_configuration #campo').animate({height: 'show'}, 1000);}else{admin('admin.php', 'title='+encodeURIComponent(t)+'&db='+encodeURIComponent(db)+'&footer='+encodeURIComponent(fo)+'&template='+encodeURIComponent(temp)+'&limit='+encodeURIComponent(limi)+'&geshi='+encodeURIComponent(gesh)+'&action=Configure', 'n_resp')}return false;">
                                <table>
                                        <tr><td colspan='2'><h4>Compilare solo i campi che di cui si vuole cambiare la configurazione!</h4></td></tr>
                                        <tr><td>Header: </td><td><input type='text' name='titolo' /></td><td></td></tr>
                                        <tr><td>Nome File Db: </td><td><input type='text' name='n_db' /></td><td></td></tr>
                                        <tr><td>Limite Pagine pr Schermata: </td><td><input type='text' name='limit' /></td><td></td></tr>
                                        <tr><td>Footer: </td><td><input type='text' name='footer' /></td><td><em>BBtag:[url][b][em][u]</em></td></tr>
                                        <tr><td>Template: </td><td><select name='template'><option value=''></option>
                                        <?php
                                                if(is_dir("themes")){
                                         		if($dd=opendir("themes")){
                                         		       while (false !== ($file = readdir($dd))){
                                         				if(preg_match("§(.*?)\.css\Z§", $file)) echo "<option value='".$file."'>".$file."</option>";
                                         			}
                                         		}
                                                        closedir($dd);
                                                }
                                        ?>
                                        </select></td><td></td></tr>
                                        <tr><td>Geshi Style: </td><td><select name='geshi'><option value=''></option>
                                        <?php
                                                if(is_dir("g_style")){
                                         		if($dd=opendir("g_style")){
                                         		       while (false !== ($file = readdir($dd))){
                                         				if(preg_match("§(.*?)\.txt\Z§", $file)) echo "<option value='".$file."'>".$file."</option>";
                                         			}
                                         		}
                                                        closedir($dd);
                                                }
                                        ?>
                                        </select></td><td></td></tr>
                                        <tr><td colspan='3'><div id="campo" class="err" style="display: none;">Compilare almeno un campo!</div></td></tr>
                                        <tr><td></td><td><input type='submit' class="but" value='Invia!' /></td><td></td></tr>
                                        
                                </table>
                                </form>
                                
			</div>						
                        </div>
		</div>
			
	<br style="clear: both;" />
	<hr />	
	
	</div>
    
</div>


<br style="clear: both;" />
</body>
