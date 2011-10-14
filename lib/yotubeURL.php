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
function getCorrect($url){
	if(preg_match("§http\:\/\/www\.youtube\.com\/watch\?v\=(.*?)\Z§", $url)){
		$url=preg_replace("§http\:\/\/www\.youtube\.com\/watch\?v\=(.*?)\Z§", '$1', $url);
                $correct=explode("&", $url);
                $url=$correct[0];
                return 'http://www.youtube.com/embed/'.$url;
	}

}
?>
