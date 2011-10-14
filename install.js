/* NotCMS (Nice Or Terrible CMS).
Copyright (C) 2011 Jona "PyLinX" Lelmi

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>. */
i=0;
current="one";
divs=new Array("one","two","three");
$(function(){
$(".next").click(function(){
n=$("input[name='nick']").val();
p1=$("input[name='pass1']").val();
p2=$("input[name='pass2']").val();
        db=$("input[name='db']").val();
if(n==""||p1==""||p2==""){$("#campi").animate({height: 'show', opacity: 'show'}, 1000); return false;}
if(p1!=p2){ $("#pass").animate({height: 'show', opacity: 'show'}, 1000); return false;}
        else{
             i++;
             $("#"+current+"").animate({height: "hide"}, 1000);
             $("#"+divs[i]+"").animate({height: "show"}, 1000);
             current=divs[i];
        }
});

$(".prew").click(function(){
             i-=1;
             $("#"+current+"").animate({height: "hide"}, 1000);
             $("#"+divs[i]+"").animate({height: "show"}, 1000);
             current=divs[i];
});


$("#more").click(function(){
             $("#ts").append('<tr><td><strong>Sezione:</strong>&nbsp<input type="text" name="sec[]" /></td><td><strong>Type:</strong>&nbsp<select name="type[]"><option value="pages">Page</option><option value="guest">Guest</option></select></td></tr>');return false;
});

$(".more").click(function(){
             $("#lu").append('<tr><td><strong>Testo:</strong>&nbsp<input type="text" name="txt[]" /></td><td><strong>Link:</strong>&nbsp<input type="text" name="link[]" /></td></tr>');return false;
});
$("#go").submit(function(){
ca=$("input[name='cap']").val();
if(ca==""||ca=="Inserire Captcha..."){ $("#captc").animate({height: 'show', opacity: 'show'}, 1000); return false;}
s=$("input[name='site']").val();
se="";
ty="";
tx="";
ln="";
$("input[name='sec[]']").each(function(){
se=se+"&sec[]="+$(this).val();
});
$("select[name='type[]']").each(function(){
ty=ty+"&type[]="+$(this).val();
});
$("input[name='txt[]']").each(function(){
tx=tx+"&txt[]="+$(this).val();
});
$("input[name='link[]']").each(function(){
ln=ln+"&link[]="+$(this).val();
});
dataString="nick="+n+"&pass1="+p1+"&db="+db+"&site="+s+"&cap="+ca+se+ty+tx+ln;
             $.ajax({
              url: "install.php",
                dataType: 'html',
              type: "POST",
              data: dataString,
              success: function(html){
                        $("#suc").append(html);
                        $("#suc").show("slow");
              }
             }); return false;

});
});