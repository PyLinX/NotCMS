<?php
function checkURL($url){
	$url=strtolower($url);
        $url=preg_replace("§\t§", "", $url);
	if(preg_match("§javascript\:(.*?)§s" ,$url)||preg_match("§java-script\:(.*?)§s" ,$url)){
		return false;
	}
	return true;
}
?>