<?php
function BB($stringa, $replaces){
	require_once("checkURL.php");
	require_once("br2nl.php");
	foreach($replaces as $key=>$value){
		switch($value){
			case "b":
				$stringa=preg_replace("�\[b\](.*?)\[\/b\]�s", "<strong>$1</strong>", $stringa);
				break;
			case "em":
				$stringa=preg_replace("�\[em\](.*?)\[\/em\]�s", "<em>$1</em>", $stringa);
				break;
			case "u":
				$stringa=preg_replace("�\[u\](.*?)\[\/u\]�s", "<u>$1</u>", $stringa);
				break;
			case "img":
				if(checkURL(preg_replace("�(.*?)\[img\](.*?)\[\/img\](.*?)�s", "$2", $stringa))===true){
					$stringa=preg_replace("�\[img\](.*?)\[\/img\]�s", "<div class='img_c'><a href=\"$1\" alt='img_link'><img src='$1' alt='' /></a></div>", $stringa);
				}
				break;
			case "url":
				if(checkURL(preg_replace("�(.*?)\[url\](.*?)\[\/url\](.*?)�s", "$2", $stringa))===true){
				        $stringa=preg_replace("�\[url\](.*?)\[\/url\]�s", "<a href=\"$1\">$1</a>", $stringa);
				}
				break;
			case "url=":
				if(checkURL(preg_replace("�(.*?)\[url=(.*?)\](.*?)\[\/url\](.*?)�s", "$2", $stringa))===true){
				        $stringa=preg_replace("�\[url=(.*?)\](.*?)\[\/url\]�s", "<a href=\"$1\">$2</a>", $stringa);
				}
				break;
			case "color":
				$stringa=preg_replace("�\[color=(.*?)\](.*?)\[\/color\]�s", "<font color=\"$1\">$2</font>", $stringa);
				break;
			case "youtube":
				require_once("yotubeURL.php");
				$url=preg_replace("�(.*?)\[youtube\](.*?)\[\/youtube\](.*?)\Z�s", "$2", $stringa);
				if(checkURL($url)){
                                	$correct=getCorrect($url);
                                	$stringa=preg_replace("�\[youtube\](.*?)\[\/youtube\]�s", '<div class=\'y_c\'><iframe src=\''.$correct.'\' title=\'\'></iframe></div>', $stringa);
                                }
                                break;
                        case "code":
                        	require("geshi/geshi/geshi.php");
                                require("config/visual_config.php");
                                $fd=fopen(GESHI_STYLE, "r");
                                $lg=preg_replace("�(.*?)\[code=(.*?)\](.*?)\[\/code\](.*?)\Z�s", "$2", $stringa);
                                $code=preg_replace("�(.*?)\[code=(.*?)\](.*?)\[\/code\](.*?)\Z�s", "$3", $stringa);
                                $content="Linguaggio: {LANGUAGE}";
                                $code=br2nl($code);
                                $code=html_entity_decode($code);
                                $g=new GeSHi($code, $lg);
                                $g->set_header_content($content);
                                $g->set_header_content_style(fread($fd, filesize(GESHI_STYLE)));
                                $g->enable_keyword_links(false);
                                $code_h=$g->parse_code();
                                $stringa=preg_replace("�\[code=(.*?)\](.*?)\[\/code\]�s", $code_h, $stringa);
                              break;
			default:
				break;
		}
	}
        return $stringa;

}

function unBB($stringa, $replaces){
        foreach($replaces as $value){
		switch($value){
			case "b":
				$stringa=preg_replace("�\<strong\>(.*?)\<\/strong\>�s","[b]$1[/b]" , $stringa);
				break;
			case "em":
				$stringa=preg_replace("�\<em\>(.*?)\<\/em\>�s","[em]$1[/em]" , $stringa);
				break;
			case "u":
				$stringa=preg_replace("�\<u\>(.*?)\<\/u\>�s","[u]$1[/u]" , $stringa);
				break;
			case "url":
				$stringa=preg_replace("�\<a href\=\"(.*?)\"\>(.*?)\<\/a\>�s", "[url=$1]$2[/url]", $stringa);
				break;
			case "img":
				$stringa=preg_replace("�\<div class\=\'img_c\'\>\<a href\=\"(.*?)\" alt\=\'img_link\'\>\<img src\=\'(.*?)\' alt\=\'\' \/\>\<\/a\>\<\/div\>�s", "[img]$1[/img]", $stringa);
				break;
			case "color":
				$stringa=preg_replace("�\<font color\=\"(.*?)\"\>(.*?)\<\/font\>�s", "[color=$1]$2[/color]", $stringa);
				break;
			case "youtube":
				$url=preg_replace("�(.*?)\<div class\=\'y_c\'\>\<iframe src\=\'http\:\/\/www\.youtube\.com\/embed\/(.*?)\' title\=\'\'\>\<\/iframe\>\<\/div\>(.*?)\Z�s", "$2", $stringa);
                                $reversed="http://www.youtube.com/watch?v=".$url;
                                $stringa=preg_replace("�\<div class\=\'y_c\'\>\<iframe src\=\'(.*?)\' title\=\'\'\>\<\/iframe\>\<\/div\>�s", "[youtube]".$reversed."[/youtube]", $stringa);
				break;
			case "code":
				$pre=preg_replace("�(.*?)\<pre class\=\"(.*?)\" style\=\"(.*?)\"\>(.*?)\<\/pre\>(.*?)\Z�s", "$4", $stringa);
                                $lg=preg_replace("�(.*?)\<div style\=\"(.*?)\"\>Linguaggio\: (.*?)\<\/div\>(.*?)\Z�s", "$3", $pre);
                                $text=preg_replace("�\<div style\=\"(.*?)\"\>Linguaggio\: (.*?)\<\/div\>�s", "", $pre);
                                $text=preg_replace("�\<span style\=\"(.*?)\"\>�s", "", $text);
                                $text=preg_replace("�\<\/span\>�s", "", $text);
                                $stringa=preg_replace("�\<pre class\=\"(.*?)\" style\=\"(.*?)\"\>(.*?)\<\/pre\>�s", "[code=".$lg."]".$text."[/code]", $stringa);
				break;
			default:
				break;
		}
        }
        return $stringa;
}
?>
