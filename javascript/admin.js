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
$(document).ready(function(){
$("#more").click(function(){
             $("#t_more").before('<tr><td><strong>Sezione:</strong>&nbsp<input type="text" name="sec[]" /></td><td><strong>Type:</strong>&nbsp<select name="type[]"><option value="pages">Page</option><option value="guest">Guest</option></select></td></tr>');return false;
});
$('.more').click(function(){
             $("#l_more").before('<tr><td><strong>Testo:</strong>&nbsp<input type="text" name="txt[]" /></td><td><strong>Link:</strong>&nbsp<input type="text" name="link[]" /></td></tr>');return false;
});

$('a.act').click(function(){
        $('div.act').each(function(){$(this).hide('slow');});
        if($($(this).attr('href')).css('display')=='none'){
              $($(this).attr('href')).show('slow');
        }
        
});
});


function addBB(tag, p){
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
                        lg=prompt('Lang:');

			bbcode='[code='+lg+'][/code]';
			break;
		case "url":
			bbcode='[url][/url]';
			break;
                case 'img':
			bbcode='[img][/img]';
			break;    
                case 'youtube':
			bbcode='[youtube][/youtube]';
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
	
	$('#t'+p).val($('#t'+p).val()+bbcode);
}

function admin(where, par, resp_location){
        $(document).ready(function(){
        $.ajax({
                url: where,
                type: 'POST', 
                dataType: 'html',
                data: par,
                success: function(html){
                        document.getElementById(resp_location).innerHTML=html;
                }
        });
        });
}

function refreshSections(where){
	$(document).ready(function(){
        $.ajax({
                url: where,
                type: 'POST', 
                dataType: 'xml',
                data: {action: "refreshS"},
                success: function(xml){
                	document.getElementById("sec_id_li").innerHTML="";
                	document.getElementById("sec_id_pag").innerHTML="";
                       $(xml).find("sezione").each(function(){
				$("#sec_id_li").append("<option value="+$(this).attr('id')+">"+$(this).text()+"</option>");
                                if($(this).attr("type")=="pages"){
				      $("#sec_id_pag").append("<option value="+$(this).attr('id')+">"+$(this).text()+"</option>");
                                }
                       });
                }
        });
        });
}
