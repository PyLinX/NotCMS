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
var indice=0;
function stampa_ora(){
var orologio=document.getElementById("orologio");
var ora=new Date();
var h;
var m;
var s;
var tot;
h=ora.getHours();
m=ora.getMinutes();
s=ora.getSeconds();
if(h<10) h="0"+h;
if(m<10) m="0"+m;
if(s<10) s="0"+s;
tot=h+" : "+m+" : "+s;
b=document.createElement("strong").appendChild(document.createTextNode(tot));
orologio.replaceChild(b, orologio.lastChild);
orologio.setAttribute("style", "color: #ff7000;");
indice++;
if(indice>2){
indice=0;
}
setTimeout("stampa_ora("+indice+")", 1000);
}
