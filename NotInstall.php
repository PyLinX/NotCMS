<!-- NotCMS (Nice Or Terrible CMS).
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
along with this program. If not, see <http://www.gnu.org/licenses/>. -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta name="author" content="" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<link type="text/css" rel="stylesheet" media="all" href="themes/default.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/Javascript" src="install.js"></script>
<title>Prova</title>
<style type="text/css">
#one, #two, #three, #suc{
width: 100%;
}

#one table, #two table, #three table{
width: 100%;
}

th, td { padding: 5px; }


.next, .prew{
font-size: 18px;
}

.err{
font-size: 10px;
color: #0088cc;
border: 1px dashed #0088cc;
}

#campi, #pass, #captc{
display: none;
width: 100%;
height: 15px;
}

#two, #three, #suc{
display: none;
}

#suc{
text-align: center;
}

.but{
width: 100%;
background-color: #121212;
color: #A2A2A2;
border: none;
border-radius: 3px;
-moz-border-radius: 3px;
-webkit-border-radius: 3px;
}

.but:hover{
text-shadow: #A2A2A2 0px 0px 2px;
-moz-box-shadow:2px 2px 2px #A2A2A2, -2px -2px 2px #A2A2A2; -webkit-box-shadow:2px 2px 2px #A2A2A2, -2px -2px 2px #A2A2A2; box-shadow:2px 2px 2px #A2A2A2, -2px -2px 2px #A2A2A2;
}

</style>
</head>
<body>
<div id="corpo">
<div id="content">
<div id="text">
<h3>Procedura Installazione NotCMS</h3>
<form id="go">
<div id="one">
<h4>Impostare dati di accesso.</h4> <br />
<table>

<tr><td colspan="2"><hr /></td></tr>
<tr><td>Nome Utente:</td><td><input type="text" name="nick" /></td></tr>
<tr><td>Password:</td><td><input type="password" name="pass1" /></td></tr>
<tr><td>Reinserisci Password:</td><td><input type="password" name="pass2" /></td></tr>
<tr><td>Nome Database:</td><td><input type="text" name="db" /></td></tr>
<tr><td></td><td><div class="campi">Compilare tutti i campi!</div></td></tr>
<tr><td></td><td><div id="pass" class="err">Le due password sono diverse!</div></td></tr>
<tr><td colspan="2"><hr /></td></tr>
<tr><td style="text-align: right;" colspan="2"><a href="#" class="next">Avanti &gt;&gt;</a></td></tr>
</table>
</div>

<div id="two">
<h4>Configurazione iniziale.</h4><br />
Sotto dovrai inserire una o più (basta cliccare su more) sezioni per il tuo sito, quelle di tipo <strong>guest</strong> sono guestbook, quelle di tipo <strong>page</strong> sono sezioni del blog/sito. NON IMMETTERE NOMI DI SEZIONI UGUALI!<br />
<table id="ts">
<tr><td colspan="2"><hr /></td></tr>
<tr><td><strong>Nome Sito:</strong></td><td><input type="text" name="site" /></td></tr>
<tr><td colspan="2"><hr /></td></tr>
<tr><td><strong>Sezione:</strong>&nbsp;<input type="text" name="sec[]" /></td><td><strong>Type:</strong>&nbsp;<select name="type[]">
<option value="pages">Page</option>
<option value="guest">Guest</option>

</select></td></tr></table>
<table>
<tr><td style="text-align: center;" colspan="2"><a href="#" id="more">-----More!-----</a></td></tr>
<tr><td colspan="2"><hr /></td></tr>
<tr><td style="text-align: left;"><a href="#" class="prew">&lt;&lt; Indietro</a></td><td style="text-align: right;"><a href="#" class="next">Avanti &gt;&gt;</a></td></tr></table>
</div>
<div id="three">
<h4>Configurazione aggiuntiva.</h4><br />
Inserire testo e link da inserire nei link utili. (Opzionale)
<table id="lu">
<tr><td colspan="2"><hr /></td></tr>
<tr><td><strong>Testo:</strong>&nbsp;<input type="text" name="txt[]" /></td><td><strong>Link:</strong>&nbsp;<input type="text" name="link[]" /></td></tr></table>
<table>
<tr><td colspan="2"><hr /></td></tr>
<tr><td style="text-align: center;" colspan="2"><a href="#" class="more">-----More!-----</a></td></tr>
<tr><td><img id="siimage" alt='' style="padding-right: 5px; height: 50px; width: 50% border: 0" src="captcha/securimage/securimage_show.php?sid=<?php echo md5(time()) ?>" /></td><td><input type="text" name="cap" value="Inserire Captcha..." /></td></tr>
<tr><td colspan="2"><div id="captc" class="err">Immettere il codice captcha!</div></td></tr>
<tr><td style="text-align: left;"><a href="#" class="prew">&lt;&lt; Indietro</a></td><td><input type="submit" value="Invia!" class="but" /></td></tr>
<tr><td colspan="2"><hr /></td></tr></table>

</div>
</form>

<div id="suc">
</div>
</div>
<br style="clear: both;" />
<hr />
</div>
</div>


<br style="clear: both;" />
</body>