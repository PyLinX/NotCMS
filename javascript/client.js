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
function show_comment(id_s, id_p){
	$(document).ready(function(){
	if($("div[id='"+id_p+"_"+id_s+"']").css("display")=="block"){
		$("div[id='"+id_p+"_"+id_s+"']").slideUp("slow");
	}else{
                $("div[id='"+id_p+"_"+id_s+"'] div[class='list']").html("");
		$.ajax({
			url: 'do.php',
			dataType: 'xml',
			type: 'POST',
			data: {id_s: id_s, id_p: id_p, what: "r_comments"},
			success: function(xml){
				$(xml).find("commento").each(function(){
content="<div class='feed'><h4><a href='"+$(this).find('web').text()+"'>"+$(this).find('name').text()+"</a></h4> <div class='date'>"+$(this).find('date').text()+"</div><div class='cont'>"+$(this).find('comment').text()+"</div></div>";
                                        $("div[id='"+id_p+"_"+id_s+"'] div[class='list']").append(content);
				});
                                $("div[id='"+id_p+"_"+id_s+"']").fadeIn("slow");
			}
		
		});
	
	}
	});
}

function add_comment(id_s, id_p){
	$(document).ready(function(){
		nic=$("div[id='"+id_p+"_"+id_s+"'] .form_commenti input[name='nick']").val();
		we=$("div[id='"+id_p+"_"+id_s+"'] .form_commenti input[name='web']").val();
		comm=$("div[id='"+id_p+"_"+id_s+"'] .form_commenti textarea[name='com']").val();
		ca=$("div[id='"+id_p+"_"+id_s+"'] .form_commenti input[name='cap']").val();
		if(nic==""||comm==""||ca==""){ $("div[id='"+id_p+"_"+id_s+"'] .form_commenti .campi").animate({height: 'show'}, 1000); }else{
		$.ajax({
			url: 'do.php',
                        dataType: 'xml', 
			type: 'POST',
			data: {id_s: id_s, id_p: id_p, nick: nic, web: we, com: comm, cap: ca, what: "add_comment"},
                        success: function(xml){
				if($(xml).find("adv[cod]").length){
					alert("Impossibile inserire il commento: "+$(xml).find('adv[cod]').text()+"");
                                        $('.siimage').attr('src', 'captcha/securimage/securimage_show.php?sid=' + Math.random());
				}else{
					content="<div class='feed'><h4><a href='"+$(xml).find('web').text()+"'>"+$(xml).find('name').text()+"</a></h4> <div class='date'>"+$(xml).find('date').text()+"</div><div class='cont'>"+$(xml).find('comment').text()+"</div></div>";
                                        $("div[id='"+id_p+"_"+id_s+"'] div[class='list']").append(content);
                                        img_cpt='captcha/securimage/securimage_show.php?sid=' + Math.random();
                                        $('.siimage').attr('src', 'captcha/securimage/securimage_show.php?sid=' + Math.random());
				}
			}
		
		});
                }
	});
}

function more_pages(id_s, from, limit){
	if(!from||!id_s||!limit){return false;}
	else{
		img_cpt='captcha/securimage/securimage_show.php?sid=' + Math.random();
		$(document).ready(function(){
			$.ajax({
				url: 'do.php',
				dataType: 'xml',
				type: 'POST',
				data: {id_s: id_s, from: from, limit: limit, what: 'more_pages'},
				success: function(xml){
					$(xml).find('page').each(function(){
                                                ids=$(this).attr('id_s');
                                                frm=$(this).attr('tstp');
						content="<div id='p"+$(this).attr('id')+"' class='page'><h3>"+$(this).find('title').text()+"</h3><div class='date'><strong><em>"+$(this).attr('date')+"</em></strong></div><br />"+$(this).find('text').text()+"<hr /><div class=\"comment\"><a href=\"#"+$(this).attr('id')+"\" onclick=\"show_comment("+$(this).attr('id_s')+","+$(this).attr('id')+");\">Commenti{"+$(this).find('commenti').find('commento').length+"}</a></div><div class='comments' id='"+$(this).attr('id')+"' style='display: none;'><div class='list'></div><div class='form_commenti'><form class='add_cm' onsubmit='add_comment("+$(this).attr('id_s')+","+$(this).attr('id')+"); return false;'><table><tr><td>Nick:</td><td><input type='text' name='nick' /></td></tr><tr><td>Website:</td><td><input type='text' name='web' /></td></tr><tr><td>Commento:</td><td><textarea name='com'></textarea></td></tr><tr><td></td><td><img id='siimage' src='"+img_cpt+"' style='border: 0;'></td></tr><tr><td>Captcha:</td><td><input type='text' name='cap' /></td></tr><tr><td colspan='2'><div class='campi'>Compilare tutti i campi, l'unico opzionale è web!</div></td></tr><tr><td></td><td><input type='submit' value='Commenta!' /></td></tr></table></form></div></div><br /></div>";
						$(content).insertBefore($('#more'));
					});
                                                document.getElementById('more').innerHTML='<a id="mp" onclick="more_pages('+ids+', '+frm+', '+$(xml).find('pagine').attr("lmt")+'); return false;" href="">----- More Pages! -----</a>';
				}
			});
		});
	}
}

function add_feed(id_s){
	$(document).ready(function(){
		nic=$(".form_commenti input[name='nick']").val();
		we=$(".form_commenti input[name='web']").val();
		comm=$(".form_commenti textarea[name='com']").val();
		ca=$(".form_commenti input[name='cap']").val();
		if(nic==""||comm==""||ca==""){ $(".form_commenti .campi").animate({height: 'show'}, 1000); }else{
		$.ajax({
			url: 'do.php',
                        dataType: 'xml', 
			type: 'POST',
			data: {id_s: id_s, nick: nic, web: we, com: comm, cap: ca, what: "add_feed"},
                        success: function(xml){
				if($(xml).find("adv[cod]").length){
					alert("Impossibile inserire il commento: "+$(xml).find('adv[cod]').text()+"");
                                        $('.siimage').attr('src', 'captcha/securimage/securimage_show.php?sid=' + Math.random());
				}else{
					content="<div class='feed'><h4><a href='"+$(xml).find('web').text()+"'>"+$(xml).find('name').text()+"</a></h4> <div class='date'>"+$(xml).find('date').text()+"</div><div class='cont'>"+$(xml).find('fe').text()+"</div></div>";
                                        $("div[id='text']").prepend(content);
                                        img_cpt='captcha/securimage/securimage_show.php?sid=' + Math.random();
                                        $('.siimage').attr('src', 'captcha/securimage/securimage_show.php?sid=' + Math.random());
				}
			}
		
		});
                }
	});
}

function addBB(tag, p, s){
	switch(tag){
		case "bold":
			bbcode='[b][/b]';
			break;
		case "em":
			bbcode='[em][/em]';
			break;
		case "und":
			bbcode='[u][/u]';
			break;
		case "cod":
                        lg=prompt("Language:");
			bbcode='[code='+lg+'][/code]';
			break;
		case "url":
			bbcode='[url][/url]';
			break;
		case "lk":
                        ur=prompt('URL:');
			bbcode='[url='+ur+'][/url]';
			break;
		case "color":
                        c=prompt('COLOR:');
			bbcode='[color='+c+'][/color]';
			break;
		default:
			break;
	}
	
	$('#p'+p+'s'+s+' .form_commenti textarea').val($('#p'+p+'s'+s+' .form_commenti textarea').val()+bbcode);
}
