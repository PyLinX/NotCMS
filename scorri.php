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
require("lib/NotConfig.php");
$n=new NotConfig();
echo '
var t, j;

function leva(){
         $("#change").fadeOut("slow");
}
var indove=0;
function goo(){
         var content=new Array('; 
         $new=$n->readNews();
         for($i=count($new)-1; $i>=0; $i--){
               if($i==0) echo '"'.$new[$i]->testo.'"';
               else echo '"'.$new[$i]->testo.'",';
         }
echo ');
         if (indove>';echo count($new)-1; echo '){
             indove=0;
         }
         if (document.getElementById){
             codice=content[indove];
             document.getElementById("change").innerHTML=codice;    
         }
         $("#change").fadeIn("slow");
   indove++;
   j=setTimeout("leva()", 4000);
   t=setTimeout("goo()", 5000);
}

function ind(n){
            clearTimeout(j);
            clearTimeout(t);
            indove=n;
            goo();
   }';    
?>